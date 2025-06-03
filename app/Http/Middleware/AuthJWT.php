<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
class AuthJWT
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
              if (!$request->headers->has('authorization')) {
                throw new UnauthorizedException('Token deve ser enviado');
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                throw new UnauthorizedException(('Não autorizado'));
            }
        } catch (JWTException $e) {
                  throw new UnauthorizedException('Token inválido');
        }

        return $next($request);
    }
}