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

// Helper function to display results in a readable format
function displayResponse($title, $response) {
    echo "=== {$title} ===\n";
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
}

try {
    // Step 1: Create a payment request
    echo "Creating a payment request...\n";
    $createResponse = $paystack->paymentRequests->create([
        'description' => 'Test Payment Request',
        'line_items' => [
            ['name' => 'Product A', 'amount' => 10000, 'quantity' => 2],
            ['name' => 'Product B', 'amount' => 5000, 'quantity' => 1]
        ],
        'tax' => [
            ['name' => 'VAT', 'amount' => 1000]
        ],
        'customer' => 'customer_email@example.com', // Can be customer code or email
        'due_date' => date('Y-m-d', strtotime('+7 days')),
        'draft' => true // Create as draft so we can test the finalize method later
    ]);
    displayResponse('Create Payment Request', $createResponse);
    
    // Save the request code for later use
    $requestCode = $createResponse['data']['request_code'] ?? null;
    if (!$requestCode) {
        throw new Exception("Failed to get request code from the created payment request");
    }
    
    // Step 2: List payment requests
    echo "Listing payment requests...\n";
    $listResponse = $paystack->paymentRequests->all([
        'perPage' => 5,
        'page' => 1
    ]);
    displayResponse('List Payment Requests', $listResponse);
    
    // Step 3: Fetch a specific payment request
    echo "Fetching payment request with code: {$requestCode}...\n";
    $fetchResponse = $paystack->paymentRequests->fetch($requestCode);
    displayResponse('Fetch Payment Request', $fetchResponse);
    
    // Step 4: Verify a payment request
    echo "Verifying payment request with code: {$requestCode}...\n";
    $verifyResponse = $paystack->paymentRequests->verify($requestCode);
    displayResponse('Verify Payment Request', $verifyResponse);
    
    // Step 5: Update the payment request
    echo "Updating payment request with code: {$requestCode}...\n";
    $updateResponse = $paystack->paymentRequests->update($requestCode, [
        'description' => 'Updated Test Payment Request',
        'due_date' => date('Y-m-d', strtotime('+10 days'))
    ]);
    displayResponse('Update Payment Request', $updateResponse);
    
    // Step 6: Finalize the draft payment request
    echo "Finalizing draft payment request with code: {$requestCode}...\n";
    $finalizeResponse = $paystack->paymentRequests->finalize($requestCode, [
        'send_notification' => true
    ]);
    displayResponse('Finalize Payment Request', $finalizeResponse);
    
    // Step 7: Send a notification for the payment request
    echo "Sending notification for payment request with code: {$requestCode}...\n";
    $notificationResponse = $paystack->paymentRequests->sendNotification($requestCode);
    displayResponse('Send Notification', $notificationResponse);
    
    // Step 8: Get payment requests totals
    echo "Getting payment request totals...\n";
    $totalsResponse = $paystack->paymentRequests->totals();
    displayResponse('Payment Request Totals', $totalsResponse);
    
    // Step 9: Archive the payment request (uncommenting will archive the request)
    /*
    echo "Archiving payment request with code: {$requestCode}...\n";
    $archiveResponse = $paystack->paymentRequests->archive($requestCode);
    displayResponse('Archive Payment Request', $archiveResponse);
    */
    
    echo "All payment request operations completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
