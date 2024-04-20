<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param AuthLoginRequest $request
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        try {
            $response = AuthService::getToken($request);
            return response()->json(['data' => $response], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
    }
}
