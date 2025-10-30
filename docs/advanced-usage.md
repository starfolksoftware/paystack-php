# Advanced Usage

This guide covers advanced patterns, optimization techniques, and complex use cases for the Paystack PHP SDK.

## Table of Contents

- [HTTP Client Configuration](#http-client-configuration)
- [Advanced Error Handling](#advanced-error-handling)
- [Retry Logic and Resilience](#retry-logic-and-resilience)
- [Webhook Security](#webhook-security)
- [Marketplace Payments](#marketplace-payments)
- [Bulk Operations](#bulk-operations)
- [Performance Optimization](#performance-optimization)
- [Testing Strategies](#testing-strategies)
- [Advanced Integrations](#advanced-integrations)

## HTTP Client Configuration

### Custom HTTP Client

The SDK uses PSR-18 HTTP clients. You can configure a custom client for specific needs:

```php
use StarfolkSoftware\Paystack\Client as PaystackClient;
use StarfolkSoftware\Paystack\ClientBuilder;
use Http\Client\Common\Plugin\RetryPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Psr\Log\LoggerInterface;

// Create a custom client builder
$clientBuilder = new ClientBuilder();

// Add retry plugin
$retryPlugin = new RetryPlugin([
    'retries' => 3,
]);
$clientBuilder->addPlugin($retryPlugin);

// Add logging plugin
$logger = new YourCustomLogger(); // Implement LoggerInterface
$loggerPlugin = new LoggerPlugin($logger);
$clientBuilder->addPlugin($loggerPlugin);

// Initialize Paystack client with custom builder
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
    'clientBuilder' => $clientBuilder,
]);
```

### Timeout Configuration

Configure request timeouts for better control:

```php
use Http\Client\Curl\Client as CurlClient;

$curlClient = new CurlClient(null, null, [
    CURLOPT_TIMEOUT => 30, // 30 seconds timeout
    CURLOPT_CONNECTTIMEOUT => 10, // 10 seconds connection timeout
]);

$clientBuilder = new ClientBuilder($curlClient);
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
    'clientBuilder' => $clientBuilder,
]);
```

## Advanced Error Handling

### Custom Exception Handling

Create a comprehensive error handling strategy:

```php
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;

class PaymentProcessor
{
    private PaystackClient $paystack;
    private LoggerInterface $logger;
    
    public function __construct(PaystackClient $paystack, LoggerInterface $logger)
    {
        $this->paystack = $paystack;
        $this->logger = $logger;
    }
    
    public function processPayment(array $paymentData): PaymentResult
    {
        try {
            $transaction = $this->paystack->transactions->initialize($paymentData);
            
            if (!$transaction['status']) {
                return $this->handleApiError($transaction);
            }
            
            return PaymentResult::success($transaction['data']);
            
        } catch (NetworkExceptionInterface $e) {
            // Network connectivity issues
            $this->logger->error('Network error during payment processing', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);
            
            return PaymentResult::failure('Network connection failed. Please try again.');
            
        } catch (RequestExceptionInterface $e) {
            // Request formatting issues
            $this->logger->error('Request error during payment processing', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);
            
            return PaymentResult::failure('Invalid payment request.');
            
        } catch (ClientExceptionInterface $e) {
            // Other HTTP client issues
            $this->logger->error('HTTP client error during payment processing', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);
            
            return PaymentResult::failure('Payment service unavailable.');
            
        } catch (\Exception $e) {
            // Unexpected errors
            $this->logger->critical('Unexpected error during payment processing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payment_data' => $paymentData
            ]);
            
            return PaymentResult::failure('An unexpected error occurred.');
        }
    }
    
    private function handleApiError(array $response): PaymentResult
    {
        $message = $response['message'] ?? 'Unknown API error';
        
        // Handle specific error types
        if (strpos($message, 'email') !== false) {
            return PaymentResult::failure('Invalid email address provided.');
        }
        
        if (strpos($message, 'amount') !== false) {
            return PaymentResult::failure('Invalid payment amount.');
        }
        
        // Log for investigation
        $this->logger->warning('Paystack API error', [
            'response' => $response
        ]);
        
        return PaymentResult::failure($message);
    }
}

class PaymentResult
{
    public bool $success;
    public ?array $data;
    public ?string $error;
    
    private function __construct(bool $success, ?array $data = null, ?string $error = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }
    
    public static function success(array $data): self
    {
        return new self(true, $data);
    }
    
    public static function failure(string $error): self
    {
        return new self(false, null, $error);
    }
}
```

## Retry Logic and Resilience

### Automatic Retry with Exponential Backoff

Implement retry logic for failed requests:

```php
class ResilientPaystackClient
{
    private PaystackClient $client;
    private int $maxRetries;
    private int $baseDelay;
    
    public function __construct(PaystackClient $client, int $maxRetries = 3, int $baseDelay = 1000)
    {
        $this->client = $client;
        $this->maxRetries = $maxRetries;
        $this->baseDelay = $baseDelay; // milliseconds
    }
    
    public function executeWithRetry(callable $operation, array $context = []): array
    {
        $lastException = null;
        
        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                return $operation();
                
            } catch (NetworkExceptionInterface $e) {
                $lastException = $e;
                
                if ($attempt < $this->maxRetries) {
                    $delay = $this->calculateDelay($attempt);
                    usleep($delay * 1000); // Convert to microseconds
                    
                    error_log("Payment attempt {$attempt} failed, retrying in {$delay}ms: " . $e->getMessage());
                    continue;
                }
                
            } catch (ClientExceptionInterface $e) {
                // Don't retry client errors (4xx)
                throw $e;
            }
        }
        
        throw $lastException;
    }
    
    private function calculateDelay(int $attempt): int
    {
        // Exponential backoff with jitter
        $delay = $this->baseDelay * pow(2, $attempt - 1);
        $jitter = random_int(0, (int)($delay * 0.1)); // 10% jitter
        
        return min($delay + $jitter, 30000); // Max 30 seconds
    }
    
    public function initializePayment(array $paymentData): array
    {
        return $this->executeWithRetry(function () use ($paymentData) {
            return $this->client->transactions->initialize($paymentData);
        });
    }
    
    public function verifyPayment(string $reference): array
    {
        return $this->executeWithRetry(function () use ($reference) {
            return $this->client->transactions->verify($reference);
        });
    }
}

// Usage
$resilientClient = new ResilientPaystackClient($paystack);
$transaction = $resilientClient->initializePayment([
    'email' => 'customer@example.com',
    'amount' => 20000
]);
```

## Webhook Security

### Advanced Webhook Verification

Implement secure webhook handling with additional security measures:

```php
class SecureWebhookHandler
{
    private string $secretKey;
    private LoggerInterface $logger;
    private array $allowedEvents;
    private int $timestampTolerance = 300; // 5 minutes
    
    public function __construct(string $secretKey, LoggerInterface $logger, array $allowedEvents = [])
    {
        $this->secretKey = $secretKey;
        $this->logger = $logger;
        $this->allowedEvents = $allowedEvents ?: [
            'charge.success',
            'charge.failed',
            'subscription.create',
            'subscription.disable',
            'invoice.create',
            'invoice.update',
            'invoice.payment_failed'
        ];
    }
    
    public function handleWebhook(): void
    {
        try {
            // Get raw payload and headers
            $payload = file_get_contents('php://input');
            $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
            $timestamp = $_SERVER['HTTP_X_PAYSTACK_TIMESTAMP'] ?? '';
            
            // Validate webhook
            if (!$this->validateWebhook($payload, $signature, $timestamp)) {
                $this->sendErrorResponse(401, 'Invalid webhook signature');
                return;
            }
            
            // Parse event
            $event = json_decode($payload, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->sendErrorResponse(400, 'Invalid JSON payload');
                return;
            }
            
            // Validate event structure
            if (!$this->validateEventStructure($event)) {
                $this->sendErrorResponse(400, 'Invalid event structure');
                return;
            }
            
            // Check if event type is allowed
            if (!in_array($event['event'], $this->allowedEvents)) {
                $this->logger->info('Ignoring unhandled webhook event', ['event' => $event['event']]);
                $this->sendSuccessResponse();
                return;
            }
            
            // Process event
            $this->processEvent($event);
            $this->sendSuccessResponse();
            
        } catch (\Exception $e) {
            $this->logger->error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->sendErrorResponse(500, 'Internal server error');
        }
    }
    
    private function validateWebhook(string $payload, string $signature, string $timestamp): bool
    {
        // Check timestamp to prevent replay attacks
        if ($timestamp && abs(time() - (int)$timestamp) > $this->timestampTolerance) {
            $this->logger->warning('Webhook timestamp out of tolerance', [
                'timestamp' => $timestamp,
                'current_time' => time()
            ]);
            return false;
        }
        
        // Verify signature
        $computedSignature = hash_hmac('sha512', $payload, $this->secretKey);
        
        if (!hash_equals($signature, $computedSignature)) {
            $this->logger->error('Invalid webhook signature', [
                'expected' => $computedSignature,
                'received' => $signature
            ]);
            return false;
        }
        
        return true;
    }
    
    private function validateEventStructure(array $event): bool
    {
        return isset($event['event']) && isset($event['data']);
    }
    
    private function processEvent(array $event): void
    {
        $this->logger->info('Processing webhook event', [
            'event' => $event['event'],
            'data_keys' => array_keys($event['data'])
        ]);
        
        switch ($event['event']) {
            case 'charge.success':
                $this->handleSuccessfulPayment($event['data']);
                break;
                
            case 'charge.failed':
                $this->handleFailedPayment($event['data']);
                break;
                
            case 'subscription.create':
                $this->handleNewSubscription($event['data']);
                break;
                
            case 'subscription.disable':
                $this->handleCancelledSubscription($event['data']);
                break;
                
            case 'invoice.create':
                $this->handleInvoiceCreated($event['data']);
                break;
                
            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event['data']);
                break;
                
            default:
                $this->logger->warning('Unhandled webhook event', ['event' => $event['event']]);
        }
    }
    
    private function handleSuccessfulPayment(array $data): void
    {
        $reference = $data['reference'];
        $amount = $data['amount'] / 100;
        $customerEmail = $data['customer']['email'];
        
        // Implement your business logic here
        // - Update order status
        // - Send confirmation email
        // - Update customer account
        // - Trigger fulfillment process
        
        $this->logger->info('Payment successful', [
            'reference' => $reference,
            'amount' => $amount,
            'customer' => $customerEmail
        ]);
    }
    
    private function handleFailedPayment(array $data): void
    {
        $reference = $data['reference'];
        $gateway_response = $data['gateway_response'] ?? 'Unknown error';
        
        // Implement failure handling
        // - Update order status
        // - Send failure notification
        // - Log for investigation
        
        $this->logger->warning('Payment failed', [
            'reference' => $reference,
            'reason' => $gateway_response
        ]);
    }
    
    private function handleNewSubscription(array $data): void
    {
        $subscriptionCode = $data['subscription_code'];
        $customerCode = $data['customer']['customer_code'];
        
        // Handle new subscription
        // - Update customer account
        // - Enable premium features
        // - Send welcome email
        
        $this->logger->info('New subscription created', [
            'subscription' => $subscriptionCode,
            'customer' => $customerCode
        ]);
    }
    
    private function handleCancelledSubscription(array $data): void
    {
        $subscriptionCode = $data['subscription_code'];
        $customerCode = $data['customer']['customer_code'];
        
        // Handle subscription cancellation
        // - Disable premium features
        // - Update billing status
        // - Send cancellation confirmation
        
        $this->logger->info('Subscription cancelled', [
            'subscription' => $subscriptionCode,
            'customer' => $customerCode
        ]);
    }
    
    private function handleInvoiceCreated(array $data): void
    {
        $invoiceCode = $data['invoice_code'];
        
        $this->logger->info('Invoice created', [
            'invoice' => $invoiceCode
        ]);
    }
    
    private function handleInvoicePaymentFailed(array $data): void
    {
        $invoiceCode = $data['invoice_code'];
        
        $this->logger->warning('Invoice payment failed', [
            'invoice' => $invoiceCode
        ]);
    }
    
    private function sendSuccessResponse(): void
    {
        http_response_code(200);
        echo 'OK';
    }
    
    private function sendErrorResponse(int $code, string $message): void
    {
        http_response_code($code);
        echo json_encode(['error' => $message]);
    }
}

// Usage
$webhookHandler = new SecureWebhookHandler(
    'sk_test_your_secret_key_here',
    $logger,
    ['charge.success', 'charge.failed', 'subscription.create']
);

$webhookHandler->handleWebhook();
```

## Marketplace Payments

### Split Payments Implementation

Handle complex marketplace scenarios with split payments:

```php
class MarketplacePaymentManager
{
    private PaystackClient $paystack;
    
    public function __construct(PaystackClient $paystack)
    {
        $this->paystack = $paystack;
    }
    
    public function setupMarketplaceVendor(array $vendorData): array
    {
        // Step 1: Create subaccount for vendor
        $subaccount = $this->paystack->subaccounts->create([
            'business_name' => $vendorData['business_name'],
            'settlement_bank' => $vendorData['bank_code'],
            'account_number' => $vendorData['account_number'],
            'percentage_charge' => $vendorData['commission_percentage'],
            'description' => $vendorData['description'] ?? 'Marketplace vendor account',
            'primary_contact_email' => $vendorData['email'],
            'primary_contact_name' => $vendorData['contact_name'],
            'primary_contact_phone' => $vendorData['phone'],
            'metadata' => [
                'vendor_id' => $vendorData['vendor_id'],
                'category' => $vendorData['category'] ?? 'general'
            ]
        ]);
        
        return $subaccount;
    }
    
    public function createPaymentSplit(array $vendors, array $splitConfig): array
    {
        // Create split configuration for multiple vendors
        $subaccounts = [];
        $totalShare = 0;
        
        foreach ($vendors as $vendor) {
            $subaccounts[] = [
                'subaccount' => $vendor['subaccount_code'],
                'share' => $vendor['percentage'],
            ];
            $totalShare += $vendor['percentage'];
        }
        
        // Ensure total doesn't exceed 100%
        if ($totalShare > 100) {
            throw new \InvalidArgumentException('Total vendor share cannot exceed 100%');
        }
        
        $split = $this->paystack->splits->create([
            'name' => $splitConfig['name'],
            'type' => 'percentage',
            'currency' => $splitConfig['currency'] ?? 'NGN',
            'subaccounts' => $subaccounts,
            'bearer_type' => $splitConfig['bearer_type'] ?? 'all',
            'bearer_subaccount' => $splitConfig['bearer_subaccount'] ?? null
        ]);
        
        return $split;
    }
    
    public function processMarketplacePayment(array $paymentData, string $splitCode): array
    {
        $transaction = $this->paystack->transactions->initialize([
            'email' => $paymentData['customer_email'],
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'NGN',
            'split_code' => $splitCode,
            'callback_url' => $paymentData['callback_url'],
            'metadata' => [
                'order_id' => $paymentData['order_id'],
                'marketplace_fee' => $paymentData['marketplace_fee'] ?? 0,
                'vendor_items' => $paymentData['vendor_items'] ?? []
            ]
        ]);
        
        return $transaction;
    }
    
    public function updateVendorSplit(string $splitId, string $subaccountCode, float $newPercentage): array
    {
        return $this->paystack->splits->addOrUpdateSubaccount($splitId, [
            'subaccount' => $subaccountCode,
            'share' => $newPercentage
        ]);
    }
    
    public function getVendorEarnings(string $subaccountCode, array $dateRange = []): array
    {
        $params = [
            'subaccount' => $subaccountCode,
            'perPage' => 100
        ];
        
        if (!empty($dateRange['from'])) {
            $params['from'] = $dateRange['from'];
        }
        
        if (!empty($dateRange['to'])) {
            $params['to'] = $dateRange['to'];
        }
        
        return $this->paystack->transactions->all($params);
    }
}

// Usage Example
$marketplace = new MarketplacePaymentManager($paystack);

// Setup vendors
$vendor1 = $marketplace->setupMarketplaceVendor([
    'vendor_id' => 'VENDOR_001',
    'business_name' => 'Tech Store Ltd',
    'bank_code' => '044',
    'account_number' => '0123456789',
    'commission_percentage' => 5.0, // 5% commission to platform
    'email' => 'vendor1@example.com',
    'contact_name' => 'John Vendor',
    'phone' => '+2348123456789'
]);

$vendor2 = $marketplace->setupMarketplaceVendor([
    'vendor_id' => 'VENDOR_002',
    'business_name' => 'Fashion Hub',
    'bank_code' => '058',
    'account_number' => '0987654321',
    'commission_percentage' => 3.0, // 3% commission to platform
    'email' => 'vendor2@example.com',
    'contact_name' => 'Jane Vendor',
    'phone' => '+2349876543210'
]);

// Create split for multi-vendor order
$split = $marketplace->createPaymentSplit([
    [
        'subaccount_code' => $vendor1['data']['subaccount_code'],
        'percentage' => 60 // 60% of payment goes to vendor1
    ],
    [
        'subaccount_code' => $vendor2['data']['subaccount_code'],
        'percentage' => 30 // 30% of payment goes to vendor2
    ]
    // Remaining 10% goes to main account (platform fee)
], [
    'name' => 'Multi-vendor Order Split',
    'currency' => 'NGN',
    'bearer_type' => 'all'
]);

// Process payment with split
$payment = $marketplace->processMarketplacePayment([
    'customer_email' => 'customer@example.com',
    'amount' => 100000, // ₦1,000
    'order_id' => 'ORD_12345',
    'callback_url' => 'https://marketplace.com/payment/callback',
    'vendor_items' => [
        ['vendor_id' => 'VENDOR_001', 'amount' => 60000, 'items' => ['laptop']],
        ['vendor_id' => 'VENDOR_002', 'amount' => 30000, 'items' => ['shirt']]
    ]
], $split['data']['split_code']);
```

## Bulk Operations

### Bulk Transfers

Process multiple transfers efficiently:

```php
class BulkTransferProcessor
{
    private PaystackClient $paystack;
    private LoggerInterface $logger;
    
    public function __construct(PaystackClient $paystack, LoggerInterface $logger)
    {
        $this->paystack = $paystack;
        $this->logger = $logger;
    }
    
    public function processBulkPayouts(array $payouts): BulkTransferResult
    {
        $results = [];
        $totalAmount = 0;
        $successCount = 0;
        $failureCount = 0;
        
        // Validate all payouts first
        foreach ($payouts as $index => $payout) {
            if (!$this->validatePayout($payout)) {
                $results[$index] = [
                    'success' => false,
                    'error' => 'Invalid payout data',
                    'payout' => $payout
                ];
                $failureCount++;
                continue;
            }
            
            $totalAmount += $payout['amount'];
        }
        
        // Check balance before processing
        $balance = $this->paystack->transferControl->checkBalance();
        if ($balance['data']['balance'] < $totalAmount) {
            throw new \Exception('Insufficient balance for bulk transfer');
        }
        
        // Create transfer recipients in bulk
        $recipients = $this->createBulkRecipients($payouts);
        
        // Process transfers
        $transfers = [];
        foreach ($payouts as $index => $payout) {
            if (isset($results[$index])) {
                continue; // Skip invalid payouts
            }
            
            try {
                $recipientCode = $recipients[$index]['recipient_code'];
                
                $transfer = $this->paystack->transfers->initiate([
                    'source' => 'balance',
                    'amount' => $payout['amount'],
                    'recipient' => $recipientCode,
                    'reason' => $payout['reason'] ?? 'Bulk payout',
                    'reference' => $payout['reference'] ?? null
                ]);
                
                if ($transfer['status']) {
                    $transfers[] = [
                        'transfer_code' => $transfer['data']['transfer_code'],
                        'recipient_code' => $recipientCode
                    ];
                    
                    $results[$index] = [
                        'success' => true,
                        'transfer_code' => $transfer['data']['transfer_code'],
                        'amount' => $payout['amount']
                    ];
                    $successCount++;
                } else {
                    $results[$index] = [
                        'success' => false,
                        'error' => $transfer['message'],
                        'payout' => $payout
                    ];
                    $failureCount++;
                }
                
            } catch (\Exception $e) {
                $this->logger->error('Bulk transfer failed', [
                    'payout' => $payout,
                    'error' => $e->getMessage()
                ]);
                
                $results[$index] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'payout' => $payout
                ];
                $failureCount++;
            }
            
            // Rate limiting - sleep between requests
            usleep(200000); // 200ms delay
        }
        
        return new BulkTransferResult($results, $successCount, $failureCount, $totalAmount);
    }
    
    private function validatePayout(array $payout): bool
    {
        $required = ['name', 'account_number', 'bank_code', 'amount'];
        
        foreach ($required as $field) {
            if (!isset($payout[$field]) || empty($payout[$field])) {
                return false;
            }
        }
        
        return $payout['amount'] > 0;
    }
    
    private function createBulkRecipients(array $payouts): array
    {
        $recipients = [];
        
        foreach ($payouts as $index => $payout) {
            try {
                $recipient = $this->paystack->transferRecipients->create([
                    'type' => 'nuban',
                    'name' => $payout['name'],
                    'account_number' => $payout['account_number'],
                    'bank_code' => $payout['bank_code'],
                    'currency' => $payout['currency'] ?? 'NGN',
                    'description' => $payout['description'] ?? 'Bulk transfer recipient'
                ]);
                
                if ($recipient['status']) {
                    $recipients[$index] = $recipient['data'];
                } else {
                    throw new \Exception($recipient['message']);
                }
                
            } catch (\Exception $e) {
                $this->logger->error('Failed to create transfer recipient', [
                    'payout' => $payout,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
            
            // Rate limiting
            usleep(100000); // 100ms delay
        }
        
        return $recipients;
    }
}

class BulkTransferResult
{
    public array $results;
    public int $successCount;
    public int $failureCount;
    public int $totalAmount;
    
    public function __construct(array $results, int $successCount, int $failureCount, int $totalAmount)
    {
        $this->results = $results;
        $this->successCount = $successCount;
        $this->failureCount = $failureCount;
        $this->totalAmount = $totalAmount;
    }
    
    public function isFullySuccessful(): bool
    {
        return $this->failureCount === 0;
    }
    
    public function getSuccessRate(): float
    {
        $total = $this->successCount + $this->failureCount;
        return $total > 0 ? ($this->successCount / $total) * 100 : 0;
    }
    
    public function getFailedPayouts(): array
    {
        return array_filter($this->results, fn($result) => !$result['success']);
    }
}

// Usage
$bulkProcessor = new BulkTransferProcessor($paystack, $logger);

$payouts = [
    [
        'name' => 'John Doe',
        'account_number' => '0123456789',
        'bank_code' => '044',
        'amount' => 50000,
        'reason' => 'Freelance payment',
        'reference' => 'PAYOUT_001'
    ],
    [
        'name' => 'Jane Smith',
        'account_number' => '0987654321',
        'bank_code' => '058',
        'amount' => 75000,
        'reason' => 'Vendor payment',
        'reference' => 'PAYOUT_002'
    ],
    // ... more payouts
];

$result = $bulkProcessor->processBulkPayouts($payouts);

echo "Bulk transfer completed:\n";
echo "Success: {$result->successCount}\n";
echo "Failed: {$result->failureCount}\n";
echo "Success rate: " . number_format($result->getSuccessRate(), 2) . "%\n";

if (!$result->isFullySuccessful()) {
    echo "Failed payouts:\n";
    foreach ($result->getFailedPayouts() as $failed) {
        echo "- {$failed['payout']['name']}: {$failed['error']}\n";
    }
}
```

## Performance Optimization

### Connection Pooling and Reuse

Optimize HTTP connections for high-throughput applications:

```php
use Http\Client\Common\PluginClient;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\DecoderPlugin;
use Http\Discovery\Psr17FactoryDiscovery;

class OptimizedPaystackClient
{
    private PaystackClient $client;
    private array $connectionPool = [];
    
    public function __construct(string $secretKey)
    {
        // Create optimized HTTP client
        $httpClient = $this->createOptimizedHttpClient();
        
        $clientBuilder = new ClientBuilder($httpClient);
        
        // Add performance plugins
        $clientBuilder->addPlugin(new ContentLengthPlugin());
        $clientBuilder->addPlugin(new DecoderPlugin());
        
        $this->client = new PaystackClient([
            'secretKey' => $secretKey,
            'clientBuilder' => $clientBuilder
        ]);
    }
    
    private function createOptimizedHttpClient()
    {
        // Configure cURL for optimal performance
        $curlClient = new \Http\Client\Curl\Client(
            Psr17FactoryDiscovery::findResponseFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            [
                CURLOPT_TCP_KEEPALIVE => 1,
                CURLOPT_TCP_KEEPIDLE => 60,
                CURLOPT_TCP_KEEPINTVL => 30,
                CURLOPT_MAXCONNECTS => 10,
                CURLOPT_FRESH_CONNECT => false,
                CURLOPT_FORBID_REUSE => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_DNS_CACHE_TIMEOUT => 300,
                CURLOPT_MAXREDIRS => 3,
            ]
        );
        
        return $curlClient;
    }
    
    public function getClient(): PaystackClient
    {
        return $this->client;
    }
}
```

### Caching Strategies

Implement caching for frequently accessed data:

```php
use Psr\SimpleCache\CacheInterface;

class CachedPaystackService
{
    private PaystackClient $paystack;
    private CacheInterface $cache;
    private int $defaultTtl = 3600; // 1 hour
    
    public function __construct(PaystackClient $paystack, CacheInterface $cache)
    {
        $this->paystack = $paystack;
        $this->cache = $cache;
    }
    
    public function getBanks(string $country = 'nigeria'): array
    {
        $cacheKey = "paystack_banks_{$country}";
        
        $banks = $this->cache->get($cacheKey);
        if ($banks !== null) {
            return $banks;
        }
        
        $response = $this->paystack->miscellaneous->listBanks(['country' => $country]);
        $banks = $response['data'] ?? [];
        
        // Cache for 24 hours (banks don't change frequently)
        $this->cache->set($cacheKey, $banks, 86400);
        
        return $banks;
    }
    
    public function getCustomer(string $customerCode): array
    {
        $cacheKey = "paystack_customer_{$customerCode}";
        
        $customer = $this->cache->get($cacheKey);
        if ($customer !== null) {
            return $customer;
        }
        
        $response = $this->paystack->customers->fetch($customerCode);
        $customer = $response['data'] ?? [];
        
        // Cache for 30 minutes
        $this->cache->set($cacheKey, $customer, 1800);
        
        return $customer;
    }
    
    public function invalidateCustomerCache(string $customerCode): void
    {
        $cacheKey = "paystack_customer_{$customerCode}";
        $this->cache->delete($cacheKey);
    }
    
    public function getTransactionWithCache(string $reference): array
    {
        $cacheKey = "paystack_transaction_{$reference}";
        
        $transaction = $this->cache->get($cacheKey);
        if ($transaction !== null) {
            return $transaction;
        }
        
        $response = $this->paystack->transactions->verify($reference);
        $transaction = $response['data'] ?? [];
        
        // Only cache successful transactions (they don't change)
        if ($transaction['status'] === 'success') {
            $this->cache->set($cacheKey, $transaction, $this->defaultTtl);
        }
        
        return $transaction;
    }
}
```

## Testing Strategies

### Mock Testing with PHPUnit

Create comprehensive tests using mocks:

```php
use PHPUnit\Framework\TestCase;
use Http\Mock\Client as MockClient;
use StarfolkSoftware\Paystack\Client as PaystackClient;
use StarfolkSoftware\Paystack\ClientBuilder;
use GuzzleHttp\Psr7\Response;

class PaymentServiceTest extends TestCase
{
    private MockClient $mockClient;
    private PaystackClient $paystack;
    private PaymentService $paymentService;
    
    protected function setUp(): void
    {
        $this->mockClient = new MockClient();
        
        $clientBuilder = new ClientBuilder($this->mockClient);
        $this->paystack = new PaystackClient([
            'secretKey' => 'sk_test_mock_key',
            'clientBuilder' => $clientBuilder
        ]);
        
        $this->paymentService = new PaymentService($this->paystack);
    }
    
    public function testSuccessfulPaymentInitialization(): void
    {
        // Mock successful response
        $mockResponse = new Response(200, [], json_encode([
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => 'https://checkout.paystack.com/abc123',
                'access_code' => 'abc123',
                'reference' => 'ref_123456789'
            ]
        ]));
        
        $this->mockClient->addResponse($mockResponse);
        
        $result = $this->paymentService->initializePayment([
            'email' => 'test@example.com',
            'amount' => 20000
        ]);
        
        $this->assertTrue($result['status']);
        $this->assertArrayHasKey('authorization_url', $result['data']);
        $this->assertEquals('ref_123456789', $result['data']['reference']);
        
        // Verify request was made correctly
        $request = $this->mockClient->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertStringContains('/transaction/initialize', $request->getUri()->getPath());
    }
    
    public function testPaymentVerification(): void
    {
        $mockResponse = new Response(200, [], json_encode([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'id' => 123456,
                'domain' => 'test',
                'status' => 'success',
                'reference' => 'ref_123456789',
                'amount' => 20000,
                'currency' => 'NGN',
                'customer' => [
                    'email' => 'test@example.com'
                ],
                'paid_at' => '2024-01-15T10:30:00Z'
            ]
        ]));
        
        $this->mockClient->addResponse($mockResponse);
        
        $result = $this->paymentService->verifyPayment('ref_123456789');
        
        $this->assertTrue($result['status']);
        $this->assertEquals('success', $result['data']['status']);
        $this->assertEquals(20000, $result['data']['amount']);
    }
    
    public function testFailedPaymentInitialization(): void
    {
        $mockResponse = new Response(400, [], json_encode([
            'status' => false,
            'message' => 'Invalid email address',
            'data' => null
        ]));
        
        $this->mockClient->addResponse($mockResponse);
        
        $result = $this->paymentService->initializePayment([
            'email' => 'invalid-email',
            'amount' => 20000
        ]);
        
        $this->assertFalse($result['status']);
        $this->assertEquals('Invalid email address', $result['message']);
    }
    
    public function testNetworkErrorHandling(): void
    {
        $this->mockClient->addException(new \Http\Client\Exception\NetworkException(
            'Network error',
            $this->createMock(\Psr\Http\Message\RequestInterface::class)
        ));
        
        $this->expectException(\Http\Client\Exception\NetworkException::class);
        
        $this->paymentService->initializePayment([
            'email' => 'test@example.com',
            'amount' => 20000
        ]);
    }
}

class PaymentService
{
    private PaystackClient $paystack;
    
    public function __construct(PaystackClient $paystack)
    {
        $this->paystack = $paystack;
    }
    
    public function initializePayment(array $data): array
    {
        return $this->paystack->transactions->initialize($data);
    }
    
    public function verifyPayment(string $reference): array
    {
        return $this->paystack->transactions->verify($reference);
    }
}
```

### Integration Testing

Test against Paystack's test environment:

```php
class PaystackIntegrationTest extends TestCase
{
    private PaystackClient $paystack;
    
    protected function setUp(): void
    {
        // Use test credentials
        $this->paystack = new PaystackClient([
            'secretKey' => $_ENV['PAYSTACK_TEST_SECRET_KEY'],
        ]);
        
        // Skip if no test credentials
        if (empty($_ENV['PAYSTACK_TEST_SECRET_KEY'])) {
            $this->markTestSkipped('Paystack test credentials not available');
        }
    }
    
    public function testRealPaymentFlow(): void
    {
        // Initialize payment
        $transaction = $this->paystack->transactions->initialize([
            'email' => 'test@example.com',
            'amount' => 20000,
            'currency' => 'NGN'
        ]);
        
        $this->assertTrue($transaction['status']);
        $this->assertNotEmpty($transaction['data']['reference']);
        
        // Verify the transaction (will be pending since not actually paid)
        $verification = $this->paystack->transactions->verify($transaction['data']['reference']);
        $this->assertEquals('pending', $verification['data']['status']);
    }
    
    public function testCustomerOperations(): void
    {
        // Create customer
        $customer = $this->paystack->customers->create([
            'email' => 'integration-test-' . time() . '@example.com',
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);
        
        $this->assertTrue($customer['status']);
        $customerCode = $customer['data']['customer_code'];
        
        // Fetch customer
        $fetchedCustomer = $this->paystack->customers->fetch($customerCode);
        $this->assertEquals($customer['data']['email'], $fetchedCustomer['data']['email']);
        
        // Update customer
        $updatedCustomer = $this->paystack->customers->update($customerCode, [
            'first_name' => 'Updated Test'
        ]);
        $this->assertEquals('Updated Test', $updatedCustomer['data']['first_name']);
    }
}
```

This advanced usage guide covers sophisticated patterns and techniques for building robust, scalable applications with the Paystack PHP SDK. For additional examples and specific use cases, refer to the [examples directory](../examples/) and [troubleshooting guide](troubleshooting.md).