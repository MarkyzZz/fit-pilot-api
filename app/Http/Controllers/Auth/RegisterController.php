<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): AuthResource
    {
        $user = User::create($request->validated());

        $user->notify(new VerifyEmailNotification);

        return AuthResource::make($user);
    }
}
