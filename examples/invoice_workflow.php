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
 * This example demonstrates a complete invoice workflow using Payment Requests:
 * 1. Create a customer (if they don't exist)
 * 2. Create a draft payment request
 * 3. Update the payment request with additional line items
 * 4. Finalize the payment request
 * 5. Send notification to the customer
 * 6. Check payment status later
 */

// Step 1: First, make sure we have a customer
// Note: In a real application, you might want to check if the customer exists first
try {
    $customer = $paystack->customers->create([
        'email' => 'john.doe@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '+2348123456789'
    ]);
    
    $customerCode = $customer['data']['customer_code'];
    echo "Customer created with code: $customerCode\n";
} catch (Exception $e) {
    // Customer might already exist
    echo "Note: " . $e->getMessage() . "\n";
    
    // In a real application, you would search for the customer here
    $customerCode = 'CUS_existing_customer_code';
}

// Step 2: Create a draft payment request
$draftPaymentRequest = $paystack->paymentRequests->create([
    'description' => 'Monthly Service Invoice - June 2025',
    'line_items' => [
        ['name' => 'Basic Subscription', 'amount' => 30000, 'quantity' => 1]
    ],
    'customer' => $customerCode,
    'due_date' => '2025-07-15',
    'draft' => true, // Create as draft initially
    'has_invoice' => true // Generate an invoice number
]);

$requestCode = $draftPaymentRequest['data']['request_code'];
$invoiceNumber = $draftPaymentRequest['data']['invoice_number'];
echo "Draft payment request created with code: $requestCode and invoice #$invoiceNumber\n";

// Step 3: Update the payment request with additional items
$updatedRequest = $paystack->paymentRequests->update($requestCode, [
    'line_items' => [
        ['name' => 'Basic Subscription', 'amount' => 30000, 'quantity' => 1],
        ['name' => 'Premium Support', 'amount' => 15000, 'quantity' => 1],
        ['name' => 'Additional Storage', 'amount' => 5000, 'quantity' => 2]
    ],
    'tax' => [
        ['name' => 'VAT (7.5%)', 'amount' => 4125]
    ],
    'description' => 'Monthly Service Invoice - June 2025 (Updated)'
]);

echo "Payment request updated with additional items\n";

// Step 4: Finalize the payment request
$finalizedRequest = $paystack->paymentRequests->finalize($requestCode, [
    'send_notification' => false // We'll send it manually in the next step
]);

echo "Payment request finalized\n";

// Step 5: Send notification to the customer
$notification = $paystack->paymentRequests->sendNotification($requestCode);
echo "Payment notification sent to customer\n";

// Step 6: Check payment status (this would typically happen later)
echo "\nSimulating checking payment status after some time...\n";

// In a real application, this would happen in a separate process or callback
$verifiedRequest = $paystack->paymentRequests->verify($requestCode);
$status = $verifiedRequest['data']['status'];
$isPaid = $verifiedRequest['data']['paid'];

echo "Current status: $status\n";
echo "Paid: " . ($isPaid ? "Yes" : "No") . "\n";

// Output the URL where the customer can view and pay the invoice (if available)
if (isset($verifiedRequest['data']['pdf_url'])) {
    echo "Invoice URL: " . $verifiedRequest['data']['pdf_url'] . "\n";
}

echo "\nPayment request workflow completed!\n";
