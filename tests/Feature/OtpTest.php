<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_otp_can_be_created(): void
    {
        $otp = Otp::createForEmailVerification('test@example.com');

        $this->assertEquals('test@example.com', $otp->email);
        $this->assertEquals(Otp::PURPOSE_SIGNUP, $otp->purpose);
        $this->assertEquals(6, strlen($otp->otp));
        $this->assertFalse($otp->used);
        $this->assertFalse($otp->isExpired());
    }

    public function test_login_otp_can_be_created(): void
    {
        $otp = Otp::createForLogin('test@example.com');

        $this->assertEquals(Otp::PURPOSE_LOGIN, $otp->purpose);
    }

    public function test_registration_redirects_to_signup_otp_verification(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name' => 'Abdul Aziz',
            'email' => 'owner@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('otp.verify', [
            'email' => 'owner@example.com',
            'purpose' => Otp::PURPOSE_SIGNUP,
        ], false));

        $this->assertDatabaseHas('users', [
            'email' => 'owner@example.com',
        ]);
        $this->assertDatabaseHas('otps', [
            'email' => 'owner@example.com',
            'purpose' => Otp::PURPOSE_SIGNUP,
        ]);

        Mail::assertSent(OtpMail::class);
    }

    public function test_verified_user_can_request_login_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->post(route('otp.send'), [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ]);

        $response->assertRedirect(route('otp.verify', [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ], false));

        $this->assertDatabaseHas('otps', [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ]);

        Mail::assertSent(OtpMail::class);
    }

    public function test_verified_user_can_log_in_with_password_without_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
        Mail::assertNothingSent();
    }

    public function test_unverified_user_cannot_log_in_with_password_until_signup_otp_is_verified(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create([
            'password' => 'password',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Please complete email verification to finish signup before logging in with a password.',
        ]);

        $this->assertGuest();
        Mail::assertNothingSent();
    }

    public function test_signup_otp_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = Otp::createForEmailVerification($user->email);

        $response = $this->postJson(route('otp.verify.post'), [
            'email' => $user->email,
            'otp' => $otp->otp,
            'purpose' => Otp::PURPOSE_SIGNUP,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Email verified successfully! Welcome to Dukaniq.',
            ]);

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_login_otp_can_be_verified_for_verified_user(): void
    {
        $user = User::factory()->create();
        $otp = Otp::createForLogin($user->email);

        $response = $this->postJson(route('otp.verify.post'), [
            'email' => $user->email,
            'otp' => $otp->otp,
            'purpose' => Otp::PURPOSE_LOGIN,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful! Welcome back.',
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_unverified_user_can_choose_login_with_otp_to_complete_signup(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->post(route('otp.send'), [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ]);

        $response->assertRedirect(route('otp.verify', [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_SIGNUP,
        ], false));

        $this->assertDatabaseHas('otps', [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_SIGNUP,
        ]);
        Mail::assertSent(OtpMail::class, 1);
    }

    public function test_unverified_password_login_does_not_send_an_extra_signup_otp(): void
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'Abdul Aziz',
            'email' => 'cooldown@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->from(route('login'))->post(route('login'), [
            'email' => 'cooldown@example.com',
            'password' => 'password',
        ])->assertRedirect(route('login'));

        Mail::assertSent(OtpMail::class, 1);
        $this->assertEquals(1, Otp::where('email', 'cooldown@example.com')
            ->where('purpose', Otp::PURPOSE_SIGNUP)
            ->count());
    }

    public function test_login_otp_requests_within_cooldown_only_send_one_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->post(route('otp.send'), [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ])->assertRedirect(route('otp.verify', [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ], false));

        $this->post(route('otp.send'), [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ])->assertRedirect(route('otp.verify', [
            'email' => $user->email,
            'purpose' => Otp::PURPOSE_LOGIN,
        ], false));

        Mail::assertSent(OtpMail::class, 1);
        $this->assertEquals(1, Otp::where('email', $user->email)
            ->where('purpose', Otp::PURPOSE_LOGIN)
            ->count());
    }
}
