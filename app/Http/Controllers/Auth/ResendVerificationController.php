<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResendVerificationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ], Response::HTTP_CONFLICT);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent.',
        ], Response::HTTP_OK);
    }
}
