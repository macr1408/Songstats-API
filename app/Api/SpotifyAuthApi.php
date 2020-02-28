<?php

namespace App\Api;

class SpotifyAuthApi extends ApiConnector
{
    public function getBaseUrl(): string
    {
        return 'https://accounts.spotify.com/';
    }
}
