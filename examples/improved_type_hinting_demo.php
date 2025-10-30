<?php

/**
 * Improved Type Hinting Demo
 * 
 * This example demonstrates the improved type hinting available in v2.x
 * after making Client API methods public. This provides better IDE support
 * and autocomplete functionality.
 * 
 * What's improved:
 * - Direct method access with proper return types
 * - Better IDE autocomplete and intellisense
 * - Clear method signatures visible in IDEs
 * - No need to rely on magic __get() method
 * 
 * Before running this example:
 * 1. Get your test secret key from https://dashboard.paystack.com/#/settings/developer
 * 2. Replace 'sk_test_your_secret_key_here' with your actual test key
 * 3. Run: php examples/improved_type_hinting_demo.php
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
 */
$secretKey = 'sk_test_your_secret_key_here';

// Initialize the Paystack client
$paystack = new PaystackClient([
    'secretKey' => $secretKey,
]);

// ============================================================================
// Improved Type Hinting Examples
// ============================================================================

echo "🚀 Demonstrating Improved Type Hinting in Paystack PHP SDK v2.x\n\n";

echo "✨ **Before (v1.x)**: Magic property access\n";
echo "   \$paystack->transactions; // IDE doesn't know the type\n";
echo "   \$paystack->customers;    // Limited autocomplete\n\n";

echo "✨ **After (v2.x)**: Direct method access with return types\n";
echo "   \$paystack->transactions(); // Returns API\\Transaction\n";
echo "   \$paystack->customers();    // Returns API\\Customer\n\n";

// ============================================================================
// Type-Safe API Access Examples
// ============================================================================

try {
    // Transaction API - Now with proper return type (API\Transaction)
    echo "📊 **Transaction API** - Direct method access:\n";
    $transactionApi = $paystack->transactions();
    echo "   Type: " . get_class($transactionApi) . "\n";
    echo "   Available methods: initialize(), verify(), all(), find(), etc.\n\n";

    // Customer API - IDE now provides full autocomplete
    echo "👤 **Customer API** - Better IDE support:\n";
    $customerApi = $paystack->customers();
    echo "   Type: " . get_class($customerApi) . "\n";
    echo "   Available methods: create(), all(), find(), update(), etc.\n\n";

    // Payment Request API - Clear method signatures
    echo "💳 **Payment Request API** - Enhanced intellisense:\n";
    $paymentRequestApi = $paystack->paymentRequests();
    echo "   Type: " . get_class($paymentRequestApi) . "\n";
    echo "   Available methods: create(), all(), fetch(), verify(), etc.\n\n";

    // Plan API - Full type information
    echo "📋 **Plan API** - Complete type safety:\n";
    $planApi = $paystack->plans();
    echo "   Type: " . get_class($planApi) . "\n";
    echo "   Available methods: create(), all(), find(), update()\n\n";

    // Subscription API - Professional developer experience
    echo "🔄 **Subscription API** - Professional DX:\n";
    $subscriptionApi = $paystack->subscriptions();
    echo "   Type: " . get_class($subscriptionApi) . "\n";
    echo "   Available methods: create(), all(), find(), enable(), disable()\n\n";

} catch (Exception $e) {
    echo "❌ Error demonstrating type hinting: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// Backward Compatibility
// ============================================================================

echo "🔄 **Backward Compatibility**: Old magic property access still works!\n\n";

try {
    // The old way still works for backward compatibility
    $oldWayTransaction = $paystack->transactions; // Magic __get() still functional
    $newWayTransaction = $paystack->transactions(); // New direct method call
    
    echo "   Old way type: " . get_class($oldWayTransaction) . "\n";
    echo "   New way type: " . get_class($newWayTransaction) . "\n";
    echo "   ✅ Both return the same API\\Transaction instance\n\n";
    
} catch (Exception $e) {
    echo "❌ Error checking backward compatibility: " . $e->getMessage() . "\n\n";
}

// ============================================================================
// IDE Benefits Summary
// ============================================================================

echo "🎯 **IDE Benefits Summary**:\n\n";

echo "✅ **Autocomplete**: IDEs now show all available API methods\n";
echo "✅ **Return Types**: Clear return type information (API\\Customer, etc.)\n";
echo "✅ **Method Signatures**: Full parameter and return type visibility\n";
echo "✅ **Refactoring**: Better support for automated refactoring tools\n";
echo "✅ **Documentation**: Inline PHPDoc comments visible in IDE tooltips\n";
echo "✅ **Error Prevention**: Catch typos and method name errors at development time\n\n";

// ============================================================================
// Migration Guide
// ============================================================================

echo "📚 **Migration Guide** (v1.x → v2.x):\n\n";

echo "**Recommended**: Use the new direct method calls\n";
echo "   // Old (still works)\n";
echo "   \$customers = \$paystack->customers->all([]);\n\n";
echo "   // New (recommended)\n";
echo "   \$customers = \$paystack->customers()->all([]);\n\n";

echo "**Benefits of migrating**:\n";
echo "   • Better IDE support and autocomplete\n";
echo "   • Clear method signatures and return types\n";
echo "   • Future-proof code for upcoming PHP versions\n";
echo "   • Enhanced developer experience\n\n";

// ============================================================================
// All Available APIs
// ============================================================================

echo "📋 **All Available APIs** (with improved type hinting):\n\n";

$apis = [
    'transactions()' => 'Transaction management and processing',
    'customers()' => 'Customer management and profiles',
    'paymentRequests()' => 'Payment requests and invoicing',
    'plans()' => 'Subscription plans and billing',
    'subscriptions()' => 'Subscription management',
    'invoices()' => 'Invoice creation and management',
    'transfers()' => 'Money transfers and payouts',
    'transferRecipients()' => 'Transfer recipient management',
    'transferControl()' => 'Transfer controls and settings',
    'splits()' => 'Revenue splitting and settlements',
    'subaccounts()' => 'Subaccount management',
    'products()' => 'Product catalog management',
    'pages()' => 'Payment pages and hosted checkout',
    'charges()' => 'Direct card charging',
    'refunds()' => 'Refund processing',
    'disputes()' => 'Dispute management',
    'settlements()' => 'Settlement tracking',
    'bulkCharges()' => 'Bulk charging operations',
    'verification()' => 'Identity and account verification',
    'miscellaneous()' => 'Utility endpoints (banks, countries)',
    'integration()' => 'Integration settings',
    'terminals()' => 'POS terminal management',
    'virtualTerminals()' => 'Virtual terminal management',
    'dedicatedVirtualAccounts()' => 'Virtual account management',
    'applePay()' => 'Apple Pay integration',
    'directDebit()' => 'Direct debit management',
];

foreach ($apis as $method => $description) {
    echo "   • \$paystack->{$method}: {$description}\n";
}

echo "\n";

// ============================================================================
// Next Steps
// ============================================================================

echo "🎉 Improved type hinting demonstration completed!\n\n";

echo "**Next Steps**:\n";
echo "• Update your IDE/editor for optimal autocomplete support\n";
echo "• Migrate existing code to use new method calls (optional but recommended)\n";
echo "• Explore upcoming phases: Response DTOs, Request objects, and more!\n";
echo "• Check out the API reference for detailed method documentation\n\n";

echo "📚 **Documentation**:\n";
echo "• Getting Started: docs/getting-started.md\n";
echo "• API Reference: docs/api-reference.md\n";
echo "• Advanced Usage: docs/advanced-usage.md\n\n";

// Show warning if using default API key
if ($secretKey === 'sk_test_your_secret_key_here') {
    echo "⚠️  **NOTE**: This demo uses a placeholder API key.\n";
    echo "   For actual API calls, replace with your real test key from:\n";
    echo "   https://dashboard.paystack.com/#/settings/developer\n\n";
}

echo "🚀 **Happy coding with improved type safety!**\n";