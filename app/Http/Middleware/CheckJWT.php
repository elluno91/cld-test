<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jwt_secret = getenv('JWT_SECRET');
        $token = $request->header('token');

        if($request->route()->getName() == "authenticate") {
            return $next($request);
        } else {
            if($token != null) {
                try{
                    JWT::decode($token, new Key($jwt_secret, 'HS256'));
                    return $next($request);
                } catch (ExpiredException $e) {
                    return response()->json(['error' => 'token expired'], Response::HTTP_UNAUTHORIZED);
                } catch (\UnexpectedValueException $e) {
                    return response()->json(['error' => 'invalid token'], Response::HTTP_UNAUTHORIZED);
                }
            } else {
                return response()->json(['error' => 'token is required'], Response::HTTP_UNAUTHORIZED);
            }
        }
    }
}
