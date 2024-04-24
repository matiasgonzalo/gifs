<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifRequest;
use App\Http\Requests\StoreGifRequest;
use App\Models\User;
use App\Repository\ApiRequestRepository;
use App\Repository\GifRepository;
use App\Repository\UserRepository;
use App\Services\GifService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GifController extends Controller
{
    public function __construct(
        public GifService $gifService,
        public ApiRequestRepository $apiRequestRepository,
        public GifRepository $gifRepository,
        public UserRepository $userRepository,
        public JsonResponse $jsonResponse
    )
    {}

    /**
     * @param SearchGifRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function search(SearchGifRequest $request): JsonResponse
    {
        try {
            $response = $this->gifService->search($request);
            $this->apiRequestRepository->storeApiRequest($request, $response);

            return $this->jsonResponse->setData($response);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
    }

    /**
     * @param GetGifByIdRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getById(GetGifByIdRequest $request): JsonResponse
    {
        try {
            $response = $this->gifService->getById($request);
            $this->apiRequestRepository->storeApiRequest($request, $response);

            return $this->jsonResponse->setData($response);
        } catch (\Exception $e) {
            $this->jsonResponse->setData(['message' => $e->getMessage(), 'code' => $e->getCode()]);
            return $this->jsonResponse->setStatusCode($e->getCode());
        }
    }

    /**
     * @param StoreGifRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(
        StoreGifRequest $request,
        User $user
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $gif = $this->gifRepository->getGifById($request->get('gif_id'));
            if (!$gif) {
                $gif = $this->gifRepository->storeGif($request->get('gif_id'));
            }
            $this->userRepository->syncGif($user, $gif, $request->get('alias'));
            DB::commit();
            return $this->jsonResponse->setData(['message' => 'Save Success', 'code' => 200]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->jsonResponse->setData(['message' => $e->getMessage(), 'code' => $e->getCode()]);
            return $this->jsonResponse->setStatusCode(500);
        }
    }
}
