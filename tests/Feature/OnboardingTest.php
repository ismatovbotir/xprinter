<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    public function test_owner_submits_company_and_waits_for_approval(): void
    {
        $owner = $this->makeUser('owner');
        $city  = $this->makeCity();

        $this->actingAs($owner)->get(route('marketplace.onboarding'))->assertOk();

        $this->actingAs($owner)->post(route('marketplace.onboarding.store'), [
            'brand'   => 'Print Master',
            'inn'     => '305123456',
            'phone'   => '+998901234567',
            'types'   => ['retail', 'service'],
            'city_id' => $city->id,
        ])->assertRedirect(route('marketplace.pending'));

        $company = Company::where('inn', '305123456')->firstOrFail();
        $this->assertSame('pending', $company->status);
        $this->assertSame('non_payer', $company->vat_status);
        $this->assertSame('print-master', $company->slug);
        $this->assertSame(['retail', 'service'], $company->types);
        $this->assertSame($company->id, $owner->fresh()->company_id);
        $this->assertDatabaseHas('addresses', ['company_id' => $company->id, 'city_id' => $city->id]);

        // Dashboard stays locked until admin approves
        $this->actingAs($owner->fresh())->get('/marketplace')->assertRedirect(route('marketplace.pending'));
    }

    public function test_duplicate_inn_is_rejected(): void
    {
        $this->makeCompany(['inn' => '305000001']);
        $owner = $this->makeUser('owner');
        $city  = $this->makeCity();

        $this->actingAs($owner)->post(route('marketplace.onboarding.store'), [
            'brand'   => 'Dup',
            'inn'     => '305000001',
            'phone'   => '+998901234567',
            'types'   => ['retail'],
            'city_id' => $city->id,
        ])->assertSessionHasErrors('inn');
    }

    public function test_admin_approval_unlocks_marketplace_and_is_audited(): void
    {
        $owner   = $this->makeOwnerWithCompany(['status' => 'pending']);
        $company = $owner->company;
        $admin   = $this->makeAdmin();

        $this->actingAs($admin)
            ->patch(route('admin.companies.approve', $company), ['admin_note' => 'OK by INN'])
            ->assertRedirect();

        $this->assertSame('approved', $company->fresh()->status);
        $this->actingAs($owner->fresh())->get('/marketplace')->assertOk();

        $this->assertDatabaseHas('activity_log', [
            'log_name'     => 'companies',
            'event'        => 'updated',
            'subject_id'   => $company->id,
            'causer_id'    => $admin->id,
        ]);
    }

    public function test_admin_rejection_requires_note(): void
    {
        $owner   = $this->makeOwnerWithCompany(['status' => 'pending']);
        $admin   = $this->makeAdmin();

        $this->actingAs($admin)
            ->patch(route('admin.companies.reject', $owner->company), [])
            ->assertSessionHasErrors('admin_note');

        $this->actingAs($admin)
            ->patch(route('admin.companies.reject', $owner->company), ['admin_note' => 'INN not found'])
            ->assertRedirect();

        $this->assertSame('rejected', $owner->company->fresh()->status);
    }
}
