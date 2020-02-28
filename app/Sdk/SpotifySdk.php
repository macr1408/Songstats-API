<?php

namespace App\Sdk;

use App\Api\SpotifyApi;
use App\Api\SpotifyAuthApi;

class SpotifySdk
{
    private $spotifyApi;
    private $spotifyAuthApi;

    public function __construct(SpotifyApi $spotifyApi, SpotifyAuthApi $spotifyAuthApi)
    {
        $this->spotifyApi = $spotifyAuthApi;
        $this->spotifyAuthApi = $spotifyAuthApi;
    }

    public function auth(string $code): array
    {
        $body = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('SPOTIFY_REDIRECT_URL')
        ];
        $headers = [
            'Authorization' => base64_encode(env('SPOTIFY_APP_ID') . ':' . env('SPOTIFY_APP_SECRET'))
        ];
        return $this->spotifyAuthApi->post('/api/token', $body, $headers);
    }
}
