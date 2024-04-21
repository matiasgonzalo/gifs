<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

class GifService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('gif.base_uri'),
            'timeout' => intval(config('gif.timeout_seconds')),
            'http_errors' => true,
            'verify' => false,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function search(Request $request): JsonResponse
    {
        $response = $this->client->request('GET', 'search', [
            'query' => [
                'api_key' => config('gif.api_key'),
                'q' => $request->get('query'),
                'limit' => $request->filled('limit') ? $request->get('limit') : config('gif.search.limit_default'),
                'offset' => $request->filled('offset') ? $request->get('offset') : config('gif.search.offset_default'),
            ],
            'headers' => $this->getHeaders(),
        ]);

        return $this->parseResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getById(Request $request): JsonResponse
    {
        $response = $this->client->request('GET', $request->get('id'), [
            'query' => [
                'api_key' => config('gif.api_key'),
            ],
            'headers' => $this->getHeaders(),
        ]);

        return $this->parseResponse($response);
    }

    /**
     * @return string[]
     */
    private function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    private function parseResponse(ResponseInterface $response): JsonResponse
    {
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            header('Content-Type: application/json');

            return response()->json($data, 200);
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->getStatusCode());
    }
}
