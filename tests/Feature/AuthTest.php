<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    public function test_registration_creates_owner_and_sends_to_onboarding(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Dilshod',
            'email'                 => 'dilshod@example.com',
            'password'              => 'Secret123',
            'password_confirmation' => 'Secret123',
            'lang'                  => 'ru',
        ]);

        $response->assertRedirect('/marketplace/onboarding');

        $user = User::where('email', 'dilshod@example.com')->firstOrFail();
        $this->assertSame('owner', $user->role);
        $this->assertSame('ru', $user->lang);
        $this->assertNull($user->company_id);
    }

    public function test_registration_rejects_weak_password(): void
    {
        $this->post('/register', [
            'name'                  => 'Weak',
            'email'                 => 'weak@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'weak@example.com']);
    }

    public function test_login_redirects_each_role_to_its_panel(): void
    {
        $cases = [
            [$this->makeAdmin(),                                      '/admin'],
            [$this->makeProducer(),                                   '/producer'],
            [$this->makeOwnerWithCompany(),                           '/marketplace'],
            [$this->makeOwnerWithCompany(['status' => 'pending']),    '/marketplace/pending'],
            [$this->makeUser('owner'),                                '/marketplace/onboarding'],
        ];

        foreach ($cases as [$user, $expected]) {
            $this->post('/login', ['email' => $user->email, 'password' => 'password'])
                ->assertRedirect($expected);
            $this->post('/logout');
        }
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $user = $this->makeAdmin();

        $this->post('/login', ['email' => $user->email, 'password' => 'nope-nope'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_home_redirect_follows_role(): void
    {
        $owner = $this->makeOwnerWithCompany();

        $this->actingAs($owner)->get('/home')->assertRedirect('/marketplace');
    }
}
