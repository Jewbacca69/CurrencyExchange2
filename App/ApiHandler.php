<?php

namespace Exchange;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class ApiHandler
{
    private string $apiKey;
    private string $apiUrl;
    private string $dataKey;
    private HttpClientInterface $client;

    public function __construct($apiUrl, $apiKey, $dataKey)
    {
        $this->client = HttpClient::create();
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->dataKey = $dataKey;
    }

    public function fetchData(): array
    {
        $url = $this->apiUrl . $this->apiKey;

        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() === 200) {
            $responseData = $response->toArray();

            if (isset($responseData[$this->dataKey])) {
                return $responseData[$this->dataKey];
            }
        }

        return [];
    }
}
