<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response;

/**
 * Base Paystack API response wrapper
 * 
 * All Paystack API responses follow a consistent structure with status, message, and data fields.
 * This class provides a typed wrapper around those responses.
 * 
 * @template T
 */
class PaystackResponse
{
    /**
     * @param bool $status Whether the request was successful
     * @param string $message Response message from the API
     * @param T|null $data The response data (varies by endpoint)
     * @param array<string, mixed> $meta Additional metadata (pagination, etc.)
     */
    public function __construct(
        public readonly bool $status,
        public readonly string $message,
        public readonly mixed $data = null,
        public readonly array $meta = []
    ) {}

    /**
     * Create a PaystackResponse from a raw API response array
     * 
     * @param array<string, mixed> $response
     * @return static
     */
    public static function fromArray(array $response): static
    {
        return new static(
            status: $response['status'] ?? false,
            message: $response['message'] ?? '',
            data: $response['data'] ?? null,
            meta: $response['meta'] ?? []
        );
    }

    /**
     * Check if the response indicates success
     */
    public function isSuccessful(): bool
    {
        return $this->status;
    }

    /**
     * Check if the response indicates failure
     */
    public function isFailed(): bool
    {
        return !$this->status;
    }

    /**
     * Get the response data, throwing an exception if the response failed
     * 
     * @return T
     * @throws PaystackResponseException
     */
    public function getData(): mixed
    {
        if ($this->isFailed()) {
            throw new PaystackResponseException($this->message);
        }

        return $this->data;
    }

    /**
     * Get the response data or return null if failed
     * 
     * @return T|null
     */
    public function getDataOrNull(): mixed
    {
        return $this->isSuccessful() ? $this->data : null;
    }

    /**
     * Convert back to array format (for backward compatibility)
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'status' => $this->status,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $result['data'] = $this->data;
        }

        if (!empty($this->meta)) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}