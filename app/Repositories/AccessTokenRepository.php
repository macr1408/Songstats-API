<?php

namespace App\Repositories;

use App\Entity\AccessToken;

class AccessTokenRepository extends Repository
{
    public function __construct(AccessToken $accessToken)
    {
        $this->model = $accessToken;
    }
}
