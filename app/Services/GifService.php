<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function search(Request $request): ResponseInterface
    {
        return $this->client->request('GET', 'search', [
            'query' => [
                'api_key' => config('gif.api_key'),
                'q' => $request->get('query'),
                'limit' => $request->filled('limit') ? $request->get('limit') : config('gif.search.limit_default'),
                'offset' => $request->filled('offset') ? $request->get('offset') : config('gif.search.offset_default'),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
    }

    public function getById(Request $request)
    {
        return $this->client->request('GET', $request->get('id'), [
            'query' => [
                'api_key' => config('gif.api_key'),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
    }
}
