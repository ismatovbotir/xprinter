<?php

namespace Tests\Feature;

use App\Http\Middleware\TwoFactorVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    private function enable2fa($user): string
    {
        $secret = (new Google2FA())->generateSecretKey();
        $user->forceFill(['two_factor_secret' => $secret, 'two_factor_confirmed_at' => now()])->save();

        return $secret;
    }

    public function test_user_without_2fa_is_not_challenged(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get(route('two-factor.challenge'))->assertRedirect('/admin');
    }

    public function test_user_with_2fa_is_redirected_until_code_is_verified(): void
    {
        $admin  = $this->makeAdmin();
        $secret = $this->enable2fa($admin);

        $this->actingAs($admin)->get('/admin')->assertRedirect(route('two-factor.challenge'));
        $this->actingAs($admin)->get(route('profile.edit'))->assertRedirect(route('two-factor.challenge'));
        $this->actingAs($admin)->get(route('two-factor.challenge'))->assertOk();

        // wrong code
        $this->actingAs($admin)->post(route('two-factor.verify'), ['code' => '000000'])
            ->assertSessionHasErrors('code');
        $this->assertFalse(session()->has(TwoFactorVerified::SESSION_KEY));

        // right code
        $code = (new Google2FA())->getCurrentOtp($secret);
        $this->actingAs($admin)->post(route('two-factor.verify'), ['code' => $code])
            ->assertRedirect('/admin');

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_setup_and_confirm_flow(): void
    {
        $owner = $this->makeOwnerWithCompany();

        $this->actingAs($owner)->get(route('two-factor.setup'))
            ->assertOk()
            ->assertSee('<svg', false);

        $secret = session('two_factor_pending_secret');
        $this->assertNotEmpty($secret);

        $this->actingAs($owner)->post(route('two-factor.confirm'), ['code' => '123456'])
            ->assertSessionHasErrors('code');
        $this->assertFalse($owner->fresh()->hasTwoFactorEnabled());

        $code = (new Google2FA())->getCurrentOtp($secret);
        $this->actingAs($owner)->post(route('two-factor.confirm'), ['code' => $code])
            ->assertRedirect(route('profile.edit'));

        $owner->refresh();
        $this->assertTrue($owner->hasTwoFactorEnabled());
        $this->assertSame($secret, $owner->two_factor_secret);
        // stored encrypted, not in plain text
        $this->assertNotSame($secret, $owner->getRawOriginal('two_factor_secret'));
        $this->assertDatabaseHas('activity_log', ['log_name' => 'users', 'subject_id' => $owner->id]);
    }

    public function test_disable_requires_current_password(): void
    {
        $admin = $this->makeAdmin();
        $this->enable2fa($admin);
        session()->put(TwoFactorVerified::SESSION_KEY, true);

        $this->actingAs($admin)->delete(route('two-factor.destroy'), ['password' => 'wrong'])
            ->assertSessionHasErrors('password');
        $this->assertTrue($admin->fresh()->hasTwoFactorEnabled());

        $this->actingAs($admin)->delete(route('two-factor.destroy'), ['password' => 'password'])
            ->assertRedirect(route('profile.edit'));
        $this->assertFalse($admin->fresh()->hasTwoFactorEnabled());
    }

    public function test_verify_is_rate_limited(): void
    {
        $admin = $this->makeAdmin();
        $this->enable2fa($admin);

        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($admin)->post(route('two-factor.verify'), ['code' => '000000']);
        }

        $this->actingAs($admin)->post(route('two-factor.verify'), ['code' => '000000'])
            ->assertStatus(429);
    }
}
