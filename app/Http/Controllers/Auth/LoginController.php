<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __construct(readonly private ResponseFactory $response)
    {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->safe(['email', 'password']))) {
            return $this->response->json([
                'message' => 'The provided credentials are incorrect.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return AuthResource::make(
            $request->user('web')
        );
    }
}
