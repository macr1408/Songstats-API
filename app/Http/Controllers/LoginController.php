<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use App\Sdk\SpotifySdk;

class LoginController extends Controller
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

    public function login(Request $request, SpotifySdk $spotifySdk)
    {
        $redirectUrl = $spotifySdk->getLoginUrl();
        return redirect($redirectUrl);
    }
}
