<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class IntegrityErrorException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'status' => 'warning',
            'message' => $this->getMessage() // Use the exception message set in the constructor
        ], 500);
    }

    // Method for handling a specific Referential integrity error
    public static function dataInUse(): self
    {
        return new self('Referential Integrity Error. <br>(This data is in use!)');
    }

    // Another method for a different Referential integrity error
    public static function foreignKeyViolation(): self
    {
        return new self('Referential Integrity Error. <br>(Foreign key violation!)');
    }

    // You can add more methods as needed
}
