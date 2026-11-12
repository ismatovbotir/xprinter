<?php

namespace Tests\Concerns;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;

/**
 * Small builders shared by feature tests. Kept explicit (no factories for
 * Company/Product yet) so a failing test reads top-to-bottom.
 */
trait MakesCompanies
{
    protected function makeCompany(array $attrs = []): Company
    {
        static $n = 0;
        $n++;

        return Company::create($attrs + [
            'name'                => "OOO Test {$n}",
            'brand'               => "Brand {$n}",
            'inn'                 => sprintf('3%08d', $n),
            'slug'                => "brand-{$n}",
            'phone'               => '+998901112233',
            'types'               => ['retail'],
            'status'              => 'approved',
            'vat_status'          => 'non_payer',
            'manufacturer_status' => 'none',
        ]);
    }

    protected function makeUser(string $role, ?Company $company = null, array $attrs = []): User
    {
        return User::factory()->create($attrs + [
            'role'       => $role,
            'company_id' => $company?->id,
            'lang'       => 'uz',
        ]);
    }

    protected function makeAdmin(): User
    {
        return $this->makeUser('admin');
    }

    protected function makeProducer(): User
    {
        return $this->makeUser('producer');
    }

    protected function makeOwnerWithCompany(array $companyAttrs = []): User
    {
        return $this->makeUser('owner', $this->makeCompany($companyAttrs));
    }

    protected function makeProduct(array $attrs = []): Product
    {
        static $n = 0;
        $n++;

        $category = Category::firstOrCreate(['slug' => 'receipt']);

        return Product::create($attrs + [
            'category_id'  => $category->id,
            'slug'         => "xp-test-{$n}",
            'model_number' => "XP-TEST-{$n}",
        ]);
    }

    protected function makeCity(): City
    {
        $country = Country::create(['code' => 'UZ']);
        $region  = Region::create(['country_id' => $country->id]);

        return City::create(['region_id' => $region->id]);
    }
}
