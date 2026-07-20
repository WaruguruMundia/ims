<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'password.reset',
                now()->addMinutes(config('auth.passwords.users.expire')),
                [
                    'token' => $notification->token,
                    'email' => $user->getEmailForPasswordReset(),
                ]
            );
            $response = $this->get($url);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }

    public function test_reset_password_screen_cannot_be_rendered_without_signature(): void
    {
        $response = $this->get('/reset-password/some-token');

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_reset_password_screen_cannot_be_rendered_with_invalid_signature(): void
    {
        $url = route('password.reset', [
            'token' => 'some-token',
            'email' => 'test@example.com',
            'expires' => now()->addMinutes(60)->timestamp,
            'signature' => 'invalid-signature-hash',
        ]);

        $response = $this->get($url);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_reset_password_screen_cannot_be_rendered_with_expired_signature(): void
    {
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'password.reset',
            now()->subMinutes(1),
            [
                'token' => 'some-token',
                'email' => 'test@example.com',
            ]
        );

        $response = $this->get($url);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors(['email']);
    }
}
