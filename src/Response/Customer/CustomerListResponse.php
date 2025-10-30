<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response\Customer;

use StarfolkSoftware\Paystack\Response\PaystackResponse;
use StarfolkSoftware\Paystack\Response\PaginationMeta;

/**
 * Response for customer list operations
 * 
 * @extends PaystackResponse<CustomerData[]>
 */
final class CustomerListResponse extends PaystackResponse
{
    /**
     * @param CustomerData[] $customers
     */
    public function __construct(
        bool $status,
        string $message,
        public readonly array $customers = [],
        public readonly ?PaginationMeta $pagination = null
    ) {
        parent::__construct($status, $message, $customers, $pagination?->toArray() ?? []);
    }

    /**
     * Create a CustomerListResponse from API response array
     * 
     * @param array<string, mixed> $response
     * @return static
     */
    public static function fromArray(array $response): static
    {
        $customers = [];
        if (isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $customerData) {
                $customers[] = CustomerData::fromArray($customerData);
            }
        }

        $pagination = null;
        if (isset($response['meta']) && is_array($response['meta'])) {
            $pagination = PaginationMeta::fromArray($response['meta']);
        }

        return new static(
            status: $response['status'] ?? false,
            message: $response['message'] ?? '',
            customers: $customers,
            pagination: $pagination
        );
    }

    /**
     * Get all customers from the response
     * 
     * @return CustomerData[]
     */
    public function getCustomers(): array
    {
        return $this->customers;
    }

    /**
     * Get the first customer from the response (useful for single-item responses)
     */
    public function getFirstCustomer(): ?CustomerData
    {
        return $this->customers[0] ?? null;
    }

    /**
     * Get pagination information
     */
    public function getPagination(): ?PaginationMeta
    {
        return $this->pagination;
    }

    /**
     * Check if there are more pages available
     */
    public function hasMorePages(): bool
    {
        return $this->pagination?->hasNextPage() ?? false;
    }

    /**
     * Get the total number of customers
     */
    public function getTotal(): int
    {
        return $this->pagination?->total ?? count($this->customers);
    }
}