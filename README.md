# Paystack PHP bindings

[![Latest Stable Version](http://poser.pugx.org/starfolksoftware/paystack-php/v)](https://packagist.org/packages/starfolksoftware/paystack-php) [![Total Downloads](http://poser.pugx.org/starfolksoftware/paystack-php/downloads)](https://packagist.org/packages/starfolksoftware/paystack-php) [![License](http://poser.pugx.org/starfolksoftware/paystack-php/license)](https://packagist.org/packages/starfolksoftware/paystack-php) [![PHP Version Require](http://poser.pugx.org/starfolksoftware/paystack-php/require/php)](https://packagist.org/packages/starfolksoftware/paystack-php)

The Paystack PHP library provides convenient access to the Paystack API from
applications written in the PHP language. It includes a pre-defined set of
classes for API resources that initialize themselves dynamically from API
responses which makes it compatible with a wide range of versions of the Paystack
API.

## Requirements

PHP 8.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require starfolksoftware/paystack-php
```

```bash
composer require php-http/guzzle7-adapter
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

Any package that implements [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation)

## Getting Started

Simple usage looks like:

```php
<?php

require_once "vendor/autoload.php";

use StarfolkSoftware\Paystack\Client as PaystackClient;

$paystack = new PaystackClient([
    'secretKey' => '*******',
]);

$response = $paystack
    ->transactions
    ->all([]);

var_dump($response['data'][0]);

\\ dumps
array(21) { ... }
...
```

### Using Payment Requests

```php
<?php

require_once "vendor/autoload.php";

use StarfolkSoftware\Paystack\Client as PaystackClient;

$paystack = new PaystackClient([
    'secretKey' => '*******',
]);

// Create a payment request
$response = $paystack->paymentRequests->create([
    'description' => 'a test invoice',
    'line_items' => [
        ['name' => 'item 1', 'amount' => 20000],
        ['name' => 'item 2', 'amount' => 20000]
    ],
    'tax' => [
        ['name' => 'VAT', 'amount' => 2000]
    ],
    'customer' => 'CUS_xwaj0txjryg393b',
    'due_date' => '2025-07-08'
]);

// List payment requests
$paymentRequests = $paystack->paymentRequests->all(['page' => 1]);

// Fetch a specific payment request
$paymentRequest = $paystack->paymentRequests->fetch('PRQ_1weqqsn2wwzgft8');

// Verify a payment request
$verification = $paystack->paymentRequests->verify('PRQ_1weqqsn2wwzgft8');

// Send notification for a payment request
$notification = $paystack->paymentRequests->sendNotification('PRQ_1weqqsn2wwzgft8');

// Get payment request totals
$totals = $paystack->paymentRequests->totals();

// Finalize a draft payment request
$finalized = $paystack->paymentRequests->finalize('PRQ_1weqqsn2wwzgft8', ['send_notification' => true]);

// Update a payment request
$updated = $paystack->paymentRequests->update('PRQ_1weqqsn2wwzgft8', [
    'description' => 'Updated test invoice',
    'due_date' => '2025-07-15'
]);

// Archive a payment request
$archived = $paystack->paymentRequests->archive('PRQ_1weqqsn2wwzgft8');
```

## Available endpoints

- [x] Customer
- [x] Invoice
- [x] Payment Request
- [x] Plan
- [x] Subscription
- [x] Transaction

## Documentation

See the [PHP API docs](https://developer.paystack.com/reference#introduction-1).


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](https://github.com/starfolksoftware/paystack-php/compare/v0.0.2...v0.6.1) for more information on what has changed recently.

## Road Map

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/CONTRIBUTING.md) on how to report security vulnerabilities.

## Credits

- [Faruk Nasir](https://github.com/frknasir)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
