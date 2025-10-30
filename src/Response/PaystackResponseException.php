<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response;

use Exception;

/**
 * Exception thrown when a Paystack API response indicates failure
 */
class PaystackResponseException extends Exception
{
    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}