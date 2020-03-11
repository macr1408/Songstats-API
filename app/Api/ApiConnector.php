<?php

namespace App\Api;

use Symfony\Component\HttpClient\HttpClient;

abstract class ApiConnector
{
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = HttpClient::create(['base_uri' => $this->getBaseUrl()]);
    }

    public abstract function getBaseUrl(): string;

    public function exec(string $method, string $endpoint, array $body, array $headers): array
    {
        $opts = ['headers' => $headers];
        if (isset($headers['Content-Type']) && $headers['Content-Type'] === 'application/json') {
            $opts['json'] = $body;
        } else {
            $opts['body'] = $body;
        }
        $response = $this->client->request($method, $endpoint, $opts);
        $response = $response->getContent();
        if (empty($response)) {
            return [];
        }
        return json_decode($response, true);
    }

    public function get(string $endpoint, array $body = [], array $headers = []): array
    {
        if (!empty($body)) {
            $endpoint .= '?' . http_build_query($body);
        }
        return $this->exec('GET', $endpoint, $body, $headers);
    }

    public function post(string $endpoint, array $body = [], array $headers = []): array
    {
        return $this->exec('POST', $endpoint, $body, $headers);
    }

    public function put(string $endpoint, array $body = [], array $headers = []): array
    {
        return $this->exec('PUT', $endpoint, $body, $headers);
    }

    public function delete(string $endpoint, array $body = [], array $headers = []): array
    {
        return $this->exec('DELETE', $endpoint, $body, $headers);
    }
}
