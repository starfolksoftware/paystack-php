# Troubleshooting Guide

This guide helps you resolve common issues when working with the Paystack PHP SDK.

## Table of Contents

- [Installation Issues](#installation-issues)
- [Authentication Problems](#authentication-problems)
- [API Request Errors](#api-request-errors)
- [Payment Issues](#payment-issues)
- [Webhook Problems](#webhook-problems)
- [Performance Issues](#performance-issues)
- [Testing and Development](#testing-and-development)
- [Common Error Messages](#common-error-messages)
- [Debug Tools and Techniques](#debug-tools-and-techniques)

## Installation Issues

### Problem: Composer Installation Fails

**Symptoms:**
```bash
composer require starfolksoftware/paystack-php
# Results in dependency conflicts or installation errors
```

**Solutions:**

1. **Update Composer:**
   ```bash
   composer self-update
   composer clear-cache
   composer install
   ```

2. **Check PHP Version:**
   ```bash
   php -v
   # Ensure PHP 8.1 or higher
   ```

3. **Force Install with Dependencies:**
   ```bash
   composer require starfolksoftware/paystack-php --with-all-dependencies
   ```

4. **Manual HTTP Client Installation:**
   ```bash
   # Choose one HTTP client implementation
   composer require php-http/guzzle7-adapter
   # OR
   composer require symfony/http-client
   ```

### Problem: HTTP Client Not Found

**Error Message:**
```
Could not find resource using any available factory. Have you installed a package that provides a psr/http-client-implementation?
```

**Solution:**
```bash
# Install an HTTP client implementation
composer require php-http/guzzle7-adapter
# OR any other PSR-18 compatible client
composer require php-http/curl-client
```

### Problem: Autoloader Issues

**Error Message:**
```
Class 'StarfolkSoftware\Paystack\Client' not found
```

**Solutions:**

1. **Check Autoloader:**
   ```php
   <?php
   require_once 'vendor/autoload.php'; // Ensure this is included
   
   use StarfolkSoftware\Paystack\Client as PaystackClient;
   ```

2. **Regenerate Autoloader:**
   ```bash
   composer dump-autoload
   ```

3. **Verify Installation:**
   ```bash
   composer show starfolksoftware/paystack-php
   ```

## Authentication Problems

### Problem: Invalid API Key Error

**Error Message:**
```json
{
  "status": false,
  "message": "Invalid key"
}
```

**Solutions:**

1. **Verify API Key Format:**
   ```php
   // Test keys start with sk_test_
   // Live keys start with sk_live_
   $secretKey = 'sk_test_your_actual_key_here';
   
   if (!str_starts_with($secretKey, 'sk_')) {
       throw new Exception('Invalid API key format');
   }
   ```

2. **Check Environment Variables:**
   ```php
   // Debug environment loading
   var_dump($_ENV['PAYSTACK_SECRET_KEY']);
   var_dump(getenv('PAYSTACK_SECRET_KEY'));
   
   // Ensure key is properly loaded
   $secretKey = $_ENV['PAYSTACK_SECRET_KEY'] ?? getenv('PAYSTACK_SECRET_KEY');
   if (empty($secretKey)) {
       throw new Exception('API key not found in environment');
   }
   ```

3. **Test API Key Validity:**
   ```php
   function testApiKey(string $secretKey): bool
   {
       try {
           $paystack = new PaystackClient(['secretKey' => $secretKey]);
           $response = $paystack->miscellaneous->listBanks(['country' => 'nigeria']);
           return $response['status'] === true;
       } catch (Exception $e) {
           echo "API Key Test Failed: " . $e->getMessage() . "\n";
           return false;
       }
   }
   
   if (!testApiKey($secretKey)) {
       echo "Please check your API key\n";
   }
   ```

### Problem: Using Live Key in Test Mode

**Symptoms:**
- Unexpected charges to real accounts
- Live webhooks triggering in development

**Solution:**
```php
class PaystackConfig
{
    public static function getConfig(): array
    {
        $environment = $_ENV['APP_ENV'] ?? 'development';
        
        if ($environment === 'production') {
            return [
                'secretKey' => $_ENV['PAYSTACK_LIVE_SECRET_KEY'],
                'publicKey' => $_ENV['PAYSTACK_LIVE_PUBLIC_KEY'],
            ];
        }
        
        return [
            'secretKey' => $_ENV['PAYSTACK_TEST_SECRET_KEY'],
            'publicKey' => $_ENV['PAYSTACK_TEST_PUBLIC_KEY'],
        ];
    }
}

// Usage
$config = PaystackConfig::getConfig();
$paystack = new PaystackClient(['secretKey' => $config['secretKey']]);
```

## API Request Errors

### Problem: Network Timeout Errors

**Error Message:**
```
cURL error 28: Operation timed out after 30000 milliseconds
```

**Solutions:**

1. **Increase Timeout:**
   ```php
   use Http\Client\Curl\Client as CurlClient;
   use StarfolkSoftware\Paystack\ClientBuilder;
   
   $curlClient = new CurlClient(null, null, [
       CURLOPT_TIMEOUT => 60, // 60 seconds
       CURLOPT_CONNECTTIMEOUT => 30, // 30 seconds connection timeout
   ]);
   
   $clientBuilder = new ClientBuilder($curlClient);
   $paystack = new PaystackClient([
       'secretKey' => $secretKey,
       'clientBuilder' => $clientBuilder,
   ]);
   ```

2. **Implement Retry Logic:**
   ```php
   function executeWithRetry(callable $operation, int $maxRetries = 3): array
   {
       $lastException = null;
       
       for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
           try {
               return $operation();
           } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
               $lastException = $e;
               
               if ($attempt < $maxRetries) {
                   $delay = pow(2, $attempt) * 1000; // Exponential backoff
                   usleep($delay * 1000);
                   echo "Retry attempt {$attempt} in {$delay}ms...\n";
                   continue;
               }
           }
       }
       
       throw $lastException;
   }
   
   // Usage
   $transaction = executeWithRetry(function() use ($paystack) {
       return $paystack->transactions->initialize([
           'email' => 'customer@example.com',
           'amount' => 20000
       ]);
   });
   ```

### Problem: SSL Certificate Issues

**Error Message:**
```
cURL error 60: SSL certificate problem: unable to get local issuer certificate
```

**Solutions:**

1. **Update CA Bundle:**
   ```php
   // Download latest CA bundle from https://curl.haxx.se/ca/cacert.pem
   // Update php.ini:
   // curl.cainfo = "/path/to/cacert.pem"
   // openssl.cafile = "/path/to/cacert.pem"
   ```

2. **Configure cURL Options (not recommended for production):**
   ```php
   $curlClient = new CurlClient(null, null, [
       CURLOPT_SSL_VERIFYPEER => false, // Only for development!
       CURLOPT_SSL_VERIFYHOST => false, // Only for development!
   ]);
   ```

3. **Use System CA Bundle:**
   ```php
   $curlClient = new CurlClient(null, null, [
       CURLOPT_CAINFO => '/etc/ssl/certs/ca-certificates.crt', // Linux
       // OR
       CURLOPT_CAINFO => '/System/Library/OpenSSL/certs/cert.pem', // macOS
   ]);
   ```

## Payment Issues

### Problem: Transaction Verification Fails

**Error Message:**
```json
{
  "status": false,
  "message": "Transaction reference not found"
}
```

**Solutions:**

1. **Check Reference Format:**
   ```php
   function verifyTransaction(string $reference): array
   {
       // Validate reference format
       if (empty($reference) || strlen($reference) < 10) {
           throw new InvalidArgumentException('Invalid transaction reference');
       }
       
       try {
           $paystack = new PaystackClient(['secretKey' => $secretKey]);
           return $paystack->transactions->verify($reference);
       } catch (Exception $e) {
           // Log error for debugging
           error_log("Transaction verification failed: {$reference} - {$e->getMessage()}");
           throw $e;
       }
   }
   ```

2. **Handle Different Transaction States:**
   ```php
   function handleTransactionVerification(string $reference): array
   {
       $response = $paystack->transactions->verify($reference);
       
       if (!$response['status']) {
           throw new Exception("Verification failed: " . $response['message']);
       }
       
       $status = $response['data']['status'];
       
       switch ($status) {
           case 'success':
               return ['status' => 'completed', 'data' => $response['data']];
               
           case 'failed':
               return ['status' => 'failed', 'reason' => $response['data']['gateway_response']];
               
           case 'abandoned':
               return ['status' => 'abandoned', 'reason' => 'Payment was abandoned'];
               
           default:
               return ['status' => 'pending', 'message' => 'Payment still processing'];
       }
   }
   ```

### Problem: Amount Mismatch

**Symptoms:**
- Expected ₦200, but charged ₦20,000
- Customer complaints about wrong amounts

**Solution:**
```php
class AmountValidator
{
    public static function validateAmount(float $amount, string $currency = 'NGN'): int
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be greater than zero');
        }
        
        // Convert to smallest currency unit (kobo for NGN)
        $koboAmount = (int) ($amount * 100);
        
        // Validate conversion
        if (abs(($koboAmount / 100) - $amount) > 0.001) {
            throw new InvalidArgumentException('Amount has too many decimal places');
        }
        
        // Minimum amount check (₦1.00 = 100 kobo)
        if ($koboAmount < 100) {
            throw new InvalidArgumentException('Amount too small (minimum ₦1.00)');
        }
        
        return $koboAmount;
    }
    
    public static function formatAmount(int $koboAmount): string
    {
        return '₦' . number_format($koboAmount / 100, 2);
    }
}

// Usage
try {
    $koboAmount = AmountValidator::validateAmount(200.00); // ₦200.00
    echo "Charging: " . AmountValidator::formatAmount($koboAmount) . "\n";
    
    $transaction = $paystack->transactions->initialize([
        'email' => 'customer@example.com',
        'amount' => $koboAmount, // 20000 kobo = ₦200.00
    ]);
} catch (InvalidArgumentException $e) {
    echo "Amount validation failed: " . $e->getMessage() . "\n";
}
```

### Problem: Payment Not Completing

**Symptoms:**
- Payment redirects but never completes
- Webhook not received
- Transaction stuck in pending

**Debug Steps:**

1. **Check Callback URL:**
   ```php
   function validateCallbackUrl(string $url): bool
   {
       // Ensure URL is accessible
       $headers = @get_headers($url);
       if (!$headers || strpos($headers[0], '200') === false) {
           echo "Warning: Callback URL not accessible: {$url}\n";
           return false;
       }
       
       // Ensure HTTPS for production
       if (strpos($url, 'https://') !== 0 && $_ENV['APP_ENV'] === 'production') {
           echo "Warning: Use HTTPS for callback URL in production\n";
           return false;
       }
       
       return true;
   }
   
   $callbackUrl = 'https://yoursite.com/payment/callback';
   if (!validateCallbackUrl($callbackUrl)) {
       echo "Fix callback URL issues before proceeding\n";
   }
   ```

2. **Test Webhook Endpoint:**
   ```php
   // webhook-test.php
   echo "Webhook endpoint is accessible\n";
   echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
   echo "Headers:\n";
   foreach (getallheaders() as $name => $value) {
       echo "  {$name}: {$value}\n";
   }
   echo "Body: " . file_get_contents('php://input') . "\n";
   ```

## Webhook Problems

### Problem: Webhook Signature Verification Fails

**Error Message:**
```
Invalid webhook signature
```

**Solutions:**

1. **Debug Signature Calculation:**
   ```php
   function debugWebhookSignature(): void
   {
       $payload = file_get_contents('php://input');
       $receivedSignature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
       $secretKey = 'sk_test_your_secret_key_here';
       
       echo "Received signature: {$receivedSignature}\n";
       echo "Payload length: " . strlen($payload) . "\n";
       echo "Payload hash: " . md5($payload) . "\n";
       
       $computedSignature = hash_hmac('sha512', $payload, $secretKey);
       echo "Computed signature: {$computedSignature}\n";
       
       echo "Signatures match: " . (hash_equals($receivedSignature, $computedSignature) ? 'YES' : 'NO') . "\n";
   }
   
   // Call this in your webhook endpoint for debugging
   debugWebhookSignature();
   ```

2. **Check Headers:**
   ```php
   function validateWebhookHeaders(): array
   {
       $requiredHeaders = ['HTTP_X_PAYSTACK_SIGNATURE'];
       $missing = [];
       
       foreach ($requiredHeaders as $header) {
           if (!isset($_SERVER[$header])) {
               $missing[] = $header;
           }
       }
       
       if (!empty($missing)) {
           throw new Exception('Missing webhook headers: ' . implode(', ', $missing));
       }
       
       return [
           'signature' => $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'],
           'timestamp' => $_SERVER['HTTP_X_PAYSTACK_TIMESTAMP'] ?? null,
       ];
   }
   ```

### Problem: Duplicate Webhook Events

**Symptoms:**
- Same event processed multiple times
- Database constraint violations
- Duplicate emails sent

**Solution:**
```php
class WebhookDeduplicator
{
    private $processedEvents = [];
    private string $cacheFile;
    
    public function __construct(string $cacheFile = 'webhook_events.json')
    {
        $this->cacheFile = $cacheFile;
        $this->loadProcessedEvents();
    }
    
    public function isEventProcessed(array $event): bool
    {
        $eventId = $this->generateEventId($event);
        return in_array($eventId, $this->processedEvents);
    }
    
    public function markEventProcessed(array $event): void
    {
        $eventId = $this->generateEventId($event);
        
        if (!in_array($eventId, $this->processedEvents)) {
            $this->processedEvents[] = $eventId;
            $this->saveProcessedEvents();
        }
    }
    
    private function generateEventId(array $event): string
    {
        // Create unique ID from event data
        $data = [
            'event' => $event['event'],
            'reference' => $event['data']['reference'] ?? '',
            'id' => $event['data']['id'] ?? '',
        ];
        
        return md5(json_encode($data));
    }
    
    private function loadProcessedEvents(): void
    {
        if (file_exists($this->cacheFile)) {
            $this->processedEvents = json_decode(file_get_contents($this->cacheFile), true) ?: [];
        }
    }
    
    private function saveProcessedEvents(): void
    {
        // Keep only last 1000 events
        if (count($this->processedEvents) > 1000) {
            $this->processedEvents = array_slice($this->processedEvents, -1000);
        }
        
        file_put_contents($this->cacheFile, json_encode($this->processedEvents));
    }
}

// Usage in webhook handler
$deduplicator = new WebhookDeduplicator();
$event = json_decode(file_get_contents('php://input'), true);

if ($deduplicator->isEventProcessed($event)) {
    echo "Event already processed";
    exit;
}

// Process event...
processEvent($event);

// Mark as processed
$deduplicator->markEventProcessed($event);
```

## Performance Issues

### Problem: Slow API Responses

**Symptoms:**
- Requests taking longer than expected
- Timeouts in high-traffic scenarios

**Solutions:**

1. **Enable HTTP/2 and Keep-Alive:**
   ```php
   $curlClient = new CurlClient(null, null, [
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
       CURLOPT_TCP_KEEPALIVE => 1,
       CURLOPT_TCP_KEEPIDLE => 60,
       CURLOPT_TCP_KEEPINTVL => 30,
       CURLOPT_MAXCONNECTS => 10,
   ]);
   ```

2. **Implement Response Caching:**
   ```php
   class CachedPaystackClient
   {
       private PaystackClient $client;
       private array $cache = [];
       private int $ttl = 300; // 5 minutes
       
       public function getCachedBanks(): array
       {
           $cacheKey = 'banks';
           
           if (isset($this->cache[$cacheKey]) && 
               $this->cache[$cacheKey]['expires'] > time()) {
               return $this->cache[$cacheKey]['data'];
           }
           
           $banks = $this->client->miscellaneous->listBanks(['country' => 'nigeria']);
           
           $this->cache[$cacheKey] = [
               'data' => $banks,
               'expires' => time() + $this->ttl
           ];
           
           return $banks;
       }
   }
   ```

3. **Use Async Requests for Bulk Operations:**
   ```php
   use GuzzleHttp\Promise;
   use GuzzleHttp\Client as GuzzleClient;
   
   function verifyTransactionsBatch(array $references): array
   {
       $client = new GuzzleClient();
       $promises = [];
       
       foreach ($references as $reference) {
           $promises[$reference] = $client->getAsync(
               "https://api.paystack.co/transaction/verify/{$reference}",
               [
                   'headers' => [
                       'Authorization' => 'Bearer ' . $secretKey,
                       'Content-Type' => 'application/json',
                   ]
               ]
           );
       }
       
       $responses = Promise\settle($promises)->wait();
       
       $results = [];
       foreach ($responses as $reference => $response) {
           if ($response['state'] === 'fulfilled') {
               $results[$reference] = json_decode($response['value']->getBody(), true);
           } else {
               $results[$reference] = ['error' => $response['reason']->getMessage()];
           }
       }
       
       return $results;
   }
   ```

## Testing and Development

### Problem: Unable to Test Payments

**Solutions:**

1. **Use Test Card Numbers:**
   ```php
   class TestCards
   {
       public const SUCCESSFUL_PAYMENT = '4084084084084081';
       public const INSUFFICIENT_FUNDS = '4084084084084107';
       public const INVALID_PIN = '4084084084084099';
       public const TIMEOUT = '4084084084084016';
       
       public static function getTestScenario(string $scenario): array
       {
           $cards = [
               'success' => self::SUCCESSFUL_PAYMENT,
               'insufficient_funds' => self::INSUFFICIENT_FUNDS,
               'invalid_pin' => self::INVALID_PIN,
               'timeout' => self::TIMEOUT,
           ];
           
           if (!isset($cards[$scenario])) {
               throw new InvalidArgumentException("Unknown test scenario: {$scenario}");
           }
           
           return [
               'card_number' => $cards[$scenario],
               'expiry_month' => '12',
               'expiry_year' => '25',
               'cvv' => '123'
           ];
       }
   }
   ```

2. **Mock Paystack Responses:**
   ```php
   use Http\Mock\Client as MockClient;
   use GuzzleHttp\Psr7\Response;
   
   function createMockPaystackClient(): PaystackClient
   {
       $mockClient = new MockClient();
       
       // Mock successful transaction initialization
       $mockClient->addResponse(new Response(200, [], json_encode([
           'status' => true,
           'message' => 'Authorization URL created',
           'data' => [
               'authorization_url' => 'https://checkout.paystack.com/test123',
               'access_code' => 'test123',
               'reference' => 'test_ref_123'
           ]
       ])));
       
       $clientBuilder = new ClientBuilder($mockClient);
       return new PaystackClient([
           'secretKey' => 'sk_test_mock',
           'clientBuilder' => $clientBuilder
       ]);
   }
   ```

## Common Error Messages

### "Customer with email already exists"

**Solution:**
```php
function getOrCreateCustomer(string $email, array $customerData = []): array
{
    try {
        // Try to fetch existing customer
        $customer = $paystack->customers->fetch($email);
        if ($customer['status']) {
            return $customer;
        }
    } catch (Exception $e) {
        // Customer doesn't exist, create new one
    }
    
    return $paystack->customers->create(array_merge([
        'email' => $email
    ], $customerData));
}
```

### "Amount should be a valid number"

**Solution:**
```php
function sanitizeAmount($amount): int
{
    // Remove currency symbols and formatting
    $amount = preg_replace('/[^0-9.]/', '', $amount);
    
    // Convert to float
    $amount = (float) $amount;
    
    // Convert to kobo (smallest unit)
    return (int) ($amount * 100);
}

// Usage
$amount = sanitizeAmount('₦200.50'); // Returns 20050
```

### "Invalid authorization code"

**Solution:**
```php
function validateAuthorizationCode(string $authCode): bool
{
    return preg_match('/^AUTH_[a-zA-Z0-9]+$/', $authCode) === 1;
}

if (!validateAuthorizationCode($authCode)) {
    throw new InvalidArgumentException('Invalid authorization code format');
}
```

## Debug Tools and Techniques

### Enable Debug Logging

```php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class PaystackDebugger
{
    private LoggerInterface $logger;
    
    public function __construct()
    {
        $this->logger = new Logger('paystack');
        $this->logger->pushHandler(new StreamHandler('paystack_debug.log', Logger::DEBUG));
    }
    
    public function logRequest(string $method, string $endpoint, array $data = []): void
    {
        $this->logger->info('Paystack API Request', [
            'method' => $method,
            'endpoint' => $endpoint,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function logResponse(array $response): void
    {
        $this->logger->info('Paystack API Response', [
            'status' => $response['status'] ?? false,
            'message' => $response['message'] ?? '',
            'data_keys' => isset($response['data']) ? array_keys($response['data']) : [],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function logError(\Exception $e): void
    {
        $this->logger->error('Paystack Error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}

// Usage
$debugger = new PaystackDebugger();

try {
    $debugger->logRequest('POST', '/transaction/initialize', $paymentData);
    
    $response = $paystack->transactions->initialize($paymentData);
    
    $debugger->logResponse($response);
} catch (Exception $e) {
    $debugger->logError($e);
    throw $e;
}
```

### Network Debugging

```php
function debugNetworkIssues(): void
{
    // Test connectivity to Paystack
    $paystackApi = 'https://api.paystack.co';
    
    echo "Testing connectivity to {$paystackApi}...\n";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET'
        ]
    ]);
    
    $start = microtime(true);
    $response = @file_get_contents($paystackApi, false, $context);
    $duration = microtime(true) - $start;
    
    if ($response === false) {
        echo "❌ Cannot reach Paystack API\n";
        echo "Check your internet connection and firewall settings\n";
    } else {
        echo "✅ Successfully connected to Paystack API\n";
        echo "Response time: " . number_format($duration * 1000, 2) . "ms\n";
    }
    
    // Test DNS resolution
    $ip = gethostbyname('api.paystack.co');
    echo "Paystack API IP: {$ip}\n";
    
    // Test cURL capabilities
    if (!function_exists('curl_init')) {
        echo "❌ cURL extension not installed\n";
    } else {
        echo "✅ cURL extension available\n";
        $version = curl_version();
        echo "cURL version: {$version['version']}\n";
        echo "SSL version: {$version['ssl_version']}\n";
    }
    
    // Test OpenSSL
    if (!extension_loaded('openssl')) {
        echo "❌ OpenSSL extension not loaded\n";
    } else {
        echo "✅ OpenSSL extension loaded\n";
    }
}

// Run diagnostics
debugNetworkIssues();
```

### Getting Help

If you're still experiencing issues:

1. **Check the logs:** Enable debug logging and review the output
2. **Test with minimal code:** Create a simple test script to isolate the issue
3. **Verify your environment:** Ensure all dependencies are properly installed
4. **Check Paystack status:** Visit [Paystack Status Page](https://status.paystack.com/)
5. **Contact support:**
   - GitHub Issues: [https://github.com/starfolksoftware/paystack-php/issues](https://github.com/starfolksoftware/paystack-php/issues)
   - Email: [contact@starfolksoftware.com](mailto:contact@starfolksoftware.com)
   - Paystack Support: [hello@paystack.com](mailto:hello@paystack.com)

### Creating Bug Reports

When reporting issues, include:

```php
// Debug information script
function generateDebugInfo(): array
{
    return [
        'php_version' => PHP_VERSION,
        'operating_system' => PHP_OS,
        'paystack_sdk_version' => 'Check composer.lock',
        'http_client' => class_exists('GuzzleHttp\Client') ? 'Guzzle' : 'Other',
        'curl_version' => curl_version()['version'] ?? 'Not available',
        'openssl_version' => OPENSSL_VERSION_TEXT ?? 'Not available',
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'error_reporting' => error_reporting(),
        'date_timezone' => date_default_timezone_get(),
    ];
}

echo "Debug Information:\n";
echo json_encode(generateDebugInfo(), JSON_PRETTY_PRINT);
```

Include this information along with:
- Steps to reproduce the issue
- Expected vs actual behavior
- Any error messages or logs
- Sample code that demonstrates the problem