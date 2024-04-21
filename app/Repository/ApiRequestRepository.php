<?php

namespace App\Repository;

use App\Models\ApiRequest;
use App\Models\User;
use App\Services\GifService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiRequestRepository
{
    public function storeApiRequest(Request $request, JsonResponse $response): void
    {
        /** @var User $user */
        $user = Auth::user();

        ApiRequest::create([
            'user_id' => $user->id,
            'requested_service_name' => GifService::SERVICE_NAME,
            'body_request' => $request->all(),
            'code_response' => $response->getStatusCode(),
            'body_response' => json_decode($response->getContent(), true),
            'ip_source' => $request->ip(),
        ]);
    }
}
