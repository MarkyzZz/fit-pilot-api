<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __invoke(Request $request): AuthResource
    {
        return new AuthResource($request->user());
    }
}
