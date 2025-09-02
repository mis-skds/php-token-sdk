<?php

namespace TokenSDK;

use GuzzleHttp\Client;

class TokenClient
{
    private $client;
    private $baseUrl;
    private $clientId;
    private $clientSecret;

    public function __construct(string $baseUrl, string $clientId, string $clientSecret)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = new Client([
            'base_uri' => $this->baseUrl . "/",
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'connect_timeout' => 5.0, // Connection timeout
            'timeout'  => 5.0,
        ]);
    }

    private function request(string $method, string $uri, array $options = [])
    {
        if ($this->clientId && $this->clientSecret) {
            $options['headers']['X-Client-Auth'] = 'Basic ' . base64_encode("{$this->clientId}:{$this->clientSecret}");
        }

        try {
            $response = $this->client->request($method, $uri, $options);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return [
                'status' => 0,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Issue a new token.
     *
     * @param array $data
     * @return array
     */
    public function issueToken(array $data): array
    {
        return $this->request('POST', 'tokens/issue', ['json' => $data]);
    }

    /**
     * Call the next token in the queue.
     *
     * @param array $data
     * @return array
     */
    public function callNextToken(array $data): array
    {
        return $this->request('POST', 'tokens/call-next', ['json' => $data]);
    }

    /**
     * Call a specific token by its ID.
     *
     * @param int $tokenId
     * @param array $data
     * @return array
     */
    public function callTokenById($tokenId, array $data = []): array
    {
        return $this->request('POST', "tokens/{$tokenId}/call", ['json' => $data]);
    }

    /**
     * Skip a specific token by its ID.
     *
     * @param int $tokenId
     * @param array $data
     * @return array
     */
    public function skipToken($tokenId, array $data = []): array
    {
        return $this->request('POST', "tokens/{$tokenId}/skip", ['json' => $data]);
    }

    /**
     * Complete a specific token by its ID.
     *
     * @param int $tokenId
     * @param array $data
     * @return array
     */
    public function completeToken($tokenId, array $data = []): array
    {
        return $this->request('POST', "tokens/{$tokenId}/complete", ['json' => $data]);
    }

    /**
     * Get display data for a specific location.
     *
     * @param int|null $locationId
     * @return array
     */
    public function getDisplayData($locationId = null): array
    {
        $uri = 'display';
        if ($locationId) {
            $uri .= '?location_id=' . $locationId;
        }
        return $this->request('GET', $uri);
    }

    /**
     * Save location data.
     *
     * @param array $data
     * @return array
     */
    public function saveLocationData(array $data): array
    {
        return $this->request('POST', 'locations/save', ['json' => $data]);
    }

    /**
     * Get a list of locations.
     *
     * @param array $filter
     * @return array
     */
    public function getLocations(array $filter): array
    {
        return $this->request(
            'GET',
            'locations',
            [
                'query' => $filter
            ]
        );
    }

    /**
     * Get a specific location by its ID.
     *
     * @param int $locationId
     * @return array
     */
    public function getLocationById($locationId): array
    {
        return $this->request('GET', "locations/{$locationId}");
    }

    /**
     * Save service point data.
     *
     * @param array $data
     * @return array
     */
    public function saveServicepointData(array $data): array
    {
        return $this->request('POST', 'service-points/save', ['json' => $data]);
    }

    /**
     * Get a list of service points.
     *
     * @param array $filter
     * @return array
     */
    public function getServicepoints(array $filter): array
    {
        return $this->request(
            'GET',
            'service-points',
            [
                'query' => $filter
            ]
        );
    }

    /**
     * Get a specific service point by its ID.
     *
     * @param int $servicepointId
     * @return array
     */
    public function getServicepointById($servicepointId): array
    {
        return $this->request('GET', "service-points/{$servicepointId}");
    }
}
