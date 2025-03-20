<?php
namespace App\Exceptions;

/**
 * NotFoundException
 * 
 * Thrown when a requested resource is not found
 */
class NotFoundException extends \Exception
{
    public function __construct(string $message = "Resource not found", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}