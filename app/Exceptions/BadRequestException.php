<?php

namespace App\Exceptions;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class BadRequestException extends Exception
{
       public function render($request)
    {
        return response()->json([
            'error' => 'Bad Request',
            'message' => $this->getMessage()
        ], Response::HTTP_BAD_REQUEST);
    }
}
