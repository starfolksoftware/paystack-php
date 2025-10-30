# Paystack PHP SDK Examples

This directory contains comprehensive examples demonstrating real-world usage patterns of the Paystack PHP SDK. Each example is thoroughly documented with explanations, best practices, and production-ready code patterns.

## 📋 Table of Contents

- [Quick Start](#quick-start)
- [Available Examples](#available-examples)
- [How to Run Examples](#how-to-run-examples)
- [Example Categories](#example-categories)
- [Testing Information](#testing-information)
- [Production Considerations](#production-considerations)

## 🚀 Quick Start

### Prerequisites

1. **PHP 8.1 or higher**
2. **Composer** (for dependency management)
3. **Paystack Account** ([sign up here](https://dashboard.paystack.com/signup))
4. **Test API Keys** from your Paystack dashboard

### Setup

1. **Install the SDK:**
   ```bash
   composer require starfolksoftware/paystack-php
   ```

2. **Get your API keys:**
   - Log into your [Paystack Dashboard](https://dashboard.paystack.com)
   - Navigate to **Settings** → **API Keys & Webhooks**
   - Copy your **Test Secret Key** (starts with `sk_test_`)

3. **Update the examples:**
   - Replace `'sk_test_your_secret_key_here'` with your actual test key
   - Never use live keys for testing!

## 📚 Available Examples

### SDK Feature Demonstrations

#### 1. **improved_type_hinting_demo.php** - Enhanced Type Safety (v2.x - Phase 1)
**Perfect for:** Developers migrating to v2.x, IDE optimization, better development experience

**What you'll learn:**
- Improved type hinting and IDE support in v2.x
- Direct method access vs magic property access
- Better autocomplete and intellisense features
- Backward compatibility with v1.x patterns

**Key Features:**
- ✅ Demonstrates all API endpoints with proper types
- ✅ Shows migration path from v1.x to v2.x
- ✅ Backward compatibility examples
- ✅ IDE benefits and developer experience improvements

```bash
php examples/improved_type_hinting_demo.php
```

#### 2. **typed_responses_demo.php** - Response DTOs (v2.x - Phase 2)
**Perfect for:** Developers wanting structured, type-safe API responses

**What you'll learn:**
- Using response DTOs instead of generic arrays
- Accessing typed data with full IDE support
- Working with pagination and helper methods
- Converting between array and typed responses
- Backward compatibility strategies

**Key Features:**
- ✅ Customer, Transaction, and PaymentRequest DTOs
- ✅ Strongly-typed response objects with autocomplete
- ✅ Helper methods for common operations
- ✅ DateTimeImmutable for dates, structured data
- ✅ Pagination support with PaginationMeta
- ✅ Shows old vs new approach side-by-side

```bash
php examples/typed_responses_demo.php
```

### Core Payment Examples

#### 3. **simple_payment_request.php** - Beginner-Friendly Introduction
**Perfect for:** First-time users, basic invoice creation, learning the fundamentals

**What you'll learn:**
- Creating payment requests with line items and taxes
- Handling API responses and error checking
- Retrieving and displaying payment request information
- Understanding the Paystack payment flow

**Key Features:**
- ✅ Comprehensive error handling
- ✅ Detailed response explanations
- ✅ Step-by-step process breakdown
- ✅ Production-ready code patterns

```bash
php examples/simple_payment_request.php
```

#### 4. **payment_request_demo.php** - Complete API Showcase
**Perfect for:** Developers who need comprehensive API coverage

**What you'll learn:**
- All payment request API methods and their parameters
- Advanced features like drafts, finalization, and archiving
- Payment verification and status monitoring
- Notification management and customer communication

**Key Features:**
- ✅ Complete API method coverage
- ✅ Advanced parameter usage
- ✅ Real-world error scenarios
- ✅ Best practice implementations

```bash
php examples/payment_request_demo.php
```

### Business Workflow Examples

#### 5. **invoice_workflow.php** - Professional Invoice Management
**Perfect for:** Service providers, freelancers, B2B businesses

**What you'll learn:**
- Complete invoice lifecycle management
- Customer profile management and updates
- Progressive billing (adding items over time)
- Professional invoice formatting and presentation
- Payment monitoring and follow-up processes

**Key Features:**
- ✅ End-to-end invoice workflow
- ✅ Customer management integration
- ✅ Automated notification systems
- ✅ Revenue tracking and analytics
- ✅ Professional formatting and presentation

```bash
php examples/invoice_workflow.php
```

#### 6. **recurring_billing.php** - Subscription & Recurring Payments
**Perfect for:** SaaS platforms, subscription services, membership sites

**What you'll learn:**
- Multi-tier subscription management
- Automated billing cycle processing
- Failed payment recovery strategies
- Subscription upgrades and downgrades
- Revenue analytics and reporting
- Customer retention management

**Key Features:**
- ✅ Comprehensive subscription management
- ✅ Automated billing processes
- ✅ Advanced analytics and reporting
- ✅ Failed payment recovery systems
- ✅ Multi-tier pricing strategies
- ✅ Production-ready automation

```bash
php examples/recurring_billing.php
```

## 🎯 Example Categories

### By Complexity Level

#### **Beginner Level**
- `improved_type_hinting_demo.php` - v2.x type safety features (Phase 1)
- `typed_responses_demo.php` - Response DTOs (Phase 2)
- `simple_payment_request.php` - Basic payment request creation
- Individual API method demonstrations

#### **Intermediate Level** 
- `payment_request_demo.php` - Complete API coverage
- `invoice_workflow.php` - Business workflow implementation

#### **Advanced Level**
- `recurring_billing.php` - Complex subscription management
- Production-ready automation systems

### By Use Case

#### **E-commerce & Retail**
- Simple product payments
- Shopping cart integration
- Order management workflows

#### **Service Businesses**
- Invoice generation and management
- Project-based billing
- Time-based service charging

#### **SaaS & Subscriptions**
- Recurring billing automation
- Subscription tier management
- Usage-based billing

#### **Marketplaces**
- Split payment scenarios
- Multi-vendor management
- Commission handling

## 🏃‍♂️ How to Run Examples

### Method 1: Direct Execution
```bash
# Run from the project root directory
php examples/improved_type_hinting_demo.php
php examples/typed_responses_demo.php
php examples/simple_payment_request.php
php examples/invoice_workflow.php
php examples/recurring_billing.php
php examples/payment_request_demo.php
```

### Method 2: Interactive Testing
```bash
# Create a test script
cp examples/simple_payment_request.php my_test.php
# Edit my_test.php with your API key
php my_test.php
```

### Method 3: Integration Testing
```bash
# Include in your application
require_once 'vendor/autoload.php';
require_once 'examples/payment_functions.php';
```

## 🧪 Testing Information

### Test Environment Setup

**Safe Testing:** All examples use test mode by default
- No real money transactions
- Safe for experimentation
- Full API feature access

**Test Credentials:**
```php
// Test Secret Key (replace with yours)
$secretKey = 'sk_test_your_actual_test_key_here';

// Test Card Numbers for Different Scenarios
$testCards = [
    'successful_payment' => '4084084084084081',
    'insufficient_funds' => '4084084084084107', 
    'invalid_pin' => '4084084084084099',
    'timeout' => '4084084084084016'
];
```

### Monitoring and Debugging

**Paystack Dashboard:**
- Monitor all test transactions in real-time
- View detailed payment logs and analytics
- Test webhook configurations

**Debug Output:**
- All examples include comprehensive logging
- Clear success/error indicators
- Detailed API response information

## 🏭 Production Considerations

### Security Best Practices

**API Key Management:**
```php
// ✅ Good: Environment variables
$secretKey = $_ENV['PAYSTACK_SECRET_KEY'];

// ❌ Bad: Hardcoded keys
$secretKey = 'sk_test_actual_key_here';
```

**Error Handling:**
- Implement comprehensive try-catch blocks
- Log errors for debugging and monitoring
- Provide user-friendly error messages
- Handle network timeouts and retries

### Performance Optimization

**Connection Management:**
- Reuse HTTP connections when possible
- Implement connection pooling for high-volume applications
- Use appropriate timeout settings

**Caching Strategies:**
- Cache frequently accessed data (banks, plans)
- Implement smart cache invalidation
- Use Redis or Memcached for distributed caching

### Monitoring and Analytics

**Webhook Implementation:**
- Set up real-time payment notifications
- Implement signature verification
- Handle duplicate events gracefully

**Analytics and Reporting:**
- Track payment success rates
- Monitor customer behavior patterns
- Generate business intelligence reports

## 🔧 Integration Patterns

### Framework Integration

**Laravel:**
```php
// Service Provider integration
// Controller method examples
// Eloquent model relationships
```

**Symfony:**
```php
// Service container configuration
// Event system integration
// Doctrine entity examples
```

**CodeIgniter:**
```php
// Library integration
// Controller patterns
// Database integration
```

### Database Integration

**Customer Management:**
```sql
-- Customer table structure
-- Payment history tracking
-- Subscription management
```

**Transaction Logging:**
```sql
-- Transaction records
-- Audit trails
-- Reconciliation support
```

## 📖 Additional Resources

### Documentation Links
- **[Getting Started Guide](../docs/getting-started.md)** - Complete setup and first payment
- **[API Reference](../docs/api-reference.md)** - Detailed method documentation
- **[Advanced Usage](../docs/advanced-usage.md)** - Complex patterns and optimizations
- **[Troubleshooting Guide](../docs/troubleshooting.md)** - Common issues and solutions

### Official Paystack Resources
- **[Paystack API Documentation](https://paystack.com/docs/api/)**
- **[Test Payment Cards](https://paystack.com/docs/payments/test-payments/)**
- **[Webhook Events Reference](https://paystack.com/docs/payments/webhooks/)**
- **[Dashboard Tutorial](https://paystack.com/docs/get-started/)**

### Community and Support
- **[GitHub Repository](https://github.com/starfolksoftware/paystack-php)**
- **[Issue Tracker](https://github.com/starfolksoftware/paystack-php/issues)**
- **[SDK Documentation](../docs/)**
- **[Email Support](mailto:contact@starfolksoftware.com)**

## 🎯 Next Steps

After exploring these examples:

1. **Choose your use case** - Pick the example closest to your needs
2. **Customize the code** - Adapt the examples to your specific requirements
3. **Implement webhooks** - Set up real-time payment notifications
4. **Test thoroughly** - Use test cards and monitor the dashboard
5. **Go live** - Switch to live API keys for production

### Common Integration Paths

**E-commerce Integration:**
1. Start with `simple_payment_request.php`
2. Implement customer management
3. Add order tracking and fulfillment
4. Set up automated receipts and notifications

**SaaS Subscription Service:**
1. Study `recurring_billing.php` thoroughly
2. Implement subscription management portal
3. Set up automated billing cycles
4. Add usage tracking and billing
5. Implement dunning management for failed payments

**Service-Based Business:**
1. Begin with `invoice_workflow.php`
2. Customize invoice templates and branding
3. Integrate with project management systems
4. Automate follow-up and reminder systems
5. Set up accounting system integration

## 💡 Tips for Success

1. **Start small** - Begin with basic examples and gradually add complexity
2. **Test extensively** - Use all available test scenarios before going live
3. **Monitor actively** - Set up comprehensive logging and monitoring
4. **Follow best practices** - Implement security, error handling, and performance optimizations
5. **Stay updated** - Follow the repository for updates and new features

---

**Happy coding! 🚀**

For additional help, check out our [troubleshooting guide](../docs/troubleshooting.md) or [open an issue](https://github.com/starfolksoftware/paystack-php/issues) on GitHub.
