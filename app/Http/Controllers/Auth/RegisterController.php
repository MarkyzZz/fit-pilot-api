<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        User::create($request->validated());

        return response()->json([
            'message' => 'Registration successful. Please check your email to verify your account.'
        ], Response::HTTP_CREATED);
    }
}
