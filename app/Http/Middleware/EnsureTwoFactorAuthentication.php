<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthentication
{
    /**
     * Routes a logged-in-but-not-yet-2FA-verified user must still be able to reach.
     *
     * @var array<int, string>
     */
    protected array $exemptRouteNames = [
        '2fa.setup',
        '2fa.setup.confirm',
        '2fa.recovery-codes',
        '2fa.challenge',
        '2fa.challenge.verify',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        if ($request->is('logout') || in_array($request->route()?->getName(), $this->exemptRouteNames, true)) {
            return $next($request);
        }

        $user = Auth::user();

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->route('2fa.setup');
        }

        if (! $request->session()->get('two_factor_passed')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}
