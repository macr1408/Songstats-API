<?php

namespace App\Http\Controllers;

use App\Http\Response\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use App\Sdk\SpotifySdk;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class AuthController extends Controller
{
    private $log;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    public function authUser(Request $request, SpotifySdk $spotifySdk): JsonResponse
    {
        $authCode = filter_var($request->input('code'), FILTER_SANITIZE_STRING);
        if (empty($authCode)) {
            return new JsonResponse(['error' => 'Code must not be empty'], 422);
        }
        try {
            $res = $spotifySdk->auth($authCode);
            $this->log->debug($res);
        } catch (ClientExceptionInterface $e) {
            return new JsonResponse($e->getResponse()->getContent(false), 422);
        }
        return new JsonResponse($res);
    }
}
