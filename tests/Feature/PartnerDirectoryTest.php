<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerDirectoryTest extends TestCase
{
    use RefreshDatabase;

    private function company(array $attrs = []): Company
    {
        static $n = 0;
        $n++;

        return Company::create($attrs + [
            'name'   => "OOO Test {$n}",
            'brand'  => "Brand {$n}",
            'inn'    => "30000000{$n}",
            'slug'   => "brand-{$n}",
            'phone'  => '+998901112233',
            'types'  => ['retail'],
            'status' => 'approved',
            'vat_status' => 'non_payer',
            'manufacturer_status' => 'none',
        ]);
    }

    public function test_lists_only_approved_companies_without_private_data(): void
    {
        $this->company(['brand' => 'Visible Print', 'inn' => '123456789']);
        $this->company(['brand' => 'Hidden Pending', 'status' => 'pending']);

        $this->get(route('partners.index'))
            ->assertOk()
            ->assertSee('Visible Print')
            ->assertDontSee('Hidden Pending')
            ->assertDontSee('123456789');
    }

    public function test_filters_by_type(): void
    {
        $this->company(['brand' => 'Fix Center', 'types' => ['service']]);
        $this->company(['brand' => 'Shop Only', 'types' => ['retail']]);

        $this->get(route('partners.index', ['type' => 'service']))
            ->assertOk()
            ->assertSee('Fix Center')
            ->assertDontSee('Shop Only');
    }

    public function test_show_page_and_product_sellers_block(): void
    {
        $category = Category::create(['slug' => 'receipt']);
        $product  = Product::create(['category_id' => $category->id, 'slug' => 'xp-q890k', 'model_number' => 'XP-Q890K']);
        $seller   = $this->company(['brand' => 'Q Seller', 'manufacturer_status' => 'authorized_partner']);
        CompanyProduct::create(['company_id' => $seller->id, 'product_id' => $product->id, 'is_available' => true]);

        $this->get(route('partners.show', $seller->slug))->assertOk()->assertSee('Q Seller')->assertSee('XP-Q890K');
        $this->get(route('products.show', ['receipt', 'xp-q890k']))->assertOk()->assertSee('Q Seller');
    }

    public function test_pending_company_page_is_404(): void
    {
        $c = $this->company(['status' => 'pending']);

        $this->get(route('partners.show', $c->slug))->assertNotFound();
    }
}
