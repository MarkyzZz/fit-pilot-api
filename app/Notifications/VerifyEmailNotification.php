<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

final class VerifyEmailNotification extends VerifyEmail
{
    /**
     * @param MustVerifyEmail $notifiable
     * @return string
     */
    protected function verificationUrl(mixed $notifiable): string
    {
        $backendUrl = URL::temporarySignedRoute(
            'user.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        parse_str(parse_url($backendUrl, PHP_URL_QUERY), $params);

        return config('app.frontend_url')
            . '/authentication'
            . '/verify-email/' . $notifiable->getKey()
            . '/' . sha1($notifiable->getEmailForVerification())
            . '?' . http_build_query([
                'expires' => $params['expires'],
                'signature' => $params['signature'],
            ]);
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }
}
