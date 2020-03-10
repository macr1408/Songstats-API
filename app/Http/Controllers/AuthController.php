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
    private $log;
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Logger $log, UserService $userService)
    {
        $this->log = $log;
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
            $userId = $this->userService->createUserAndToken($userData, $accessToken);
            $res = 'User created successfully';

            /* $res = $spotifySdk->auth($authCode);
            $userId = $this->userService->createUser($res);
            $spotifySdk->setAccessToken($this->userService->getUserAccessToken($userId));
            $res = $spotifySdk->getUser();
            $this->userService->storeUserData($res, $userId); */
        } catch (ClientExceptionInterface $e) {
            $this->log->error($e->getResponse()->getContent(false));
            return new JsonResponse($e->getResponse()->getContent(false), 422);
        }

        return new JsonResponse($res);
    }
}
