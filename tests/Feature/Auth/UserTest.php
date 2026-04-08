<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    #[Test]
    public function authenticated_user_can_fetch_their_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('whoami'));

        $response->assertOk()
            ->assertJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJsonFragment([
                'id'    => $user->id,
                'email' => $user->email,
            ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_fetch_profile(): void
    {
        $response = $this->getJson(route('whoami'));

        $response->assertUnauthorized();
    }

    #[Test]
    public function user_profile_does_not_expose_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('whoami'));

        $response->assertOk()
            ->assertJsonMissingPath('data.password');
    }
}
