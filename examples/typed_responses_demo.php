<?php

/**
 * Response DTOs Demo - Phase 2 Implementation
 * 
 * This example demonstrates the new typed response DTOs available in v2.x.
 * Instead of working with generic arrays, you now get strongly-typed objects
 * with helpful methods and better IDE support.
 * 
 * What you'll learn:
 * - Using typed response DTOs instead of arrays
 * - Accessing structured data with autocomplete
 * - Working with pagination and collections
 * - Using helper methods on response objects
 * - Backward compatibility with array-based responses
 * 
 * Before running this example:
 * 1. Get your test secret key from https://dashboard.paystack.com/#/settings/developer
 * 2. Replace 'sk_test_your_secret_key_here' with your actual test key
 * 3. Run: php examples/typed_responses_demo.php
 */

// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Import the Paystack Client and Response types
use StarfolkSoftware\Paystack\Client as PaystackClient;
use StarfolkSoftware\Paystack\Response\Customer\CustomerData;
use StarfolkSoftware\Paystack\Response\Transaction\TransactionData;
use StarfolkSoftware\Paystack\Response\PaymentRequest\PaymentRequestData;

// ============================================================================
// Configuration
// ============================================================================

$secretKey = 'sk_test_your_secret_key_here';

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => $secretKey,
]);

// ============================================================================
// Response DTOs Overview
// ============================================================================

echo "🚀 Demonstrating Typed Response DTOs in Paystack PHP SDK v2.x\n\n";

echo "✨ **Before (v1.x)**: Generic arrays with no type safety\n";
echo "   \$customer = \$paystack->customers()->create([...]);\n";
echo "   echo \$customer['data']['email']; // No autocomplete, prone to typos\n\n";

echo "✨ **After (v2.x)**: Strongly-typed DTOs with full IDE support\n";
echo "   \$response = \$paystack->customers()->createTyped([...]);\n";
echo "   \$customer = \$response->getData(); // Returns CustomerData object\n";
echo "   echo \$customer->email; // Full autocomplete and type safety\n\n";

// ============================================================================
// Example 1: Customer Management with Typed Responses
// ============================================================================

echo "📊 **Example 1: Customer Management**\n";
echo "Creating a customer and accessing typed data...\n\n";

try {
    // OLD WAY: Array-based response (still available)
    echo "1️⃣  Old array-based approach:\n";
    $arrayResponse = $paystack->customers()->create([
        'email' => 'john.doe@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '+2348123456789',
    ]);
    
    if ($arrayResponse['status']) {
        $customerArray = $arrayResponse['data'];
        echo "   ✅ Customer created: {$customerArray['email']}\n";
        echo "   📝 Customer code: {$customerArray['customer_code']}\n";
        echo "   ⚠️  Type: array - No autocomplete, manual array access\n\n";
    }

    // NEW WAY: Typed DTO response
    echo "2️⃣  New typed DTO approach:\n";
    $typedResponse = $paystack->customers()->createTyped([
        'email' => 'jane.smith@example.com',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'phone' => '+2348987654321',
    ]);
    
    if ($typedResponse->isSuccessful()) {
        /** @var CustomerData $customer */
        $customer = $typedResponse->getData();
        
        echo "   ✅ Customer created: {$customer->email}\n";
        echo "   📝 Customer code: {$customer->customer_code}\n";
        echo "   👤 Full name: {$customer->getFullName()}\n";
        echo "   📱 Phone: {$customer->phone}\n";
        echo "   🔒 Type: CustomerData - Full autocomplete and type safety!\n\n";
        
        // Use helper methods
        echo "   **Helper Methods**:\n";
        echo "   • Has authorizations: " . ($customer->hasAuthorizations() ? 'Yes' : 'No') . "\n";
        echo "   • Has subscriptions: " . ($customer->hasSubscriptions() ? 'Yes' : 'No') . "\n";
        echo "   • Risk action: {$customer->getRiskAction()}\n";
        echo "   • Is identified: " . ($customer->isIdentified() ? 'Yes' : 'No') . "\n\n";
    }

} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// Example 2: Transaction Initialization with Typed Response
// ============================================================================

echo "💳 **Example 2: Transaction Initialization**\n";
echo "Initializing a transaction and accessing typed data...\n\n";

try {
    // NEW WAY: Typed initialization response
    $initResponse = $paystack->transactions()->initializeTyped([
        'email' => 'customer@example.com',
        'amount' => '50000', // 500 NGN in kobo
        'currency' => 'NGN',
        'reference' => 'TXN_' . uniqid(),
        'callback_url' => 'https://example.com/callback',
    ]);
    
    if ($initResponse->isInitialized()) {
        echo "   ✅ Transaction initialized successfully!\n\n";
        echo "   **Typed Access**:\n";
        echo "   • Authorization URL: {$initResponse->getAuthorizationUrl()}\n";
        echo "   • Access Code: {$initResponse->getAccessCode()}\n";
        echo "   • Reference: {$initResponse->getReference()}\n\n";
        
        echo "   **Type Safety**: TransactionInitializeResponse\n";
        echo "   • No array key typos possible\n";
        echo "   • IDE shows all available methods\n";
        echo "   • Helper method isInitialized() included\n\n";
        
        // Store reference for verification example
        $transactionRef = $initResponse->getReference();
    }

} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// Example 3: Transaction Verification with Typed Response
// ============================================================================

if (isset($transactionRef)) {
    echo "🔍 **Example 3: Transaction Verification**\n";
    echo "Verifying transaction with typed response...\n\n";
    
    try {
        $verifyResponse = $paystack->transactions()->verifyTyped($transactionRef);
        
        if ($verifyResponse->isSuccessful()) {
            /** @var TransactionData $transaction */
            $transaction = $verifyResponse->getData();
            
            echo "   ✅ Transaction verified!\n\n";
            echo "   **Transaction Details**:\n";
            echo "   • Reference: {$transaction->reference}\n";
            echo "   • Status: {$transaction->status}\n";
            echo "   • Amount: {$transaction->getFormattedAmount()}\n";
            echo "   • Channel: {$transaction->channel}\n";
            echo "   • Customer: {$transaction->getCustomerEmail()}\n\n";
            
            echo "   **Helper Methods**:\n";
            echo "   • Is successful: " . ($transaction->isSuccessful() ? 'Yes' : 'No') . "\n";
            echo "   • Is pending: " . ($transaction->isPending() ? 'Yes' : 'No') . "\n";
            echo "   • Amount in major unit: ₦{$transaction->getAmountInMajorUnit()}\n";
            echo "   • Total fees: ₦" . ($transaction->getTotalFees() / 100) . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

// ============================================================================
// Example 4: Paginated Lists with Typed Responses
// ============================================================================

echo "📋 **Example 4: Paginated Customer List**\n";
echo "Fetching customers with pagination support...\n\n";

try {
    $customersResponse = $paystack->customers()->allTyped([
        'perPage' => 5,
        'page' => 1,
    ]);
    
    if ($customersResponse->isSuccessful()) {
        $customers = $customersResponse->getCustomers();
        $pagination = $customersResponse->getPagination();
        
        echo "   ✅ Retrieved {$customersResponse->getTotal()} total customers\n";
        echo "   📄 Showing " . count($customers) . " customers on this page\n\n";
        
        echo "   **Customer List** (strongly typed):\n";
        foreach ($customers as $index => $customer) {
            echo "   " . ($index + 1) . ". {$customer->getFullName()} ({$customer->email})\n";
            echo "      Code: {$customer->customer_code}\n";
            echo "      Created: {$customer->created_at?->format('Y-m-d H:i:s')}\n";
        }
        echo "\n";
        
        if ($pagination) {
            echo "   **Pagination Info**:\n";
            echo "   • Total: {$pagination->total}\n";
            echo "   • Per Page: {$pagination->perPage}\n";
            echo "   • Current Page: {$pagination->page}\n";
            echo "   • Total Pages: {$pagination->pageCount}\n";
            echo "   • Has next: " . ($pagination->hasNextPage() ? 'Yes' : 'No') . "\n";
            echo "   • Has previous: " . ($pagination->hasPreviousPage() ? 'Yes' : 'No') . "\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// Example 5: Payment Requests with Typed Responses
// ============================================================================

echo "💰 **Example 5: Payment Request with Typed Response**\n";
echo "Creating a payment request and accessing structured data...\n\n";

try {
    $prResponse = $paystack->paymentRequests()->createTyped([
        'description' => 'Website Development Services',
        'line_items' => [
            [
                'name' => 'Frontend Development',
                'amount' => 50000,
                'quantity' => 1
            ],
            [
                'name' => 'Backend Development',
                'amount' => 75000,
                'quantity' => 1
            ]
        ],
        'tax' => [
            [
                'name' => 'VAT (7.5%)',
                'amount' => 9375
            ]
        ],
        'customer' => 'client@example.com',
        'due_date' => date('Y-m-d', strtotime('+30 days')),
        'currency' => 'NGN',
    ]);
    
    if ($prResponse->isSuccessful()) {
        /** @var PaymentRequestData $paymentRequest */
        $paymentRequest = $prResponse->getData();
        
        echo "   ✅ Payment request created!\n\n";
        echo "   **Payment Request Details**:\n";
        echo "   • Request Code: {$paymentRequest->request_code}\n";
        echo "   • Description: {$paymentRequest->description}\n";
        echo "   • Amount: {$paymentRequest->getFormattedAmount()}\n";
        echo "   • Status: {$paymentRequest->status}\n";
        echo "   • Due Date: {$paymentRequest->due_date}\n\n";
        
        echo "   **Helper Methods**:\n";
        echo "   • Is pending: " . ($paymentRequest->isPending() ? 'Yes' : 'No') . "\n";
        echo "   • Is paid: " . ($paymentRequest->isPaid() ? 'Yes' : 'No') . "\n";
        echo "   • Has due date: " . ($paymentRequest->hasDueDate() ? 'Yes' : 'No') . "\n";
        echo "   • Is overdue: " . ($paymentRequest->isOverdue() ? 'Yes' : 'No') . "\n";
        echo "   • Line items total: ₦" . ($paymentRequest->getLineItemsTotal() / 100) . "\n";
        echo "   • Tax total: ₦" . ($paymentRequest->getTaxTotal() / 100) . "\n\n";
        
        if ($paymentRequest->invoice_url) {
            echo "   🔗 Invoice URL: {$paymentRequest->invoice_url}\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// Example 6: Backward Compatibility
// ============================================================================

echo "🔄 **Example 6: Backward Compatibility**\n";
echo "Both old and new methods work side by side!\n\n";

try {
    // Old array-based method (still works)
    $oldResponse = $paystack->customers()->all(['perPage' => 2]);
    $oldCount = isset($oldResponse['data']) && is_array($oldResponse['data']) ? count($oldResponse['data']) : 0;
    echo "   ✅ Old method: Returns array with {$oldCount} customers\n";
    
    // New typed method
    $newResponse = $paystack->customers()->allTyped(['perPage' => 2]);
    echo "   ✅ New method: Returns CustomerListResponse with " . count($newResponse->getCustomers()) . " customers\n\n";
    
    echo "   **Migration Strategy**:\n";
    echo "   • Use *Typed() methods for new code\n";
    echo "   • Keep existing code unchanged\n";
    echo "   • Gradually migrate as you refactor\n";
    echo "   • Both approaches are fully supported\n\n";
    
} catch (Exception $e) {
    echo "   ⚠️  Note: API calls require valid credentials\n";
    echo "   Both old and new methods work identically with real API keys\n\n";
    
    echo "   **Migration Strategy**:\n";
    echo "   • Use *Typed() methods for new code\n";
    echo "   • Keep existing code unchanged\n";
    echo "   • Gradually migrate as you refactor\n";
    echo "   • Both approaches are fully supported\n\n";
}

// ============================================================================
// Benefits Summary
// ============================================================================

echo "🎯 **Benefits of Response DTOs**:\n\n";

echo "✅ **Type Safety**:\n";
echo "   • Catch errors at development time, not runtime\n";
echo "   • No more typos in array keys\n";
echo "   • Clear property types in IDE\n\n";

echo "✅ **Better IDE Support**:\n";
echo "   • Full autocomplete for all properties\n";
echo "   • Inline documentation tooltips\n";
echo "   • Jump to definition support\n\n";

echo "✅ **Helper Methods**:\n";
echo "   • getFormattedAmount() for currency formatting\n";
echo "   • isSuccessful(), isPending(), etc. for status checks\n";
echo "   • getFullName(), getCustomerEmail() for convenience\n";
echo "   • Type-specific utility functions\n\n";

echo "✅ **Structured Data**:\n";
echo "   • DateTimeImmutable for dates (not strings)\n";
echo "   • Nested objects for related data\n";
echo "   • Proper types for integers, booleans, etc.\n\n";

echo "✅ **Pagination Support**:\n";
echo "   • Dedicated PaginationMeta class\n";
echo "   • Helper methods: hasNextPage(), hasPreviousPage()\n";
echo "   • Easy to implement infinite scroll\n\n";

// ============================================================================
// Available Typed Methods
// ============================================================================

echo "📚 **Available Typed Methods**:\n\n";

$typedMethods = [
    'Customer API' => [
        'createTyped()' => 'Create customer with typed response',
        'allTyped()' => 'List customers with pagination',
    ],
    'Transaction API' => [
        'initializeTyped()' => 'Initialize with TransactionInitializeResponse',
        'verifyTyped()' => 'Verify with TransactionData',
        'allTyped()' => 'List transactions with pagination',
    ],
    'PaymentRequest API' => [
        'createTyped()' => 'Create with PaymentRequestData',
        'allTyped()' => 'List payment requests with pagination',
    ],
];

foreach ($typedMethods as $api => $methods) {
    echo "**{$api}**:\n";
    foreach ($methods as $method => $description) {
        echo "   • {$method}: {$description}\n";
    }
    echo "\n";
}

// ============================================================================
// Next Steps
// ============================================================================

echo "🎉 Demonstration completed!\n\n";

echo "**Next Steps**:\n";
echo "• Start using *Typed() methods in your new code\n";
echo "• Explore the DTO classes for available methods\n";
echo "• Migrate existing code gradually\n";
echo "• Look forward to Phase 3: Request DTOs\n\n";

echo "📚 **Documentation**:\n";
echo "• Response DTOs: src/Response/\n";
echo "• API Reference: docs/api-reference.md\n";
echo "• Getting Started: docs/getting-started.md\n\n";

// Show warning if using default API key
if ($secretKey === 'sk_test_your_secret_key_here') {
    echo "⚠️  **NOTE**: This demo uses a placeholder API key.\n";
    echo "   For actual API calls, replace with your real test key from:\n";
    echo "   https://dashboard.paystack.com/#/settings/developer\n\n";
}

echo "🚀 **Enjoy enhanced type safety with Response DTOs!**\n";