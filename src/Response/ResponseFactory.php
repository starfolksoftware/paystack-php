<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response;

use StarfolkSoftware\Paystack\Response\Customer\CustomerData;
use StarfolkSoftware\Paystack\Response\Customer\CustomerListResponse;
use StarfolkSoftware\Paystack\Response\Transaction\TransactionData;
use StarfolkSoftware\Paystack\Response\Transaction\TransactionInitializeResponse;
use StarfolkSoftware\Paystack\Response\PaymentRequest\PaymentRequestData;

/**
 * Factory for creating response DTOs from API response arrays
 */
class ResponseFactory
{
    /**
     * Create a customer response from API response array
     * 
     * @param array<string, mixed> $response
     * @return PaystackResponse<CustomerData>
     */
    public static function createCustomerResponse(array $response): PaystackResponse
    {
        if (!($response['status'] ?? false)) {
            return PaystackResponse::fromArray($response);
        }

        $customerData = CustomerData::fromArray($response['data'] ?? []);
        return new PaystackResponse(
            status: $response['status'],
            message: $response['message'] ?? '',
            data: $customerData,
            meta: $response['meta'] ?? []
        );
    }

    /**
     * Create a customer list response from API response array
     * 
     * @param array<string, mixed> $response
     * @return CustomerListResponse
     */
    public static function createCustomerListResponse(array $response): CustomerListResponse
    {
        return CustomerListResponse::fromArray($response);
    }

    /**
     * Create a transaction response from API response array
     * 
     * @param array<string, mixed> $response
     * @return PaystackResponse<TransactionData>
     */
    public static function createTransactionResponse(array $response): PaystackResponse
    {
        if (!($response['status'] ?? false)) {
            return PaystackResponse::fromArray($response);
        }

        $transactionData = TransactionData::fromArray($response['data'] ?? []);
        return new PaystackResponse(
            status: $response['status'],
            message: $response['message'] ?? '',
            data: $transactionData,
            meta: $response['meta'] ?? []
        );
    }

    /**
     * Create a transaction initialization response from API response array
     * 
     * @param array<string, mixed> $response
     * @return TransactionInitializeResponse
     */
    public static function createTransactionInitializeResponse(array $response): TransactionInitializeResponse
    {
        return TransactionInitializeResponse::fromArray($response);
    }

    /**
     * Create a transaction list response from API response array
     * 
     * @param array<string, mixed> $response
     * @return PaginatedResponse<TransactionData>
     */
    public static function createTransactionListResponse(array $response): PaginatedResponse
    {
        return PaginatedResponse::fromArrayWithFactory(
            $response,
            fn(array $data) => TransactionData::fromArray($data)
        );
    }

    /**
     * Create a payment request response from API response array
     * 
     * @param array<string, mixed> $response
     * @return PaystackResponse<PaymentRequestData>
     */
    public static function createPaymentRequestResponse(array $response): PaystackResponse
    {
        if (!($response['status'] ?? false)) {
            return PaystackResponse::fromArray($response);
        }

        $paymentRequestData = PaymentRequestData::fromArray($response['data'] ?? []);
        return new PaystackResponse(
            status: $response['status'],
            message: $response['message'] ?? '',
            data: $paymentRequestData,
            meta: $response['meta'] ?? []
        );
    }

    /**
     * Create a payment request list response from API response array
     * 
     * @param array<string, mixed> $response
     * @return PaginatedResponse<PaymentRequestData>
     */
    public static function createPaymentRequestListResponse(array $response): PaginatedResponse
    {
        return PaginatedResponse::fromArrayWithFactory(
            $response,
            fn(array $data) => PaymentRequestData::fromArray($data)
        );
    }

    /**
     * Create a generic Paystack response from API response array
     * 
     * @param array<string, mixed> $response
     * @return PaystackResponse<mixed>
     */
    public static function createGenericResponse(array $response): PaystackResponse
    {
        return PaystackResponse::fromArray($response);
    }
}