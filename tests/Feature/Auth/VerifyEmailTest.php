<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    private function verificationUrl(User $user): string
    {
        return URL::signedRoute('user.verify', [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);
    }

    #[Test]
    public function user_can_verify_email_with_valid_signed_url(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->getJson($this->verificationUrl($user));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function already_verified_user_gets_success_response(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson($this->verificationUrl($user));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);

        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function verification_fails_with_invalid_signature(): void
    {
        $user = User::factory()->unverified()->create();

        $url = route('user.verify', [
            'id' => $user->getKey(),
            'hash' => 'tampered-hash',
        ]);

        $response = $this->getJson($url);

        $response->assertForbidden();
    }

    #[Test]
    public function verification_fails_for_nonexistent_user(): void
    {
        $user = User::factory()->unverified()->create();

        $url = URL::signedRoute('user.verify', [
            'id' => 99999,
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $response = $this->getJson($url);

        $response->assertForbidden();
    }

    #[Test]
    public function verification_fails_for_mismatched_user(): void
    {
        $user = User::factory()->unverified()->create();
        $other = User::factory()->unverified()->create();

        $url = URL::signedRoute('user.verify', [
            'id' => $other->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $response = $this->getJson($url);

        $response->assertForbidden();
    }
}
