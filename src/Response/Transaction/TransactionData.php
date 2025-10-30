<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response\Transaction;

use DateTimeImmutable;

/**
 * Transaction response data transfer object
 */
final class TransactionData
{
    public function __construct(
        public readonly int $id,
        public readonly string $reference,
        public readonly string $status,
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $domain,
        public readonly ?string $gateway_response = null,
        public readonly ?string $message = null,
        public readonly ?string $channel = null,
        public readonly ?string $ip_address = null,
        public readonly array $fees = [],
        public readonly array $authorization = [],
        public readonly array $customer = [],
        public readonly array $plan = [],
        public readonly array $metadata = [],
        public readonly ?DateTimeImmutable $created_at = null,
        public readonly ?DateTimeImmutable $updated_at = null,
        public readonly ?DateTimeImmutable $paid_at = null,
        public readonly ?DateTimeImmutable $transaction_date = null
    ) {}

    /**
     * Create a TransactionData instance from API response array
     * 
     * @param array<string, mixed> $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            reference: $data['reference'],
            status: $data['status'],
            amount: $data['amount'],
            currency: $data['currency'],
            domain: $data['domain'],
            gateway_response: $data['gateway_response'] ?? null,
            message: $data['message'] ?? null,
            channel: $data['channel'] ?? null,
            ip_address: $data['ip_address'] ?? null,
            fees: $data['fees'] ?? [],
            authorization: $data['authorization'] ?? [],
            customer: $data['customer'] ?? [],
            plan: $data['plan'] ?? [],
            metadata: $data['metadata'] ?? [],
            created_at: isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null,
            paid_at: isset($data['paid_at']) ? new DateTimeImmutable($data['paid_at']) : null,
            transaction_date: isset($data['transaction_date']) ? new DateTimeImmutable($data['transaction_date']) : null,
        );
    }

    /**
     * Check if the transaction was successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if the transaction failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the transaction was abandoned
     */
    public function isAbandoned(): bool
    {
        return $this->status === 'abandoned';
    }

    /**
     * Get the transaction amount in the major currency unit (e.g., Naira instead of kobo)
     */
    public function getAmountInMajorUnit(): float
    {
        return $this->amount / 100;
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmount(): string
    {
        $majorAmount = $this->getAmountInMajorUnit();
        return number_format($majorAmount, 2) . ' ' . $this->currency;
    }

    /**
     * Get customer email if available
     */
    public function getCustomerEmail(): ?string
    {
        return $this->customer['email'] ?? null;
    }

    /**
     * Get customer name if available
     */
    public function getCustomerName(): ?string
    {
        $first = $this->customer['first_name'] ?? '';
        $last = $this->customer['last_name'] ?? '';
        $name = trim($first . ' ' . $last);
        return $name ?: null;
    }

    /**
     * Get total fees amount
     */
    public function getTotalFees(): int
    {
        if (empty($this->fees)) {
            return 0;
        }

        return array_sum(array_column($this->fees, 'amount'));
    }

    /**
     * Get authorization code if available
     */
    public function getAuthorizationCode(): ?string
    {
        return $this->authorization['authorization_code'] ?? null;
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
            'reference' => $this->reference,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'domain' => $this->domain,
            'gateway_response' => $this->gateway_response,
            'message' => $this->message,
            'channel' => $this->channel,
            'ip_address' => $this->ip_address,
            'fees' => $this->fees,
            'authorization' => $this->authorization,
            'customer' => $this->customer,
            'plan' => $this->plan,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->format('c'),
            'updated_at' => $this->updated_at?->format('c'),
            'paid_at' => $this->paid_at?->format('c'),
            'transaction_date' => $this->transaction_date?->format('c'),
        ];
    }
}