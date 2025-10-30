<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response;

/**
 * Pagination metadata for list responses
 */
final class PaginationMeta
{
    public function __construct(
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $page,
        public readonly int $pageCount,
        public readonly ?string $next = null,
        public readonly ?string $previous = null
    ) {}

    /**
     * Create pagination metadata from API response meta array
     * 
     * @param array<string, mixed> $meta
     * @return static
     */
    public static function fromArray(array $meta): static
    {
        return new static(
            total: $meta['total'] ?? 0,
            perPage: $meta['perPage'] ?? 50,
            page: $meta['page'] ?? 1,
            pageCount: $meta['pageCount'] ?? 1,
            next: $meta['next'] ?? null,
            previous: $meta['previous'] ?? null
        );
    }

    /**
     * Check if there is a next page
     */
    public function hasNextPage(): bool
    {
        return $this->page < $this->pageCount;
    }

    /**
     * Check if there is a previous page
     */
    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    /**
     * Get the next page number (or null if no next page)
     */
    public function getNextPageNumber(): ?int
    {
        return $this->hasNextPage() ? $this->page + 1 : null;
    }

    /**
     * Get the previous page number (or null if no previous page)
     */
    public function getPreviousPageNumber(): ?int
    {
        return $this->hasPreviousPage() ? $this->page - 1 : null;
    }

    /**
     * Convert to array format
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'perPage' => $this->perPage,
            'page' => $this->page,
            'pageCount' => $this->pageCount,
            'next' => $this->next,
            'previous' => $this->previous,
        ];
    }
}