<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateController extends Controller
{
    //
    public function index() {
        return response()->json([]);
    }
    public function authenticate(Request $request) {
        $app_id = $request->input('app_id');
        $app_secret = $request->input('app_secret');

        if($app_id !== null && $app_secret !== null) {
            if ($app_id == "123456" && $app_secret == "123456") {
                $payload = array(
                    "iss" => request()->getHttpHost(),
                    "iat" => time(),
                    "exp" => time() + (60 * 60),
                );
                $token = JWT::encode($payload, $this->jwt_secret,'HS256');
                return response()->json(['token' => $token], Response::HTTP_OK);
            } else {

                return response()->json(['error' => 'invalid app_secret'], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return response()->json(['error' => 'app_id or app_secret are required'], Response::HTTP_UNAUTHORIZED);
        }


    }
}
