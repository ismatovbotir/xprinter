<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    public function test_company_changes_are_recorded_with_old_and_new_values(): void
    {
        $admin   = $this->makeAdmin();
        $company = $this->makeCompany(['status' => 'pending', 'brand' => 'Audit Co']);

        $this->actingAs($admin)
            ->patch(route('admin.companies.approve', $company), ['admin_note' => 'checked'])
            ->assertRedirect();

        $this->actingAs($admin)->get(route('admin.activity.index'))
            ->assertOk()
            ->assertSee('Audit Co')
            ->assertSee('status')
            ->assertSee('approved')
            ->assertSee($admin->name);
    }

    public function test_untouched_attributes_do_not_produce_log_rows(): void
    {
        $company = $this->makeCompany();
        $before  = \Spatie\Activitylog\Models\Activity::count();

        $company->update(['brand' => $company->brand]); // no-op

        $this->assertSame($before, \Spatie\Activitylog\Models\Activity::count());
    }

    public function test_activity_page_filters_by_log_name_and_is_admin_only(): void
    {
        $admin   = $this->makeAdmin();
        $owner   = $this->makeOwnerWithCompany();
        $product = $this->makeProduct();

        $this->actingAs($owner)->post(route('marketplace.assortiment.store'), [
            'product_id'      => $product->id,
            'price_retail'    => 500,
            'price_wholesale' => 400,
            'currency'        => 'uzs',
        ]);

        $this->actingAs($admin)->get(route('admin.activity.index', ['log' => 'prices']))
            ->assertOk()
            ->assertSee('prices');

        $this->actingAs($owner)->get(route('admin.activity.index'))->assertForbidden();
    }
}
