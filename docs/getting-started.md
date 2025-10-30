# Getting Started with Paystack PHP SDK

This guide will help you integrate Paystack payments into your PHP application using the official Paystack PHP SDK.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Authentication](#authentication)
- [Your First Payment](#your-first-payment)
- [Common Use Cases](#common-use-cases)
- [Best Practices](#best-practices)
- [Next Steps](#next-steps)

## Prerequisites

Before you begin, ensure you have:

1. **PHP 8.1 or higher** installed on your system
2. **Composer** for dependency management
3. A **Paystack account** ([sign up here](https://dashboard.paystack.com/signup))
4. Your **API keys** from the Paystack dashboard

### Getting Your API Keys

1. Log into your [Paystack Dashboard](https://dashboard.paystack.com)
2. Navigate to **Settings** → **API Keys & Webhooks**
3. Copy your **Test Secret Key** (starts with `sk_test_`)
4. For production, you'll use your **Live Secret Key** (starts with `sk_live_`)

⚠️ **Security Note**: Never expose your secret keys in client-side code or public repositories.

## Installation

### Step 1: Install the SDK

```bash
# Install the Paystack PHP SDK
composer require starfolksoftware/paystack-php

# Install an HTTP client (choose one)
composer require php-http/guzzle7-adapter
# OR
composer require symfony/http-client
# OR  
composer require php-http/curl-client
```

### Step 2: Verify Installation

Create a simple test file to verify the installation:

```php
<?php
// test-installation.php
require_once 'vendor/autoload.php';

use StarfolkSoftware\Paystack\Client as PaystackClient;

echo "Paystack PHP SDK installed successfully!\n";
echo "SDK Version: " . PaystackClient::class . "\n";
```

Run the test:

```bash
php test-installation.php
```

## Authentication

### Basic Setup

```php
<?php
require_once 'vendor/autoload.php';

use StarfolkSoftware\Paystack\Client as PaystackClient;

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here', // Replace with your actual test key
]);

// Test the connection
try {
    $response = $paystack->miscellaneous->listBanks(['country' => 'nigeria']);
    echo "Connected to Paystack successfully!\n";
    echo "Found " . count($response['data']) . " banks\n";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
```

### Environment Configuration

For better security, store your API keys in environment variables:

```php
// .env file
PAYSTACK_SECRET_KEY=sk_test_your_secret_key_here
PAYSTACK_PUBLIC_KEY=pk_test_your_public_key_here
```

```php
<?php
// config.php
$paystackConfig = [
    'secretKey' => $_ENV['PAYSTACK_SECRET_KEY'] ?? getenv('PAYSTACK_SECRET_KEY'),
    'publicKey' => $_ENV['PAYSTACK_PUBLIC_KEY'] ?? getenv('PAYSTACK_PUBLIC_KEY'),
];

$paystack = new PaystackClient([
    'secretKey' => $paystackConfig['secretKey'],
]);
```

## Your First Payment

Let's create a complete payment flow from initialization to verification.

### Step 1: Initialize a Payment

```php
<?php
// payment-init.php
require_once 'vendor/autoload.php';

use StarfolkSoftware\Paystack\Client as PaystackClient;

$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
]);

try {
    // Initialize a payment
    $transaction = $paystack->transactions->initialize([
        'email' => 'customer@example.com',
        'amount' => 20000, // Amount in kobo (₦200.00)
        'currency' => 'NGN',
        'callback_url' => 'https://yourwebsite.com/payment/callback',
        'metadata' => [
            'order_id' => 'ORD_12345',
            'custom_fields' => [
                [
                    'display_name' => 'Order ID',
                    'variable_name' => 'order_id',
                    'value' => 'ORD_12345'
                ]
            ]
        ]
    ]);

    if ($transaction['status']) {
        echo "Payment initialized successfully!\n";
        echo "Reference: " . $transaction['data']['reference'] . "\n";
        echo "Authorization URL: " . $transaction['data']['authorization_url'] . "\n";
        
        // Store the reference for later verification
        file_put_contents('payment_reference.txt', $transaction['data']['reference']);
        
        // In a web application, you would redirect the user to the authorization_url
        // header('Location: ' . $transaction['data']['authorization_url']);
        
    } else {
        echo "Payment initialization failed: " . $transaction['message'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Step 2: Handle Payment Callback

Create a callback handler to process payment responses:

```php
<?php
// payment-callback.php
require_once 'vendor/autoload.php';

use StarfolkSoftware\Paystack\Client as PaystackClient;

$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
]);

// Get the transaction reference from URL parameter
$reference = $_GET['reference'] ?? null;

if (!$reference) {
    die('No payment reference provided');
}

try {
    // Verify the transaction
    $verification = $paystack->transactions->verify($reference);
    
    if ($verification['data']['status'] === 'success') {
        $amount = $verification['data']['amount'] / 100; // Convert from kobo to naira
        $customerEmail = $verification['data']['customer']['email'];
        $paidAt = $verification['data']['paid_at'];
        
        echo "Payment Successful!\n";
        echo "Amount: ₦{$amount}\n";
        echo "Customer: {$customerEmail}\n";
        echo "Paid at: {$paidAt}\n";
        
        // Here you would typically:
        // 1. Update your database
        // 2. Send confirmation email
        // 3. Fulfill the order
        // 4. Redirect to success page
        
    } else {
        echo "Payment verification failed or payment was not successful\n";
        echo "Status: " . $verification['data']['status'] . "\n";
        
        // Handle failed payment
        // Redirect to failure page
    }

} catch (Exception $e) {
    echo "Verification error: " . $e->getMessage() . "\n";
}
```

### Step 3: Webhook Handler (Recommended)

For production applications, implement webhook handling for real-time payment notifications:

```php
<?php
// webhook-handler.php
require_once 'vendor/autoload.php';

// Get the payload
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

// Verify the signature
$secretKey = 'sk_test_your_secret_key_here';
$computedSignature = hash_hmac('sha512', $payload, $secretKey);

if (!hash_equals($signature, $computedSignature)) {
    http_response_code(400);
    die('Invalid signature');
}

// Parse the event
$event = json_decode($payload, true);

switch ($event['event']) {
    case 'charge.success':
        handleSuccessfulPayment($event['data']);
        break;
        
    case 'charge.failed':
        handleFailedPayment($event['data']);
        break;
        
    case 'subscription.create':
        handleNewSubscription($event['data']);
        break;
        
    default:
        error_log("Unhandled webhook event: " . $event['event']);
}

function handleSuccessfulPayment($data) {
    $reference = $data['reference'];
    $amount = $data['amount'] / 100;
    $customerEmail = $data['customer']['email'];
    
    // Update your database
    // Send confirmation email
    // Fulfill order
    
    error_log("Payment successful: {$reference} - ₦{$amount} from {$customerEmail}");
}

function handleFailedPayment($data) {
    $reference = $data['reference'];
    error_log("Payment failed: {$reference}");
}

function handleNewSubscription($data) {
    $subscriptionCode = $data['subscription_code'];
    error_log("New subscription: {$subscriptionCode}");
}

// Always respond with 200 OK
http_response_code(200);
echo "OK";
```

## Common Use Cases

### 1. Customer Management

```php
// Create a customer
$customer = $paystack->customers->create([
    'email' => 'john.doe@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+2348123456789',
    'metadata' => [
        'custom_fields' => [
            ['display_name' => 'Loyalty Number', 'variable_name' => 'loyalty_number', 'value' => 'LOY123456']
        ]
    ]
]);

$customerCode = $customer['data']['customer_code'];

// Update customer information
$updatedCustomer = $paystack->customers->update($customerCode, [
    'first_name' => 'Jonathan',
    'metadata' => [
        'vip_status' => 'gold',
        'last_purchase_date' => date('Y-m-d')
    ]
]);

// Fetch customer details
$customerDetails = $paystack->customers->fetch($customerCode);
```

### 2. Subscription Billing

```php
// Step 1: Create a plan
$plan = $paystack->plans->create([
    'name' => 'Premium Monthly Subscription',
    'interval' => 'monthly',
    'amount' => 5000, // ₦50.00 per month
    'currency' => 'NGN',
    'description' => 'Access to premium features',
    'send_invoices' => true,
    'send_sms' => false
]);

$planCode = $plan['data']['plan_code'];

// Step 2: Create subscription (after customer has completed initial payment)
$subscription = $paystack->subscriptions->create([
    'customer' => $customerCode,
    'plan' => $planCode,
    'authorization' => 'AUTH_authorization_code', // From previous successful payment
]);

// Step 3: Manage subscription
$subscriptions = $paystack->subscriptions->all([
    'customer' => $customerCode
]);

// Disable subscription
$paystack->subscriptions->disable($subscription['data']['subscription_code'], [
    'code' => $subscription['data']['subscription_code'],
    'token' => 'subscription_email_token'
]);
```

### 3. Payment Requests/Invoicing

```php
// Create an invoice
$invoice = $paystack->paymentRequests->create([
    'description' => 'Web Development Services - Project Alpha',
    'line_items' => [
        [
            'name' => 'Frontend Development',
            'amount' => 150000, // ₦1,500
            'quantity' => 1
        ],
        [
            'name' => 'Backend API Development',
            'amount' => 200000, // ₦2,000
            'quantity' => 1
        ],
        [
            'name' => 'Database Design',
            'amount' => 75000, // ₦750
            'quantity' => 1
        ]
    ],
    'tax' => [
        [
            'name' => 'VAT (7.5%)',
            'amount' => 31875 // 7.5% of 425000
        ]
    ],
    'customer' => 'client@company.com',
    'due_date' => date('Y-m-d', strtotime('+30 days')),
    'send_notification' => true,
    'invoice_number' => 1001,
    'currency' => 'NGN'
]);

// Send payment reminder
if (isset($invoice['data']['request_code'])) {
    $paystack->paymentRequests->sendNotification($invoice['data']['request_code']);
}
```

### 4. Transfer Money

```php
// Step 1: Create transfer recipient
$recipient = $paystack->transferRecipients->create([
    'type' => 'nuban',
    'name' => 'John Doe',
    'account_number' => '0123456789',
    'bank_code' => '044', // Access Bank
    'currency' => 'NGN'
]);

// Step 2: Initiate transfer
$transfer = $paystack->transfers->initiate([
    'source' => 'balance',
    'amount' => 50000, // ₦500.00
    'recipient' => $recipient['data']['recipient_code'],
    'reason' => 'Payment for freelance work',
    'currency' => 'NGN'
]);

// Step 3: Check transfer status
$transferStatus = $paystack->transfers->fetch($transfer['data']['transfer_code']);
```

## Best Practices

### 1. Error Handling

Always implement comprehensive error handling:

```php
function processPayment($paymentData) {
    try {
        $paystack = new PaystackClient([
            'secretKey' => $_ENV['PAYSTACK_SECRET_KEY'],
        ]);
        
        $transaction = $paystack->transactions->initialize($paymentData);
        
        if (!$transaction['status']) {
            throw new Exception('Payment initialization failed: ' . $transaction['message']);
        }
        
        return [
            'success' => true,
            'data' => $transaction['data']
        ];
        
    } catch (\Psr\Http\Client\NetworkExceptionInterface $e) {
        // Network errors
        error_log('Network error: ' . $e->getMessage());
        return ['success' => false, 'error' => 'Network connection failed'];
        
    } catch (\Psr\Http\Client\RequestExceptionInterface $e) {
        // Request errors
        error_log('Request error: ' . $e->getMessage());
        return ['success' => false, 'error' => 'Invalid request'];
        
    } catch (Exception $e) {
        // General errors
        error_log('Payment error: ' . $e->getMessage());
        return ['success' => false, 'error' => 'Payment processing failed'];
    }
}
```

### 2. Security

- **Never expose secret keys** in client-side code
- **Always verify webhooks** using signature validation
- **Use HTTPS** for all payment-related endpoints
- **Validate all input** before sending to Paystack
- **Store sensitive data securely** using encryption

```php
// Good: Store keys in environment variables
$paystack = new PaystackClient([
    'secretKey' => $_ENV['PAYSTACK_SECRET_KEY'],
]);

// Bad: Hardcoded keys in code
// $paystack = new PaystackClient([
//     'secretKey' => 'sk_test_actual_key_here', // DON'T DO THIS!
// ]);
```

### 3. Testing

Use Paystack's test mode for development:

```php
// Test card numbers for different scenarios
$testCards = [
    'successful_payment' => '4084084084084081',
    'insufficient_funds' => '4084084084084107',
    'invalid_pin' => '4084084084084099',
    'timeout' => '4084084084084016'
];

// Use test keys during development
$isProduction = $_ENV['APP_ENV'] === 'production';
$secretKey = $isProduction 
    ? $_ENV['PAYSTACK_LIVE_SECRET_KEY'] 
    : $_ENV['PAYSTACK_TEST_SECRET_KEY'];
```

### 4. Logging and Monitoring

Implement proper logging for payment activities:

```php
function logPaymentActivity($type, $reference, $data = []) {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => $type,
        'reference' => $reference,
        'data' => $data
    ];
    
    error_log('PAYSTACK: ' . json_encode($logEntry));
    
    // You might also want to log to a database or external service
}

// Usage
logPaymentActivity('payment_initialized', $transaction['data']['reference'], [
    'amount' => $paymentData['amount'],
    'email' => $paymentData['email']
]);
```

## Next Steps

Now that you have a basic understanding of the Paystack PHP SDK:

1. **Read the [API Reference](api-reference.md)** for detailed documentation of all available methods
2. **Check out [Advanced Usage](advanced-usage.md)** for complex scenarios and optimization techniques
3. **Review the [Examples](../examples/)** directory for more code samples
4. **Visit the [Troubleshooting Guide](troubleshooting.md)** if you encounter issues
5. **Explore Paystack's [official documentation](https://paystack.com/docs/api/)** for additional insights

### Useful Resources

- [Paystack Dashboard](https://dashboard.paystack.com/)
- [Paystack API Documentation](https://paystack.com/docs/api/)
- [Test Payment Cards](https://paystack.com/docs/payments/test-payments/)
- [Webhook Events Reference](https://paystack.com/docs/payments/webhooks/)
- [SDK GitHub Repository](https://github.com/starfolksoftware/paystack-php)

### Community and Support

- **GitHub Issues**: [Report bugs or request features](https://github.com/starfolksoftware/paystack-php/issues)
- **Email Support**: [contact@starfolksoftware.com](mailto:contact@starfolksoftware.com)
- **Paystack Support**: [hello@paystack.com](mailto:hello@paystack.com)

Happy coding! 🚀