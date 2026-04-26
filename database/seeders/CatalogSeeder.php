<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryParameter;
use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        [$catReceipt, $catLabel, $catMobile, $catWristband] = $this->seedCategories();
        $params = $this->seedParameters();
        $this->linkParameters($catReceipt, $catLabel, $catMobile, $catWristband, $params);
        $this->seedProducts($catReceipt, $catLabel, $catMobile, $catWristband, $params);
    }

    private function seedCategories(): array
    {
        $cats = [
            ['slug' => 'receipt-printers', 'uz' => 'Chek printerlar', 'ru' => 'Принтеры чеков', 'en' => 'Receipt Printers'],
            ['slug' => 'label-printers',   'uz' => 'Etiketka printerlar', 'ru' => 'Принтеры этикеток', 'en' => 'Label Printers'],
            ['slug' => 'mobile-printers',  'uz' => 'Mobil printerlar', 'ru' => 'Мобильные принтеры', 'en' => 'Mobile Printers'],
            ['slug' => 'wristband-printers','uz' => 'Bilakuzuk printerlar', 'ru' => 'Принтеры для браслетов', 'en' => 'Wristband Printers'],
        ];

        $result = [];
        foreach ($cats as $c) {
            $cat = Category::firstOrCreate(['slug' => $c['slug']]);
            foreach (['uz', 'ru', 'en'] as $lang) {
                $cat->translations()->updateOrCreate(['lang' => $lang], ['name' => $c[$lang]]);
            }
            $result[] = $cat;
        }
        return $result;
    }

    private function seedParameters(): array
    {
        $defs = [
            'paper_width' => ['uz' => 'Qog\'oz kengligi', 'ru' => 'Ширина бумаги', 'en' => 'Paper Width',
                'values' => [
                    ['uz' => '58 mm', 'ru' => '58 мм', 'en' => '58 mm'],
                    ['uz' => '80 mm', 'ru' => '80 мм', 'en' => '80 mm'],
                    ['uz' => '2 inch (57 mm)', 'ru' => '2 дюйма (57 мм)', 'en' => '2 inch (57 mm)'],
                    ['uz' => '3 inch (80 mm)', 'ru' => '3 дюйма (80 мм)', 'en' => '3 inch (80 mm)'],
                    ['uz' => '4 inch (108 mm)', 'ru' => '4 дюйма (108 мм)', 'en' => '4 inch (108 mm)'],
                ],
            ],
            'print_speed' => ['uz' => 'Chop etish tezligi', 'ru' => 'Скорость печати', 'en' => 'Print Speed',
                'values' => [
                    ['uz' => '150 mm/s', 'ru' => '150 мм/с', 'en' => '150 mm/s'],
                    ['uz' => '200 mm/s', 'ru' => '200 мм/с', 'en' => '200 mm/s'],
                    ['uz' => '250 mm/s', 'ru' => '250 мм/с', 'en' => '250 mm/s'],
                    ['uz' => '127 mm/s', 'ru' => '127 мм/с', 'en' => '127 mm/s'],
                ],
            ],
            'dpi' => ['uz' => 'Ruxsat', 'ru' => 'Разрешение', 'en' => 'Resolution',
                'values' => [
                    ['uz' => '203 DPI', 'ru' => '203 DPI', 'en' => '203 DPI'],
                    ['uz' => '300 DPI', 'ru' => '300 DPI', 'en' => '300 DPI'],
                ],
            ],
            'interface' => ['uz' => 'Interfeys', 'ru' => 'Интерфейс', 'en' => 'Interface',
                'values' => [
                    ['uz' => 'USB', 'ru' => 'USB', 'en' => 'USB'],
                    ['uz' => 'USB + Serial + LAN', 'ru' => 'USB + Serial + LAN', 'en' => 'USB + Serial + LAN'],
                    ['uz' => 'USB + Ethernet', 'ru' => 'USB + Ethernet', 'en' => 'USB + Ethernet'],
                    ['uz' => 'USB + Bluetooth', 'ru' => 'USB + Bluetooth', 'en' => 'USB + Bluetooth'],
                    ['uz' => 'Bluetooth', 'ru' => 'Bluetooth', 'en' => 'Bluetooth'],
                ],
            ],
            'connectivity' => ['uz' => 'Ulanish', 'ru' => 'Подключение', 'en' => 'Connectivity',
                'values' => [
                    ['uz' => 'Simli', 'ru' => 'Проводное', 'en' => 'Wired'],
                    ['uz' => 'Simsiz (Wi-Fi)', 'ru' => 'Беспроводное (Wi-Fi)', 'en' => 'Wireless (Wi-Fi)'],
                    ['uz' => 'Bluetooth', 'ru' => 'Bluetooth', 'en' => 'Bluetooth'],
                ],
            ],
        ];

        $result = [];
        foreach ($defs as $key => $def) {
            $param = Parameter::firstOrCreate([]);
            // use first param without translations as a placeholder approach
            // Actually let's check if param with this translation exists
            $existing = Parameter::whereHas('translations', fn($q) =>
                $q->where('lang', 'uz')->where('name', $def['uz'])
            )->first();

            $param = $existing ?? Parameter::create([]);

            foreach (['uz', 'ru', 'en'] as $lang) {
                $param->translations()->updateOrCreate(['lang' => $lang], ['name' => $def[$lang]]);
            }

            $result[$key] = ['param' => $param, 'values' => []];

            foreach ($def['values'] as $valDef) {
                $existingVal = ParameterValue::where('parameter_id', $param->id)
                    ->whereHas('translations', fn($q) =>
                        $q->where('lang', 'uz')->where('name', $valDef['uz'])
                    )->first();

                $pv = $existingVal ?? ParameterValue::create(['parameter_id' => $param->id]);

                foreach (['uz', 'ru', 'en'] as $lang) {
                    $pv->translations()->updateOrCreate(['lang' => $lang], ['name' => $valDef[$lang]]);
                }

                $result[$key]['values'][$valDef['uz']] = $pv;
            }
        }

        return $result;
    }

    private function linkParameters(Category $receipt, Category $label, Category $mobile, Category $wristband, array $params): void
    {
        $links = [
            $receipt->id   => ['paper_width', 'print_speed', 'interface', 'dpi'],
            $label->id     => ['paper_width', 'print_speed', 'interface', 'dpi'],
            $mobile->id    => ['paper_width', 'print_speed', 'connectivity'],
            $wristband->id => ['paper_width', 'dpi', 'interface'],
        ];

        foreach ($links as $catId => $paramKeys) {
            foreach ($paramKeys as $order => $key) {
                CategoryParameter::firstOrCreate([
                    'category_id'  => $catId,
                    'parameter_id' => $params[$key]['param']->id,
                ], ['sort_order' => $order + 1]);
            }
        }
    }

    private function seedProducts(Category $receipt, Category $label, Category $mobile, Category $wristband, array $params): void
    {
        $v = fn(string $param, string $name) => $params[$param]['values'][$name] ?? null;

        $products = [
            // ── Receipt ──────────────────────────────────────────────
            [
                'category' => $receipt, 'model' => 'XP-Q890K',
                'uz' => 'Chek printeri XP-Q890K', 'ru' => 'Принтер чеков XP-Q890K', 'en' => 'Receipt Printer XP-Q890K',
                'desc_uz' => '80 mm, supermarketlar uchun eng mashhur model',
                'desc_ru' => '80 мм, самая популярная модель для супермаркетов',
                'desc_en' => '80 mm, most popular model for supermarkets',
                'params' => [
                    $v('paper_width', '80 mm'),
                    $v('print_speed', '250 mm/s'),
                    $v('interface', 'USB + Serial + LAN'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            [
                'category' => $receipt, 'model' => 'XP-Q809K',
                'uz' => 'Chek printeri XP-Q809K', 'ru' => 'Принтер чеков XP-Q809K', 'en' => 'Receipt Printer XP-Q809K',
                'desc_uz' => '80 mm stol usti modeli',
                'desc_ru' => '80 мм настольная модель',
                'desc_en' => '80 mm desktop model',
                'params' => [
                    $v('paper_width', '80 mm'),
                    $v('print_speed', '200 mm/s'),
                    $v('interface', 'USB + Serial + LAN'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            [
                'category' => $receipt, 'model' => 'XP-V320N',
                'uz' => 'Chek printeri XP-V320N', 'ru' => 'Принтер чеков XP-V320N', 'en' => 'Receipt Printer XP-V320N',
                'desc_uz' => '80 mm, avtokalibratsiya bilan',
                'desc_ru' => '80 мм с автокалибровкой резака',
                'desc_en' => '80 mm with auto-calibration cutter',
                'params' => [
                    $v('paper_width', '80 mm'),
                    $v('print_speed', '250 mm/s'),
                    $v('interface', 'USB + Ethernet'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            [
                'category' => $receipt, 'model' => 'XP-58IIH',
                'uz' => 'Chek printeri XP-58IIH', 'ru' => 'Принтер чеков XP-58IIH', 'en' => 'Receipt Printer XP-58IIH',
                'desc_uz' => '58 mm, kafe va restoran uchun byudjetli model',
                'desc_ru' => '58 мм, бюджетная модель для кафе и ресторанов',
                'desc_en' => '58 mm, budget model for cafes and restaurants',
                'params' => [
                    $v('paper_width', '58 mm'),
                    $v('print_speed', '150 mm/s'),
                    $v('interface', 'USB'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            // ── Label ─────────────────────────────────────────────────
            [
                'category' => $label, 'model' => 'XP-428B',
                'uz' => 'Etiketka printeri XP-428B', 'ru' => 'Принтер этикеток XP-428B', 'en' => 'Label Printer XP-428B',
                'desc_uz' => '4 inch, shtrix-kodlar va marketplace etiketkalari uchun',
                'desc_ru' => '4 дюйма, для штрихкодов и этикеток маркетплейсов',
                'desc_en' => '4 inch, for barcodes and marketplace labels',
                'params' => [
                    $v('paper_width', '4 inch (108 mm)'),
                    $v('print_speed', '127 mm/s'),
                    $v('interface', 'USB'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            [
                'category' => $label, 'model' => 'XP-490B',
                'uz' => 'Etiketka printeri XP-490B', 'ru' => 'Принтер этикеток XP-490B', 'en' => 'Label Printer XP-490B',
                'desc_uz' => '4 inch termal etiketka printeri',
                'desc_ru' => '4 дюйма термальный принтер этикеток',
                'desc_en' => '4 inch thermal label printer',
                'params' => [
                    $v('paper_width', '4 inch (108 mm)'),
                    $v('print_speed', '127 mm/s'),
                    $v('interface', 'USB + Ethernet'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            [
                'category' => $label, 'model' => 'XP-365B',
                'uz' => 'Etiketka printeri XP-365B', 'ru' => 'Принтер этикеток XP-365B', 'en' => 'Label Printer XP-365B',
                'desc_uz' => '3 inch (80 mm) etiketka printeri',
                'desc_ru' => '3 дюйма (80 мм) принтер этикеток',
                'desc_en' => '3 inch (80 mm) label printer',
                'params' => [
                    $v('paper_width', '3 inch (80 mm)'),
                    $v('print_speed', '127 mm/s'),
                    $v('interface', 'USB'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            [
                'category' => $label, 'model' => 'XP-235B',
                'uz' => 'Etiketka printeri XP-235B', 'ru' => 'Принтер этикеток XP-235B', 'en' => 'Label Printer XP-235B',
                'desc_uz' => '2 inch (58 mm) etiketka printeri',
                'desc_ru' => '2 дюйма (58 мм) принтер этикеток',
                'desc_en' => '2 inch (58 mm) label printer',
                'params' => [
                    $v('paper_width', '2 inch (57 mm)'),
                    $v('print_speed', '127 mm/s'),
                    $v('interface', 'USB'),
                    $v('dpi', '203 DPI'),
                ],
            ],
            // ── Mobile ────────────────────────────────────────────────
            [
                'category' => $mobile, 'model' => 'XP-P816',
                'uz' => 'Mobil printer XP-P816', 'ru' => 'Мобильный принтер XP-P816', 'en' => 'Mobile Printer XP-P816',
                'desc_uz' => '4 inch bluetooth mobil etiketka printeri',
                'desc_ru' => '4 дюйма Bluetooth мобильный принтер этикеток',
                'desc_en' => '4 inch Bluetooth mobile label printer',
                'params' => [
                    $v('paper_width', '4 inch (108 mm)'),
                    $v('print_speed', '127 mm/s'),
                    $v('connectivity', 'Bluetooth'),
                ],
            ],
            [
                'category' => $mobile, 'model' => 'MP3',
                'uz' => 'Pocket printer MP3', 'ru' => 'Карманный принтер MP3', 'en' => 'Pocket Printer MP3',
                'desc_uz' => '3 inch pocket printer, chek va etiketka',
                'desc_ru' => '3 дюйма карманный, чеки и этикетки',
                'desc_en' => '3 inch pocket, receipts and labels',
                'params' => [
                    $v('paper_width', '3 inch (80 mm)'),
                    $v('print_speed', '150 mm/s'),
                    $v('connectivity', 'Bluetooth'),
                ],
            ],
            // ── Wristband ─────────────────────────────────────────────
            [
                'category' => $wristband, 'model' => 'XP-D281B',
                'uz' => 'Bilakuzuk printeri XP-D281B', 'ru' => 'Принтер браслетов XP-D281B', 'en' => 'Wristband Printer XP-D281B',
                'desc_uz' => '2 inch, tibbiyot va mehmonxonalar uchun',
                'desc_ru' => '2 дюйма, для медицины и отелей',
                'desc_en' => '2 inch, for medical and hospitality',
                'params' => [
                    $v('paper_width', '2 inch (57 mm)'),
                    $v('dpi', '203 DPI'),
                    $v('interface', 'USB'),
                ],
            ],
            [
                'category' => $wristband, 'model' => 'XP-T261B',
                'uz' => 'Bilakuzuk printeri XP-T261B', 'ru' => 'Принтер браслетов XP-T261B', 'en' => 'Wristband Printer XP-T261B',
                'desc_uz' => '2 inch termal bilakuzuk printeri',
                'desc_ru' => '2 дюйма термальный принтер браслетов',
                'desc_en' => '2 inch thermal wristband printer',
                'params' => [
                    $v('paper_width', '2 inch (57 mm)'),
                    $v('dpi', '300 DPI'),
                    $v('interface', 'USB + Ethernet'),
                ],
            ],
        ];

        foreach ($products as $def) {
            $slug = Str::slug($def['model']);
            $existing = Product::where('slug', $slug)->first();
            $product = $existing ?? Product::create([
                'category_id'  => $def['category']->id,
                'slug'         => $slug,
                'model_number' => $def['model'],
            ]);

            foreach (['uz', 'ru', 'en'] as $lang) {
                $product->translations()->updateOrCreate(['lang' => $lang], [
                    'name'        => $def[$lang],
                    'description' => $def["desc_{$lang}"] ?? '',
                ]);
            }

            foreach ($def['params'] as $pv) {
                if ($pv) {
                    \App\Models\ProductParameterValue::firstOrCreate([
                        'product_id'         => $product->id,
                        'parameter_value_id' => $pv->id,
                    ]);
                }
            }
        }
    }
}
