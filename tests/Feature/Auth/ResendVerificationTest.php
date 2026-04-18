<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmailNotification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResendVerificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unverified_user_can_resend_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->postJson(route('auth.email.resend'));

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Verification link sent.']);

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    #[Test]
    public function already_verified_user_cannot_resend_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('auth.email.resend'));

        $response->assertConflict()
            ->assertJsonFragment(['message' => 'Email already verified.']);

        Notification::assertNothingSent();
    }

    #[Test]
    public function unauthenticated_user_cannot_resend_verification_email(): void
    {
        $response = $this->postJson(route('auth.email.resend'));

        $response->assertUnauthorized();
    }
}
