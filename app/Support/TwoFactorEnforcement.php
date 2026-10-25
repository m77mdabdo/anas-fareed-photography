<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use RuntimeException;

/**
 * Whether a confirmed second factor is required to reach an admin.
 *
 * ───────────────────────────────────────────────────────────────────────────
 * AN ENVIRONMENT DECISION, NOT A DELETION.
 *
 * The client wants to sign in with an email and a password while the site is
 * being built. The requirement itself does not move: EnsureTwoFactorEnabled,
 * the setup page, the challenge and every test about them are all still here
 * and still mean what they meant. What changed is that one environment can
 * answer "no" to the question.
 *
 * The default is yes. config/auth.php explains the cast that makes a missing,
 * blank or misspelt env var mean yes — the only way to switch this off is to
 * spell it correctly on purpose.
 * ───────────────────────────────────────────────────────────────────────────
 */
final class TwoFactorEnforcement
{
    /** Is a confirmed second factor required of admins? */
    public static function required(): bool
    {
        return (bool) config('auth.require_two_factor', true);
    }

    /**
     * Is a confirmed second factor required of THIS user?
     *
     * Yes whenever enforcement is on, except for the one Owner account named
     * by AUTH_TWO_FACTOR_EXEMPT_OWNER. The exemption is narrow on purpose:
     * an address match alone is not enough — the role must be Owner too, so
     * changing an editor's email to the configured one grants nothing.
     */
    public static function requiredFor(User $user): bool
    {
        return self::required() && ! self::exempts($user);
    }

    /** Is this user the owner account named in the exemption setting? */
    public static function exempts(User $user): bool
    {
        $exempt = config('auth.two_factor_exempt_owner');

        if (! is_string($exempt) || trim($exempt) === '') {
            return false;
        }

        return $user->isOwner()
            && mb_strtolower(trim((string) $user->email)) === mb_strtolower(trim($exempt));
    }

    /**
     * Refuse to boot a production application with the second factor optional.
     *
     * ───────────────────────────────────────────────────────────────────────
     * LOUD, AND AT STARTUP, BECAUSE THE QUIET VERSION IS THE WHOLE FAILURE.
     *
     * A production deploy that carries a development .env is not a theoretical
     * mistake; it is the most common one there is. If the only symptom were
     * "the panel stopped asking for a code", nobody would notice for months —
     * the site would work, every page would load, and the admin would be a
     * password away from anybody who has ever seen the email address.
     *
     * So this throws, from AppServiceProvider::boot(), before a single route
     * is matched. Web requests become a 500; `php artisan` — including the
     * migrate step of a deploy — exits non-zero with the same sentence. The
     * deploy fails rather than the security does.
     *
     * It deliberately does not try to be clever about "which" production:
     * APP_ENV=production and enforcement off is the condition, full stop.
     * ───────────────────────────────────────────────────────────────────────
     *
     * @throws RuntimeException
     */
    public static function assertSafeForEnvironment(?string $environment = null): void
    {
        $environment ??= (string) config('app.env');

        if ($environment !== 'production') {
            return;
        }

        if (self::required()) {
            return;
        }

        throw new RuntimeException(
            'AUTH_REQUIRE_TWO_FACTOR is false while APP_ENV is production. '
            .'Mandatory two-factor authentication cannot be switched off in production: '
            .'it is the only thing between a leaked password and the whole site. '
            .'Remove AUTH_REQUIRE_TWO_FACTOR from the production .env (or set it to true) '
            .'and deploy again. If you are seeing this locally, APP_ENV is set to production '
            .'by mistake.'
        );
    }
}
