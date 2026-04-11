<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

final class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json(['message' => 'Email verified successfully.']);
    }
}
