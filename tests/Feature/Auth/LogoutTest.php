<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    #[Test]
    public function authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('auth.logout'));

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Logged out successfully.']);

        $this->assertGuest();
    }

    #[Test]
    public function unauthenticated_user_cannot_logout(): void
    {
        $response = $this->postJson(route('auth.logout'));

        $response->assertUnauthorized();
    }
}
