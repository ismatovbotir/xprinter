<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/producer')->assertRedirect('/login');
        $this->get('/marketplace')->assertRedirect('/login');
    }

    public function test_admin_panel_rejects_other_roles(): void
    {
        $this->actingAs($this->makeOwnerWithCompany())->get('/admin')->assertForbidden();
        $this->actingAs($this->makeProducer())->get('/admin')->assertForbidden();
        $this->actingAs($this->makeAdmin())->get('/admin')->assertOk();
    }

    public function test_producer_panel_rejects_other_roles(): void
    {
        $this->actingAs($this->makeAdmin())->get('/producer')->assertForbidden();
        $this->actingAs($this->makeOwnerWithCompany())->get('/producer')->assertForbidden();
        $this->actingAs($this->makeProducer())->get('/producer')->assertOk();
    }

    public function test_marketplace_rejects_admin_and_producer(): void
    {
        $this->actingAs($this->makeAdmin())->get('/marketplace')->assertForbidden();
        $this->actingAs($this->makeProducer())->get('/marketplace')->assertForbidden();
    }

    public function test_owner_without_approved_company_is_redirected(): void
    {
        $this->actingAs($this->makeUser('owner'))
            ->get('/marketplace')
            ->assertRedirect(route('marketplace.onboarding'));

        $this->actingAs($this->makeOwnerWithCompany(['status' => 'pending']))
            ->get('/marketplace')
            ->assertRedirect(route('marketplace.pending'));
    }

    public function test_operator_cannot_open_owner_only_pages(): void
    {
        $company  = $this->makeCompany();
        $operator = $this->makeUser('user', $company);

        $this->actingAs($operator)->get('/marketplace')->assertOk();
        $this->actingAs($operator)->get('/marketplace/team')->assertForbidden();
        $this->actingAs($operator)->get('/marketplace/company')->assertForbidden();
    }

    public function test_company_permissions_helper(): void
    {
        $company = $this->makeCompany();

        $owner  = $this->makeUser('owner', $company);
        $legacy = $this->makeUser('user', $company, ['permissions' => null]);
        $scoped = $this->makeUser('user', $company, ['permissions' => ['view_leads']]);

        $this->assertTrue($owner->hasPermission('manage_prices'));
        $this->assertTrue($legacy->hasPermission('manage_prices'));
        $this->assertFalse($scoped->hasPermission('manage_prices'));
        $this->assertTrue($scoped->hasPermission('view_leads'));
    }
}
