<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryParameter;
use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\Product;
use App\Models\ProductParameterValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
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
            // Integer type — bare numeric values, no per-language variation needed.
            'dpi' => ['uz' => 'Ruxsat', 'ru' => 'Разрешение', 'en' => 'Resolution', 'type' => 'integer',
                'values' => [
                    ['uz' => '203', 'ru' => '203', 'en' => '203'],
                    ['uz' => '300', 'ru' => '300', 'en' => '300'],
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
            // Boolean type — values are auto-seeded (Ha/Yo'q), never listed here.
            'autocutter' => ['uz' => 'Avtokesuvchi', 'ru' => 'Автообрезчик', 'en' => 'Auto-cutter', 'type' => 'boolean'],
        ];

        $result = [];
        foreach ($defs as $key => $def) {
            $existing = Parameter::whereHas('translations', fn($q) =>
                $q->where('lang', 'uz')->where('name', $def['uz'])
            )->first();

            $param = $existing ?? Parameter::create([]);
            $param->update(['type' => $def['type'] ?? 'string']);

            foreach (['uz', 'ru', 'en'] as $lang) {
                $param->translations()->updateOrCreate(['lang' => $lang], ['name' => $def[$lang]]);
            }

            $result[$key] = ['param' => $param, 'values' => []];

            if (($def['type'] ?? 'string') === 'boolean') {
                $valueDefs = [
                    ['uz' => 'Ha',   'ru' => 'Да',  'en' => 'Yes'],
                    ['uz' => "Yo'q", 'ru' => 'Нет', 'en' => 'No'],
                ];
            } else {
                $valueDefs = $def['values'] ?? [];
            }

            foreach ($valueDefs as $valDef) {
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
            $receipt->id   => ['paper_width', 'print_speed', 'interface', 'dpi', 'autocutter'],
            $label->id     => ['paper_width', 'print_speed', 'interface', 'dpi'],
            $mobile->id    => ['paper_width', 'print_speed', 'connectivity'],
            $wristband->id => ['paper_width', 'dpi', 'interface'],
        ];

        // Parameters the dealer picks per-listing — one Xprinter model number covers several
        // physical variants (e.g. same XP-Q890K sold with different interfaces).
        $variantKeys = [
            $receipt->id => ['interface'],
            $label->id   => ['interface'],
        ];

        foreach ($links as $catId => $paramKeys) {
            foreach ($paramKeys as $order => $key) {
                CategoryParameter::updateOrCreate(
                    ['category_id' => $catId, 'parameter_id' => $params[$key]['param']->id],
                    [
                        'sort_order' => $order + 1,
                        'is_variant' => in_array($key, $variantKeys[$catId] ?? [], true),
                    ]
                );
            }
        }
    }

    /**
     * Products come from database/seeders/data/products.json (models found on OLX.uz,
     * generic Chinese "XP-80 / XP-58" clones excluded). Re-running updates, never duplicates.
     */
    private function seedProducts(Category $receipt, Category $label, Category $mobile, Category $wristband, array $params): void
    {
        $categories = ['receipt' => $receipt, 'label' => $label, 'mobile' => $mobile, 'wristband' => $wristband];
        $products   = json_decode(file_get_contents(database_path('seeders/data/products.json')), true, flags: JSON_THROW_ON_ERROR);

        foreach ($products as $def) {
            $category = $categories[$def['category']] ?? null;
            if (!$category) {
                $this->command?->warn("Unknown category '{$def['category']}' for {$def['model']} — skipped");
                continue;
            }

            $product = Product::firstOrCreate(
                ['slug' => Str::slug($def['model'])],
                ['category_id' => $category->id, 'model_number' => $def['model']]
            );
            $product->update(['category_id' => $category->id, 'model_number' => $def['model']]);

            foreach (['uz', 'ru', 'en'] as $lang) {
                $product->translations()->updateOrCreate(['lang' => $lang], [
                    'name'        => $def[$lang]['name'],
                    'description' => $def[$lang]['description'] ?? '',
                ]);
            }

            // The JSON is the source of truth for specs: replace, don't accumulate.
            ProductParameterValue::where('product_id', $product->id)->delete();

            foreach ($def['specs'] ?? [] as $key => $value) {
                if (!isset($params[$key]) || $value === '' || $value === null) {
                    continue;
                }

                $pv = $this->parameterValue($params[$key]['param'], $key, (string) $value);

                ProductParameterValue::create([
                    'product_id'         => $product->id,
                    'parameter_value_id' => $pv->id,
                ]);
            }

            $this->ensurePhotos($product);
        }
    }

    /** Finds a parameter value by its uz name, creating it (with ru/en names) if the JSON uses a new one. */
    private function parameterValue(Parameter $param, string $key, string $value): ParameterValue
    {
        if ($key === 'autocutter') {
            $value = in_array(strtolower($value), ['yes', 'ha', '1', 'true'], true) ? 'Ha' : "Yo'q";
        }

        $existing = ParameterValue::where('parameter_id', $param->id)
            ->whereHas('translations', fn($q) => $q->where('lang', 'uz')->where('name', $value))
            ->first();

        if ($existing) {
            return $existing;
        }

        $pv = ParameterValue::create(['parameter_id' => $param->id]);
        $ru = str_replace(['mm/s', 'mm', 'inch'], ['мм/с', 'мм', 'дюйма'], $value);

        foreach (['uz' => $value, 'ru' => $ru, 'en' => $value] as $lang => $name) {
            $pv->translations()->create(['lang' => $lang, 'name' => $name]);
        }

        return $pv;
    }

    /**
     * The product form requires at least 4 photos before it can be saved — without this,
     * opening any seeded product in the admin panel to edit e.g. its description would
     * immediately fail validation. Generates simple placeholder images so every seeded
     * product already satisfies that minimum.
     */
    private function ensurePhotos(Product $product): void
    {
        if ($product->photos()->exists()) {
            return;
        }

        if (!function_exists('imagecreatetruecolor')) {
            return;
        }

        for ($i = 1; $i <= 4; $i++) {
            $product->photos()->create([
                'path'       => $this->placeholderImage($product->model_number, $i),
                'sort_order' => $i,
            ]);
        }

        $product->update(['photo' => $product->photos()->orderBy('sort_order')->value('path')]);
    }

    private function placeholderImage(string $modelNumber, int $index): string
    {
        $width = $height = 600;

        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, 234, 242, 253));
        imagerectangle($image, 16, 16, $width - 16, $height - 16, imagecolorallocate($image, 220, 233, 251));

        $ink = imagecolorallocate($image, 10, 27, 61);
        $label = "{$modelNumber} #{$index}";
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($label);
        imagestring($image, $font, (int) (($width - $textWidth) / 2), (int) ($height / 2), $label, $ink);

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        $path = 'products/' . Str::uuid() . '.png';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
