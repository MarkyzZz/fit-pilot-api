<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    #[Test]
    public function user_can_register_with_valid_data(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'first_name', 'last_name', 'email', 'email_verified_at']]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    #[Test]
    public function verification_email_is_sent_after_registration(): void
    {
        $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        $user = User::where('email', 'john@example.com')->first();

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    #[Test]
    public function register_requires_all_fields(): void
    {
        $response = $this->postJson(route('auth.register'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'password']);
    }

    #[Test]
    public function register_requires_a_valid_email_format(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'not-an-email',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function register_rejects_weak_password(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'weakpass',
            'password_confirmation' => 'weakpass',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function register_rejects_mismatched_password_confirmation(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Different123',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }
}
