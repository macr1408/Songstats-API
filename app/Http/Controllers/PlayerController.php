<?php

namespace App\Http\Controllers;

use App\Http\Response\JsonResponse;
use App\Sdk\SpotifySdk;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class PlayerController
{
    private $spotifySdk;
    private $logger;
    private $userService;

    public function __construct(SpotifySdk $spotifySdk, Logger $logger, UserService $userService)
    {
        $this->spotifySdk = $spotifySdk;
        $this->logger = $logger;
        $this->userService = $userService;
    }

    public function current(Request $request)
    {
        try {
            $dbUser = $request->user();
            $accessToken = $this->userService->getUserAccessToken($dbUser['id']);
            $this->spotifySdk->setAccessToken($accessToken);
            $currentlyPlaying = $this->spotifySdk->getCurrentPlaying();
        } catch (ClientExceptionInterface $e) {
            $this->logger->error($e->getResponse()->getContent(false));
            return new JsonResponse($e->getResponse()->getContent(false), 422);
        }
        if (empty($currentlyPlaying)) {
            return new JsonResponse(['playing' => false]);
        }
        return new JsonResponse([]);
    }
}
