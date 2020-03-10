<?php

namespace App\Services;

use App\Repositories\AccessTokenRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class UserService
{

    private $userRepository;
    private $accessTokenRepository;

    public function __construct(UserRepository $userRepository, AccessTokenRepository $accessTokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function createUserAndToken(array $userData, array $accessToken): int
    {
        $userId = $this->createUser($userData);
        $this->createAccessToken($accessToken, $userId);
        return $userId;
    }

    public function createUser(array $userData): int
    {
        $user = $this->userRepository->get($userData['email'], 'email');
        if (empty($user)) {
            $user = $this->userRepository->create(['email' => $userData['email']]);
        }
        return $user->getAttribute('id');
    }

    public function createAccessToken(array $accessToken, int $userId = null): bool
    {
        $dbAcessToken = $this->accessTokenRepository->get($userId, 'user_id');
        $expiresIn = new Carbon();
        $expiresIn = $expiresIn->addSeconds($accessToken['expires_in']);
        if (empty($dbAcessToken)) {
            $this->accessTokenRepository->create([
                'user_id' => $userId,
                'access_token' => $accessToken['access_token'],
                'expires_in' => $expiresIn,
                'refresh_token' => $accessToken['refresh_token'],
                'scope' => $accessToken['scope'],
                'site' => 'spotify'
            ]);
        } else {
            $dbAcessToken->update([
                'user_id' => $userId,
                'access_token' => $accessToken['access_token'],
                'expires_in' => $expiresIn,
                'refresh_token' => $accessToken['refresh_token'],
                'scope' => $accessToken['scope'],
                'site' => 'spotify'
            ]);
        }
        return true;
    }

    public function storeUserData(array $data, int $userId): bool
    {
        $dbUser = $this->userRepository->get($data['email'], 'email');
        if (empty($dbUser)) {
            return $this->userRepository->update(['email' => $data['email']], $userId);
        } else {
            $this->userRepository->delete($userId);
        }
    }

    public function getUserAccessToken(int $userId): string
    {
        $user = $this->accessTokenRepository->get($userId, 'user_id');
        return $user->getAttribute('access_token');
    }
}
