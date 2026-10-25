<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use App\Support\TwoFactorEnforcement;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

/**
 * Fortify's login step that sends an enrolled user to the code challenge —
 * except the owner account exempted by AUTH_TWO_FACTOR_EXEMPT_OWNER.
 *
 * The password is still checked first, by the same validateCredentials()
 * (timing-safe, rate-limited, Failed event on a wrong password). Only the
 * decision that follows changes: the exempt owner continues to the normal
 * sign-in instead of the challenge. Their stored secret and recovery codes
 * are left exactly as they are, so removing the setting brings the
 * challenge back with the same authenticator app.
 */
class RedirectIfTwoFactorRequired extends RedirectIfTwoFactorAuthenticatable
{
    public function handle($request, $next)
    {
        $user = $this->validateCredentials($request);

        if ($user instanceof User && TwoFactorEnforcement::exempts($user)) {
            return $next($request);
        }

        if ($user->two_factor_secret !== null && $user->two_factor_confirmed_at !== null) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }
}
