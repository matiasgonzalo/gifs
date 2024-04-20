<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Repository\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param AuthLoginRequest $request
     * @param UserRepository $userRepository
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function login(
        AuthLoginRequest $request,
        UserRepository $userRepository,
        AuthService $authService
    ): JsonResponse {
        try {
            $user = $userRepository->getUserById($request->get('email'));
            $response = $authService->getToken($user, $request);

            return response()->json(['data' => $response], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
    }
}
