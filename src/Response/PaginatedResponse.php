<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response;

/**
 * Generic collection response for paginated list endpoints
 * 
 * @template T
 * @extends PaystackResponse<T[]>
 */
final class PaginatedResponse extends PaystackResponse
{
    /**
     * @param T[] $items
     */
    public function __construct(
        bool $status,
        string $message,
        public readonly array $items = [],
        public readonly ?PaginationMeta $pagination = null
    ) {
        parent::__construct($status, $message, $items, $pagination?->toArray() ?? []);
    }

    /**
     * Create a PaginatedResponse from API response array with item factory
     * 
     * @param array<string, mixed> $response
     * @param callable(array): T $itemFactory Function to create items from array data
     * @return static<T>
     */
    public static function fromArrayWithFactory(array $response, callable $itemFactory): static
    {
        $items = [];
        if (isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $itemData) {
                $items[] = $itemFactory($itemData);
            }
        }

        $pagination = null;
        if (isset($response['meta']) && is_array($response['meta'])) {
            $pagination = PaginationMeta::fromArray($response['meta']);
        }

        return new static(
            status: $response['status'] ?? false,
            message: $response['message'] ?? '',
            items: $items,
            pagination: $pagination
        );
    }

    /**
     * Get all items from the response
     * 
     * @return T[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get the first item from the response
     * 
     * @return T|null
     */
    public function getFirstItem(): mixed
    {
        return $this->items[0] ?? null;
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
     * Get the total number of items
     */
    public function getTotal(): int
    {
        return $this->pagination?->total ?? count($this->items);
    }

    /**
     * Check if the response is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Get the count of items in current page
     */
    public function count(): int
    {
        return count($this->items);
    }
}