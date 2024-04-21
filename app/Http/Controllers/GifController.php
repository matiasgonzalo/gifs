<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifRequest;
use App\Http\Requests\StoreGifRequest;
use App\Repository\GifRepository;
use App\Repository\UserRepository;
use App\Services\GifService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GifController extends Controller
{
    /**
     * @param SearchGifRequest $request
     * @param GifService $gifService
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function search(SearchGifRequest $request, GifService $gifService): JsonResponse
    {
        $response = $gifService->search($request);

        return response()->json($response);
    }

    /**
     * @param GetGifByIdRequest $request
     * @param GifService $gifService
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getById(GetGifByIdRequest $request, GifService $gifService): JsonResponse
    {
        $response = $gifService->getById($request);

        return response()->json($response);
    }

    /**
     * @param StoreGifRequest $request
     * @param UserRepository $userRepository
     * @param GifRepository $gifRepository
     * @return Response
     */
    public function store(
        StoreGifRequest $request,
        UserRepository $userRepository,
        GifRepository $gifRepository
    ): Response {
        $user = $userRepository->getUserById($request->get('user_id'));
        $gif = $gifRepository->getGifById($request->get('gif_id'));
        if (!$gif) {
            $gif = $gifRepository->saveGif($request->get('gif_id'));
        }
        $userRepository->syncGif($user, $gif, $request->get('alias'));

        return response()->noContent();
    }
}
