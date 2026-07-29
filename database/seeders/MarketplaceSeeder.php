<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\CompanyProductParameterValue;
use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * One demo dealer with a small assortment — exercises the marketplace side of the
 * schema (Company, CompanyProduct, Price, CompanyProductParameterValue) so a fresh
 * install has data to click through instead of empty tables.
 */
class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $company = $this->seedCompany();
        $this->seedOwner($company);
        $this->seedAssortment($company);
    }

    private function seedCompany(): Company
    {
        $company = Company::firstOrCreate(
            ['inn' => '123456789'],
            [
                'name'                => 'MirKons Print Servis',
                'brand'               => 'MirKons',
                'phone'               => '+998901112233',
                'legal_form'          => 'MChJ',
                'slug'                => 'mirkons-print-servis',
                'types'               => ['retail', 'service'],
                'status'              => 'approved',
                'vat_status'          => 'payer',
                'manufacturer_status' => 'authorized_partner',
            ]
        );

        $city = City::first();
        if ($city && $company->addresses()->doesntExist()) {
            Address::create([
                'company_id' => $company->id,
                'city_id'    => $city->id,
                'name'       => 'Asosiy ombor',
            ]);
        }

        return $company;
    }

    private function seedOwner(Company $company): void
    {
        User::firstOrCreate(
            ['email' => 'dealer@xprinter.uz'],
            [
                'name'       => 'Dilshod',
                'last_name'  => 'Karimov',
                'role'       => 'owner',
                'company_id' => $company->id,
                'lang'       => 'uz',
                'phone'      => '+998901112233',
                'password'   => 'password',
            ]
        );
    }

    private function seedAssortment(Company $company): void
    {
        $items = [
            ['model' => 'XP-Q890K', 'retail' => 2_450_000, 'wholesale' => 2_150_000, 'interface' => 'USB + Serial + LAN'],
            ['model' => 'XP-428B',  'retail' => 3_200_000, 'wholesale' => 2_850_000, 'interface' => 'USB'],
            ['model' => 'MP3',      'retail' => 1_800_000, 'wholesale' => 1_550_000, 'interface' => null],
        ];

        foreach ($items as $item) {
            $product = Product::where('model_number', $item['model'])->first();
            if (!$product) {
                continue;
            }

            $companyProduct = CompanyProduct::firstOrCreate(
                ['company_id' => $company->id, 'product_id' => $product->id],
                ['is_available' => true, 'quantity' => 10]
            );

            Price::updateOrCreate(
                ['company_product_id' => $companyProduct->id, 'type' => 'retail'],
                ['value' => $item['retail'], 'currency' => 'uzs']
            );
            Price::updateOrCreate(
                ['company_product_id' => $companyProduct->id, 'type' => 'wholesale'],
                ['value' => $item['wholesale'], 'currency' => 'uzs']
            );

            if ($item['interface']) {
                $this->seedVariantSelection($companyProduct, $product, $item['interface']);
            }
        }
    }

    private function seedVariantSelection(CompanyProduct $companyProduct, Product $product, string $interfaceLabel): void
    {
        $interfaceParam = Parameter::whereHas('translations', fn($q) =>
            $q->where('lang', 'uz')->where('name', 'Interfeys')
        )->first();

        $isVariant = $interfaceParam
            ?->categories()
            ->where('category_id', $product->category_id)
            ->wherePivot('is_variant', true)
            ->exists();

        if (!$interfaceParam || !$isVariant) {
            return;
        }

        $value = ParameterValue::where('parameter_id', $interfaceParam->id)
            ->whereHas('translations', fn($q) => $q->where('lang', 'uz')->where('name', $interfaceLabel))
            ->first();

        if (!$value) {
            return;
        }

        CompanyProductParameterValue::updateOrCreate(
            ['company_product_id' => $companyProduct->id, 'parameter_id' => $interfaceParam->id],
            ['parameter_value_id' => $value->id]
        );
    }
}
