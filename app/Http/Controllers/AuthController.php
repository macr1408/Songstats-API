<?php

namespace App\Http\Controllers;

use App\Http\Response\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use App\Sdk\SpotifySdk;
use App\Services\UserService;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class AuthController extends Controller
{
    private $logger;
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Logger $logger, UserService $userService)
    {
        $this->logger = $logger;
        $this->userService = $userService;
    }

    public function authUser(Request $request, SpotifySdk $spotifySdk): JsonResponse
    {
        $authCode = filter_var($request->input('code'), FILTER_SANITIZE_STRING);
        if (empty($authCode)) {
            return new JsonResponse(['error' => 'Code must not be empty'], 422);
        }
        try {
            $accessToken = $spotifySdk->auth($authCode);
            $spotifySdk->setAccessToken($accessToken['access_token']);
            $userData = $spotifySdk->getUser();
            $user = $this->userService->createUserAndToken($userData, $accessToken);
        } catch (ClientExceptionInterface $e) {
            $this->logger->error($e->getResponse()->getContent(false));
            return new JsonResponse($e->getResponse()->getContent(false), 422);
        }

        return new JsonResponse($user['token']);
    }

    public function test(Request $request)
    {
        return new JsonResponse('exito');
    }
}
