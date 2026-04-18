<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
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
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    #[Test]
    public function registered_event_is_dispatched_after_registration(): void
    {
        $this->postJson(route('auth.register'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ]);

        Event::assertDispatched(Registered::class);
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
