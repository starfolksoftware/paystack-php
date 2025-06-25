<?php

// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Import the Paystack Client
use StarfolkSoftware\Paystack\Client as PaystackClient;

// Replace with your actual test secret key
$secretKey = 'sk_test_your_secret_key_here';

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => $secretKey,
]);

// Create a payment request
$paymentRequest = $paystack->paymentRequests->create([
    'description' => 'Invoice for June services',
    'line_items' => [
        ['name' => 'Web Development', 'amount' => 50000],
        ['name' => 'Server Maintenance', 'amount' => 20000]
    ],
    'tax' => [
        ['name' => 'VAT', 'amount' => 3500]
    ],
    'customer' => 'customer@example.com',
    'due_date' => '2025-07-10'
]);

// Output the result
echo "Payment Request Created:\n";
echo "Request Code: " . $paymentRequest['data']['request_code'] . "\n";
echo "Amount: " . ($paymentRequest['data']['amount'] / 100) . " " . $paymentRequest['data']['currency'] . "\n";
echo "Status: " . $paymentRequest['data']['status'] . "\n";
echo "Due Date: " . $paymentRequest['data']['due_date'] . "\n";

// List recent payment requests
$recentRequests = $paystack->paymentRequests->all(['perPage' => 3]);
echo "\nRecent Payment Requests:\n";
foreach ($recentRequests['data'] as $request) {
    echo "- " . $request['description'] . " (Code: " . $request['request_code'] . ")\n";
}
