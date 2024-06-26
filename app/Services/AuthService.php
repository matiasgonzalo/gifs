<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param User $user
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function getToken(User $user, Request $request): array
    {
        try {
            $credentials = [
                'email' => $request['email'],
                'password' => $request['password']
            ];
            if (Hash::check($request['password'], $user->password) && Auth::attempt($credentials)) {
                $token = Auth::user()->createToken('Laravel Password Grant Client')->accessToken;

                return [
                    'id' => $user->id,
                    'access_token' => $token,
                    'name' => $user->name,
                    'email' => $user->email,
                    'token_type' => 'Bearer',
                ];
            } else {
                throw new Exception('Wrong user and/or password.', 404);
            }
        } catch (Exception $e) {
            throw($e);
        }
    }
}
