<?php

namespace App\Services\Chatbot;

use App\Models\SiteSetting;

/**
 * Rule-based site assistant — no AI service. Detects what the question is about
 * (intent), finds the matching site data and fills an uz/ru answer template.
 * Returns null when it has nothing reliable, so the question goes to the admin.
 */
class SiteBot
{
    /** Keyword fragments per intent, matched as substrings of the normalized question (uz latin + ru). */
    private const INTENTS = [
        'greeting' => ['salom', 'assalom', 'привет', 'здравств', 'добрый день', 'добрый вечер', 'доброе утро', 'hello'],
        'thanks'   => ['rahmat', 'raxmat', 'спасибо', 'благодар', 'thanks'],
        'price'    => ['narx', 'qancha', 'necha pul', "so'm", 'цен', 'стоим', 'сколько стоит', 'прайс', 'price'],
        'drivers'  => ['drayver', 'driver', 'драйвер', 'yuklab', 'скача', 'dastur', 'программ', 'утилит', 'firmware', 'прошивк', "qo'llanma", 'инструкц', 'manual'],
        'specs'    => ['xususiyat', 'xarakteristik', 'характеристик', 'параметр', 'tezlik', 'скорост', 'dpi', 'kenglik', 'ширин', 'interfeys', 'интерфейс', 'usb', 'wifi'],
        'warranty' => ['kafolat', 'гарант', 'garant'],
        'delivery' => ['yetkaz', 'dostavka', 'доставк', 'olib ket', 'самовывоз'],
        'service'  => ['servis', 'сервис', "ta'mir", 'tamir', 'ремонт', 'buzil', 'сломал', 'не работает', 'ishlamay'],
        'buy'      => ['sotib ol', 'qayerdan', 'qayerda', 'где купить', 'купить', 'продав', 'sotuvchi', 'diler', 'дилер', 'magazin', 'магазин', "do'kon"],
        'register' => ["ro'yxatdan", 'royxatdan', 'diler bo', 'hamkor bo', 'qo\'shil', 'регистрац', 'стать дилер', 'стать партн', 'подключ', 'зарегистр', 'inn', 'инн'],
        'contacts' => ['aloqa', 'telefon', 'raqam', 'manzil', 'ish vaqt', 'контакт', 'телефон', 'номер', 'адрес', 'часы работ', 'график', 'email', 'почт', 'telegram'],
        'catalog'  => ['katalog', 'каталог', 'qanday printer', 'какие принтер', 'modellar', 'модели', 'assortiment', 'ассортимент'],
    ];

    /** Words that carry no meaning for matching taught Q&A. */
    private const STOPWORDS = [
        'va', 'bu', 'u', 'men', 'siz', 'biz', 'bor', 'mi', 'ham', 'uchun', 'qanday', 'nima', 'kerak', 'bilan',
        'и', 'в', 'на', 'с', 'по', 'а', 'у', 'я', 'вы', 'мы', 'это', 'как', 'что', 'ли', 'есть', 'для', 'мне', 'можно', 'ли',
    ];

    public function __construct(private KnowledgeBase $kb) {}

    /**
     * @param  array<int, array{role:string, content:string}>  $history
     */
    public function answer(string $question, string $lang, array $history = []): ?string
    {
        $q       = self::normalize($question);
        $intents = $this->intents($q);
        $words   = count(preg_split('/\s+/u', trim($q)));

        // 1. Small talk (only when the message is basically just that).
        if ($words <= 4 && $intents === ['greeting']) {
            return $this->t($lang, 'greeting');
        }
        if ($words <= 4 && in_array('thanks', $intents, true) && count($intents) === 1) {
            return $this->t($lang, 'thanks');
        }

        // 2. Admin-taught answers win over everything generated.
        if ($taught = $this->taughtAnswer($q, $lang, 0.6)) {
            return $taught;
        }

        // 2b. A described problem, how-to or definition ("prints blank receipt", "firmware nima",
        //     "драйвер что это") → knowledge-base article, before category/intent matching grabs a
        //     generic word. Skipped when the question is clearly about a place or an action
        //     (register, contacts, buy) — those have dedicated answers.
        $asksDefinition = (bool) preg_match('/\b(nima|nimadir|degani|что такое|что это|what is|what are)\b/u', $q);
        $structural     = $this->findPlace($q) !== null || array_intersect($intents, ['register', 'contacts', 'buy']);

        if (($words >= 3 || $asksDefinition) && !$structural && ($kb = $this->kbAnswer($q, $lang, null, 0.6))) {
            return $kb;
        }

        // 3. A specific model — in this question, or the one we were just talking about.
        $product = $this->findProduct($q, $lang)
            ?? (array_intersect($intents, ['price', 'drivers', 'specs', 'buy']) ? $this->productFromHistory($history, $lang) : null);

        if ($product) {
            // "XP-Q890K prints blank" → a how-to-fix article beats the generic product card.
            if ($words >= 3 && ($kb = $this->kbAnswer($q, $lang, $product['model']))) {
                return $kb;
            }

            return match (true) {
                in_array('drivers', $intents, true) => $this->drivers($product, $lang),
                in_array('price', $intents, true), in_array('buy', $intents, true) => $this->sellers($product, $lang),
                default => $this->productCard($product, $lang),
            };
        }

        // 4. General topics.
        $place = $this->findPlace($q);

        return match (true) {
            in_array('register', $intents, true)                                  => $this->register($lang),
            in_array('service', $intents, true)                                   => $this->partners($lang, 'service', $place),
            in_array('buy', $intents, true), $place !== null                      => $this->partners($lang, null, $place),
            in_array('warranty', $intents, true)                                  => $this->t($lang, 'warranty', ['url' => route('partners.index', ['type' => 'service'])]),
            in_array('delivery', $intents, true)                                  => $this->t($lang, 'delivery', ['url' => route('partners.index')]),
            in_array('price', $intents, true)                                     => $this->t($lang, 'price_general', ['url' => route('catalog')]),
            in_array('drivers', $intents, true)                                   => $this->t($lang, 'drivers_general', ['url' => route('catalog')]),
            in_array('contacts', $intents, true)                                  => $this->contacts($lang),
            ($category = $this->findCategory($q)) !== null                        => $this->categoryList($category, $lang),
            in_array('catalog', $intents, true)                                   => $this->catalogOverview($lang),
            default => $this->taughtAnswer($q, $lang, 0.4) ?? $this->kbAnswer($q, $lang) ?? $this->helpAnswer($q, $lang),
        };
    }

    // ── Matching ─────────────────────────────────────────────

    public static function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace(['ʻ', 'ʼ', '’', '‘', '`', 'ʹ'], "'", $text);

        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    private function intents(string $q): array
    {
        $found = [];

        foreach (self::INTENTS as $intent => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($q, $needle)) {
                    $found[] = $intent;
                    break;
                }
            }
        }

        return $found;
    }

    /** Model number match ("q890k", "XP-Q890K", "xp q890k"), longest model first. */
    private function findProduct(string $q, string $lang): ?array
    {
        $compactQ = KnowledgeBase::compact($q);
        $best     = null;

        foreach ($this->kb->products($lang) as $p) {
            $short = preg_replace('/^xp/', '', $p['compact']);

            foreach (array_unique([$p['compact'], $short]) as $needle) {
                if (mb_strlen($needle) >= 3 && preg_match('/\d/', $needle) && str_contains($compactQ, $needle)) {
                    if (!$best || mb_strlen($p['compact']) > mb_strlen($best['compact'])) {
                        $best = $p;
                    }
                }
            }
        }

        return $best;
    }

    private function productFromHistory(array $history, string $lang): ?array
    {
        foreach (array_reverse($history) as $turn) {
            if ($p = $this->findProduct(self::normalize($turn['content']), $lang)) {
                return $p;
            }
        }

        return null;
    }

    private function findCategory(string $q): ?array
    {
        $aliases = [
            'receipt'  => ['chek', 'чек', 'kassa', 'касс', 'receipt'],
            'label'    => ['etiket', 'этикет', 'shtrix', 'штрих', 'label', 'stiker', 'стикер', 'termoetiket'],
            'mobile'   => ['mobil', 'мобил', 'portativ', 'портатив', 'bluetooth', 'cho\'ntak', 'карман'],
            'wristband'=> ['bilakuzuk', 'браслет', 'wristband'],
        ];

        foreach ($this->kb->categories() as $cat) {
            $search = mb_strtolower($cat['search']);

            foreach ($aliases as $key => $words) {
                if (!str_contains($search, $key)) {
                    continue;
                }
                foreach ($words as $w) {
                    if (str_contains($q, $w)) {
                        return $cat;
                    }
                }
            }
        }

        return null;
    }

    /**
     * A region/city mentioned in the question, matched by name stem ("Samarqandda" → Samarqand).
     * Returns all language variants of that place, so partners can be matched in any language.
     *
     * @return array{label:string, names:array}|null
     */
    private function findPlace(string $q): ?array
    {
        $best = null;

        foreach ($this->kb->places() as $names) {
            foreach ($names as $name) {
                $base = mb_strtolower(preg_replace('/\s+(viloyati|shahri|область|город|region)$/u', '', $name));
                $stem = mb_substr($base, 0, max(4, min(6, mb_strlen($base) - 1)));

                if (mb_strlen($stem) < 4 || !str_contains($q, $stem)) {
                    continue;
                }

                $len = mb_strlen($stem);

                if (!$best || $len > $best['len']) {
                    $best = ['label' => $name, 'names' => $names, 'len' => $len];
                } elseif ($len === $best['len']) {
                    // Same name for city and region (Toshkent shahri / Toshkent viloyati) — cover both.
                    $best['names'] = array_values(array_unique(array_merge($best['names'], $names)));
                    $best['label'] = mb_strlen($name) < mb_strlen($best['label']) ? $name : $best['label'];
                }
            }
        }

        return $best;
    }

    private function taughtAnswer(string $q, string $lang, float $threshold): ?string
    {
        $qStems = $this->stems($q);

        if (!$qStems) {
            return null;
        }

        $best = null;
        $bestScore = 0;

        foreach ($this->kb->taught($lang) as $entry) {
            $eStems = $this->stems(self::normalize($entry->question));

            if (!$eStems) {
                continue;
            }

            $hits  = count(array_filter($eStems, fn ($e) => $this->stemMatches($e, $qStems)));
            $score = $hits / min(count($eStems), max(count($qStems), 1));

            if ($hits > 0 && $score > $bestScore) {
                $best = $entry;
                $bestScore = $score;
            }
        }

        return $best && $bestScore >= $threshold ? $best->answer : null;
    }

    /**
     * Knowledge-base article (troubleshooting / settings / glossary) whose title or keywords
     * overlap the question. Optional $model (e.g. "XP-Q890K") prefers articles tied to that model.
     */
    private function kbAnswer(string $q, string $lang, ?string $model = null, float $threshold = 0.5): ?string
    {
        // Model numbers ("q890k", "420b") are handled by product matching — drop them here so an
        // unknown model doesn't drag the score of "XP-Q890K bo'sh chek chiqaryapti" down.
        $qStems = array_values(array_filter($this->stems($q), fn ($s) => !preg_match('/\d/u', $s)));

        if (!$qStems) {
            return null;
        }

        $articles = $this->kb->articles($lang);
        if (!$articles) {
            return null;
        }

        // "драйвер что это", "firmware nima", "what is tspl" → one meaningful stem left:
        // answer only with a glossary entry whose title carries that term.
        if (count($qStems) === 1) {
            foreach ($articles as $article) {
                if ($article['type'] === 'glossary'
                    && $this->stemMatches($qStems[0], $this->stems(self::normalize($article['title_search'])))) {
                    return $this->kbReply($article, $lang);
                }
            }

            return null;
        }

        // Stems per article + how many articles each stem appears in, so "printer" / "chek" /
        // "печатает" (in nearly every article) weigh far less than "bo'sh", "иероглифы", "kalibrovka".
        $articleStems = [];
        $titleStems   = [];
        $df = [];
        foreach ($articles as $i => $article) {
            $stems = $this->stems(self::normalize($article['search']));
            $articleStems[$i] = $stems;
            $titleStems[$i]   = $this->stems(self::normalize($article['title_search']));
            foreach ($stems as $s) {
                $df[$s] = ($df[$s] ?? 0) + 1;
            }
        }
        $n = count($articles);
        // 1/df: a word found in one article is worth 1.0, in eight articles 0.125, unknown words 1.0 (they count against every article).
        $weight = fn (string $qStem): float => 1.0 / max(1, $this->docFrequency($qStem, $df, $n));

        $total = array_sum(array_map($weight, $qStems));
        if ($total <= 0) {
            return null;
        }

        $best      = null;
        $bestScore = 0.0;

        foreach ($articles as $i => $article) {
            $aStems = $articleStems[$i];
            if (!$aStems) {
                continue;
            }

            $matched = array_filter($qStems, fn ($s) => $this->stemMatches($s, $aStems));
            if (!$matched) {
                continue;
            }

            // Weighted share of the question this article explains…
            $score = array_sum(array_map($weight, $matched)) / $total;
            // …plus a small bonus when the words sit in the title (tie-break between sibling articles)…
            $inTitle = count(array_filter($matched, fn ($s) => $this->stemMatches($s, $titleStems[$i])));
            $score  += min(0.1, 0.05 * $inTitle);
            // …and a nudge for articles tied to the model we are talking about.
            if ($model && in_array($model, $article['models'], true)) {
                $score += 0.15;
            }

            if ($score > $bestScore) {
                $best      = $article;
                $bestScore = $score;
            }
        }

        if (!$best || $bestScore < $threshold) {
            return null;
        }

        return $this->kbReply($best, $lang);
    }

    private function kbReply(array $article, string $lang): string
    {
        return "{$article['title']}\n{$article['excerpt']}\n" . $this->t($lang, 'more', ['url' => $article['url']]);
    }

    /** Number of articles whose stems match this question stem (prefix-tolerant, like stemMatches). */
    private function docFrequency(string $qStem, array $df, int $n): int
    {
        if (isset($df[$qStem])) {
            return $df[$qStem];
        }

        $count = 0;
        foreach ($df as $stem => $c) {
            if ($this->stemMatches($qStem, [$stem])) {
                $count += $c;
            }
        }

        return min($count, $n);
    }

    private function helpAnswer(string $q, string $lang): ?string
    {
        $qStems = $this->stems($q);

        foreach ($this->kb->help($lang) as $article) {
            $tStems = $this->stems(self::normalize($article['title']));
            $hits   = count(array_filter($tStems, fn ($t) => $this->stemMatches($t, $qStems)));

            if ($tStems && $hits / count($tStems) >= 0.6) {
                return "{$article['title']}\n{$article['content']}";
            }
        }

        return null;
    }

    /** Meaningful words cut to a rough stem (first 5 letters) — good enough for uz/ru suffixes. */
    private function stems(string $text): array
    {
        $words = preg_split("/[^\p{L}\p{N}']+/u", $text, -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_unique(array_map(
            fn ($w) => mb_substr($w, 0, 5),
            array_filter($words, fn ($w) => mb_strlen($w) >= 3 && !in_array($w, self::STOPWORDS, true))
        )));
    }

    private function stemMatches(string $stem, array $stems): bool
    {
        foreach ($stems as $s) {
            if ($s === $stem || (mb_strlen($s) >= 4 && mb_strlen($stem) >= 4 && (str_starts_with($s, $stem) || str_starts_with($stem, $s)))) {
                return true;
            }
        }

        return false;
    }

    // ── Answers ──────────────────────────────────────────────

    private function productCard(array $p, string $lang): string
    {
        $title = str_contains(mb_strtolower($p['name']), mb_strtolower($p['model'])) ? $p['name'] : "{$p['name']} ({$p['model']})";
        $lines = ["{$title} — {$p['cat_name']}"];

        foreach (array_slice($p['specs'], 0, 6, true) as $k => $v) {
            $lines[] = "• {$k}: {$v}";
        }
        if ($p['text']) {
            $lines[] = $p['text'];
        }

        $lines[] = $this->t($lang, 'more', ['url' => $p['url']]);
        $lines[] = $p['sellers']
            ? $this->t($lang, 'sellers_count', ['n' => count($p['sellers'])])
            : $this->t($lang, 'no_sellers_short');

        return implode("\n", $lines);
    }

    private function sellers(array $p, string $lang): string
    {
        if (!$p['sellers']) {
            return $this->t($lang, 'no_sellers', ['model' => $p['model'], 'url' => route('partners.index')]) . "\n" . $this->contacts($lang);
        }

        $list = collect($p['sellers'])->take(5)
            ->map(fn ($s) => "• {$s['name']}" . ($s['phone'] ? " — {$s['phone']}" : '') . " — {$s['url']}")
            ->implode("\n");

        return $this->t($lang, 'sellers', ['model' => $p['model']]) . "\n{$list}\n" . $this->t($lang, 'price_note');
    }

    private function drivers(array $p, string $lang): string
    {
        if (!$p['files']) {
            return $this->t($lang, 'no_files', ['model' => $p['model'], 'url' => $p['url']]);
        }

        $list = collect($p['files'])
            ->sortBy(fn ($f) => $f['type'] === 'driver' ? 0 : 1)
            ->take(6)
            ->map(fn ($f) => "• {$f['name']}: {$f['url']}")
            ->implode("\n");

        return $this->t($lang, 'files', ['model' => $p['model']]) . "\n{$list}";
    }

    private function partners(string $lang, ?string $type, ?array $place): string
    {
        $all = collect($this->kb->partners());

        $found = $all
            ->when($type, fn ($c) => $c->filter(fn ($p) => in_array($type, $p['types'], true)))
            ->when($place, fn ($c) => $c->filter(fn ($p) => array_intersect($place['names'], $p['places'])))
            ->take(5);

        $url = route('partners.index', array_filter(['type' => $type]));

        if ($found->isEmpty()) {
            return $this->t($lang, $place ? 'no_partners_place' : 'no_partners', ['place' => $place['label'] ?? '', 'url' => $url]);
        }

        $list = $found->map(fn ($p) => '• ' . $p['name'] . ($p['auth'] ? ' ✓' : '')
            . ($p['places'] ? ' — ' . $p['places'][0] : '')
            . ($p['phone'] ? " — {$p['phone']}" : '')
            . " — {$p['url']}")->implode("\n");

        $key = $type === 'service' ? 'service_list' : 'partner_list';

        return $this->t($lang, $key, ['place' => $place ? " ({$place['label']})" : '']) . "\n{$list}\n" . $this->t($lang, 'all_partners', ['url' => $url]);
    }

    private function categoryList(array $cat, string $lang): string
    {
        $items = collect($this->kb->products($lang))->where('category', $cat['id'])->take(8)
            ->map(fn ($p) => "• {$p['model']} — {$p['url']}")->implode("\n");

        $name = $cat['names'][$lang] ?? reset($cat['names']);

        return $this->t($lang, 'category', ['name' => $name]) . "\n{$items}\n" . $this->t($lang, 'more', ['url' => $cat['url']]);
    }

    private function catalogOverview(string $lang): string
    {
        $lines = collect($this->kb->categories())->map(fn ($c) => '• ' . ($c['names'][$lang] ?? reset($c['names'])) . " — {$c['url']}");

        return $this->t($lang, 'catalog') . "\n" . $lines->implode("\n");
    }

    private function contacts(string $lang): string
    {
        $s = SiteSetting::current();
        $labels = $lang === 'ru'
            ? ['Телефон', 'Telegram', 'WhatsApp', 'Email', 'Адрес', 'Часы работы']
            : ['Telefon', 'Telegram', 'WhatsApp', 'Email', 'Manzil', 'Ish vaqti'];

        $rows = collect(array_combine($labels, [$s->phone, $s->telegram, $s->whatsapp, $s->email, $s->address, $s->work_time_display]))
            ->filter()
            ->map(fn ($v, $k) => "• {$k}: {$v}")
            ->implode("\n");

        return $this->t($lang, 'contacts') . "\n" . ($rows ? $rows . "\n" : '') . route('contact');
    }

    private function register(string $lang): string
    {
        return $this->t($lang, 'register', ['url' => route('register')]);
    }

    private function t(string $lang, string $key, array $vars = []): string
    {
        $text = self::TEXTS[$lang][$key] ?? self::TEXTS['uz'][$key];

        foreach ($vars as $k => $v) {
            $text = str_replace(":{$k}", (string) $v, $text);
        }

        return $text;
    }

    private const TEXTS = [
        'uz' => [
            'greeting'          => "Assalomu alaykum! Men Xprinter.uz yordamchisiman. Masalan, so'rang:\n• XP-T80A xususiyatlari\n• XP-365B drayveri\n• Samarqandda servis markazi\n• Qanday qilib diler bo'lish mumkin?",
            'thanks'            => "Arzimaydi! Yana savol bo'lsa, yozing.",
            'more'              => 'Batafsil: :url',
            'sellers_count'     => ":n ta tekshirilgan sotuvchida mavjud. Kim sotishini bilish uchun \"qayerdan sotib olsam bo'ladi?\" deb yozing.",
            'no_sellers_short'  => "Hozircha hech bir sotuvchida mavjud deb belgilanmagan.",
            'sellers'           => ':model ni sotadigan tekshirilgan sotuvchilar:',
            'price_note'        => "Narxlar har bir sotuvchida har xil — narxni to'g'ridan-to'g'ri sotuvchidan aniqlang.",
            'no_sellers'        => ":model hozircha hech bir sotuvchida mavjud emas. Barcha dilerlar: :url",
            'files'             => ':model uchun fayllar:',
            'no_files'          => ":model uchun fayllar hali yuklanmagan. Mahsulot sahifasi: :url",
            'service_list'      => 'Servis markazlari:place:',
            'partner_list'      => 'Tekshirilgan dilerlar va sotuvchilar:place:',
            'all_partners'      => "To'liq ro'yxat: :url",
            'no_partners'       => "Hozircha bunday hamkor yo'q. Barcha dilerlar va servis markazlari: :url",
            'no_partners_place' => ":place hududida hozircha tekshirilgan hamkor yo'q. Barcha hududlar: :url",
            'warranty'          => "Xprinter printerlariga O'zbekistonda 12 oy rasmiy kafolat beriladi. Kafolat ta'miri va texnik yordam tarmoqdagi servis markazlari orqali: :url",
            'delivery'          => "Toshkent bo'yicha yetkazib berish — odatda 1 kun, viloyatlarga — 3–7 kun. Aniq shartlarni tanlagan sotuvchingizdan so'rang: :url",
            'price_general'     => "Platformada bir nechta sotuvchi bor va har birining narxi o'zicha. Modelni tanlang (:url) — sahifasida uni kim sotishini ko'rasiz. Model nomini yozsangiz, sotuvchilarni ko'rsataman.",
            'drivers_general'   => "Drayverlar va qo'llanmalar har bir mahsulot sahifasida (\"Drayverlar va fayllar\"). Model nomini yozing, masalan: \"XP-365B drayver\". Katalog: :url",
            'contacts'          => 'Biz bilan aloqa:',
            'category'          => ':name:',
            'catalog'           => 'Katalogdagi printer turlari:',
            'register'          => "Platformaga diler yoki servis markazi sifatida qo'shilish:\n1. Ro'yxatdan o'ting: :url\n2. Kompaniya brendi, INN, telefon va faoliyat turini kiriting\n3. Administrator INN bo'yicha tekshiradi va tasdiqlaydi\nShundan so'ng assortiment va narxlaringizni qo'shasiz, mijozlar sizni katalogda topadi.",
        ],
        'ru' => [
            'greeting'          => "Здравствуйте! Я помощник Xprinter.uz. Например, спросите:\n• характеристики XP-T80A\n• драйвер XP-365B\n• сервис-центр в Самарканде\n• как стать дилером?",
            'thanks'            => 'Пожалуйста! Если будут вопросы — пишите.',
            'more'              => 'Подробнее: :url',
            'sellers_count'     => 'В наличии у проверенных продавцов: :n. Чтобы увидеть, кто продаёт, напишите «где купить?».',
            'no_sellers_short'  => 'Пока ни один продавец не отметил наличие.',
            'sellers'           => 'Проверенные продавцы :model:',
            'price_note'        => 'Цены у каждого продавца свои — уточняйте напрямую у продавца.',
            'no_sellers'        => 'Модель :model пока нет в наличии ни у одного продавца. Все дилеры: :url',
            'files'             => 'Файлы для :model:',
            'no_files'          => 'Для :model файлы ещё не загружены. Страница товара: :url',
            'service_list'      => 'Сервис-центры:place:',
            'partner_list'      => 'Проверенные дилеры и продавцы:place:',
            'all_partners'      => 'Полный список: :url',
            'no_partners'       => 'Пока таких партнёров нет. Все дилеры и сервис-центры: :url',
            'no_partners_place' => 'В регионе «:place» пока нет проверенных партнёров. Все регионы: :url',
            'warranty'          => 'На принтеры Xprinter действует официальная гарантия 12 месяцев на территории Узбекистана. Гарантийный ремонт и техподдержка — через сервис-центры сети: :url',
            'delivery'          => 'Доставка по Ташкенту — обычно 1 день, в регионы — 3–7 дней. Точные условия уточняйте у выбранного продавца: :url',
            'price_general'     => 'На платформе несколько продавцов, и у каждого свои цены. Выберите модель (:url) — на её странице видно, кто её продаёт. Напишите название модели, и я покажу продавцов.',
            'drivers_general'   => 'Драйверы и инструкции — на странице каждого товара («Драйверы и файлы»). Напишите модель, например: «драйвер XP-365B». Каталог: :url',
            'contacts'          => 'Наши контакты:',
            'category'          => ':name:',
            'catalog'           => 'Типы принтеров в каталоге:',
            'register'          => "Как подключиться к платформе как дилер или сервис-центр:\n1. Зарегистрируйтесь: :url\n2. Укажите бренд компании, ИНН, телефон и тип деятельности\n3. Администратор проверит компанию по ИНН и подтвердит\nПосле этого вы добавите ассортимент и цены, и клиенты найдут вас в каталоге.",
        ],
    ];
}
