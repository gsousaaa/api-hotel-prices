<?php

namespace App\Exceptions;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class UnauthorizedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => $this->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }
}