<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = User::find($this->route('id'));

        return match(true) {
            ! $user => false,
            ! hash_equals((string) $user->getKey(), (string) $this->route('id')) => false,
            ! hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash')) => false,
            default => true,
        };
    }

    public function rules(): array
    {
        return [];
    }

    public function fulfill(): User
    {
        $user = User::find($this->route('id'));

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return $user;
    }
}
