<?php

namespace TokenSDK;

use GuzzleHttp\Client;

class TokenClient {
    private $client;
    private $baseUrl;
    private $apiKey;

    public function __construct(string $baseUrl, string $apiKey = null) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 5.0,
        ]);
    }

    private function request(string $method, string $uri, array $options = []) {
        if ($this->apiKey) {
            $options['headers']['Authorization'] = 'Bearer ' . $this->apiKey;
        }
        $response = $this->client->request($method, $uri, $options);
        return json_decode($response->getBody(), true);
    }

    public function issueToken(array $data) {
        return $this->request('POST', '/api/tokens/issue', ['json' => $data]);
    }

    public function callNextToken(array $data) {
        return $this->request('POST', '/api/tokens/call-next', ['json' => $data]);
    }

    public function callTokenById($tokenId, array $data = []) {
        return $this->request('POST', "/api/tokens/call/{$tokenId}", ['json' => $data]);
    }

    public function skipToken($tokenId) {
        return $this->request('POST', "/api/tokens/skip/{$tokenId}");
    }

    public function completeToken($tokenId) {
        return $this->request('POST', "/api/tokens/complete/{$tokenId}");
    }

    public function getDisplayData($locationId = null) {
        $uri = '/api/display';
        if ($locationId) {
            $uri .= '?location_id=' . $locationId;
        }
        return $this->request('GET', $uri);
    }
}
