<?php

/**
 * Recurring Billing and Subscription Management Example
 * 
 * This comprehensive example demonstrates how to implement recurring billing
 * using Paystack Payment Requests. It covers subscription management,
 * automated billing cycles, payment tracking, and customer notifications.
 * 
 * Features Demonstrated:
 * - Multi-tier subscription management
 * - Automated monthly billing cycles
 * - Payment status monitoring
 * - Automated reminder notifications
 * - Revenue tracking and reporting
 * - Subscription upgrades/downgrades
 * - Failed payment handling
 * 
 * Use Cases:
 * - SaaS subscription billing
 * - Membership site billing
 * - Service-based recurring payments
 * - Multi-tier product subscriptions
 * 
 * Before running:
 * 1. Replace the secret key with your test key
 * 2. Run: php examples/recurring_billing.php
 */

// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Import the Paystack Client
use StarfolkSoftware\Paystack\Client as PaystackClient;

// ============================================================================
// Configuration and Setup
// ============================================================================

$secretKey = 'sk_test_your_secret_key_here';

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => $secretKey,
]);

// ============================================================================
// Subscription Configuration
// ============================================================================

/**
 * Define subscription tiers with features and pricing
 */
$subscriptionTiers = [
    'starter' => [
        'name' => 'Starter Plan',
        'monthly_fee' => 5000,   // ₦50.00
        'annual_fee' => 50000,   // ₦500.00 (2 months free)
        'description' => 'Perfect for individuals and small projects',
        'features' => [
            '5 Projects',
            '10GB Storage',
            'Email Support',
            'Basic Analytics'
        ],
        'limits' => [
            'projects' => 5,
            'storage_gb' => 10,
            'api_calls' => 1000
        ]
    ],
    'professional' => [
        'name' => 'Professional Plan',
        'monthly_fee' => 15000,  // ₦150.00
        'annual_fee' => 150000,  // ₦1,500.00 (2 months free)
        'description' => 'Ideal for growing businesses and teams',
        'features' => [
            'Unlimited Projects',
            '100GB Storage',
            'Priority Support',
            'Advanced Analytics',
            'API Access',
            'Team Collaboration'
        ],
        'limits' => [
            'projects' => -1, // Unlimited
            'storage_gb' => 100,
            'api_calls' => 10000
        ]
    ],
    'enterprise' => [
        'name' => 'Enterprise Plan',
        'monthly_fee' => 50000,  // ₦500.00
        'annual_fee' => 500000,  // ₦5,000.00 (2 months free)
        'description' => 'Full-featured plan for large organizations',
        'features' => [
            'Unlimited Everything',
            '1TB Storage',
            'Dedicated Support',
            'Custom Analytics',
            'Full API Access',
            'Advanced Team Management',
            'Custom Integrations',
            'SLA Guarantee'
        ],
        'limits' => [
            'projects' => -1, // Unlimited
            'storage_gb' => 1000,
            'api_calls' => 100000
        ]
    ]
];

/**
 * Sample customer database with subscription details
 */
$customers = [
    [
        'id' => 'CUST_001',
        'email' => 'sarah.johnson@techstartup.com',
        'first_name' => 'Sarah',
        'last_name' => 'Johnson',
        'company' => 'Tech Startup Inc.',
        'phone' => '+2348123456789',
        'subscription' => [
            'tier' => 'professional',
            'billing_cycle' => 'monthly',
            'start_date' => '2024-01-15',
            'last_billed' => '2024-10-15',
            'next_billing' => '2024-11-15',
            'status' => 'active'
        ],
        'payment_history' => [
            'successful_payments' => 9,
            'failed_payments' => 1,
            'total_paid' => 135000 // ₦1,350.00
        ]
    ],
    [
        'id' => 'CUST_002',
        'email' => 'mike.chen@designagency.com',
        'first_name' => 'Mike',
        'last_name' => 'Chen',
        'company' => 'Creative Design Agency',
        'phone' => '+2348987654321',
        'subscription' => [
            'tier' => 'starter',
            'billing_cycle' => 'annual',
            'start_date' => '2024-03-01',
            'last_billed' => '2024-03-01',
            'next_billing' => '2025-03-01',
            'status' => 'active'
        ],
        'payment_history' => [
            'successful_payments' => 1,
            'failed_payments' => 0,
            'total_paid' => 50000 // ₦500.00
        ]
    ],
    [
        'id' => 'CUST_003',
        'email' => 'alex.rivera@bigcorp.com',
        'first_name' => 'Alex',
        'last_name' => 'Rivera',
        'company' => 'Big Corporation Ltd.',
        'phone' => '+2347012345678',
        'subscription' => [
            'tier' => 'enterprise',
            'billing_cycle' => 'monthly',
            'start_date' => '2024-06-01',
            'last_billed' => '2024-10-01',
            'next_billing' => '2024-11-01',
            'status' => 'active'
        ],
        'payment_history' => [
            'successful_payments' => 5,
            'failed_payments' => 0,
            'total_paid' => 250000 // ₦2,500.00
        ]
    ],
    [
        'id' => 'CUST_004',
        'email' => 'emma.wilson@freelancer.com',
        'first_name' => 'Emma',
        'last_name' => 'Wilson',
        'company' => 'Freelance Designer',
        'phone' => '+2348765432109',
        'subscription' => [
            'tier' => 'starter',
            'billing_cycle' => 'monthly',
            'start_date' => '2024-08-01',
            'last_billed' => '2024-10-01',
            'next_billing' => '2024-11-01',
            'status' => 'payment_failed' // Needs attention
        ],
        'payment_history' => [
            'successful_payments' => 2,
            'failed_payments' => 1,
            'total_paid' => 10000 // ₦100.00
        ]
    ]
];

// ============================================================================
// Helper Functions
// ============================================================================

function formatCurrency(int $amountInKobo): string {
    return '₦' . number_format($amountInKobo / 100, 2);
}

function printHeader(string $title): void {
    echo "\n" . str_repeat('=', 80) . "\n";
    echo "  " . strtoupper($title) . "\n";
    echo str_repeat('=', 80) . "\n";
}

function printSubHeader(string $title): void {
    echo "\n" . str_repeat('-', 60) . "\n";
    echo "🚀 " . $title . "\n";
    echo str_repeat('-', 60) . "\n";
}

function calculateTax(int $amount, float $taxRate = 0.075): int {
    return (int) round($amount * $taxRate);
}

function isCustomerDueForBilling(array $customer): bool {
    $nextBilling = strtotime($customer['subscription']['next_billing']);
    $today = strtotime(date('Y-m-d'));
    
    // Customer is due if next billing date is today or in the past
    return $nextBilling <= $today;
}

function getSubscriptionAmount(array $customer, array $subscriptionTiers): int {
    $tier = $customer['subscription']['tier'];
    $cycle = $customer['subscription']['billing_cycle'];
    
    if ($cycle === 'annual') {
        return $subscriptionTiers[$tier]['annual_fee'];
    }
    
    return $subscriptionTiers[$tier]['monthly_fee'];
}

// ============================================================================
// Start Recurring Billing Process
// ============================================================================

printHeader("Recurring Billing System - " . date('F Y'));

echo "🔄 Starting automated billing process...\n";
echo "📅 Processing Date: " . date('Y-m-d H:i:s') . "\n";
echo "👥 Total Customers: " . count($customers) . "\n";

// ============================================================================
// Step 1: Identify Customers Due for Billing
// ============================================================================

printSubHeader("Step 1: Identifying Customers Due for Billing");

$customersDue = array_filter($customers, 'isCustomerDueForBilling');
$customersWithFailedPayments = array_filter($customers, function($customer) {
    return $customer['subscription']['status'] === 'payment_failed';
});

echo "📊 Billing Analysis:\n";
echo "   • Customers due for billing: " . count($customersDue) . "\n";
echo "   • Customers with failed payments: " . count($customersWithFailedPayments) . "\n";
echo "   • Active subscriptions: " . count(array_filter($customers, function($c) {
    return $c['subscription']['status'] === 'active';
})) . "\n\n";

if (empty($customersDue) && empty($customersWithFailedPayments)) {
    echo "ℹ️  No customers due for billing today.\n";
    echo "   For demonstration, we'll process all customers anyway.\n\n";
    $customersDue = $customers; // Process all for demo
}

// ============================================================================
// Step 2: Process Billing for Due Customers
// ============================================================================

printSubHeader("Step 2: Processing Billing for Due Customers");

$billingResults = [];
$totalBillingAmount = 0;

foreach (array_merge($customersDue, $customersWithFailedPayments) as $customer) {
    $subscription = $customer['subscription'];
    $tier = $subscriptionTiers[$subscription['tier']];
    
    echo "Processing: {$customer['first_name']} {$customer['last_name']} ({$customer['email']})\n";
    echo "   Company: {$customer['company']}\n";
    echo "   Plan: {$tier['name']} ({$subscription['billing_cycle']})\n";
    echo "   Status: " . ucfirst($subscription['status']) . "\n";
    
    try {
        // Calculate billing amount
        $amount = getSubscriptionAmount($customer, $subscriptionTiers);
        $taxAmount = calculateTax($amount);
        $totalAmount = $amount + $taxAmount;
        
        // Prepare line items
        $lineItems = [
            [
                'name' => "{$tier['name']} - " . ucfirst($subscription['billing_cycle']) . " Subscription",
                'amount' => $amount,
                'quantity' => 1,
                'description' => $tier['description']
            ]
        ];
        
        // Add usage-based charges if applicable
        if ($subscription['tier'] === 'professional' || $subscription['tier'] === 'enterprise') {
            // Simulate additional usage charges
            $extraStorage = random_int(0, 20); // GB
            if ($extraStorage > 0) {
                $storageCharge = $extraStorage * 100; // ₦1.00 per GB
                $lineItems[] = [
                    'name' => 'Additional Storage',
                    'amount' => $storageCharge,
                    'quantity' => $extraStorage,
                    'description' => 'Extra storage beyond plan limit'
                ];
                $totalAmount += $storageCharge;
            }
        }
        
        // Prepare payment request
        $billingPeriod = $subscription['billing_cycle'] === 'annual' ? 
            date('Y') : date('F Y');
            
        $description = "{$tier['name']} - {$billingPeriod}";
        
        // Add retry indicator for failed payments
        if ($subscription['status'] === 'payment_failed') {
            $description .= " (Payment Retry)";
        }
        
        $paymentRequest = $paystack->paymentRequests->create([
            'description' => $description,
            'line_items' => $lineItems,
            'tax' => [
                [
                    'name' => 'VAT (7.5%)',
                    'amount' => calculateTax($totalAmount - $taxAmount)
                ]
            ],
            'customer' => $customer['email'],
            'due_date' => date('Y-m-d', strtotime('+7 days')),
            'send_notification' => true,
            'currency' => 'NGN',
            'metadata' => [
                'customer_id' => $customer['id'],
                'subscription_tier' => $subscription['tier'],
                'billing_cycle' => $subscription['billing_cycle'],
                'billing_period' => $billingPeriod,
                'retry_count' => $subscription['status'] === 'payment_failed' ? 1 : 0
            ]
        ]);
        
        if ($paymentRequest['status']) {
            $requestCode = $paymentRequest['data']['request_code'];
            $finalAmount = $paymentRequest['data']['amount'];
            
            echo "   ✅ Payment request created successfully\n";
            echo "   📋 Request Code: {$requestCode}\n";
            echo "   💰 Amount: " . formatCurrency($finalAmount) . "\n";
            echo "   📧 Notification sent to: {$customer['email']}\n";
            
            $billingResults[] = [
                'customer' => $customer,
                'request_code' => $requestCode,
                'amount' => $finalAmount,
                'status' => 'created',
                'tier' => $tier
            ];
            
            $totalBillingAmount += $finalAmount;
        } else {
            throw new Exception($paymentRequest['message']);
        }
        
    } catch (Exception $e) {
        echo "   ❌ Failed to create payment request: {$e->getMessage()}\n";
        
        $billingResults[] = [
            'customer' => $customer,
            'status' => 'failed',
            'error' => $e->getMessage(),
            'tier' => $tier
        ];
    }
    
    echo "\n";
}

// ============================================================================
// Step 3: Billing Summary and Analytics
// ============================================================================

printSubHeader("Step 3: Billing Summary and Analytics");

$successfulBilling = array_filter($billingResults, function($result) {
    return $result['status'] === 'created';
});

$failedBilling = array_filter($billingResults, function($result) {
    return $result['status'] === 'failed';
});

echo "📊 Billing Results Summary:\n";
echo "   ✅ Successful: " . count($successfulBilling) . " payment requests\n";
echo "   ❌ Failed: " . count($failedBilling) . " attempts\n";
echo "   💰 Total Billed: " . formatCurrency($totalBillingAmount) . "\n";
echo "   📈 Success Rate: " . (count($billingResults) > 0 ? 
    round((count($successfulBilling) / count($billingResults)) * 100, 1) : 0) . "%\n\n";

// Breakdown by subscription tier
$tierBreakdown = [];
foreach ($successfulBilling as $result) {
    $tierName = $result['tier']['name'];
    if (!isset($tierBreakdown[$tierName])) {
        $tierBreakdown[$tierName] = ['count' => 0, 'amount' => 0];
    }
    $tierBreakdown[$tierName]['count']++;
    $tierBreakdown[$tierName]['amount'] += $result['amount'];
}

echo "📋 Revenue Breakdown by Tier:\n";
foreach ($tierBreakdown as $tierName => $data) {
    echo "   • {$tierName}: {$data['count']} customers → " . 
         formatCurrency($data['amount']) . "\n";
}

// ============================================================================
// Step 4: Payment Status Monitoring
// ============================================================================

printSubHeader("Step 4: Payment Status Monitoring");

echo "🔍 Checking payment status for recent payment requests...\n\n";

foreach ($successfulBilling as $result) {
    $customer = $result['customer'];
    $requestCode = $result['request_code'];
    
    echo "Monitoring: {$customer['first_name']} {$customer['last_name']} ({$requestCode})\n";
    
    try {
        $verification = $paystack->paymentRequests->verify($requestCode);
        
        if ($verification['status']) {
            $data = $verification['data'];
            $isPaid = $data['paid'];
            $status = $data['status'];
            $amountPaid = $data['amount_paid'];
            $amountDue = $data['amount'] - $amountPaid;
            
            echo "   📊 Status: " . ucfirst($status) . "\n";
            echo "   💰 Amount: " . formatCurrency($data['amount']) . "\n";
            echo "   ✅ Paid: " . formatCurrency($amountPaid) . "\n";
            echo "   ⏳ Outstanding: " . formatCurrency($amountDue) . "\n";
            echo "   📅 Due Date: {$data['due_date']}\n";
            
            if (isset($data['pdf_url'])) {
                echo "   🔗 Invoice URL: {$data['pdf_url']}\n";
            }
            
            // Automated actions based on payment status
            if (!$isPaid && $status === 'pending') {
                echo "   📧 Automated Action: Payment reminder will be sent in 3 days\n";
            } elseif ($isPaid) {
                echo "   🎉 Automated Action: Subscription renewal confirmed\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ Error checking status: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

// ============================================================================
// Step 5: Account-Wide Analytics
// ============================================================================

printSubHeader("Step 5: Account-Wide Payment Analytics");

try {
    echo "📈 Fetching comprehensive payment analytics...\n\n";
    
    $totals = $paystack->paymentRequests->totals();
    
    if ($totals['status']) {
        $totalsData = $totals['data'];
        
        echo "🏢 Account Overview:\n";
        echo "   📋 Total Payment Requests: {$totalsData['total_requests']}\n";
        echo "   ⏳ Pending Amount: " . formatCurrency($totalsData['pending_amount']) . "\n";
        echo "   ✅ Successful Amount: " . formatCurrency($totalsData['successful_amount']) . "\n";
        echo "   📊 Success Rate: " . ($totalsData['total_requests'] > 0 ? 
            round(($totalsData['successful_requests'] / $totalsData['total_requests']) * 100, 1) : 0) . "%\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error fetching analytics: {$e->getMessage()}\n\n";
}

// ============================================================================
// Step 6: Subscription Management Actions
// ============================================================================

printSubHeader("Step 6: Subscription Management Actions");

echo "🔧 Demonstrating subscription management actions...\n\n";

// Simulate subscription upgrade
$upgradeCustomer = $customers[0]; // Sarah Johnson
echo "📈 Simulating subscription upgrade for {$upgradeCustomer['first_name']} {$upgradeCustomer['last_name']}:\n";
echo "   Current Plan: Professional (₦150/month)\n";
echo "   Upgrade To: Enterprise (₦500/month)\n";
echo "   Prorated Amount: " . formatCurrency(35000) . " (for remaining billing period)\n";

try {
    $upgradeRequest = $paystack->paymentRequests->create([
        'description' => 'Subscription Upgrade - Professional to Enterprise',
        'line_items' => [
            [
                'name' => 'Plan Upgrade (Prorated)',
                'amount' => 35000, // Prorated difference
                'quantity' => 1,
                'description' => 'Upgrade from Professional to Enterprise plan'
            ]
        ],
        'customer' => $upgradeCustomer['email'],
        'due_date' => date('Y-m-d', strtotime('+3 days')),
        'send_notification' => true,
        'metadata' => [
            'type' => 'subscription_upgrade',
            'from_tier' => 'professional',
            'to_tier' => 'enterprise',
            'customer_id' => $upgradeCustomer['id']
        ]
    ]);
    
    if ($upgradeRequest['status']) {
        echo "   ✅ Upgrade payment request created: {$upgradeRequest['data']['request_code']}\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Upgrade request failed: {$e->getMessage()}\n";
}

// ============================================================================
// Step 7: Failed Payment Recovery
// ============================================================================

printSubHeader("Step 7: Failed Payment Recovery Process");

$failedPaymentCustomers = array_filter($customers, function($customer) {
    return $customer['subscription']['status'] === 'payment_failed';
});

echo "🔄 Processing failed payment recovery...\n\n";

foreach ($failedPaymentCustomers as $customer) {
    echo "Recovering payment for: {$customer['first_name']} {$customer['last_name']}\n";
    echo "   Failed payments: {$customer['payment_history']['failed_payments']}\n";
    echo "   Last successful payment: " . formatCurrency($customer['payment_history']['total_paid']) . "\n";
    
    // Implement recovery strategy
    $failedCount = $customer['payment_history']['failed_payments'];
    
    if ($failedCount === 1) {
        echo "   🔄 Strategy: Immediate retry with email notification\n";
    } elseif ($failedCount === 2) {
        echo "   📞 Strategy: Personal outreach + payment plan option\n";
    } else {
        echo "   ⚠️  Strategy: Account suspension warning\n";
    }
    
    echo "\n";
}

// ============================================================================
// Completion Summary
// ============================================================================

printHeader("Recurring Billing Process Completed");

echo "🎉 Billing cycle completed successfully!\n\n";

echo "📊 Final Summary:\n";
echo "   • Total customers processed: " . count($billingResults) . "\n";
echo "   • Payment requests created: " . count($successfulBilling) . "\n";
echo "   • Total revenue billed: " . formatCurrency($totalBillingAmount) . "\n";
echo "   • Processing time: " . number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2) . " seconds\n\n";

echo "🔧 Next Steps in Production:\n";
echo "   • Schedule this script to run monthly/daily via cron\n";
echo "   • Set up webhook handlers for payment notifications\n";
echo "   • Implement automatic subscription renewals\n";
echo "   • Create customer portal for subscription management\n";
echo "   • Set up automated dunning management for failed payments\n";
echo "   • Generate detailed analytics and reporting dashboards\n\n";

echo "📚 Integration Examples:\n";
echo "   • webhook_handler.php - Handle real-time payment events\n";
echo "   • subscription_portal.php - Customer self-service portal\n";
echo "   • analytics_dashboard.php - Revenue and subscription analytics\n";
echo "   • dunning_management.php - Automated failed payment recovery\n\n";

// ============================================================================
// Development Notes
// ============================================================================

if ($secretKey === 'sk_test_your_secret_key_here') {
    echo "⚠️  DEVELOPMENT REMINDER:\n";
    echo "   Replace the API key with your actual test key for full functionality\n";
    echo "   Get your key from: https://dashboard.paystack.com/#/settings/developer\n\n";
}

echo "🧪 Testing Information:\n";
echo "   • This example uses test mode (safe for development)\n";
echo "   • Use test card 4084084084084081 for successful payments\n";
echo "   • Monitor results in your Paystack dashboard\n";
echo "   • Test different billing cycles and scenarios\n\n";

echo "📖 Documentation Links:\n";
echo "   • Payment Requests API: https://paystack.com/docs/api/payment-request/\n";
echo "   • Subscription Best Practices: docs/advanced-usage.md\n";
echo "   • Error Handling Guide: docs/troubleshooting.md\n";
echo "   • SDK GitHub Repository: https://github.com/starfolksoftware/paystack-php\n";
