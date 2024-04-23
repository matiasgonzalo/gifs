<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\GifController;
use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifRequest;
use App\Http\Requests\StoreGifRequest;
use App\Models\Gif;
use App\Models\User;
use App\Repository\ApiRequestRepository;
use App\Repository\GifRepository;
use App\Repository\UserRepository;
use App\Services\GifService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class GifControllerTest extends TestCase
{
    public function tearDown(): void
    {
        \Mockery::close();
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function search()
    {
        $gifService = \Mockery::mock(GifService::class);
        $apiRequestRepository = \Mockery::mock(ApiRequestRepository::class);
        $gifRepository = \Mockery::mock(GifRepository::class);
        $userRepository = \Mockery::mock(UserRepository::class);
        $jsonResponse = \Mockery::mock(JsonResponse::class);
        $gifController = new GifController($gifService, $apiRequestRepository, $gifRepository, $userRepository,
            $jsonResponse);

        $request = new SearchGifRequest;
        $request->merge([
            'query' => 'Mati',
            'limit' => 1,
        ]);

        $json = new JsonResponse(file_get_contents('tests/Unit/Http/Controllers/GifControllerResponses/searchResponse.json'));

        $gifService->shouldReceive('search')->once()->andReturn($json);
        $apiRequestRepository->shouldReceive('storeApiRequest')->once();
        $jsonResponse->shouldReceive('setData')->once();

        $response = $gifController->search($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function getById()
    {
        $gifService = \Mockery::mock(GifService::class);
        $apiRequestRepository = \Mockery::mock(ApiRequestRepository::class);
        $gifRepository = \Mockery::mock(GifRepository::class);
        $userRepository = \Mockery::mock(UserRepository::class);
        $jsonResponse = \Mockery::mock(JsonResponse::class);
        $gifController = new GifController($gifService, $apiRequestRepository, $gifRepository, $userRepository,
            $jsonResponse);

        $request = new GetGifByIdRequest;
        $request->merge([
            'id' => 'APqEbxBsVlkWSuFpth',
        ]);

        $json = new JsonResponse(file_get_contents('tests/Unit/Http/Controllers/GifControllerResponses/getByIdResponse.json'));

        $gifService->shouldReceive('getById')->once()->andReturn($json);
        $apiRequestRepository->shouldReceive('storeApiRequest')->once();
        $jsonResponse->shouldReceive('setData')->once();

        $response = $gifController->getById($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * @test
     */
    public function store()
    {
        $gifService = \Mockery::mock(GifService::class);
        $apiRequestRepository = \Mockery::mock(ApiRequestRepository::class);
        $gifRepository = \Mockery::mock(GifRepository::class);
        $userRepository = \Mockery::mock(UserRepository::class);
        $jsonResponse = \Mockery::mock(JsonResponse::class);
        $gifController = new GifController($gifService, $apiRequestRepository, $gifRepository, $userRepository,
            $jsonResponse);

        $request = new StoreGifRequest;
        $request->merge([
            'gif_id' => 'APqEbxBsVlkWSuFpth',
            'alias' => 'matias',
        ]);

        $gifRepository->shouldReceive('getGifById')
                      ->once()
                      ->with($request->get('gif_id'))
                      ->andReturn(null);
        $gifFake = \Mockery::mock(Gif::class);
        $userFake = \Mockery::mock(User::class);
        $gifRepository->shouldReceive('storeGif')
                      ->once()
                      ->with($request->get('gif_id'))
                      ->andReturn($gifFake);
        $userRepository->shouldReceive('syncGif')
                       ->once()
                       ->with($userFake, $gifFake, $request->get('alias'));
        $jsonResponse->shouldReceive('setData')->once();
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        $response = $gifController->store($request, $userFake);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
