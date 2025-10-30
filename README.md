# Paystack PHP SDK

[![Latest Stable Version](http://poser.pugx.org/starfolksoftware/paystack-php/v)](https://packagist.org/packages/starfolksoftware/paystack-php) [![Total Downloads](http://poser.pugx.org/starfolksoftware/paystack-php/downloads)](https://packagist.org/packages/starfolksoftware/paystack-php) [![License](http://poser.pugx.org/starfolksoftware/paystack-php/license)](https://packagist.org/packages/starfolksoftware/paystack-php) [![PHP Version Require](http://poser.pugx.org/starfolksoftware/paystack-php/require/php)](https://packagist.org/packages/starfolksoftware/paystack-php)

A modern, developer-friendly PHP SDK for the [Paystack API](https://paystack.com/docs/api/). This library provides convenient access to Paystack's payment infrastructure from applications written in PHP. It includes a comprehensive set of classes for all API resources with full type safety and automatic parameter validation.

## Features

- ✅ **Complete API Coverage** - All Paystack API endpoints supported
- ✅ **Type Safety** - Full PHP 8.2+ type declarations
- ✅ **Parameter Validation** - Automatic validation of API parameters
- ✅ **PSR-18 HTTP Client** - Compatible with any PSR-18 HTTP client
- ✅ **Comprehensive Examples** - Detailed usage examples for all features
- ✅ **Exception Handling** - Detailed error responses and exception handling
- ✅ **Modern PHP** - Built for PHP 8.2+ with modern coding standards

## Quick Links

- [Getting Started Guide](docs/getting-started.md)
- [API Reference](docs/api-reference.md)
- [Examples](examples/)
- [Advanced Usage](docs/advanced-usage.md)
- [Troubleshooting](docs/troubleshooting.md)

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Authentication](#authentication)
- [Quick Start](#quick-start)
- [Available Resources](#available-resources)
- [Usage Examples](#usage-examples)
  - [Transactions](#transactions)
  - [Customers](#customers)
  - [Payment Requests](#payment-requests)
  - [Subscriptions](#subscriptions)
- [Error Handling](#error-handling)
- [Testing](#testing)
- [Contributing](#contributing)
- [Support](#support)

## Requirements

- **PHP 8.2 or higher**
- **PSR-18 HTTP Client** (any implementation)
- **Composer** for dependency management

## Installation

### Using Composer

Install the SDK using Composer:

```bash
composer require starfolksoftware/paystack-php
```

### Install HTTP Client

This package requires a PSR-18 HTTP client. If you don't have one installed, we recommend Guzzle:

```bash
composer require php-http/guzzle7-adapter
```

Alternatively, you can use any PSR-18 compatible client:

```bash
# Symfony HTTP Client
composer require symfony/http-client

# cURL client
composer require php-http/curl-client

# Mock client (for testing)
composer require php-http/mock-client
```

### Autoloading

Include Composer's autoloader in your project:

```php
<?php
require_once 'vendor/autoload.php';
```

## Authentication

Get your API keys from your [Paystack Dashboard](https://dashboard.paystack.com/#/settings/developer):

- **Test Secret Key**: `sk_test_...` (for development)
- **Live Secret Key**: `sk_live_...` (for production)

**⚠️ Important**: Never expose your secret key in client-side code or public repositories.

## Quick Start

```php
<?php
require_once 'vendor/autoload.php';

use StarfolkSoftware\Paystack\Client as PaystackClient;

// Initialize the client
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
]);

// List all transactions
$transactions = $paystack->transactions->all([
    'perPage' => 50,
    'page' => 1
]);

echo "Total transactions: " . $transactions['meta']['total'] . "\n";

// Create a customer
$customer = $paystack->customers->create([
    'email' => 'customer@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+2348123456789'
]);

echo "Customer created: " . $customer['data']['customer_code'] . "\n";
```

### Payment Requests

```php
// Create a payment request
$paymentRequest = $paystack->paymentRequests->create([
    'description' => 'Website Development Invoice',
    'line_items' => [
        ['name' => 'Frontend Development', 'amount' => 50000, 'quantity' => 1],
        ['name' => 'Backend Development', 'amount' => 75000, 'quantity' => 1],
        ['name' => 'UI/UX Design', 'amount' => 25000, 'quantity' => 1]
    ],
    'tax' => [
        ['name' => 'VAT', 'amount' => 11250] // 7.5% of total
    ],
    'customer' => 'customer@example.com',
    'due_date' => date('Y-m-d', strtotime('+30 days')),
    'send_notification' => true
]);

// List payment requests
$paymentRequests = $paystack->paymentRequests->all([
    'perPage' => 20,
    'page' => 1,
    'status' => 'pending'
]);

// Fetch specific payment request
$paymentRequest = $paystack->paymentRequests->fetch('PRQ_1weqqsn2wwzgft8');

// Update payment request
$updated = $paystack->paymentRequests->update('PRQ_1weqqsn2wwzgft8', [
    'description' => 'Updated Website Development Invoice',
    'due_date' => date('Y-m-d', strtotime('+45 days'))
]);

// Send reminder notification
$notification = $paystack->paymentRequests->sendNotification('PRQ_1weqqsn2wwzgft8');
```

### Subscriptions

```php
// Create a plan
$plan = $paystack->plans->create([
    'name' => 'Premium Monthly',
    'interval' => 'monthly',
    'amount' => 5000, // ₦50.00 per month
    'currency' => 'NGN',
    'description' => 'Premium subscription with all features'
]);

// Create subscription
$subscription = $paystack->subscriptions->create([
    'customer' => 'CUS_xwaj0txjryg393b',
    'plan' => $plan['data']['plan_code'],
    'authorization' => 'AUTH_authorization_code'
]);

// List subscriptions
$subscriptions = $paystack->subscriptions->all([
    'perPage' => 50,
    'plan' => $plan['data']['plan_code']
]);

// Disable subscription
$disabled = $paystack->subscriptions->disable('SUB_subscription_code', [
    'code' => 'SUB_subscription_code',
    'token' => 'subscription_email_token'
]);
```

## Error Handling

The SDK provides comprehensive error handling with detailed error messages:

```php
use StarfolkSoftware\Paystack\Client as PaystackClient;

try {
    $paystack = new PaystackClient([
        'secretKey' => 'sk_test_your_secret_key_here',
    ]);
    
    $transaction = $paystack->transactions->initialize([
        'email' => 'invalid-email', // This will cause an error
        'amount' => 20000
    ]);
    
} catch (\Psr\Http\Client\ClientExceptionInterface $e) {
    // Network or HTTP-related errors
    echo "HTTP Error: " . $e->getMessage() . "\n";
    
} catch (\Exception $e) {
    // General errors
    echo "Error: " . $e->getMessage() . "\n";
}

// Handle API response errors
$response = $paystack->transactions->verify('invalid_reference');
if (!$response['status']) {
    echo "API Error: " . $response['message'] . "\n";
    // Handle the error appropriately
}
```

## Configuration Options

You can customize the client behavior with various configuration options:

```php
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
    'apiVersion' => 'v1', // API version (default: v1)
    'baseUri' => 'https://api.paystack.co', // Custom base URI
]);

// Access the underlying HTTP client if needed
$httpClient = $paystack->getHttpClient();
```

## Webhook Handling

```php
// In your webhook endpoint
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

// Verify webhook signature
$secretKey = 'sk_test_your_secret_key_here';
$computedSignature = hash_hmac('sha512', $payload, $secretKey);

if (hash_equals($signature, $computedSignature)) {
    $event = json_decode($payload, true);
    
    switch ($event['event']) {
        case 'charge.success':
            // Handle successful payment
            $reference = $event['data']['reference'];
            echo "Payment successful: {$reference}\n";
            break;
            
        case 'subscription.create':
            // Handle new subscription
            $subscriptionCode = $event['data']['subscription_code'];
            echo "New subscription: {$subscriptionCode}\n";
            break;
            
        default:
            echo "Unhandled event: {$event['event']}\n";
    }
} else {
    echo "Invalid signature\n";
    http_response_code(400);
}
```


## Testing

Run the test suite using PHPUnit:

```bash
# Install development dependencies
composer install --dev

# Run all tests
composer test

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage

# Run specific test class
./vendor/bin/phpunit tests/TransactionTest.php

# Run tests with verbose output
./vendor/bin/phpunit --testdox
```

### Testing Your Integration

Use Paystack's test mode to test your integration:

```php
// Use test secret key
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_test_secret_key_here',
]);

// Test card numbers for different scenarios
$testCards = [
    'success' => '4084084084084081',
    'insufficient_funds' => '4084084084084107',
    'invalid_pin' => '4084084084084099'
];
```

## Contributing

We welcome contributions! Please see our [Contributing Guide](.github/CONTRIBUTING.md) for details.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/starfolksoftware/paystack-php.git
cd paystack-php

# Install dependencies
composer install

# Run tests
composer test
```

### Coding Standards

This project follows PSR-12 coding standards. Please ensure your code adheres to these standards:

```bash
# Check code style (if you have PHP_CodeSniffer installed)
phpcs src/ --standard=PSR12

# Fix code style automatically
phpcbf src/ --standard=PSR12
```

## Support

- **Documentation**: [API Reference](docs/api-reference.md) | [Getting Started](docs/getting-started.md)
- **Examples**: Check the [examples](examples/) directory
- **Issues**: [GitHub Issues](https://github.com/starfolksoftware/paystack-php/issues)
- **Email**: [contact@starfolksoftware.com](mailto:contact@starfolksoftware.com)
- **Paystack Documentation**: [Official API Docs](https://paystack.com/docs/api/)

## Changelog

Please see [CHANGELOG](https://github.com/starfolksoftware/paystack-php/compare/v0.0.2...v0.6.1) for more information on what has changed recently.

## Security Vulnerabilities

If you discover a security vulnerability, please send an email to [contact@starfolksoftware.com](mailto:contact@starfolksoftware.com). All security vulnerabilities will be promptly addressed.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

**Made with ❤️ by [Starfolk Software](https://starfolksoftware.com)**
