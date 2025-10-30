# Upgrade Guide: v1.x to v2.x

This guide will help you upgrade your application from Paystack PHP SDK v1.x to v2.x. Version 2.x introduces significant improvements in type safety, developer experience, and API design while maintaining backward compatibility wherever possible.

## Table of Contents

- [Overview](#overview)
- [Requirements](#requirements)
- [Breaking Changes](#breaking-changes)
- [New Features](#new-features)
- [Step-by-Step Migration](#step-by-step-migration)
- [Feature-by-Feature Guide](#feature-by-feature-guide)
- [Testing Your Migration](#testing-your-migration)
- [Need Help?](#need-help)

## Overview

### What's New in v2.x

- ✅ **Response DTOs**: Strongly-typed response objects with helper methods
- ✅ **Enhanced Type Safety**: Full PHP 8.2+ type declarations
- ✅ **Parameter Validation**: Automatic validation using dedicated Options classes
- ✅ **Improved IDE Support**: Better autocomplete and intellisense
- ✅ **Modern PHP**: Built for PHP 8.2+ with current best practices
- ✅ **Comprehensive Examples**: Updated documentation and examples

### Migration Difficulty

- **Easy**: Most code requires minimal or no changes
- **Time Required**: 15 minutes - 2 hours depending on codebase size
- **Backward Compatibility**: Most v1.x code continues to work in v2.x

## Requirements

### Before Upgrading

**v1.x Requirements:**
- PHP 7.4 or higher

**v2.x Requirements:**
- PHP 8.2 or higher
- PSR-18 HTTP Client implementation
- Composer for dependency management

### Checking Your PHP Version

```bash
php -v
```

If you're running PHP < 8.2, you'll need to upgrade PHP first:

```bash
# macOS (using Homebrew)
brew install php@8.2

# Ubuntu/Debian
sudo apt-get update
sudo apt-get install php8.2

# Check installation
php -v
```

## Breaking Changes

### 1. Minimum PHP Version

**Impact**: HIGH  
**Action**: REQUIRED

```php
// ❌ v1.x: PHP 7.4+
// ✅ v2.x: PHP 8.2+
```

**Migration Steps:**
1. Update your PHP version to 8.2 or higher
2. Update `composer.json` PHP requirement
3. Test your application thoroughly

### 2. API Method Access Pattern

**Impact**: LOW  
**Action**: RECOMMENDED (but not required)

In v2.x, API methods should be accessed as method calls rather than properties for better type hinting.

```php
// ❌ v1.x: Magic property access
$customers = $paystack->customers->all([]);

// ✅ v2.x: Direct method calls (recommended)
$customers = $paystack->customers()->all([]);

// ℹ️  Note: Both work in v2.x for backward compatibility
```

**Migration Strategy:**
- **Option 1 (Recommended)**: Find and replace property access with method calls
- **Option 2**: Keep existing code (still works, but less IDE support)

**Find & Replace Pattern:**
```php
// Find:    $paystack->customers->
// Replace: $paystack->customers()->

// Find:    $paystack->transactions->
// Replace: $paystack->transactions()->

// Apply to all API endpoints
```

### 3. Response Structure Changes

**Impact**: LOW  
**Action**: OPTIONAL (new methods available)

Array-based responses still work, but v2.x introduces typed response objects.

```php
// ✅ v1.x style (still works in v2.x)
$response = $paystack->customers()->create([...]);
$email = $response['data']['email'];

// ⭐ v2.x style (new, recommended)
$response = $paystack->customers()->createTyped([...]);
$customer = $response->getData(); // CustomerData object
$email = $customer->email;
```

**Migration Strategy:**
- Existing code continues to work
- Use new `*Typed()` methods for new features
- Gradually migrate during refactoring

## New Features

### 1. Response DTOs (Data Transfer Objects)

v2.x introduces strongly-typed response objects with helper methods:

#### Customer Response DTOs

```php
use StarfolkSoftware\Paystack\Response\Customer\CustomerData;

$response = $paystack->customers()->createTyped([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
]);

if ($response->isSuccessful()) {
    $customer = $response->getData(); // CustomerData object
    
    // Direct property access with autocomplete
    echo $customer->email;
    echo $customer->customer_code;
    echo $customer->phone;
    
    // Helper methods
    echo $customer->getFullName(); // "John Doe"
    $hasAuth = $customer->hasAuthorizations();
    $hasSubs = $customer->hasSubscriptions();
    $isIdentified = $customer->isIdentified();
}

// Paginated lists
$response = $paystack->customers()->allTyped(['perPage' => 50]);
foreach ($response->getCustomers() as $customer) {
    echo $customer->getFullName() . "\n";
}

// Pagination info
$pagination = $response->getPagination();
echo "Page {$pagination->page} of {$pagination->pageCount}\n";
echo "Total: {$pagination->total}\n";
if ($pagination->hasNextPage()) {
    echo "More results available\n";
}
```

#### Transaction Response DTOs

```php
use StarfolkSoftware\Paystack\Response\Transaction\TransactionData;

// Initialize transaction
$response = $paystack->transactions()->initializeTyped([
    'email' => 'customer@example.com',
    'amount' => '50000',
]);

if ($response->isInitialized()) {
    echo $response->getAuthorizationUrl();
    echo $response->getAccessCode();
    echo $response->getReference();
}

// Verify transaction
$response = $paystack->transactions()->verifyTyped($reference);
$transaction = $response->getData();

// Status checking
if ($transaction->isSuccessful()) {
    echo "Payment successful!\n";
} elseif ($transaction->isPending()) {
    echo "Payment pending...\n";
} elseif ($transaction->isFailed()) {
    echo "Payment failed\n";
}

// Amount helpers
echo $transaction->getFormattedAmount(); // ₦500.00
echo $transaction->getAmountInMajorUnit(); // 500
echo $transaction->getTotalFees();

// Customer info
echo $transaction->getCustomerEmail();
echo $transaction->getCustomerName();
```

#### Payment Request Response DTOs

```php
use StarfolkSoftware\Paystack\Response\PaymentRequest\PaymentRequestData;

$response = $paystack->paymentRequests()->createTyped([
    'description' => 'Invoice #1234',
    'line_items' => [...],
    'customer' => 'customer@example.com',
    'due_date' => '2024-12-31',
]);

$paymentRequest = $response->getData();

// Status checking
if ($paymentRequest->isPending()) {
    echo "Awaiting payment\n";
} elseif ($paymentRequest->isPaid()) {
    echo "Fully paid!\n";
} elseif ($paymentRequest->isPartiallyPaid()) {
    echo "Partially paid\n";
}

// Amount calculations
echo $paymentRequest->getFormattedAmount();
echo $paymentRequest->getLineItemsTotal();
echo $paymentRequest->getTaxTotal();

// Due date helpers
if ($paymentRequest->hasDueDate()) {
    $dueDate = $paymentRequest->getDueDateAsDateTime();
    if ($paymentRequest->isOverdue()) {
        echo "Invoice overdue by " . 
             $dueDate->diff(new DateTime())->days . " days\n";
    }
}
```

### 2. Options Classes for Parameter Validation

v2.x uses dedicated Options classes for automatic parameter validation:

```php
use StarfolkSoftware\Paystack\Options\Customer\CreateOptions;
use StarfolkSoftware\Paystack\Options\Transaction\InitializeOptions;

// Customer creation with validation
$options = new CreateOptions([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+2348123456789',
]);

// Transaction initialization with validation
$options = new InitializeOptions([
    'email' => 'customer@example.com',
    'amount' => '50000',
    'currency' => 'NGN',
]);

// Invalid parameters will throw exceptions immediately
```

**Benefits:**
- Early error detection
- Clear parameter requirements
- Better IDE autocomplete for parameters
- Validation before API calls

### 3. Enhanced Error Handling

```php
use StarfolkSoftware\Paystack\Response\PaystackResponseException;

try {
    $response = $paystack->transactions()->initializeTyped([
        'email' => 'invalid-email',
        'amount' => '50000',
    ]);
} catch (PaystackResponseException $e) {
    // API responded with an error
    echo "API Error: " . $e->getMessage() . "\n";
    echo "Status Code: " . $e->getStatusCode() . "\n";
    
    if ($e->hasResponse()) {
        $response = $e->getResponse();
        // Handle error details
    }
} catch (\Psr\Http\Client\ClientExceptionInterface $e) {
    // Network or HTTP error
    echo "Network Error: " . $e->getMessage() . "\n";
}
```

## Step-by-Step Migration

### Step 1: Update Dependencies

Update your `composer.json`:

```json
{
    "require": {
        "starfolksoftware/paystack-php": "^2.0",
        "php": "^8.2"
    }
}
```

Run Composer update:

```bash
composer update starfolksoftware/paystack-php
```

### Step 2: Fix PHP Version Incompatibilities

If you encounter PHP 8.2 compatibility issues in your code:

1. **Replace deprecated features:**
   ```php
   // ❌ PHP 7.x: Dynamic properties
   class MyClass {
       // Implicit property declaration
   }
   
   // ✅ PHP 8.2: Explicit property declaration
   class MyClass {
       public string $myProperty;
   }
   ```

2. **Update type declarations:**
   ```php
   // ❌ Old: No type hints
   function processPayment($amount) { }
   
   // ✅ New: Type hints
   function processPayment(int $amount): void { }
   ```

### Step 3: Update API Access Patterns (Recommended)

Use find & replace in your IDE:

```php
// Replace all occurrences:
$paystack->customers->      →  $paystack->customers()->
$paystack->transactions->   →  $paystack->transactions()->
$paystack->plans->          →  $paystack->plans()->
$paystack->subscriptions->  →  $paystack->subscriptions()->
$paystack->paymentRequests-> →  $paystack->paymentRequests()->
$paystack->invoices->       →  $paystack->invoices()->
$paystack->transfers->      →  $paystack->transfers()->
// ... and so on for all API endpoints
```

### Step 4: Gradually Adopt New Features

You don't need to migrate everything at once. Start with new code:

```php
// Strategy 1: Keep existing code as-is
$oldResponse = $paystack->customers()->all([]);
// Works perfectly in v2.x

// Strategy 2: Use new typed methods for new features
$newResponse = $paystack->customers()->allTyped([]);
// Better type safety and IDE support

// Strategy 3: Migrate during refactoring
// When you're already touching old code, upgrade it to use typed methods
```

### Step 5: Update Tests

Update your test suite to handle v2.x:

```php
// Before
public function testCustomerCreation()
{
    $response = $this->paystack->customers->create([
        'email' => 'test@example.com',
    ]);
    
    $this->assertTrue($response['status']);
    $this->assertEquals('test@example.com', $response['data']['email']);
}

// After (both styles work)
public function testCustomerCreation()
{
    // Option 1: Array-based (still works)
    $response = $this->paystack->customers()->create([
        'email' => 'test@example.com',
    ]);
    $this->assertTrue($response['status']);
    
    // Option 2: Typed (recommended)
    $response = $this->paystack->customers()->createTyped([
        'email' => 'test@example.com',
    ]);
    $this->assertTrue($response->isSuccessful());
    $this->assertEquals('test@example.com', $response->getData()->email);
}
```

Run your tests:

```bash
composer test
```

### Step 6: Update Documentation and Comments

Update inline documentation to reflect v2.x patterns:

```php
/**
 * Create a new customer in Paystack
 * 
 * @param array $data Customer data
 * @return array Response from Paystack API (v1.x style)
 */
// ↓ Update to:

/**
 * Create a new customer in Paystack
 * 
 * @param array $data Customer data
 * @return PaystackResponse<CustomerData> Typed response object (v2.x)
 */
```

## Feature-by-Feature Guide

### Transactions

#### Initialization

```php
// v1.x
$response = $paystack->transactions->initialize([
    'email' => 'customer@example.com',
    'amount' => '50000',
]);
$url = $response['data']['authorization_url'];

// v2.x (array style still works)
$response = $paystack->transactions()->initialize([
    'email' => 'customer@example.com',
    'amount' => '50000',
]);
$url = $response['data']['authorization_url'];

// v2.x (new typed style)
$response = $paystack->transactions()->initializeTyped([
    'email' => 'customer@example.com',
    'amount' => '50000',
]);
$url = $response->getAuthorizationUrl();
```

#### Verification

```php
// v1.x
$response = $paystack->transactions->verify($reference);
if ($response['data']['status'] === 'success') {
    // Process payment
}

// v2.x (new typed style)
$response = $paystack->transactions()->verifyTyped($reference);
$transaction = $response->getData();
if ($transaction->isSuccessful()) {
    // Process payment
}
```

#### Listing Transactions

```php
// v1.x
$response = $paystack->transactions->all(['perPage' => 50]);
foreach ($response['data'] as $transaction) {
    echo $transaction['reference'] . "\n";
}

// v2.x (new typed style)
$response = $paystack->transactions()->allTyped(['perPage' => 50]);
foreach ($response->getData() as $transaction) {
    echo $transaction->reference . "\n";
    echo $transaction->getFormattedAmount() . "\n";
}
```

### Customers

#### Creating Customers

```php
// v1.x
$response = $paystack->customers->create([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
]);
$code = $response['data']['customer_code'];

// v2.x (new typed style)
$response = $paystack->customers()->createTyped([
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
]);
$customer = $response->getData();
$code = $customer->customer_code;
$fullName = $customer->getFullName(); // Helper method
```

#### Listing Customers

```php
// v1.x
$response = $paystack->customers->all(['perPage' => 50]);
$total = $response['meta']['total'];

// v2.x (new typed style)
$response = $paystack->customers()->allTyped(['perPage' => 50]);
$total = $response->getTotal();
$pagination = $response->getPagination();

foreach ($response->getCustomers() as $customer) {
    echo $customer->getFullName() . "\n";
}

if ($pagination->hasNextPage()) {
    echo "More results available\n";
}
```

### Payment Requests

#### Creating Payment Requests

```php
// v1.x
$response = $paystack->paymentRequests->create([
    'description' => 'Invoice for services',
    'line_items' => [...],
    'customer' => 'customer@example.com',
]);
$requestCode = $response['data']['request_code'];

// v2.x (new typed style)
$response = $paystack->paymentRequests()->createTyped([
    'description' => 'Invoice for services',
    'line_items' => [...],
    'customer' => 'customer@example.com',
]);
$paymentRequest = $response->getData();
$requestCode = $paymentRequest->request_code;

// Use helper methods
if ($paymentRequest->isPending()) {
    echo "Awaiting payment\n";
}
echo "Total: " . $paymentRequest->getFormattedAmount() . "\n";
```

### Plans and Subscriptions

```php
// v1.x
$plan = $paystack->plans->create([
    'name' => 'Premium Monthly',
    'interval' => 'monthly',
    'amount' => 5000,
]);

$subscription = $paystack->subscriptions->create([
    'customer' => 'CUS_xxx',
    'plan' => $plan['data']['plan_code'],
]);

// v2.x (both styles work, but method call recommended)
$plan = $paystack->plans()->create([
    'name' => 'Premium Monthly',
    'interval' => 'monthly',
    'amount' => 5000,
]);

$subscription = $paystack->subscriptions()->create([
    'customer' => 'CUS_xxx',
    'plan' => $plan['data']['plan_code'],
]);
```

### Webhooks

Webhook handling remains the same:

```php
// Works in both v1.x and v2.x
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

$computedSignature = hash_hmac('sha512', $payload, $secretKey);

if (hash_equals($signature, $computedSignature)) {
    $event = json_decode($payload, true);
    // Process event
}
```

## Testing Your Migration

### 1. Run Existing Tests

```bash
composer test
```

### 2. Test Critical Flows

Create a migration test script:

```php
<?php
// test_migration.php

require 'vendor/autoload.php';

use StarfolkSoftware\Paystack\Client as PaystackClient;

$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_test_key',
]);

// Test 1: Basic API access
echo "Testing API access...\n";
$customers = $paystack->customers();
echo "✅ Customer API accessible\n";

// Test 2: Array-based response (backward compatibility)
echo "\nTesting backward compatibility...\n";
$response = $paystack->customers()->all(['perPage' => 1]);
if (isset($response['status']) && $response['status']) {
    echo "✅ Array-based responses work\n";
}

// Test 3: Typed responses (new feature)
echo "\nTesting typed responses...\n";
$response = $paystack->customers()->allTyped(['perPage' => 1]);
if ($response->isSuccessful()) {
    echo "✅ Typed responses work\n";
}

echo "\n✅ Migration successful!\n";
```

Run the test:

```bash
php test_migration.php
```

### 3. Gradual Rollout

1. **Development Environment**: Test thoroughly
2. **Staging Environment**: Deploy and monitor
3. **Production**: Deploy during low-traffic period
4. **Monitor**: Watch for errors and performance

## Common Issues and Solutions

### Issue 1: PHP Version Error

**Error:**
```
Your lock file does not contain a compatible set of packages. Please run composer update.
Problem 1
  - Root composer.json requires php ^8.2 but your php version (7.4.x) does not satisfy that requirement.
```

**Solution:**
Upgrade PHP to 8.2 or higher (see [Requirements](#requirements) section).

### Issue 2: Type Errors

**Error:**
```
TypeError: Argument 1 passed to X must be of type string, null given
```

**Solution:**
PHP 8.2 is stricter with types. Add null checks:

```php
// Before
function process($value) {
    return strtoupper($value);
}

// After
function process(?string $value): string {
    return $value ? strtoupper($value) : '';
}
```

### Issue 3: Magic Property Access Warning

**Warning:**
```
Deprecated: Magic property access is deprecated, use method call instead
```

**Solution:**
Update property access to method calls:

```php
// Change:
$paystack->customers->all([]);

// To:
$paystack->customers()->all([]);
```

### Issue 4: Missing Return Types

**Error:**
```
Fatal error: Could not check compatibility
```

**Solution:**
Add return type declarations:

```php
// Before
function getCustomer() {
    // ...
}

// After
function getCustomer(): ?array {
    // ...
}
```

## Best Practices for v2.x

### 1. Use Typed Responses for New Code

```php
// ✅ Recommended
$response = $paystack->customers()->createTyped([...]);
$customer = $response->getData();

// ❌ Avoid for new code (but still works)
$response = $paystack->customers()->create([...]);
$customer = $response['data'];
```

### 2. Leverage Helper Methods

```php
// Instead of:
if ($transaction->data['status'] === 'success') { }

// Use:
if ($transaction->isSuccessful()) { }

// Instead of:
$amount = $transaction->amount / 100;

// Use:
$amount = $transaction->getAmountInMajorUnit();
```

### 3. Use Options Classes for Complex Requests

```php
use StarfolkSoftware\Paystack\Options\Transaction\InitializeOptions;

$options = new InitializeOptions([
    'email' => 'customer@example.com',
    'amount' => '50000',
    'currency' => 'NGN',
    'metadata' => [...],
]);

// Validation happens automatically
```

### 4. Type Hint Your Functions

```php
use StarfolkSoftware\Paystack\Response\Customer\CustomerData;
use StarfolkSoftware\Paystack\Response\PaystackResponse;

function processCustomer(CustomerData $customer): void {
    echo $customer->getFullName();
}

function createCustomer(array $data): PaystackResponse {
    return $this->paystack->customers()->createTyped($data);
}
```

### 5. Handle Pagination Properly

```php
$page = 1;
$allCustomers = [];

do {
    $response = $paystack->customers()->allTyped([
        'perPage' => 100,
        'page' => $page,
    ]);
    
    $allCustomers = array_merge($allCustomers, $response->getCustomers());
    $pagination = $response->getPagination();
    
    $page++;
} while ($pagination->hasNextPage());
```

## Migration Checklist

Use this checklist to track your migration progress:

- [ ] Review upgrade guide completely
- [ ] Check PHP version (8.2+ required)
- [ ] Update `composer.json` dependencies
- [ ] Run `composer update`
- [ ] Update API property access to method calls
- [ ] Fix PHP 8.2 compatibility issues (if any)
- [ ] Update test suite
- [ ] Test in development environment
- [ ] Update documentation and comments
- [ ] Deploy to staging environment
- [ ] Monitor staging for issues
- [ ] Deploy to production
- [ ] Monitor production
- [ ] Gradually adopt typed responses in new code
- [ ] Plan refactoring of existing code (optional)

## Performance Considerations

v2.x maintains similar performance to v1.x:

- **Response DTOs**: Minimal overhead, lazy initialization
- **Options Validation**: Happens before API calls, catches errors early
- **Type Safety**: Compile-time feature, no runtime cost
- **Backward Compatibility**: No performance penalty for using old methods

## Future-Proofing

v2.x is designed with future enhancements in mind:

### Upcoming in v2.1+ (Planned)

- **Request DTOs**: Strongly-typed request objects
- **Builder Patterns**: Fluent API for complex requests
- **More Response DTOs**: Coverage for all endpoints
- **Async Support**: Support for async HTTP clients
- **Event System**: Middleware and event listeners

### Staying Updated

```bash
# Watch for updates
composer show starfolksoftware/paystack-php

# Update to latest v2.x
composer update starfolksoftware/paystack-php
```

## Need Help?

### Resources

- **Documentation**: [API Reference](docs/api-reference.md)
- **Examples**: Check the [examples/](examples/) directory
- **Phase 2 Summary**: [PHASE2_SUMMARY.md](PHASE2_SUMMARY.md)

### Demos

Run the demo scripts to see v2.x features in action:

```bash
# Response DTOs demo
php examples/typed_responses_demo.php

# Type hinting improvements demo
php examples/improved_type_hinting_demo.php

# Payment request workflow
php examples/payment_request_demo.php
```

### Support Channels

- **GitHub Issues**: [Report bugs or request features](https://github.com/starfolksoftware/paystack-php/issues)
- **Email**: [contact@starfolksoftware.com](mailto:contact@starfolksoftware.com)
- **Documentation**: [Official Paystack API Docs](https://paystack.com/docs/api/)

### Getting Help with Migration

If you encounter issues during migration:

1. **Check this guide**: Most common issues are covered
2. **Review examples**: See working code in `examples/` directory
3. **Run demos**: Test features interactively
4. **Check tests**: See how the test suite uses the API
5. **Open an issue**: Describe your problem with code examples

## Summary

v2.x brings significant improvements while maintaining backward compatibility:

✅ **What Changed:**
- PHP 8.2+ required
- Method calls recommended over property access
- New typed response objects available

✅ **What Stayed the Same:**
- Array-based responses still work
- All API endpoints unchanged
- Webhook handling unchanged
- Configuration options unchanged

✅ **Migration Strategy:**
1. Update PHP and dependencies
2. Update API access patterns (recommended)
3. Keep existing code working
4. Adopt new features gradually
5. Enjoy improved type safety and DX

**The upgrade is designed to be smooth, gradual, and non-disruptive. Most applications can migrate with minimal code changes.**

---

**Happy upgrading! 🚀**

Made with ❤️ by [Starfolk Software](https://starfolksoftware.com)
