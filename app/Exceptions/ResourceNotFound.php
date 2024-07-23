<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFound extends Exception
{
    protected $code;
    public function __construct($message = "Resource not found.", $code = 500, Exception $previous = null)
    {
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }
    public function getStatusCode(): int
    {
        return $this->code;
    }
}
