<?php

/**
 * Simple Payment Request Example
 * 
 * This example demonstrates the basic usage of Paystack Payment Requests API.
 * Payment requests are perfect for sending invoices and managing payments
 * from your customers.
 * 
 * What you'll learn:
 * - Creating a payment request with line items and taxes
 * - Retrieving payment request information
 * - Listing recent payment requests
 * 
 * Before running this example:
 * 1. Get your test secret key from https://dashboard.paystack.com/#/settings/developer
 * 2. Replace 'sk_test_your_secret_key_here' with your actual test key
 * 3. Run: php examples/simple_payment_request.php
 */

// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Import the Paystack Client
use StarfolkSoftware\Paystack\Client as PaystackClient;

// ============================================================================
// Configuration
// ============================================================================

/**
 * Replace with your actual test secret key from Paystack Dashboard
 * Test keys start with 'sk_test_' and won't charge real money
 * Live keys start with 'sk_live_' and will charge real money
 */
$secretKey = 'sk_test_your_secret_key_here';

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => $secretKey,
]);

// ============================================================================
// Create Payment Request
// ============================================================================

echo "🚀 Creating a payment request...\n\n";

try {
    /**
     * Create a payment request with multiple line items and tax
     * 
     * Payment requests are like invoices that you can send to customers.
     * They support:
     * - Multiple line items with quantities
     * - Tax calculations
     * - Due dates
     * - Custom descriptions
     * - Automatic email notifications
     */
    $paymentRequest = $paystack->paymentRequests->create([
        'description' => 'Website Development Services - June 2024',
        
        // Line items represent individual products or services
        'line_items' => [
            [
                'name' => 'Frontend Development',
                'amount' => 50000, // Amount in kobo (₦500.00)
                'quantity' => 1
            ],
            [
                'name' => 'Backend API Development', 
                'amount' => 75000, // Amount in kobo (₦750.00)
                'quantity' => 1
            ],
            [
                'name' => 'Database Design',
                'amount' => 25000, // Amount in kobo (₦250.00)
                'quantity' => 1
            ]
        ],
        
        // Tax items (can be multiple taxes)
        'tax' => [
            [
                'name' => 'VAT (7.5%)',
                'amount' => 11250 // 7.5% of ₦1,500 = ₦112.50 in kobo
            ]
        ],
        
        // Customer can be email, customer code, or customer ID
        'customer' => 'john.doe@example.com',
        
        // Due date in YYYY-MM-DD format
        'due_date' => date('Y-m-d', strtotime('+30 days')), // 30 days from now
        
        // Whether to send email notification to customer
        'send_notification' => true,
        
        // Currency (defaults to NGN)
        'currency' => 'NGN'
    ]);

    // Check if payment request was created successfully
    if ($paymentRequest['status']) {
        echo "✅ Payment request created successfully!\n\n";
        
        // Extract important information from the response
        $requestData = $paymentRequest['data'];
        
        echo "📄 Payment Request Details:\n";
        echo "   Request Code: {$requestData['request_code']}\n";
        echo "   Description: {$requestData['description']}\n";
        echo "   Amount: ₦" . number_format($requestData['amount'] / 100, 2) . "\n";
        echo "   Currency: {$requestData['currency']}\n";
        echo "   Status: {$requestData['status']}\n";
        echo "   Due Date: {$requestData['due_date']}\n";
        echo "   Created: {$requestData['created_at']}\n";
        
        // If an invoice URL is available, show it
        if (isset($requestData['invoice_url'])) {
            echo "   Invoice URL: {$requestData['invoice_url']}\n";
        }
        
        echo "\n";
        
        // Show line items breakdown
        if (isset($requestData['line_items']) && is_array($requestData['line_items'])) {
            echo "📋 Line Items:\n";
            foreach ($requestData['line_items'] as $item) {
                $itemTotal = ($item['amount'] * $item['quantity']) / 100;
                echo "   • {$item['name']}: ₦" . number_format($item['amount'] / 100, 2) 
                   . " × {$item['quantity']} = ₦" . number_format($itemTotal, 2) . "\n";
            }
            echo "\n";
        }
        
        // Show tax breakdown
        if (isset($requestData['tax']) && is_array($requestData['tax'])) {
            echo "💰 Taxes:\n";
            foreach ($requestData['tax'] as $tax) {
                echo "   • {$tax['name']}: ₦" . number_format($tax['amount'] / 100, 2) . "\n";
            }
            echo "\n";
        }
        
        // Store the request code for further operations
        $requestCode = $requestData['request_code'];
        
    } else {
        throw new Exception("Failed to create payment request: " . $paymentRequest['message']);
    }

} catch (Exception $e) {
    echo "❌ Error creating payment request: " . $e->getMessage() . "\n";
    echo "   Please check your API key and try again.\n\n";
    exit(1);
}

// ============================================================================
// List Recent Payment Requests
// ============================================================================

echo "📋 Retrieving recent payment requests...\n\n";

try {
    /**
     * List payment requests with pagination
     * 
     * You can filter by:
     * - perPage: Number of requests per page (max 100)
     * - page: Page number
     * - customer: Filter by customer
     * - status: Filter by status (pending, paid, etc.)
     * - from/to: Date range filters
     */
    $recentRequests = $paystack->paymentRequests->all([
        'perPage' => 5, // Get latest 5 requests
        'page' => 1,    // First page
    ]);

    if ($recentRequests['status'] && !empty($recentRequests['data'])) {
        echo "✅ Found {$recentRequests['meta']['total']} payment request(s) in your account\n\n";
        
        echo "📄 Recent Payment Requests:\n";
        foreach ($recentRequests['data'] as $index => $request) {
            $amount = number_format($request['amount'] / 100, 2);
            $status = ucfirst($request['status']);
            
            echo "   " . ($index + 1) . ". {$request['description']}\n";
            echo "      Code: {$request['request_code']}\n";
            echo "      Amount: ₦{$amount} {$request['currency']}\n";
            echo "      Status: {$status}\n";
            echo "      Created: {$request['created_at']}\n";
            
            if (isset($request['due_date'])) {
                echo "      Due: {$request['due_date']}\n";
            }
            
            echo "\n";
        }
        
        // Show pagination info
        $meta = $recentRequests['meta'];
        echo "📊 Pagination Info:\n";
        echo "   Total: {$meta['total']}\n";
        echo "   Per Page: {$meta['perPage']}\n";
        echo "   Current Page: {$meta['page']}\n";
        echo "   Total Pages: {$meta['pageCount']}\n\n";
        
    } else {
        echo "ℹ️  No payment requests found in your account.\n";
        echo "   The payment request created above should appear here.\n\n";
    }

} catch (Exception $e) {
    echo "❌ Error retrieving payment requests: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// Additional Operations (Optional)
// ============================================================================

if (isset($requestCode)) {
    echo "🔍 Demonstrating additional operations...\n\n";
    
    try {
        // Fetch specific payment request
        echo "Fetching payment request details...\n";
        $fetchedRequest = $paystack->paymentRequests->fetch($requestCode);
        
        if ($fetchedRequest['status']) {
            echo "✅ Successfully fetched payment request: {$requestCode}\n";
            echo "   Current status: {$fetchedRequest['data']['status']}\n\n";
        }
        
        // Verify payment request (check payment status)
        echo "Verifying payment status...\n";
        $verification = $paystack->paymentRequests->verify($requestCode);
        
        if ($verification['status']) {
            $verificationData = $verification['data'];
            echo "✅ Payment verification completed\n";
            echo "   Payment Status: {$verificationData['status']}\n";
            echo "   Amount Paid: ₦" . number_format($verificationData['amount_paid'] / 100, 2) . "\n";
            echo "   Amount Expected: ₦" . number_format($verificationData['amount'] / 100, 2) . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error during additional operations: " . $e->getMessage() . "\n\n";
    }
}

// ============================================================================
// Next Steps
// ============================================================================

echo "🎉 Example completed successfully!\n\n";

echo "Next steps you can try:\n";
echo "• Send the payment request URL to a customer\n";
echo "• Use payment_request_demo.php for more advanced features\n";
echo "• Check out invoice_workflow.php for complete invoice management\n";
echo "• Explore recurring_billing.php for subscription patterns\n\n";

echo "💡 Tips:\n";
echo "• All amounts are in kobo (smallest currency unit)\n";
echo "• Use test card 4084084084084081 for successful test payments\n";
echo "• Check your Paystack dashboard for real-time updates\n";
echo "• Enable webhooks for automatic payment notifications\n\n";

echo "📚 Documentation:\n";
echo "• Getting Started: docs/getting-started.md\n";
echo "• API Reference: docs/api-reference.md\n";
echo "• Advanced Usage: docs/advanced-usage.md\n";
echo "• Troubleshooting: docs/troubleshooting.md\n\n";

// Show warning if using default API key
if ($secretKey === 'sk_test_your_secret_key_here') {
    echo "⚠️  WARNING: You're using the default API key!\n";
    echo "   Please replace it with your actual test key from:\n";
    echo "   https://dashboard.paystack.com/#/settings/developer\n\n";
}
