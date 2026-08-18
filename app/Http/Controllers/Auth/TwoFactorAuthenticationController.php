<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TwoFactorAuthenticationController extends Controller
{
    protected function engine()
    {
        return app('pragmarx.google2fa');
    }

    /**
     * Show the QR-code enrollment screen for a user who has not confirmed 2FA yet.
     */
    public function setup(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->intended('/dashboard');
        }

        // Keep the pending secret in the session so a page refresh doesn't
        // invalidate a QR code the user already scanned into their app.
        $secretKey = $request->session()->get('two_factor_pending_secret');

        if (! $secretKey) {
            $secretKey = $this->engine()->generateSecretKey();
            $request->session()->put('two_factor_pending_secret', $secretKey);
        }

        $qrCodeSvg = $this->engine()->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secretKey
        );

        return view('content.authentications.two-factor-setup', [
            'pageConfigs' => ['myLayout' => 'blank'],
            'secretKey' => trim(chunk_split($secretKey, 4, ' ')),
            'qrCodeSvg' => $qrCodeSvg,
        ]);
    }

    /**
     * Confirm enrollment by checking the first code generated from the scanned QR code.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $secretKey = $request->session()->get('two_factor_pending_secret');

        \Log::info('2FA DEBUG confirm attempt', [
            'secretKey' => $secretKey,
            'submittedCode' => $request->input('code'),
            'currentValidCode' => $secretKey ? $this->engine()->getCurrentOtp($secretKey) : null,
            'verifyResult' => $secretKey ? $this->engine()->verifyKey($secretKey, $request->input('code')) : null,
            'time' => now()->toDateTimeString(),
        ]);

        if (! $secretKey || ! $this->engine()->verifyKey($secretKey, $request->input('code'))) {
            return back()
                ->withErrors(['code' => 'That code is incorrect or has expired. Please try again with the current code shown in your app.'])
                ->withInput();
        }

        $user = $request->user();
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->forceFill([
            'two_factor_secret' => $secretKey,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('two_factor_pending_secret');
        $request->session()->put('two_factor_passed', true);
        $request->session()->put('two_factor_recovery_codes_to_show', $recoveryCodes);

        return redirect()->route('2fa.recovery-codes');
    }

    /**
     * One-time display of the freshly generated recovery codes.
     */
    public function showRecoveryCodes(Request $request): View|RedirectResponse
    {
        $codes = $request->session()->pull('two_factor_recovery_codes_to_show');

        if (! $codes) {
            return redirect()->intended('/dashboard');
        }

        return view('content.authentications.two-factor-recovery-codes', [
            'pageConfigs' => ['myLayout' => 'blank'],
            'codes' => $codes,
        ]);
    }

    /**
     * Show the login-time challenge for an already-enrolled user.
     */
    public function challenge(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('two_factor_passed')) {
            return redirect()->intended('/dashboard');
        }

        return view('content.authentications.two-factor-challenge', [
            'pageConfigs' => ['myLayout' => 'blank'],
        ]);
    }

    /**
     * Verify a login-time code, or consume a recovery code as a fallback.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $user = $request->user();

        if ($request->filled('recovery_code')) {
            return $this->attemptRecoveryCode($request, $user);
        }

        if (! $request->filled('code') || ! $this->engine()->verifyKey($user->two_factor_secret, $request->input('code'))) {
            return back()->withErrors(['code' => 'That code is incorrect or has expired. Please try again.']);
        }

        $request->session()->put('two_factor_passed', true);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    protected function attemptRecoveryCode(Request $request, User $user): RedirectResponse
    {
        $codes = $user->two_factor_recovery_codes ?? [];
        $submitted = strtoupper(trim($request->input('recovery_code')));

        if (! in_array($submitted, $codes, true)) {
            return back()->withErrors(['recovery_code' => 'That recovery code is not valid or was already used.']);
        }

        $user->forceFill([
            'two_factor_recovery_codes' => array_values(array_diff($codes, [$submitted])),
        ])->save();

        $request->session()->put('two_factor_passed', true);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard')
            ->with('status', 'Signed in using a recovery code. That code is now used up — store your remaining codes safely.');
    }

    protected function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn () => strtoupper(Str::random(5).'-'.Str::random(5)))
            ->all();
    }
}
