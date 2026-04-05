<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    #[Test]
    public function user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['id', 'name', 'email']);
    }

    #[Test]
    public function user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized()
            ->assertJsonFragment(['message' => 'The provided credentials are incorrect.']);
    }

    #[Test]
    public function login_requires_email_and_password(): void
    {
        $response = $this->postJson(route('auth.login'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    #[Test]
    public function login_requires_a_valid_email_format(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}
