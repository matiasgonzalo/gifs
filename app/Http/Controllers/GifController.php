<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifRequest;
use App\Http\Requests\StoreGifRequest;
use App\Repository\ApiRequestRepository;
use App\Repository\GifRepository;
use App\Repository\UserRepository;
use App\Services\GifService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GifController extends Controller
{
    /**
     * @param SearchGifRequest $request
     * @param GifService $gifService
     * @param ApiRequestRepository $apiRequestRepository
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function search(SearchGifRequest $request, GifService $gifService, ApiRequestRepository $apiRequestRepository): JsonResponse
    {
        try {
            $response = $gifService->search($request);
            $apiRequestRepository->storeApiRequest($request, $response);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
    }

    /**
     * @param GetGifByIdRequest $request
     * @param GifService $gifService
     * @param ApiRequestRepository $apiRequestRepository
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getById(GetGifByIdRequest $request, GifService $gifService, ApiRequestRepository $apiRequestRepository): JsonResponse
    {
        try {
            $response = $gifService->getById($request);
            $apiRequestRepository->storeApiRequest($request, $response);

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
    }

    /**
     * @param StoreGifRequest $request
     * @param UserRepository $userRepository
     * @param GifRepository $gifRepository
     * @return JsonResponse
     */
    public function store(
        StoreGifRequest $request,
        UserRepository $userRepository,
        GifRepository $gifRepository
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $user = $userRepository->getUserById($request->get('user_id'));
            $gif = $gifRepository->getGifById($request->get('gif_id'));
            if (!$gif) {
                $gif = $gifRepository->storeGif($request->get('gif_id'));
            }
            $userRepository->syncGif($user, $gif, $request->get('alias'));
            DB::commit();
            return response()->json(['message' => 'Save Success', 'code' => 200], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
    }
}
