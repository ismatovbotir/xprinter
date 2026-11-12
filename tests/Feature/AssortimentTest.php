<?php

namespace Tests\Feature;

use App\Models\CompanyProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class AssortimentTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    public function test_owner_adds_product_with_retail_and_partner_prices(): void
    {
        $owner   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();

        $this->actingAs($owner)->post(route('marketplace.assortiment.store'), [
            'product_id'      => $product->id,
            'price_retail'    => 1250000,
            'price_wholesale' => 1100000,
            'currency'        => 'uzs',
            'is_available'    => 1,
            'quantity'        => 12,
        ])->assertRedirect(route('marketplace.assortiment.index'));

        $cp = CompanyProduct::where('company_id', $owner->company_id)->where('product_id', $product->id)->firstOrFail();
        $this->assertTrue($cp->is_available);
        $this->assertSame(12, $cp->quantity);
        $this->assertSame(1250000, $cp->retailPrice()->value);
        $this->assertSame(1100000, $cp->wholesalePrice()->value);

        $this->assertDatabaseHas('activity_log', ['log_name' => 'prices', 'event' => 'created', 'causer_id' => $owner->id]);
    }

    public function test_both_prices_are_required(): void
    {
        $owner   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();

        $this->actingAs($owner)->post(route('marketplace.assortiment.store'), [
            'product_id'   => $product->id,
            'price_retail' => 1250000,
            'currency'     => 'uzs',
        ])->assertSessionHasErrors('price_wholesale');
    }

    public function test_same_product_cannot_be_added_twice(): void
    {
        $owner   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();
        CompanyProduct::create(['company_id' => $owner->company_id, 'product_id' => $product->id]);

        $this->actingAs($owner)->post(route('marketplace.assortiment.store'), [
            'product_id'      => $product->id,
            'price_retail'    => 1,
            'price_wholesale' => 1,
            'currency'        => 'uzs',
        ])->assertSessionHas('error');

        $this->assertSame(1, CompanyProduct::where('company_id', $owner->company_id)->count());
    }

    public function test_update_changes_prices_and_is_audited(): void
    {
        $owner   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();
        $cp      = CompanyProduct::create(['company_id' => $owner->company_id, 'product_id' => $product->id]);
        $cp->prices()->create(['type' => 'retail', 'value' => 100, 'currency' => 'uzs']);
        $cp->prices()->create(['type' => 'wholesale', 'value' => 90, 'currency' => 'uzs']);

        $this->actingAs($owner)->put(route('marketplace.assortiment.update', $cp), [
            'price_retail'    => 200,
            'price_wholesale' => 180,
            'currency'        => 'uzs',
            'is_available'    => 0,
        ])->assertRedirect(route('marketplace.assortiment.index'));

        $this->assertSame(200, $cp->retailPrice()->value);
        $this->assertSame(180, $cp->wholesalePrice()->value);
        $this->assertFalse($cp->fresh()->is_available);
        $this->assertDatabaseHas('activity_log', ['log_name' => 'prices', 'event' => 'updated']);
    }

    public function test_other_company_cannot_touch_my_items(): void
    {
        $mine    = $this->makeOwnerWithCompany();
        $other   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();
        $cp      = CompanyProduct::create(['company_id' => $mine->company_id, 'product_id' => $product->id]);

        $this->actingAs($other)->get(route('marketplace.assortiment.edit', $cp))->assertForbidden();
        $this->actingAs($other)->delete(route('marketplace.assortiment.destroy', $cp))->assertForbidden();

        $this->assertDatabaseHas('company_products', ['id' => $cp->id]);
    }
}
