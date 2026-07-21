<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Intern;
use App\Models\Role;
use App\Models\User;
use App\Notifications\InternActivationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class InternActivationTest extends TestCase
{
    use RefreshDatabase;

    protected User $supervisorUser;
    protected User $internUser;
    protected Intern $intern;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::factory()->create();
        $this->supervisorUser = User::factory()->supervisor()->create();
        $this->internUser = User::factory()->intern()->create([
            'email_verified_at' => null, // not activated yet
            'password' => Hash::make('placeholder-random-password'),
        ]);

        $this->intern = Intern::create([
            'user_id' => $this->internUser->id,
            'dept_id' => $department->id,
            'supervisor_id' => $this->supervisorUser->id,
            'institution' => 'Test Uni',
            'programme' => 'Test Course',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);
    }

    public function test_activation_request_page_loads_successfully(): void
    {
        $this->get(route('activate.request'))
            ->assertOk()
            ->assertSee('Pre-registered Intern?');
    }

    public function test_non_existent_email_cannot_request_activation(): void
    {
        $this->post(route('activate.send'), [
            'email' => 'doesnotexist@example.com',
        ])->assertSessionHasErrors(['email']);
    }

    public function test_non_intern_accounts_cannot_use_activation(): void
    {
        $this->post(route('activate.send'), [
            'email' => $this->supervisorUser->email,
        ])->assertSessionHasErrors(['email']);
    }

    public function test_valid_intern_requests_activation_succeeds_and_sends_notification(): void
    {
        Notification::fake();

        $this->post(route('activate.send'), [
            'email' => $this->internUser->email,
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('status', 'An activation link has been sent to your registered email address.');

        Notification::assertSentTo(
            $this->internUser,
            InternActivationNotification::class,
            function ($notification, $channels) {
                // Verify activation URL is generated
                return !empty($notification->toMail($this->internUser)->actionUrl);
            }
        );
    }

    public function test_accessing_activation_form_without_valid_signature_fails(): void
    {
        // Missing signature
        $this->get(route('activate.reset', ['email' => $this->internUser->email]))
            ->assertStatus(403);
            
        // Invalid signature
        $url = URL::temporarySignedRoute(
            'activate.reset',
            now()->addHours(24),
            ['email' => $this->internUser->email]
        );
        $invalidUrl = $url . 'corrupted';

        $this->get($invalidUrl)->assertStatus(403);
    }

    public function test_accessing_activation_form_with_valid_signature_succeeds(): void
    {
        $url = URL::temporarySignedRoute(
            'activate.reset',
            now()->addHours(24),
            ['email' => $this->internUser->email]
        );

        $this->get($url)
            ->assertOk()
            ->assertSee($this->internUser->email)
            ->assertSee('Choose Password');
    }

    public function test_completing_activation_sets_password_verifies_email_and_logs_in(): void
    {
        $url = URL::temporarySignedRoute(
            'activate.store',
            now()->addHours(24),
            ['email' => $this->internUser->email]
        );

        // Validation fails with mismatched password
        $this->postJson($url, [
            'password' => 'newpassword123',
            'password_confirmation' => 'mismatched',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);

        // Success activation
        $response = $this->post($url, [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('intern.dashboard'));
        $response->assertSessionHas('status', 'Welcome! Your intern account has been activated successfully.');

        // Verify password and activation
        $this->assertTrue(Hash::check('newpassword123', $this->internUser->fresh()->password));
        $this->assertNotNull($this->internUser->fresh()->email_verified_at);
        $this->assertAuthenticatedAs($this->internUser);
    }
}
