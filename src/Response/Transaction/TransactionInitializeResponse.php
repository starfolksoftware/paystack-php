<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response\Transaction;

use StarfolkSoftware\Paystack\Response\PaystackResponse;

/**
 * Response for transaction initialization
 * 
 * @extends PaystackResponse<array{authorization_url: string, access_code: string, reference: string}>
 */
final class TransactionInitializeResponse extends PaystackResponse
{
    public function __construct(
        bool $status,
        string $message,
        public readonly ?string $authorization_url = null,
        public readonly ?string $access_code = null,
        public readonly ?string $reference = null
    ) {
        $data = null;
        if ($authorization_url && $access_code && $reference) {
            $data = [
                'authorization_url' => $authorization_url,
                'access_code' => $access_code,
                'reference' => $reference,
            ];
        }
        
        parent::__construct($status, $message, $data);
    }

    /**
     * Create a TransactionInitializeResponse from API response array
     * 
     * @param array<string, mixed> $response
     * @return static
     */
    public static function fromArray(array $response): static
    {
        $data = $response['data'] ?? [];
        
        return new static(
            status: $response['status'] ?? false,
            message: $response['message'] ?? '',
            authorization_url: $data['authorization_url'] ?? null,
            access_code: $data['access_code'] ?? null,
            reference: $data['reference'] ?? null
        );
    }

    /**
     * Get the authorization URL for payment
     */
    public function getAuthorizationUrl(): ?string
    {
        return $this->authorization_url;
    }

    /**
     * Get the access code for payment
     */
    public function getAccessCode(): ?string
    {
        return $this->access_code;
    }

    /**
     * Get the transaction reference
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * Check if initialization was successful and all required data is present
     */
    public function isInitialized(): bool
    {
        return $this->isSuccessful() 
            && $this->authorization_url !== null 
            && $this->access_code !== null 
            && $this->reference !== null;
    }
}