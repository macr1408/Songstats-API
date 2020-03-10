<?php

namespace App\Api;

class SpotifyApi extends JsonApi
{
    public function getBaseUrl(): string
    {
        return 'https://api.spotify.com/';
    }
}
