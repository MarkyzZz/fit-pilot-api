<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->safe(['email', 'password']))) {
            return \Response::json([
                'message' => 'The provided credentials are incorrect.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();

        return response()->json($request->user());
    }
}
