<?php

/**
 * Complete Invoice Workflow Example
 * 
 * This example demonstrates a comprehensive invoice management workflow using
 * Paystack Payment Requests API. It covers the entire invoice lifecycle from
 * creation to payment verification.
 * 
 * Workflow Steps:
 * 1. Customer management (create/get customer)
 * 2. Create draft payment request
 * 3. Update invoice with additional items
 * 4. Finalize and send to customer
 * 5. Monitor payment status
 * 
 * Use Cases:
 * - Monthly service billing
 * - Project-based invoicing
 * - Progressive billing (add items over time)
 * - Professional service billing
 * 
 * Before running:
 * 1. Replace the secret key with your test key
 * 2. Run: php examples/invoice_workflow.php
 */

// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Import the Paystack Client
use StarfolkSoftware\Paystack\Client as PaystackClient;

// ============================================================================
// Configuration
// ============================================================================

$secretKey = 'sk_test_your_secret_key_here';

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => $secretKey,
]);

// Customer information for this example
$customerInfo = [
    'email' => 'john.doe@techcompany.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+2348123456789',
    'company' => 'Tech Company Ltd.',
    'address' => '123 Business Avenue, Lagos, Nigeria'
];

// ============================================================================
// Helper Functions
// ============================================================================

/**
 * Format currency for display
 */
function formatCurrency(int $amountInKobo): string {
    return '₦' . number_format($amountInKobo / 100, 2);
}

/**
 * Print section header
 */
function printSection(string $title): void {
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "  " . strtoupper($title) . "\n";
    echo str_repeat('=', 60) . "\n";
}

/**
 * Print step information
 */
function printStep(int $step, string $description): void {
    echo "\n🚀 Step {$step}: {$description}\n";
    echo str_repeat('-', 50) . "\n";
}

// ============================================================================
// Start Invoice Workflow
// ============================================================================

printSection("Complete Invoice Workflow");
echo "This example demonstrates professional invoice management\n";
echo "Customer: {$customerInfo['first_name']} {$customerInfo['last_name']} ({$customerInfo['email']})\n";

try {
    // ========================================================================
    // Step 1: Customer Management
    // ========================================================================
    
    printStep(1, "Customer Management");
    
    $customerCode = null;
    
    try {
        // Try to create customer (might already exist)
        echo "Creating customer profile...\n";
        
        $customer = $paystack->customers->create([
            'email' => $customerInfo['email'],
            'first_name' => $customerInfo['first_name'],
            'last_name' => $customerInfo['last_name'],
            'phone' => $customerInfo['phone'],
            'metadata' => [
                'company' => $customerInfo['company'],
                'address' => $customerInfo['address'],
                'registration_date' => date('Y-m-d'),
                'customer_type' => 'business'
            ]
        ]);
        
        $customerCode = $customer['data']['customer_code'];
        echo "✅ New customer created successfully\n";
        echo "   Customer Code: {$customerCode}\n";
        echo "   Customer ID: {$customer['data']['id']}\n";
        
    } catch (Exception $e) {
        // Customer likely already exists
        echo "ℹ️  Customer creation note: {$e->getMessage()}\n";
        
        try {
            // Try to fetch existing customer
            echo "Fetching existing customer...\n";
            $existingCustomer = $paystack->customers->fetch($customerInfo['email']);
            
            if ($existingCustomer['status']) {
                $customerCode = $existingCustomer['data']['customer_code'];
                echo "✅ Found existing customer\n";
                echo "   Customer Code: {$customerCode}\n";
                echo "   Total Transactions: {$existingCustomer['data']['transactions_count']}\n";
                echo "   Total Value: " . formatCurrency($existingCustomer['data']['total_transaction_value']) . "\n";
            }
        } catch (Exception $fetchError) {
            // For demo purposes, use a placeholder
            echo "⚠️  Using placeholder customer for demo\n";
            $customerCode = 'demo_customer_' . time();
        }
    }
    
    // ========================================================================
    // Step 2: Create Draft Payment Request
    // ========================================================================
    
    printStep(2, "Creating Draft Invoice");
    
    echo "Creating draft payment request with initial items...\n";
    
    $draftPaymentRequest = $paystack->paymentRequests->create([
        'description' => 'Monthly Service Invoice - ' . date('F Y'),
        
        // Initial line items (more can be added later)
        'line_items' => [
            [
                'name' => 'Basic Cloud Hosting',
                'amount' => 25000, // ₦250.00
                'quantity' => 1,
                'description' => 'Monthly cloud hosting service'
            ],
            [
                'name' => 'Domain Registration',
                'amount' => 5000, // ₦50.00
                'quantity' => 1,
                'description' => 'Annual domain registration'
            ]
        ],
        
        'customer' => $customerCode,
        'due_date' => date('Y-m-d', strtotime('+30 days')), // 30 days from now
        'draft' => true, // Create as draft initially
        'has_invoice' => true, // Generate professional invoice number
        'currency' => 'NGN',
        
        // Additional metadata for tracking
        'metadata' => [
            'invoice_type' => 'monthly_service',
            'billing_period' => date('Y-m'),
            'created_by' => 'billing_system',
            'department' => 'hosting_services'
        ]
    ]);
    
    if (!$draftPaymentRequest['status']) {
        throw new Exception("Failed to create draft payment request: " . $draftPaymentRequest['message']);
    }
    
    $requestCode = $draftPaymentRequest['data']['request_code'];
    $invoiceNumber = $draftPaymentRequest['data']['invoice_number'];
    $currentAmount = $draftPaymentRequest['data']['amount'];
    
    echo "✅ Draft payment request created successfully\n";
    echo "   Request Code: {$requestCode}\n";
    echo "   Invoice Number: #{$invoiceNumber}\n";
    echo "   Current Amount: " . formatCurrency($currentAmount) . "\n";
    echo "   Status: {$draftPaymentRequest['data']['status']}\n";
    echo "   Due Date: {$draftPaymentRequest['data']['due_date']}\n";
    
    // ========================================================================
    // Step 3: Add Additional Services (Update Invoice)
    // ========================================================================
    
    printStep(3, "Adding Additional Services");
    
    echo "Simulating additional services being added to the invoice...\n";
    
    // In a real scenario, these might be added over time as services are rendered
    $updatedRequest = $paystack->paymentRequests->update($requestCode, [
        'line_items' => [
            // Original items
            [
                'name' => 'Basic Cloud Hosting',
                'amount' => 25000,
                'quantity' => 1,
                'description' => 'Monthly cloud hosting service'
            ],
            [
                'name' => 'Domain Registration',
                'amount' => 5000,
                'quantity' => 1,
                'description' => 'Annual domain registration'
            ],
            
            // Additional services
            [
                'name' => 'Premium Support',
                'amount' => 15000, // ₦150.00
                'quantity' => 1,
                'description' => '24/7 premium technical support'
            ],
            [
                'name' => 'SSL Certificate',
                'amount' => 8000, // ₦80.00
                'quantity' => 1,
                'description' => 'Wildcard SSL certificate'
            ],
            [
                'name' => 'Backup Storage',
                'amount' => 3000, // ₦30.00
                'quantity' => 5, // 5 GB
                'description' => 'Additional backup storage (per GB)'
            ]
        ],
        
        // Add applicable taxes
        'tax' => [
            [
                'name' => 'VAT (7.5%)',
                'amount' => 4275 // 7.5% of ₦570 = ₦42.75
            ]
        ],
        
        // Update description to reflect changes
        'description' => 'Monthly Service Invoice - ' . date('F Y') . ' (Comprehensive Package)',
        
        // Add discount if applicable
        'discount' => [
            [
                'name' => 'Early Payment Discount',
                'amount' => 2000 // ₦20.00 discount
            ]
        ]
    ]);
    
    if (!$updatedRequest['status']) {
        throw new Exception("Failed to update payment request: " . $updatedRequest['message']);
    }
    
    $newAmount = $updatedRequest['data']['amount'];
    
    echo "✅ Invoice updated with additional services\n";
    echo "   Previous Amount: " . formatCurrency($currentAmount) . "\n";
    echo "   New Amount: " . formatCurrency($newAmount) . "\n";
    echo "   Difference: " . formatCurrency($newAmount - $currentAmount) . "\n";
    
    // Show detailed breakdown
    echo "\n📋 Invoice Breakdown:\n";
    if (isset($updatedRequest['data']['line_items'])) {
        $subtotal = 0;
        foreach ($updatedRequest['data']['line_items'] as $item) {
            $lineTotal = $item['amount'] * $item['quantity'];
            $subtotal += $lineTotal;
            echo "   • {$item['name']}: " . formatCurrency($item['amount']) 
               . " × {$item['quantity']} = " . formatCurrency($lineTotal) . "\n";
        }
        echo "   Subtotal: " . formatCurrency($subtotal) . "\n";
    }
    
    if (isset($updatedRequest['data']['tax'])) {
        foreach ($updatedRequest['data']['tax'] as $tax) {
            echo "   + {$tax['name']}: " . formatCurrency($tax['amount']) . "\n";
        }
    }
    
    if (isset($updatedRequest['data']['discount'])) {
        foreach ($updatedRequest['data']['discount'] as $discount) {
            echo "   - {$discount['name']}: " . formatCurrency($discount['amount']) . "\n";
        }
    }
    
    echo "   Total: " . formatCurrency($newAmount) . "\n";
    
    // ========================================================================
    // Step 4: Finalize and Send Invoice
    // ========================================================================
    
    printStep(4, "Finalizing and Sending Invoice");
    
    echo "Finalizing the payment request...\n";
    
    // Finalize the payment request (converts from draft to active)
    $finalizedRequest = $paystack->paymentRequests->finalize($requestCode, [
        'send_notification' => false // We'll send notification manually for better control
    ]);
    
    if (!$finalizedRequest['status']) {
        throw new Exception("Failed to finalize payment request: " . $finalizedRequest['message']);
    }
    
    echo "✅ Payment request finalized successfully\n";
    echo "   Status: {$finalizedRequest['data']['status']}\n";
    
    // Send email notification to customer
    echo "\nSending invoice notification to customer...\n";
    
    $notification = $paystack->paymentRequests->sendNotification($requestCode);
    
    if ($notification['status']) {
        echo "✅ Invoice notification sent successfully\n";
        echo "   Notification sent to: {$customerInfo['email']}\n";
        echo "   Customer can view and pay the invoice online\n";
    } else {
        echo "⚠️  Warning: Could not send notification - {$notification['message']}\n";
    }
    
    // ========================================================================
    // Step 5: Payment Status Monitoring
    // ========================================================================
    
    printStep(5, "Payment Status Monitoring");
    
    echo "Checking current payment status...\n";
    
    // Verify payment request status
    $verifiedRequest = $paystack->paymentRequests->verify($requestCode);
    
    if (!$verifiedRequest['status']) {
        throw new Exception("Failed to verify payment request: " . $verifiedRequest['message']);
    }
    
    $verification = $verifiedRequest['data'];
    
    echo "📊 Payment Status Report:\n";
    echo "   Invoice Number: #{$verification['invoice_number']}\n";
    echo "   Request Code: {$verification['request_code']}\n";
    echo "   Status: " . ucfirst($verification['status']) . "\n";
    echo "   Amount: " . formatCurrency($verification['amount']) . "\n";
    echo "   Amount Paid: " . formatCurrency($verification['amount_paid']) . "\n";
    echo "   Amount Due: " . formatCurrency($verification['amount'] - $verification['amount_paid']) . "\n";
    echo "   Paid: " . ($verification['paid'] ? '✅ Yes' : '❌ No') . "\n";
    echo "   Created: {$verification['created_at']}\n";
    echo "   Due Date: {$verification['due_date']}\n";
    
    // Show payment URLs if available
    if (isset($verification['pdf_url'])) {
        echo "\n🔗 Customer Links:\n";
        echo "   PDF Invoice: {$verification['pdf_url']}\n";
    }
    
    if (isset($verification['invoice_url'])) {
        echo "   Payment Page: {$verification['invoice_url']}\n";
    }
    
    // ========================================================================
    // Additional Features Demo
    // ========================================================================
    
    printStep(6, "Additional Features");
    
    echo "Demonstrating additional invoice features...\n";
    
    // Get payment request totals (summary across all payment requests)
    echo "\nFetching account payment request totals...\n";
    $totals = $paystack->paymentRequests->totals();
    
    if ($totals['status']) {
        $totalsData = $totals['data'];
        echo "📈 Account Summary:\n";
        echo "   Total Payment Requests: {$totalsData['total_requests']}\n";
        echo "   Pending Amount: " . formatCurrency($totalsData['pending_amount']) . "\n";
        echo "   Successful Amount: " . formatCurrency($totalsData['successful_amount']) . "\n";
    }
    
    echo "\n💡 Next Steps in Real Application:\n";
    echo "   • Set up webhook endpoints to monitor payment events\n";
    echo "   • Implement automatic payment reminders for overdue invoices\n";
    echo "   • Create recurring payment requests for subscription billing\n";
    echo "   • Generate PDF invoices with your company branding\n";
    echo "   • Integrate with your accounting/CRM system\n";
    
    // ========================================================================
    // Workflow Completion
    // ========================================================================
    
    printSection("Workflow Completed Successfully");
    
    echo "🎉 Invoice workflow completed successfully!\n\n";
    
    echo "📋 Summary:\n";
    echo "   ✅ Customer managed\n";
    echo "   ✅ Draft invoice created\n";
    echo "   ✅ Additional services added\n";
    echo "   ✅ Invoice finalized and sent\n";
    echo "   ✅ Payment status monitored\n\n";
    
    echo "📊 Final Invoice Details:\n";
    echo "   Invoice #: {$invoiceNumber}\n";
    echo "   Customer: {$customerInfo['first_name']} {$customerInfo['last_name']}\n";
    echo "   Amount: " . formatCurrency($verification['amount']) . "\n";
    echo "   Status: " . ucfirst($verification['status']) . "\n";
    echo "   Due: {$verification['due_date']}\n\n";
    
    echo "🔧 Integration Examples:\n";
    echo "   • payment_request_demo.php - Advanced payment request features\n";
    echo "   • recurring_billing.php - Subscription and recurring payments\n";
    echo "   • webhook_handler.php - Real-time payment notifications\n\n";

} catch (Exception $e) {
    echo "\n❌ Error in invoice workflow: " . $e->getMessage() . "\n";
    echo "   Please check your API key and network connection.\n";
    echo "   For troubleshooting, see: docs/troubleshooting.md\n\n";
    exit(1);
}

// ============================================================================
// Development Notes
// ============================================================================

if ($secretKey === 'sk_test_your_secret_key_here') {
    echo "⚠️  IMPORTANT: Update your API key!\n";
    echo "   Replace 'sk_test_your_secret_key_here' with your actual test key\n";
    echo "   Get it from: https://dashboard.paystack.com/#/settings/developer\n\n";
}

echo "🧪 Testing Notes:\n";
echo "   • This example uses test mode (safe for experimentation)\n";
echo "   • Use test card 4084084084084081 for successful payments\n";
echo "   • Check your Paystack dashboard for real-time updates\n";
echo "   • Enable test webhooks for complete integration testing\n\n";

echo "📚 Learn More:\n";
echo "   • Paystack API Docs: https://paystack.com/docs/api/\n";
echo "   • SDK Documentation: docs/\n";
echo "   • GitHub Repository: https://github.com/starfolksoftware/paystack-php\n";
