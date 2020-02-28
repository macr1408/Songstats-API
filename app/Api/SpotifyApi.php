<?php

namespace App\Api;

class SpotifyApi extends ApiConnector
{
    public function getBaseUrl(): string
    {
        return 'https://api.spotify.com';
    }
}
