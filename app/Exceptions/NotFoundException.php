<?php

namespace App\Exceptions;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class NotFoundException extends Exception
{
       public function render($request)
    {
        return response()->json([
            'error' => 'NotFound',
            'message' => $this->getMessage()
        ], Response::HTTP_NOT_FOUND);
    }
}
