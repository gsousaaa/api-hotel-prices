<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function signUp(Request $request): JsonResponse{
        $parsed = $request->validate([
            'company_name' => 'required|string|max:255',
            'cnpj' => 'required|string|min:14|max:18',
            'uf' => 'nullable|string|max:2',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $token = $this->authService->register($parsed);
        
        return response()->json(['accessToken' => $token], 201);
    }

    public function signIn(Request $request): JsonResponse {
        $parsed = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $token = $this->authService->login($parsed);

        return response()->json(['accessToken' => $token], 200);
    }


}