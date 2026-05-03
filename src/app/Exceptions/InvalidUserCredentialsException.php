<?php

namespace App\Exceptions;

use Exception;

class InvalidUserCredentialsException extends Exception
{
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => 'Неверная попытка входа',
        ], 401);
    }
}
