<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\HttpClient\Message;

use Psr\Http\Message\ResponseInterface;
use StarfolkSoftware\Paystack\Response\ResponseFactory;
use StarfolkSoftware\Paystack\Response\PaystackResponse;

class ResponseMediator
{
    /**
     * Get content as array (backward compatibility)
     * 
     * @param ResponseInterface $response
     * @return array<string, mixed>
     */
    public static function getContent(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get content as PaystackResponse DTO
     * 
     * @param ResponseInterface $response
     * @return PaystackResponse<mixed>
     */
    public static function getPaystackResponse(ResponseInterface $response): PaystackResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createGenericResponse($content);
    }

    /**
     * Get customer response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return PaystackResponse
     */
    public static function getCustomerResponse(ResponseInterface $response): PaystackResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createCustomerResponse($content);
    }

    /**
     * Get customer list response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return \StarfolkSoftware\Paystack\Response\Customer\CustomerListResponse
     */
    public static function getCustomerListResponse(ResponseInterface $response): \StarfolkSoftware\Paystack\Response\Customer\CustomerListResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createCustomerListResponse($content);
    }

    /**
     * Get transaction response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return PaystackResponse
     */
    public static function getTransactionResponse(ResponseInterface $response): PaystackResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createTransactionResponse($content);
    }

    /**
     * Get transaction initialization response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return \StarfolkSoftware\Paystack\Response\Transaction\TransactionInitializeResponse
     */
    public static function getTransactionInitializeResponse(ResponseInterface $response): \StarfolkSoftware\Paystack\Response\Transaction\TransactionInitializeResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createTransactionInitializeResponse($content);
    }

    /**
     * Get transaction list response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return \StarfolkSoftware\Paystack\Response\PaginatedResponse
     */
    public static function getTransactionListResponse(ResponseInterface $response): \StarfolkSoftware\Paystack\Response\PaginatedResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createTransactionListResponse($content);
    }

    /**
     * Get payment request response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return PaystackResponse
     */
    public static function getPaymentRequestResponse(ResponseInterface $response): PaystackResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createPaymentRequestResponse($content);
    }

    /**
     * Get payment request list response as typed DTO
     * 
     * @param ResponseInterface $response
     * @return \StarfolkSoftware\Paystack\Response\PaginatedResponse
     */
    public static function getPaymentRequestListResponse(ResponseInterface $response): \StarfolkSoftware\Paystack\Response\PaginatedResponse
    {
        $content = self::getContent($response);
        return ResponseFactory::createPaymentRequestListResponse($content);
    }
}