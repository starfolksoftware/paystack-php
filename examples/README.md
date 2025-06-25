# Paystack PHP Examples

This directory contains example scripts demonstrating how to use the Paystack PHP client library.

## Prerequisites

Before running these examples, make sure you have:

1. Installed this package via Composer (`composer require starfolksoftware/paystack-php`)
2. A valid Paystack secret key (test or live)

## Available Examples

### Payment Request Examples

- `simple_payment_request.php`: Basic example showing how to create and list payment requests
- `payment_request_demo.php`: Comprehensive example demonstrating all payment request API methods
- `invoice_workflow.php`: Demonstrates a complete invoice workflow using payment requests
- `recurring_billing.php`: Advanced example showing how to implement recurring billing using payment requests

## How to Run

1. Update the `$secretKey` variable in the example files with your Paystack secret key.
2. Execute the script using PHP:

```bash
php examples/simple_payment_request.php
```

## Notes

- These examples use the test mode by default. To use them in production, replace the test key with your live key.
- The payment request API allows you to create, manage, and track payment requests and invoices for your customers.
- Some examples create draft payment requests that don't send notifications automatically, giving you control over when to finalize and notify customers.

## Error Handling

The examples include basic error handling. In a production environment, you should implement more comprehensive error handling strategies based on your application's requirements.

## Documentation

For more information on the available methods and parameters, refer to:
- The official Paystack API documentation: https://paystack.com/docs/api/payment-request/
- The library's main README file
