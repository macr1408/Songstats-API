<?php

namespace App\Api;

abstract class JsonApi extends ApiConnector
{
    public function post(string $endpoint, array $body = [], array $headers = []): array
    {
        $headers['Content-Type'] = 'application/json';
        return $this->exec('POST', $endpoint, $body, $headers);
    }

    public function put(string $endpoint, array $body = [], array $headers = []): array
    {
        $headers['Content-Type'] = 'application/json';
        return $this->exec('PUT', $endpoint, $body, $headers);
    }

    public function delete(string $endpoint, array $body = [], array $headers = []): array
    {
        $headers['Content-Type'] = 'application/json';
        return $this->exec('DELETE', $endpoint, $body, $headers);
    }
}
