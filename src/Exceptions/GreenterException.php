<?php

namespace CodersFree\LaravelGreenter\Exceptions;

use Exception;

class GreenterException extends Exception
{
    /**
     * GreenterException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}