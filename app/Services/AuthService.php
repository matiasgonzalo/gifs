<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\ArrayShape;

class AuthService
{
    /**
     * @param $request
     * @return array
     * @throws Exception
     */
    public static function getToken($request): array
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (isset($user)) {
                if (Hash::check($request['password'], $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    return [
                        'id' => $user->id,
                        'access_token' => $token,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token_type'=>'Bearer'
                    ];
                } else {
                    throw new Exception('Wrong user and/or password.', 404);
                }
            }
            throw new Exception('Wrong user and/or password.', 404);
        } catch (Exception $e) {
            throw($e);
        }
    }
}
