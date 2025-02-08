<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public $token;
    public $jwt_secret;
    public function __construct()
    {
        $this->token = request()->header('token');
        $this->jwt_secret = getenv('JWT_SECRET');
    }
}
