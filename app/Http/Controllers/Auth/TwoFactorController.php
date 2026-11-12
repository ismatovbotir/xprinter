<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\TwoFactorVerified;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

/**
 * TOTP two-factor auth for admin / owner / producer accounts.
 *
 * Setup:     GET  /two-factor          → QR + secret (pending, in session)
 *            POST /two-factor          → verify first code, persist secret
 *            DELETE /two-factor        → disable (requires current password)
 * Challenge: GET  /two-factor/challenge, POST /two-factor/challenge
 */
class TwoFactorController extends Controller
{
    private const PENDING_KEY = 'two_factor_pending_secret';

    public function __construct(private readonly Google2FA $google2fa) {}

    public function setup(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.edit')->with('success', 'Ikki bosqichli himoya allaqachon yoqilgan.');
        }

        $secret = $request->session()->get(self::PENDING_KEY) ?? $this->google2fa->generateSecretKey();
        $request->session()->put(self::PENDING_KEY, $secret);

        $otpUrl = $this->google2fa->getQRCodeUrl(config('app.name', 'Xprinter.uz'), $user->email, $secret);
        $qrSvg  = $this->renderQr($otpUrl);

        return view('auth.two-factor-setup', [
            'layout' => $this->layoutFor($user),
            'secret' => $secret,
            'qrSvg'  => $qrSvg,
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user   = $request->user();
        $secret = $request->session()->get(self::PENDING_KEY);

        if (!$secret) {
            return redirect()->route('two-factor.setup')->withErrors(['code' => 'Sessiya muddati tugadi, qaytadan boshlang.']);
        }

        if (!$this->google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Kod noto\'g\'ri. Ilovadagi joriy kodni kiriting.']);
        }

        $user->forceFill([
            'two_factor_secret'       => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget(self::PENDING_KEY);
        $request->session()->put(TwoFactorVerified::SESSION_KEY, true);

        return redirect()->route('profile.edit')->with('success', 'Ikki bosqichli himoya yoqildi.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $request->user()->forceFill([
            'two_factor_secret'       => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget(TwoFactorVerified::SESSION_KEY);

        return redirect()->route('profile.edit')->with('success', 'Ikki bosqichli himoya o\'chirildi.');
    }

    public function challenge(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (!$user->hasTwoFactorEnabled() || $request->session()->get(TwoFactorVerified::SESSION_KEY)) {
            return redirect($user->redirectPath());
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user = $request->user();

        if (!$this->google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            return back()->withErrors(['code' => 'Kod noto\'g\'ri yoki muddati tugagan.']);
        }

        $request->session()->put(TwoFactorVerified::SESSION_KEY, true);

        return redirect()->intended($user->redirectPath());
    }

    private function renderQr(string $data): string
    {
        $renderer = new ImageRenderer(new RendererStyle(220, 0), new SvgImageBackEnd());

        return (new Writer($renderer))->writeString($data);
    }

    private function layoutFor($user): string
    {
        return match (true) {
            $user->isAdmin()    => 'layouts.admin',
            $user->isProducer() => 'layouts.producer',
            default             => 'layouts.marketplace',
        };
    }
}
