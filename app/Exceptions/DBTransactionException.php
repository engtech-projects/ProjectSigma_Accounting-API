<?php

namespace App\Exceptions;

use Exception;

class DBTransactionException extends Exception
{
    protected $code;
    public function __construct($message = "Transaction failed.", $code = 500, Exception $previous = null)
    {
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }
    public function getStatusCode(): int
    {
        return $this->code;
    }
}