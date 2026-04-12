<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): AuthResource
    {
        $request->fulfill();

        Auth::guard('web')->login($request->user());

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return AuthResource::make($request->user());
    }
}
