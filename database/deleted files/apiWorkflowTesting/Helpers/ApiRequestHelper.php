<?php

use GuzzleHttp\Client;

trait ApiRequestHelper
{
    private $baseUrl = 'http://localhost/api';

    public function post($endpoint, $data = [], $token = null)
    {
        $client = new Client();
        $headers = $token ? ['Authorization' => "Bearer $token"] : [];
        $response = $client->post($this->baseUrl . $endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);
        return $this->processResponse($response);
    }

    public function get($endpoint, $token = null)
    {
        $client = new Client();
        $headers = $token ? ['Authorization' => "Bearer $token"] : [];
        $response = $client->get($this->baseUrl . $endpoint, [
            'headers' => $headers,
        ]);
        return $this->processResponse($response);
    }

    public function put($endpoint, $data = [], $token = null)
    {
        $client = new Client();
        $headers = $token ? ['Authorization' => "Bearer $token"] : [];
        $response = $client->put($this->baseUrl . $endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);
        return $this->processResponse($response);
    }

    private function processResponse($response)
    {
        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        return [
            'status' => $response->getStatusCode(),
            'data' => $data,
        ];
    }
}
