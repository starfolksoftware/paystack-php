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

/**
 * This example demonstrates using Payment Requests for recurring billing:
 * - Creating payment requests for multiple customers based on their subscription tiers
 * - Managing customers who are due for billing
 * - Tracking payment statuses
 */

// Mock data for customers and their subscription tiers
$customerData = [
    [
        'email' => 'customer1@example.com',
        'name' => 'Customer One',
        'tier' => 'basic',
        'last_billed' => '2025-05-25', // Last month
    ],
    [
        'email' => 'customer2@example.com',
        'name' => 'Customer Two',
        'tier' => 'premium',
        'last_billed' => '2025-05-25', // Last month
    ],
    [
        'email' => 'customer3@example.com',
        'name' => 'Customer Three',
        'tier' => 'enterprise',
        'last_billed' => '2025-05-25', // Last month
    ],
];

// Subscription tier pricing (in kobo - Nigerian currency)
$tierPricing = [
    'basic' => [
        'monthly_fee' => 10000, // ₦100
        'description' => 'Basic Plan - Monthly Subscription',
        'features' => ['Feature 1', 'Feature 2']
    ],
    'premium' => [
        'monthly_fee' => 25000, // ₦250
        'description' => 'Premium Plan - Monthly Subscription',
        'features' => ['Feature 1', 'Feature 2', 'Feature 3', 'Premium Support']
    ],
    'enterprise' => [
        'monthly_fee' => 50000, // ₦500
        'description' => 'Enterprise Plan - Monthly Subscription',
        'features' => ['Feature 1', 'Feature 2', 'Feature 3', 'Premium Support', 'API Access', 'Advanced Analytics']
    ],
];

// Today's date for billing check
$today = date('Y-m-d');
$currentMonth = date('F Y');

// Get customers to bill this month (normally would check if it's been a month since last billing)
$customersToBill = array_filter($customerData, function($customer) {
    // In a real application, you would check if it's been a month since last billing
    return true; // For this example, bill all customers
});

echo "=== Starting monthly billing process for $currentMonth ===\n\n";

// Process each customer
$createdRequests = [];
foreach ($customersToBill as $customer) {
    echo "Processing {$customer['name']} ({$customer['email']}) - {$customer['tier']} tier...\n";
    
    // Get the tier details
    $tier = $tierPricing[$customer['tier']];
    
    // Create line items based on the tier features
    $lineItems = [
        [
            'name' => "{$customer['tier']} Monthly Subscription",
            'amount' => $tier['monthly_fee'],
            'quantity' => 1
        ]
    ];
    
    // Add tax (for example, 7.5% VAT)
    $taxAmount = round($tier['monthly_fee'] * 0.075);
    
    try {
        // Create payment request for this customer
        $paymentRequest = $paystack->paymentRequests->create([
            'description' => "{$tier['description']} - $currentMonth",
            'line_items' => $lineItems,
            'tax' => [
                ['name' => 'VAT (7.5%)', 'amount' => $taxAmount]
            ],
            'customer' => $customer['email'],
            'due_date' => date('Y-m-d', strtotime('+7 days')), // Due in 7 days
            'send_notification' => true // Send right away
        ]);
        
        $requestCode = $paymentRequest['data']['request_code'];
        $amount = $paymentRequest['data']['amount'] / 100; // Convert from kobo to naira for display
        
        echo "  ✓ Created payment request: {$requestCode} for ₦{$amount}\n";
        
        // Store for later reference
        $createdRequests[] = [
            'customer' => $customer,
            'request_code' => $requestCode,
            'amount' => $paymentRequest['data']['amount'],
            'due_date' => $paymentRequest['data']['due_date']
        ];
        
    } catch (Exception $e) {
        echo "  ✗ Failed to create payment request: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

echo "=== Billing process complete ===\n";
echo "Created " . count($createdRequests) . " payment requests\n\n";

// Simulate checking payment status after some time
echo "=== Simulating payment status check after 3 days ===\n\n";

foreach ($createdRequests as $request) {
    echo "Checking status for {$request['customer']['name']}'s payment request ({$request['request_code']})...\n";
    
    try {
        $status = $paystack->paymentRequests->verify($request['request_code']);
        $isPaid = $status['data']['paid'];
        $statusText = $status['data']['status'];
        
        echo "  Status: " . ($isPaid ? "PAID ✓" : "PENDING ⌛") . " ($statusText)\n";
        
        // If not paid and due date is approaching (in a real app you'd check the actual date)
        if (!$isPaid) {
            // Send a reminder notification
            echo "  Sending payment reminder...\n";
            $paystack->paymentRequests->sendNotification($request['request_code']);
            echo "  Reminder sent successfully\n";
        }
        
    } catch (Exception $e) {
        echo "  Failed to check status: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

// Get aggregated payment request totals
echo "=== Payment Request Totals ===\n";
try {
    $totals = $paystack->paymentRequests->totals();
    
    echo "Pending payments:\n";
    foreach ($totals['data']['pending'] as $currency) {
        echo "  {$currency['currency']}: " . ($currency['amount'] / 100) . "\n";
    }
    
    echo "Successful payments:\n";
    foreach ($totals['data']['successful'] as $currency) {
        echo "  {$currency['currency']}: " . ($currency['amount'] / 100) . "\n";
    }
    
    echo "Total payments (pending + successful):\n";
    foreach ($totals['data']['total'] as $currency) {
        echo "  {$currency['currency']}: " . ($currency['amount'] / 100) . "\n";
    }
    
} catch (Exception $e) {
    echo "Failed to get payment totals: {$e->getMessage()}\n";
}

echo "\nRecurring billing example completed!\n";
