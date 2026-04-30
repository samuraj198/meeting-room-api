<?php

namespace App\Exceptions;

use Exception;

class BookingAlreadyCancelledException extends Exception
{
    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => 'Бронь уже отменена'
        ], 409);
    }
}
