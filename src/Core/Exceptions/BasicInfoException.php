<?php

namespace MadeiraMadeira\HealthCheck\Core\Exceptions;

use Exception;
use Throwable;

class BasicInfoException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}