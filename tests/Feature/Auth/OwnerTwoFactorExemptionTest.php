<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use App\Support\TwoFactorEnforcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * AUTH_TWO_FACTOR_EXEMPT_OWNER: one Owner account signs in with its password
 * alone, while enforcement stays on for everyone else.
 *
 * What has to hold: the named owner reaches the dashboard straight from the
 * login form (no setup page, no challenge); nobody else's requirement moves;
 * the password, the throttle and the guest redirect are unchanged; the stored
 * second factor is never touched; and removing the setting restores it all.
 */
class OwnerTwoFactorExemptionTest extends TestCase
{
    use RefreshDatabase;

    private const OWNER = 'owner@anasfareed.co';

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.require_two_factor' => true]);
    }

    private function exempt(?string $email = self::OWNER): void
    {
        config(['auth.two_factor_exempt_owner' => $email]);
    }

    private function user(UserRole $role, string $email, bool $enrolled): User
    {
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make('the-password'),
        ]);

        $user->forceFill([
            'role' => $role,
            'two_factor_secret' => $enrolled ? encrypt('SECRETVALUE') : null,
            'two_factor_recovery_codes' => $enrolled ? encrypt('["a","b"]') : null,
            'two_factor_confirmed_at' => $enrolled ? now() : null,
        ])->save();

        return $user->refresh();
    }

    public function test_the_exempt_owner_without_a_factor_reaches_the_dashboard(): void
    {
        $this->exempt();
        $owner = $this->user(UserRole::Owner, self::OWNER, enrolled: false);

        $this->post('/login', ['email' => self::OWNER, 'password' => 'the-password'])
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($owner);
        $this->get('/admin')->assertOk();
        $this->get('/admin/projects')->assertOk();
        $this->get('/admin')->assertOk(); // a reload is not sent anywhere either
    }

    public function test_the_exempt_owner_with_a_factor_skips_the_challenge_and_keeps_it(): void
    {
        $this->exempt();
        $owner = $this->user(UserRole::Owner, self::OWNER, enrolled: true);
        $before = $owner->only(['two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']);

        $this->post('/login', ['email' => self::OWNER, 'password' => 'the-password'])
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($owner);
        $this->get('/admin')->assertOk();

        $this->assertEquals($before, $owner->fresh()->only(array_keys($before)), 'the stored factor changed');
    }

    public function test_sign_out_and_back_in_works(): void
    {
        $this->exempt();
        $this->user(UserRole::Owner, self::OWNER, enrolled: false);

        $this->post('/login', ['email' => self::OWNER, 'password' => 'the-password'])->assertRedirect('/admin');
        $this->post('/logout');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect(route('login'));

        $this->post('/login', ['email' => self::OWNER, 'password' => 'the-password'])->assertRedirect('/admin');
        $this->get('/admin')->assertOk();
    }

    public function test_the_address_case_does_not_matter(): void
    {
        $this->exempt('Owner@AnasFareed.co');
        $owner = $this->user(UserRole::Owner, self::OWNER, enrolled: false);

        $this->assertTrue(TwoFactorEnforcement::exempts($owner));
    }

    public function test_a_wrong_password_is_still_refused_and_throttled(): void
    {
        $this->exempt();
        $this->user(UserRole::Owner, self::OWNER, enrolled: false);

        foreach (range(1, 5) as $_) {
            $this->post('/login', ['email' => self::OWNER, 'password' => 'wrong'])
                ->assertSessionHasErrors('email');
        }

        $this->assertGuest();

        // The sixth is throttled even with the right password.
        $this->post('/login', ['email' => self::OWNER, 'password' => 'the-password'])
            ->assertStatus(429);
        $this->assertGuest();
    }

    public function test_a_guest_still_cannot_reach_the_dashboard(): void
    {
        $this->exempt();
        $this->user(UserRole::Owner, self::OWNER, enrolled: false);

        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/admin/projects')->assertRedirect(route('login'));
    }

    public function test_an_editor_with_the_exempt_address_is_not_exempt(): void
    {
        $this->exempt();
        $editor = $this->user(UserRole::Editor, self::OWNER, enrolled: false);

        $this->assertFalse(TwoFactorEnforcement::exempts($editor));
        $this->actingAs($editor)->get('/admin')->assertRedirect(route('two-factor.setup'));
    }

    public function test_every_other_account_is_still_asked_for_its_factor(): void
    {
        $this->exempt();
        $this->user(UserRole::Owner, self::OWNER, enrolled: false);
        $other = $this->user(UserRole::Owner, 'second-owner@anasfareed.co', enrolled: false);
        $enrolled = $this->user(UserRole::Editor, 'editor@anasfareed.co', enrolled: true);

        $this->actingAs($other)->get('/admin')->assertRedirect(route('two-factor.setup'));

        $this->post('/logout');
        $this->post('/login', ['email' => 'editor@anasfareed.co', 'password' => 'the-password'])
            ->assertRedirect('/two-factor-challenge');
        $this->assertGuest();
        $this->assertNotNull($enrolled->fresh()->two_factor_confirmed_at);
    }

    public function test_without_the_setting_the_owner_is_asked_again(): void
    {
        $this->exempt(null);
        $owner = $this->user(UserRole::Owner, self::OWNER, enrolled: false);

        $this->actingAs($owner)->get('/admin')->assertRedirect(route('two-factor.setup'));
    }

    public function test_without_the_setting_an_enrolled_owner_gets_the_challenge(): void
    {
        $this->exempt(null);
        $this->user(UserRole::Owner, self::OWNER, enrolled: true);

        $this->post('/login', ['email' => self::OWNER, 'password' => 'the-password'])
            ->assertRedirect('/two-factor-challenge');
        $this->assertGuest();
    }

    public function test_the_exemption_does_not_switch_enforcement_off_or_trip_the_production_guard(): void
    {
        $this->exempt();

        $this->assertTrue(TwoFactorEnforcement::required());
        TwoFactorEnforcement::assertSafeForEnvironment('production');
        $this->addToAssertionCount(1);
    }
}
