<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifRequest;
use App\Http\Requests\StoreGifRequest;
use App\Repository\GifRepository;
use App\Repository\UserRepository;
use App\Services\GifService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;

class GifController extends Controller
{
    /**
     * @param SearchGifRequest $request
     * @param GifService $gifService
     * @return StreamInterface
     * @throws GuzzleException
     */
    public function search(SearchGifRequest $request, GifService $gifService): StreamInterface
    {
        $response = $gifService->search($request);

        return $response->getBody();
    }

    /**
     * @param GetGifByIdRequest $request
     * @param GifService $gifService
     * @return StreamInterface
     */
    public function getById(GetGifByIdRequest $request, GifService $gifService): StreamInterface
    {
        $response = $gifService->getById($request);

        return $response->getBody();
    }

    /**
     * @param StoreGifRequest $request
     * @param UserRepository $userRepository
     * @param GifRepository $gifRepository
     * @return void
     */
    public function store(StoreGifRequest $request, UserRepository $userRepository, GifRepository $gifRepository)
    {
        $user = $userRepository->getUserById($request->get('user_id'));
        $gif = $gifRepository->getGifById($request->get('gif_id'));
        if ($gif) {
            $gif = $gifRepository->saveGif($request->get('gif_id'));
        }
        $userRepository->syncGif($user, $gif, $request->get('alias'));
    }
}
