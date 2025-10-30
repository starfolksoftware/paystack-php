<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Response\PaymentRequest;

use DateTimeImmutable;

/**
 * Payment request response data transfer object
 */
final class PaymentRequestData
{
    public function __construct(
        public readonly int $id,
        public readonly string $request_code,
        public readonly string $description,
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $status,
        public readonly ?string $due_date = null,
        public readonly bool $has_invoice = false,
        public readonly ?string $invoice_url = null,
        public readonly bool $offline_reference = false,
        public readonly array $customer = [],
        public readonly array $line_items = [],
        public readonly array $tax = [],
        public readonly array $metadata = [],
        public readonly ?DateTimeImmutable $created_at = null,
        public readonly ?DateTimeImmutable $updated_at = null
    ) {}

    /**
     * Create a PaymentRequestData instance from API response array
     * 
     * @param array<string, mixed> $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            id: $data['id'],
            request_code: $data['request_code'],
            description: $data['description'],
            amount: $data['amount'],
            currency: $data['currency'],
            status: $data['status'],
            due_date: $data['due_date'] ?? null,
            has_invoice: $data['has_invoice'] ?? false,
            invoice_url: $data['invoice_url'] ?? null,
            offline_reference: $data['offline_reference'] ?? false,
            customer: $data['customer'] ?? [],
            line_items: $data['line_items'] ?? [],
            tax: $data['tax'] ?? [],
            metadata: $data['metadata'] ?? [],
            created_at: isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null,
        );
    }

    /**
     * Check if the payment request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the payment request is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the payment request is partially paid
     */
    public function isPartiallyPaid(): bool
    {
        return $this->status === 'partially_paid';
    }

    /**
     * Check if the payment request is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the payment request amount in major currency unit
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
     * Get total line items amount
     */
    public function getLineItemsTotal(): int
    {
        if (empty($this->line_items)) {
            return 0;
        }

        $total = 0;
        foreach ($this->line_items as $item) {
            $total += ($item['amount'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return $total;
    }

    /**
     * Get total tax amount
     */
    public function getTaxTotal(): int
    {
        if (empty($this->tax)) {
            return 0;
        }

        return array_sum(array_column($this->tax, 'amount'));
    }

    /**
     * Check if payment request has due date
     */
    public function hasDueDate(): bool
    {
        return $this->due_date !== null;
    }

    /**
     * Get due date as DateTimeImmutable if available
     */
    public function getDueDateAsDateTime(): ?DateTimeImmutable
    {
        return $this->due_date ? new DateTimeImmutable($this->due_date) : null;
    }

    /**
     * Check if payment request is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->hasDueDate() || $this->isPaid()) {
            return false;
        }

        $dueDate = $this->getDueDateAsDateTime();
        return $dueDate && $dueDate < new DateTimeImmutable();
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
            'request_code' => $this->request_code,
            'description' => $this->description,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'has_invoice' => $this->has_invoice,
            'invoice_url' => $this->invoice_url,
            'offline_reference' => $this->offline_reference,
            'customer' => $this->customer,
            'line_items' => $this->line_items,
            'tax' => $this->tax,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->format('c'),
            'updated_at' => $this->updated_at?->format('c'),
        ];
    }
}