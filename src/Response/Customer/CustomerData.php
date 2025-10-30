<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response\Customer;

use DateTimeImmutable;

/**
 * Customer response data transfer object
 */
final class CustomerData
{
    public function __construct(
        public readonly int $id,
        public readonly string $customer_code,
        public readonly string $email,
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $phone = null,
        public readonly ?string $risk_action = null,
        public readonly bool $identified = false,
        public readonly array $identifications = [],
        public readonly array $authorizations = [],
        public readonly array $subscriptions = [],
        public readonly array $metadata = [],
        public readonly ?DateTimeImmutable $created_at = null,
        public readonly ?DateTimeImmutable $updated_at = null
    ) {}

    /**
     * Create a CustomerData instance from API response array
     * 
     * @param array<string, mixed> $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            customer_code: $data['customer_code'],
            email: $data['email'],
            first_name: $data['first_name'] ?? null,
            last_name: $data['last_name'] ?? null,
            phone: $data['phone'] ?? null,
            risk_action: $data['risk_action'] ?? null,
            identified: $data['identified'] ?? false,
            identifications: $data['identifications'] ?? [],
            authorizations: $data['authorizations'] ?? [],
            subscriptions: $data['subscriptions'] ?? [],
            metadata: $data['metadata'] ?? [],
            created_at: isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null,
        );
    }

    /**
     * Get the customer's full name
     */
    public function getFullName(): string
    {
        $parts = array_filter([$this->first_name, $this->last_name]);
        return implode(' ', $parts) ?: $this->email;
    }

    /**
     * Check if customer has been identified
     */
    public function isIdentified(): bool
    {
        return $this->identified;
    }

    /**
     * Check if customer has active authorizations
     */
    public function hasAuthorizations(): bool
    {
        return !empty($this->authorizations);
    }

    /**
     * Check if customer has active subscriptions
     */
    public function hasSubscriptions(): bool
    {
        return !empty($this->subscriptions);
    }

    /**
     * Get the customer's risk action
     */
    public function getRiskAction(): string
    {
        return $this->risk_action ?? 'default';
    }

    /**
     * Convert to array format
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_code' => $this->customer_code,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'risk_action' => $this->risk_action,
            'identified' => $this->identified,
            'identifications' => $this->identifications,
            'authorizations' => $this->authorizations,
            'subscriptions' => $this->subscriptions,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->format('c'),
            'updated_at' => $this->updated_at?->format('c'),
        ];
    }
}