<?php

namespace App\Sdk;

use App\Api\SpotifyApi;
use App\Api\SpotifyAuthApi;

class SpotifySdk
{
    private $spotifyApi;
    private $spotifyAuthApi;
    private $redirectUrl;
    private $appId;
    private $appSecret;
    private $accessToken;

    public function __construct(SpotifyApi $spotifyApi, SpotifyAuthApi $spotifyAuthApi)
    {
        $this->spotifyApi = $spotifyApi;
        $this->spotifyAuthApi = $spotifyAuthApi;
        $this->redirectUrl = env('SPOTIFY_REDIRECT_URL');
        $this->appId = env('SPOTIFY_APP_ID');
        $this->appSecret = env('SPOTIFY_APP_SECRET');
        $this->accessToken = '';
    }

    private function getAuthHeader(): string
    {
        return 'Basic ' . base64_encode($this->appId . ':' . $this->appSecret);
    }

    private function getAccessToken(): string
    {
        return 'Bearer ' . $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getLoginUrl(): string
    {
        $params = [
            'scope' => 'user-read-currently-playing user-read-email',
            'response_type' => 'code',
            'client_id' => $this->appId,
            'redirect_uri' => $this->redirectUrl,
            'show_dialog' => true
        ];
        return $this->spotifyAuthApi->getBaseUrl() . 'authorize?' . http_build_query($params);
    }

    public function auth(string $code): array
    {
        $body = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUrl
        ];
        $headers = [
            'Authorization' => $this->getAuthHeader()
        ];
        return $this->spotifyAuthApi->post('/api/token', $body, $headers);
    }

    public function getUser(string $user = ''): array
    {
        $body = [];
        $headers = [
            'Authorization' => $this->getAccessToken()
        ];
        if (!$user) {
            return $this->spotifyApi->get('/v1/me', $body, $headers);
        } else {
            return $this->spotifyApi->get('/v1/users/' . $user, $body, $headers);
        }
    }

    public function getCurrentPlaying(): array
    {
        $headers = [
            'Authorization' => $this->getAccessToken()
        ];
        return $this->spotifyApi->get('/v1/me/player/currently-playing', [], $headers);
    }
}
