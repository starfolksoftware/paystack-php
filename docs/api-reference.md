# API Reference

This document provides a comprehensive reference for all Paystack PHP SDK classes and methods.

## Table of Contents

- [Client Configuration](#client-configuration)
- [Core Resources](#core-resources)
  - [Transactions](#transactions)
  - [Customers](#customers)
  - [Payment Requests](#payment-requests)
  - [Plans](#plans)
  - [Subscriptions](#subscriptions)
- [Transfer Resources](#transfer-resources)
  - [Transfers](#transfers)
  - [Transfer Recipients](#transfer-recipients)
  - [Transfer Control](#transfer-control)
- [Advanced Resources](#advanced-resources)
  - [Subaccounts](#subaccounts)
  - [Splits](#splits)
  - [Apple Pay](#apple-pay)
  - [Charges](#charges)
  - [Bulk Charges](#bulk-charges)
- [Dispute & Refund Resources](#dispute--refund-resources)
  - [Disputes](#disputes)
  - [Refunds](#refunds)
  - [Settlements](#settlements)
- [Utility Resources](#utility-resources)
  - [Pages](#pages)
  - [Products](#products)
  - [Invoices](#invoices)
  - [Verification](#verification)
  - [Dedicated Virtual Accounts](#dedicated-virtual-accounts)
  - [Terminals](#terminals)
  - [Virtual Terminals](#virtual-terminals)
  - [Integration](#integration)
  - [Miscellaneous](#miscellaneous)

## Client Configuration

### StarfolkSoftware\Paystack\Client

The main client class for interacting with the Paystack API.

#### Constructor

```php
public function __construct(array $opts = [])
```

**Parameters:**

- `secretKey` (string, required): Your Paystack secret key
- `apiVersion` (string, optional): API version to use (default: 'v1')
- `baseUri` (string, optional): Custom base URI (default: 'https://api.paystack.co')

**Example:**

```php
$paystack = new PaystackClient([
    'secretKey' => 'sk_test_your_secret_key_here',
    'apiVersion' => 'v1',
    'baseUri' => 'https://api.paystack.co'
]);
```

#### Methods

##### getHttpClient()

Returns the underlying HTTP client instance.

```php
public function getHttpClient(): HttpMethodsClientInterface
```

---

## Core Resources

### Transactions

Manage payment transactions.

#### Methods

##### initialize()

Initialize a transaction.

```php
public function initialize(array $params): array
```

**Parameters:**

- `email` (string, required): Customer's email address
- `amount` (int, required): Amount in kobo (smallest currency unit)
- `currency` (string, optional): Currency code (default: NGN)
- `reference` (string, optional): Unique transaction reference
- `callback_url` (string, optional): URL to redirect after payment
- `plan` (string, optional): Plan code for subscription
- `invoice_limit` (int, optional): Number of invoices to generate
- `metadata` (array, optional): Additional transaction data
- `channels` (array, optional): Payment channels to allow
- `split_code` (string, optional): Transaction split code
- `subaccount` (string, optional): Subaccount code
- `transaction_charge` (int, optional): Amount to charge subaccount
- `bearer` (string, optional): Who bears Paystack charges

**Example:**

```php
$transaction = $paystack->transactions->initialize([
    'email' => 'customer@example.com',
    'amount' => 20000,
    'currency' => 'NGN',
    'callback_url' => 'https://yoursite.com/payment/callback',
    'metadata' => [
        'custom_fields' => [
            ['display_name' => 'Cart ID', 'variable_name' => 'cart_id', 'value' => '12345']
        ]
    ]
]);
```

##### verify()

Verify a transaction.

```php
public function verify(string $reference): array
```

**Parameters:**

- `reference` (string, required): Transaction reference

**Example:**

```php
$verification = $paystack->transactions->verify('transaction_reference');
```

##### all()

List transactions with optional filters.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of transactions per page
- `page` (int, optional): Page number
- `customer` (string, optional): Customer ID or code
- `status` (string, optional): Transaction status
- `from` (string, optional): Start date (YYYY-MM-DD)
- `to` (string, optional): End date (YYYY-MM-DD)
- `amount` (int, optional): Filter by amount

**Example:**

```php
$transactions = $paystack->transactions->all([
    'perPage' => 50,
    'status' => 'success',
    'from' => '2024-01-01',
    'to' => '2024-12-31'
]);
```

##### fetch()

Get details of a transaction.

```php
public function fetch(int $id): array
```

**Parameters:**

- `id` (int, required): Transaction ID

##### chargeAuthorization()

Charge an authorization.

```php
public function chargeAuthorization(array $params): array
```

**Parameters:**

- `authorization_code` (string, required): Authorization code
- `email` (string, required): Customer's email
- `amount` (int, required): Amount in kobo

##### checkAuthorization()

Check authorization validity.

```php
public function checkAuthorization(array $params): array
```

##### timeline()

View transaction timeline.

```php
public function timeline(string $idOrReference): array
```

##### totals()

Get transaction totals.

```php
public function totals(array $params = []): array
```

##### export()

Export transactions.

```php
public function export(array $params = []): array
```

##### partialDebit()

Perform partial debit.

```php
public function partialDebit(array $params): array
```

---

### Customers

Manage customer data and profiles.

#### Methods

##### create()

Create a new customer.

```php
public function create(array $params): array
```

**Parameters:**

- `email` (string, required): Customer's email address
- `first_name` (string, optional): Customer's first name
- `last_name` (string, optional): Customer's last name
- `phone` (string, optional): Customer's phone number
- `metadata` (array, optional): Additional customer data

**Example:**

```php
$customer = $paystack->customers->create([
    'email' => 'customer@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+2348123456789',
    'metadata' => [
        'custom_fields' => [
            ['display_name' => 'Loyalty ID', 'variable_name' => 'loyalty_id', 'value' => 'LTY123']
        ]
    ]
]);
```

##### all()

List customers.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of customers per page
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date

##### fetch()

Get customer details.

```php
public function fetch(string $emailOrCode): array
```

**Parameters:**

- `emailOrCode` (string, required): Customer email or customer code

##### update()

Update customer information.

```php
public function update(string $customerCode, array $params): array
```

**Parameters:**

- `customerCode` (string, required): Customer code
- `first_name` (string, optional): Updated first name
- `last_name` (string, optional): Updated last name
- `phone` (string, optional): Updated phone number
- `metadata` (array, optional): Updated metadata

##### validate()

Validate customer identity.

```php
public function validate(string $customerCode, array $params): array
```

##### setRiskAction()

Set risk action for customer.

```php
public function setRiskAction(string $customerCode, array $params): array
```

##### deactivateAuthorization()

Deactivate customer authorization.

```php
public function deactivateAuthorization(array $params): array
```

---

### Payment Requests

Create and manage payment requests and invoices.

#### Methods

##### create()

Create a payment request.

```php
public function create(array $params): array
```

**Parameters:**

- `description` (string, optional): Payment request description
- `line_items` (array, optional): Array of line items
- `tax` (array, optional): Array of tax items
- `customer` (string, required): Customer ID, code, or email
- `due_date` (string, optional): Due date (YYYY-MM-DD)
- `send_notification` (bool, optional): Send email notification
- `draft` (bool, optional): Save as draft
- `has_invoice` (bool, optional): Generate invoice number
- `invoice_number` (int, optional): Custom invoice number
- `split_code` (string, optional): Split code

**Example:**

```php
$paymentRequest = $paystack->paymentRequests->create([
    'description' => 'Website Development Invoice',
    'line_items' => [
        ['name' => 'Frontend Development', 'amount' => 50000, 'quantity' => 1],
        ['name' => 'Backend Development', 'amount' => 75000, 'quantity' => 1]
    ],
    'tax' => [
        ['name' => 'VAT', 'amount' => 9375] // 7.5%
    ],
    'customer' => 'customer@example.com',
    'due_date' => date('Y-m-d', strtotime('+30 days')),
    'send_notification' => true
]);
```

##### all()

List payment requests.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of requests per page
- `page` (int, optional): Page number
- `customer` (string, optional): Customer filter
- `status` (string, optional): Status filter

##### fetch()

Get payment request details.

```php
public function fetch(string $idOrCode): array
```

##### verify()

Verify payment request.

```php
public function verify(string $code): array
```

##### sendNotification()

Send payment request notification.

```php
public function sendNotification(string $code): array
```

##### totals()

Get payment request totals.

```php
public function totals(): array
```

##### finalize()

Finalize a draft payment request.

```php
public function finalize(string $code, array $params = []): array
```

**Parameters:**

- `code` (string, required): Payment request code
- `send_notification` (bool, optional): Send notification after finalizing

##### update()

Update payment request.

```php
public function update(string $idOrCode, array $params): array
```

##### archive()

Archive payment request.

```php
public function archive(string $code): array
```

---

### Plans

Create and manage subscription plans.

#### Methods

##### create()

Create a subscription plan.

```php
public function create(array $params): array
```

**Parameters:**

- `name` (string, required): Plan name
- `interval` (string, required): Billing interval (daily, weekly, monthly, quarterly, biannually, annually)
- `amount` (int, required): Plan amount in kobo
- `description` (string, optional): Plan description
- `send_invoices` (bool, optional): Send invoices
- `send_sms` (bool, optional): Send SMS notifications
- `currency` (string, optional): Currency code

**Example:**

```php
$plan = $paystack->plans->create([
    'name' => 'Premium Monthly Plan',
    'interval' => 'monthly',
    'amount' => 5000, // ₦50.00
    'description' => 'Premium features with monthly billing',
    'currency' => 'NGN',
    'send_invoices' => true,
    'send_sms' => false
]);
```

##### all()

List plans.

```php
public function all(array $params = []): array
```

##### fetch()

Get plan details.

```php
public function fetch(string $idOrCode): array
```

##### update()

Update plan.

```php
public function update(string $idOrCode, array $params): array
```

---

### Subscriptions

Manage recurring billing subscriptions.

#### Methods

##### create()

Create a subscription.

```php
public function create(array $params): array
```

**Parameters:**

- `customer` (string, required): Customer code
- `plan` (string, required): Plan code
- `authorization` (string, optional): Authorization code
- `start_date` (string, optional): Subscription start date

**Example:**

```php
$subscription = $paystack->subscriptions->create([
    'customer' => 'CUS_xwaj0txjryg393b',
    'plan' => 'PLN_plancode',
    'authorization' => 'AUTH_authorization_code'
]);
```

##### all()

List subscriptions.

```php
public function all(array $params = []): array
```

##### fetch()

Get subscription details.

```php
public function fetch(string $idOrCode): array
```

##### enable()

Enable subscription.

```php
public function enable(array $params): array
```

##### disable()

Disable subscription.

```php
public function disable(string $code, array $params): array
```

##### generateUpdateSubscriptionLink()

Generate subscription update link.

```php
public function generateUpdateSubscriptionLink(string $code): array
```

##### sendUpdateSubscriptionLink()

Send subscription update link.

```php
public function sendUpdateSubscriptionLink(string $code): array
```

---

## Transfer Resources

### Transfers

Send money to bank accounts and mobile money wallets.

#### Methods

##### initiate()

Initiate a transfer.

```php
public function initiate(array $params): array
```

**Parameters:**

- `source` (string, required): Transfer source
- `amount` (int, required): Amount in kobo
- `recipient` (string, required): Transfer recipient code
- `reason` (string, optional): Transfer reason
- `currency` (string, optional): Currency code
- `reference` (string, optional): Transfer reference

**Example:**

```php
$transfer = $paystack->transfers->initiate([
    'source' => 'balance',
    'amount' => 100000, // ₦1,000.00
    'recipient' => 'RCP_recipient_code',
    'reason' => 'Payment for services',
    'currency' => 'NGN'
]);
```

##### finalize()

Finalize transfer.

```php
public function finalize(string $transferCode, array $params): array
```

##### initiateBulk()

Initiate bulk transfer.

```php
public function initiateBulk(array $params): array
```

##### all()

List transfers.

```php
public function all(array $params = []): array
```

##### fetch()

Get transfer details.

```php
public function fetch(string $idOrCode): array
```

##### verify()

Verify transfer.

```php
public function verify(string $reference): array
```

---

### Transfer Recipients

Manage transfer beneficiaries.

#### Methods

##### create()

Create transfer recipient.

```php
public function create(array $params): array
```

**Parameters:**

- `type` (string, required): Recipient type (nuban, mobile_money, basa)
- `name` (string, required): Recipient name
- `account_number` (string, required): Account number
- `bank_code` (string, required): Bank code
- `description` (string, optional): Description
- `currency` (string, optional): Currency code
- `authorization_code` (string, optional): Authorization code
- `metadata` (array, optional): Additional data

**Example:**

```php
$recipient = $paystack->transferRecipients->create([
    'type' => 'nuban',
    'name' => 'John Doe',
    'account_number' => '0123456789',
    'bank_code' => '044',
    'currency' => 'NGN',
    'description' => 'Primary business account'
]);
```

##### bulkCreate()

Create multiple recipients.

```php
public function bulkCreate(array $params): array
```

##### all()

List transfer recipients.

```php
public function all(array $params = []): array
```

##### fetch()

Get recipient details.

```php
public function fetch(string $idOrCode): array
```

##### update()

Update recipient.

```php
public function update(string $idOrCode, array $params): array
```

##### delete()

Delete transfer recipient.

```php
public function delete(string $idOrCode): array
```

---

### Transfer Control

Manage transfer settings and controls.

#### Methods

##### checkBalance()

Check transfer balance.

```php
public function checkBalance(): array
```

##### fetchBalanceLedger()

Fetch balance ledger.

```php
public function fetchBalanceLedger(): array
```

##### resendOtp()

Resend transfer OTP.

```php
public function resendOtp(array $params): array
```

##### disableOtp()

Disable OTP requirement.

```php
public function disableOtp(): array
```

##### finalizeDisableOtp()

Finalize OTP disable.

```php
public function finalizeDisableOtp(array $params): array
```

##### enableOtp()

Enable OTP requirement.

```php
public function enableOtp(): array
```

---

## Advanced Resources

### Subaccounts

Create marketplace split payment accounts.

#### Methods

##### create()

Create subaccount.

```php
public function create(array $params): array
```

**Parameters:**

- `business_name` (string, required): Business name
- `settlement_bank` (string, required): Settlement bank code
- `account_number` (string, required): Account number
- `percentage_charge` (float, required): Percentage of each payment
- `description` (string, optional): Description
- `primary_contact_email` (string, optional): Contact email
- `primary_contact_name` (string, optional): Contact name
- `primary_contact_phone` (string, optional): Contact phone
- `metadata` (array, optional): Additional data

**Example:**

```php
$subaccount = $paystack->subaccounts->create([
    'business_name' => 'Vendor Store',
    'settlement_bank' => '044',
    'account_number' => '0123456789',
    'percentage_charge' => 15.5, // 15.5% of each transaction
    'description' => 'Vendor commission account',
    'primary_contact_email' => 'vendor@example.com',
    'primary_contact_name' => 'Jane Vendor',
    'primary_contact_phone' => '+2348123456789'
]);
```

##### all()

List subaccounts.

```php
public function all(array $params = []): array
```

##### fetch()

Get subaccount details.

```php
public function fetch(string $idOrCode): array
```

##### update()

Update subaccount.

```php
public function update(string $idOrCode, array $params): array
```

---

### Splits

Advanced payment splitting configuration.

#### Methods

##### create()

Create payment split.

```php
public function create(array $params): array
```

**Parameters:**

- `name` (string, required): Split configuration name
- `type` (string, required): Split type (percentage, flat)
- `currency` (string, required): Currency code
- `subaccounts` (array, required): Array of subaccount splits
- `bearer_type` (string, required): Who bears charges
- `bearer_subaccount` (string, optional): Subaccount to bear charges

**Example:**

```php
$split = $paystack->splits->create([
    'name' => 'Marketplace Split',
    'type' => 'percentage',
    'currency' => 'NGN',
    'subaccounts' => [
        ['subaccount' => 'ACCT_vendor1', 'share' => 70],
        ['subaccount' => 'ACCT_vendor2', 'share' => 20]
    ],
    'bearer_type' => 'all',
]);
```

##### all()

List payment splits.

```php
public function all(array $params = []): array
```

##### fetch()

Get split details.

```php
public function fetch(string $id): array
```

##### update()

Update split configuration.

```php
public function update(string $id, array $params): array
```

##### addOrUpdateSubaccount()

Add or update subaccount in split.

```php
public function addOrUpdateSubaccount(string $id, array $params): array
```

##### removeSubaccount()

Remove subaccount from split.

```php
public function removeSubaccount(string $id, array $params): array
```

---

This documentation provides comprehensive coverage of the Paystack PHP SDK API. For more detailed examples and use cases, refer to the [Getting Started Guide](getting-started.md) and [Advanced Usage](advanced-usage.md) documentation.