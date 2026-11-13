<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotKnowledge;
use App\Models\Company;
use App\Models\HelpArticle;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

/**
 * Read-only, cached snapshot of the site data the bot answers from:
 * products (specs, files, sellers), verified partners, admin-taught Q&A, dealer help articles.
 */
class KnowledgeBase
{
    private const TTL = 600;

    /** @return array<int, array> */
    public function products(string $lang): array
    {
        return Cache::remember("chatbot:products:{$lang}", self::TTL, fn () => Product::with([
            'translations',
            'category.translations',
            'parameterValues.translations',
            'parameterValues.parameter.translations',
            'files',
            'companyProducts' => fn ($q) => $q->where('is_available', true)
                ->whereHas('company', fn ($c) => $c->where('status', 'approved'))
                ->with('company:id,name,brand,slug,phone'),
        ])->get()->map(function (Product $p) use ($lang) {
            $t = $p->translations->firstWhere('lang', $lang) ?? $p->translations->first();

            return [
                'id'        => $p->id,
                'model'     => (string) $p->model_number,
                'compact'   => self::compact((string) $p->model_number),
                'name'      => $t?->name ?: $p->model_number,
                'text'      => str(strip_tags((string) $t?->description))->squish()->limit(220)->toString(),
                'category'  => $p->category_id,
                'cat_name'  => $p->category?->translations->firstWhere('lang', $lang)?->name ?? $p->category?->slug,
                'url'       => $p->category ? route('products.show', [$p->category->slug, $p->slug]) : route('catalog'),
                'specs'     => $p->parameterValues->mapWithKeys(fn ($v) => [
                    ($v->parameter?->translations->firstWhere('lang', $lang)?->name ?? '—')
                        => $v->translations->firstWhere('lang', $lang)?->name ?? $v->translations->first()?->name,
                ])->filter()->all(),
                'files'     => $p->files->map(fn ($f) => [
                    'name' => $f->name ?: $f->file_name,
                    'type' => $f->pivot->type,
                    'url'  => $f->url ? url($f->url) : null,
                ])->filter(fn ($f) => $f['url'])->values()->all(),
                'sellers'   => $p->companyProducts->pluck('company')->filter()->map(fn ($c) => [
                    'name'  => $c->displayName(),
                    'phone' => $c->phone,
                    'url'   => route('partners.show', $c->slug),
                ])->values()->all(),
                // Every language, so a question in either language finds the product.
                'search'    => implode(' ', [
                    $p->model_number,
                    $p->translations->pluck('name')->implode(' '),
                    $p->category?->translations->pluck('name')->implode(' '),
                ]),
            ];
        })->all());
    }

    /** @return array<int, array{id:int, names:array, search:string, url:string}> */
    public function categories(): array
    {
        return Cache::remember('chatbot:categories', self::TTL, fn () => \App\Models\Category::with('translations')->get()
            ->map(fn ($c) => [
                'id'     => $c->id,
                'names'  => $c->translations->pluck('name', 'lang')->all(),
                'search' => $c->slug . ' ' . $c->translations->pluck('name')->implode(' '),
                'url'    => route('catalog.category', $c->slug),
            ])->all());
    }

    /** @return array<int, array> */
    public function partners(): array
    {
        return Cache::remember('chatbot:partners', self::TTL, fn () => Company::where('status', 'approved')
            ->with(['addresses.city.translations', 'addresses.city.region.translations', 'contacts'])
            ->get()
            ->map(fn (Company $c) => [
                'name'   => $c->displayName(),
                'types'  => $c->types ?? [],
                'auth'   => $c->isAuthorized(),
                'phone'  => collect([$c->phone])->merge($c->contacts->pluck('number'))->filter()->first(),
                'places' => $c->addresses->flatMap(fn ($a) => array_merge(
                    $a->city?->translations->pluck('name')->all() ?? [],
                    $a->city?->region?->translations->pluck('name')->all() ?? [],
                ))->filter()->unique()->values()->all(),
                'url'    => route('partners.show', $c->slug),
            ])
            ->sortByDesc('auth')
            ->values()
            ->all());
    }

    /** Every region and city with its names in all languages (for "…in Samarqand" questions). */
    public function places(): array
    {
        return Cache::remember('chatbot:places', 3600, fn () => array_merge(
            \App\Models\Region::with('translations')->get()->map(fn ($r) => $r->translations->pluck('name')->filter()->unique()->values()->all())->all(),
            \App\Models\City::with('translations')->get()->map(fn ($c) => $c->translations->pluck('name')->filter()->unique()->values()->all())->all(),
        ));
    }

    public function taught(string $lang)
    {
        return Cache::remember('chatbot:knowledge', self::TTL, fn () => ChatbotKnowledge::where('is_active', true)->get())
            ->filter(fn ($e) => $e->lang === null || $e->lang === $lang);
    }

    /** Dealer-facing help articles (marketplace sections) in the given language. */
    public function help(string $lang): array
    {
        return Cache::remember("chatbot:help:{$lang}", self::TTL, fn () => HelpArticle::active()
            ->where('section', 'like', 'marketplace.%')
            ->with(['translations' => fn ($q) => $q->where('lang', $lang)])
            ->get()
            ->map(fn ($a) => $a->translations->first())
            ->filter()
            ->map(fn ($t) => [
                'title'   => $t->title,
                'content' => str(strip_tags($t->content))->squish()->limit(500)->toString(),
            ])
            ->values()
            ->all());
    }

    /**
     * Published knowledge-base articles (how to fix / configure, what a driver or tool is).
     *
     * @return array<int, array{title:string, excerpt:string, search:string, url:string, type:string, models:array}>
     */
    public function articles(string $lang): array
    {
        return Cache::remember("chatbot:kb:{$lang}", self::TTL, fn () => \App\Models\KbArticle::published()
            ->with(['translations', 'products:id,model_number'])
            ->orderBy('sort_order')
            ->get()
            ->map(function (\App\Models\KbArticle $a) use ($lang) {
                $t = $a->translations->firstWhere('lang', $lang) ?? $a->translations->firstWhere('lang', 'uz') ?? $a->translations->first();
                if (!$t) {
                    return null;
                }

                return [
                    'title'   => $t->title,
                    'excerpt' => $t->excerpt ?: str($t->plainText())->limit(400)->toString(),
                    'type'    => $a->type,
                    'url'     => route('support.show', $a->slug),
                    'models'  => $a->products->pluck('model_number')->all(),
                    // Titles in every language + keywords, so uz/ru questions both match.
                    'search'  => implode(' ', array_filter([
                        $a->translations->pluck('title')->implode(' '),
                        (string) $a->keywords,
                    ])),
                    // Titles only — a hit here is a stronger signal than a hit in keywords.
                    'title_search' => $a->translations->pluck('title')->implode(' '),
                ];
            })
            ->filter()
            ->values()
            ->all());
    }

    /** "XP-Q890K" → "xpq890k". */
    public static function compact(string $text): string
    {
        return preg_replace('/[^\p{L}\p{N}]+/u', '', mb_strtolower($text));
    }

    public static function forgetCache(): void
    {
        foreach (['chatbot:knowledge', 'chatbot:partners', 'chatbot:categories', 'chatbot:places'] as $key) {
            Cache::forget($key);
        }
        foreach (['uz', 'ru', 'en'] as $lang) {
            Cache::forget("chatbot:products:{$lang}");
            Cache::forget("chatbot:help:{$lang}");
            Cache::forget("chatbot:kb:{$lang}");
        }
    }
}
