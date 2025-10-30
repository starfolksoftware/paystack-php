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

### Apple Pay

Manage Apple Pay domain registration for your integration.

#### Methods

##### registerDomain()

Register a top-level domain or subdomain for your Apple Pay integration.

```php
public function registerDomain(array $params): array
```

**Parameters:**

- `domainName` (string, required): The domain name to register

**Example:**

```php
$applePay = $paystack->applePay->registerDomain([
    'domainName' => 'example.com'
]);
```

##### listDomains()

List all registered domains on your integration.

```php
public function listDomains(array $params = []): array
```

**Parameters:**

- `use_cursor` (bool, optional): Use cursor for pagination
- `next` (string, optional): Cursor for next page
- `previous` (string, optional): Cursor for previous page

##### unregisterDomain()

Unregister a top-level domain or subdomain previously used for your Apple Pay integration.

```php
public function unregisterDomain(array $params): array
```

**Parameters:**

- `domainName` (string, required): The domain name to unregister

---

### Charges

Initiate and manage direct charges through various payment channels.

#### Methods

##### create()

Initiate a payment by integrating the payment channel of choice.

```php
public function create(array $params): array
```

**Parameters:**

- `email` (string, required): Customer's email address
- `amount` (int, required): Amount in kobo
- `bank` (array, optional): Bank details for bank charge
- `authorization_code` (string, optional): Authorization code for card charge
- `pin` (string, optional): Card PIN
- `metadata` (array, optional): Additional metadata

**Example:**

```php
$charge = $paystack->charges->create([
    'email' => 'customer@example.com',
    'amount' => 10000,
    'bank' => [
        'code' => '057',
        'account_number' => '0000000000'
    ]
]);
```

##### submitPin()

Submit PIN to continue a charge.

```php
public function submitPin(array $params): array
```

**Parameters:**

- `pin` (string, required): Card PIN
- `reference` (string, required): Transaction reference

##### submitOtp()

Submit OTP to complete a charge.

```php
public function submitOtp(array $params): array
```

**Parameters:**

- `otp` (string, required): One-time password
- `reference` (string, required): Transaction reference

##### submitPhone()

Submit phone when requested.

```php
public function submitPhone(array $params): array
```

**Parameters:**

- `phone` (string, required): Customer's phone number
- `reference` (string, required): Transaction reference

##### submitBirthday()

Submit birthday when requested.

```php
public function submitBirthday(array $params): array
```

**Parameters:**

- `birthday` (string, required): Customer's birthday (YYYY-MM-DD)
- `reference` (string, required): Transaction reference

##### submitAddress()

Submit address to continue charge.

```php
public function submitAddress(array $params): array
```

**Parameters:**

- `address` (string, required): Customer's address
- `reference` (string, required): Transaction reference
- `city` (string, required): City
- `state` (string, required): State
- `zipcode` (string, required): Zip code

##### checkPending()

Check pending charge.

```php
public function checkPending(string $reference): array
```

**Parameters:**

- `reference` (string, required): Transaction reference

---

### Bulk Charges

Process multiple charges in batches.

#### Methods

##### initiate()

Send an array of objects with authorization codes and amounts to process transactions as a batch.

```php
public function initiate(array $params): array
```

**Parameters:**

- `body` (array, required): Array of charge objects containing authorization codes and amounts

**Example:**

```php
$bulkCharge = $paystack->bulkCharges->initiate([
    [
        'authorization' => 'AUTH_code1',
        'amount' => 10000,
        'reference' => 'ref_001'
    ],
    [
        'authorization' => 'AUTH_code2',
        'amount' => 20000,
        'reference' => 'ref_002'
    ]
]);
```

##### all()

List bulk charge batches created by the integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of batches per page
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date

##### find()

Retrieve a specific batch code and its progress information.

```php
public function find(string $idOrCode): array
```

**Parameters:**

- `idOrCode` (string, required): Batch ID or code

##### getCharges()

Retrieve the charges associated with a specified batch code.

```php
public function getCharges(string $idOrCode, array $params = []): array
```

**Parameters:**

- `idOrCode` (string, required): Batch ID or code
- `status` (string, optional): Filter by status
- `perPage` (int, optional): Number of charges per page
- `page` (int, optional): Page number

##### pause()

Pause processing a batch.

```php
public function pause(string $batchCode): array
```

**Parameters:**

- `batchCode` (string, required): Batch code

##### resume()

Resume processing a batch.

```php
public function resume(string $batchCode): array
```

**Parameters:**

- `batchCode` (string, required): Batch code

---

## Dispute & Refund Resources

### Disputes

Manage transaction disputes filed against you.

#### Methods

##### all()

List disputes filed against you.

```php
public function all(array $params = []): array
```

**Parameters:**

- `from` (string, optional): Start date (YYYY-MM-DD)
- `to` (string, optional): End date (YYYY-MM-DD)
- `perPage` (int, optional): Number of disputes per page
- `page` (int, optional): Page number
- `transaction` (string, optional): Transaction ID
- `status` (string, optional): Dispute status

**Example:**

```php
$disputes = $paystack->disputes->all([
    'status' => 'pending',
    'perPage' => 20
]);
```

##### find()

Get details of a dispute.

```php
public function find(string $id): array
```

**Parameters:**

- `id` (string, required): Dispute ID

##### update()

Update details of a dispute.

```php
public function update(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Dispute ID
- `refund_amount` (int, optional): Amount to refund in kobo
- `uploaded_filename` (string, optional): Filename of uploaded evidence

##### addEvidence()

Add evidence to a dispute.

```php
public function addEvidence(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Dispute ID
- `customer_email` (string, required): Customer's email
- `customer_name` (string, required): Customer's name
- `customer_phone` (string, required): Customer's phone
- `service_details` (string, required): Service details
- `delivery_address` (string, optional): Delivery address
- `delivery_date` (string, optional): Delivery date

##### getUploadUrl()

Get upload URL for a dispute file.

```php
public function getUploadUrl(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Dispute ID
- `upload_filename` (string, required): Filename to upload

##### resolve()

Resolve a dispute.

```php
public function resolve(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Dispute ID
- `resolution` (string, required): Resolution type (merchant-accepted, declined)
- `message` (string, required): Resolution message
- `refund_amount` (int, required): Amount to refund in kobo
- `uploaded_filename` (string, required): Evidence filename
- `evidence` (int, optional): Evidence ID

##### export()

Export disputes.

```php
public function export(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of disputes to export
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date

---

### Refunds

Initiate and manage transaction refunds.

#### Methods

##### create()

Initiate a refund on a successful transaction.

```php
public function create(array $params): array
```

**Parameters:**

- `transaction` (string, required): Transaction reference or ID
- `amount` (int, optional): Amount to refund in kobo (defaults to full amount)
- `currency` (string, optional): Currency code
- `customer_note` (string, optional): Note for customer
- `merchant_note` (string, optional): Internal note

**Example:**

```php
$refund = $paystack->refunds->create([
    'transaction' => 'transaction_reference',
    'amount' => 5000,
    'customer_note' => 'Refund for cancelled order'
]);
```

##### all()

List refunds available on your integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `reference` (string, optional): Transaction reference
- `currency` (string, optional): Currency code
- `perPage` (int, optional): Number of refunds per page
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date

##### find()

Get details of a refund.

```php
public function find(string $id): array
```

**Parameters:**

- `id` (string, required): Refund ID

---

### Settlements

View settlements made to your settlement accounts.

#### Methods

##### all()

List settlements made to your settlement accounts.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of settlements per page
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date
- `subaccount` (string, optional): Subaccount ID

**Example:**

```php
$settlements = $paystack->settlements->all([
    'perPage' => 50,
    'from' => '2024-01-01'
]);
```

##### getTransactions()

Get the transactions that make up a particular settlement.

```php
public function getTransactions(string $id, array $params = []): array
```

**Parameters:**

- `id` (string, required): Settlement ID
- `perPage` (int, optional): Number of transactions per page
- `page` (int, optional): Page number

---

## Utility Resources

### Pages

Create and manage payment pages for collecting payments.

#### Methods

##### create()

Create a payment page.

```php
public function create(array $params): array
```

**Parameters:**

- `name` (string, required): Page name
- `description` (string, optional): Page description
- `amount` (int, optional): Amount in kobo (for fixed amount pages)
- `slug` (string, optional): Custom slug
- `metadata` (array, optional): Additional metadata
- `redirect_url` (string, optional): Redirect URL after payment
- `custom_fields` (array, optional): Custom form fields

**Example:**

```php
$page = $paystack->pages->create([
    'name' => 'Product Payment Page',
    'description' => 'Payment for premium product',
    'amount' => 50000,
    'slug' => 'premium-product',
    'redirect_url' => 'https://yoursite.com/thank-you'
]);
```

##### all()

List payment pages available on your integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of pages per page
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date

##### find()

Get details of a payment page.

```php
public function find(string $idOrSlug): array
```

**Parameters:**

- `idOrSlug` (string, required): Page ID or slug

##### update()

Update a payment page.

```php
public function update(string $idOrSlug, array $params): array
```

**Parameters:**

- `idOrSlug` (string, required): Page ID or slug
- `name` (string, optional): Updated page name
- `description` (string, optional): Updated description
- `amount` (int, optional): Updated amount
- `active` (bool, optional): Active status

##### checkSlugAvailability()

Check the availability of a slug for a payment page.

```php
public function checkSlugAvailability(string $slug): array
```

**Parameters:**

- `slug` (string, required): Slug to check

##### addProducts()

Add products to a payment page.

```php
public function addProducts(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Page ID
- `product` (array, required): Array of product IDs

---

### Products

Create and manage products for your payment pages.

#### Methods

##### create()

Create a product on your integration.

```php
public function create(array $params): array
```

**Parameters:**

- `name` (string, required): Product name
- `description` (string, required): Product description
- `price` (int, required): Product price in kobo
- `currency` (string, required): Currency code
- `unlimited` (bool, optional): Unlimited stock
- `quantity` (int, optional): Available quantity

**Example:**

```php
$product = $paystack->products->create([
    'name' => 'Premium Subscription',
    'description' => 'Monthly premium access',
    'price' => 10000,
    'currency' => 'NGN',
    'unlimited' => false,
    'quantity' => 100
]);
```

##### all()

List products available on your integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of products per page
- `page` (int, optional): Page number
- `from` (string, optional): Start date
- `to` (string, optional): End date

##### find()

Get details of a product on your integration.

```php
public function find(string $id): array
```

**Parameters:**

- `id` (string, required): Product ID

##### update()

Update a product details on your integration.

```php
public function update(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Product ID
- `name` (string, optional): Updated product name
- `description` (string, optional): Updated description
- `price` (int, optional): Updated price
- `currency` (string, optional): Updated currency
- `unlimited` (bool, optional): Updated stock status
- `quantity` (int, optional): Updated quantity

---

### Invoices

Create and manage invoices (alternative to Payment Requests).

#### Methods

##### create()

Create a new invoice.

```php
public function create(array $params): array
```

**Parameters:**

- `customer` (string, required): Customer code or email
- `description` (string, optional): Invoice description
- `due_date` (string, optional): Due date (YYYY-MM-DD)
- `line_items` (array, optional): Array of line items
- `tax` (array, optional): Array of tax items
- `send_notification` (bool, optional): Send notification
- `draft` (bool, optional): Save as draft

**Example:**

```php
$invoice = $paystack->invoices->create([
    'customer' => 'customer@example.com',
    'description' => 'Consulting Services',
    'line_items' => [
        ['name' => 'Consultation', 'amount' => 50000, 'quantity' => 2]
    ],
    'due_date' => date('Y-m-d', strtotime('+14 days'))
]);
```

##### all()

Retrieve all invoices.

```php
public function all(array $params): array
```

**Parameters:**

- `perPage` (int, optional): Number of invoices per page
- `page` (int, optional): Page number
- `customer` (string, optional): Customer ID or code
- `status` (string, optional): Invoice status
- `currency` (string, optional): Currency code
- `from` (string, optional): Start date
- `to` (string, optional): End date

##### find()

Retrieve an invoice.

```php
public function find(string $id): array
```

**Parameters:**

- `id` (string, required): Invoice ID or code

##### verify()

Verify details of an invoice on your integration.

```php
public function verify(string $id): array
```

**Parameters:**

- `id` (string, required): Invoice ID or code

##### notify()

Send notification of an invoice to your customers.

```php
public function notify(string $id): array
```

**Parameters:**

- `id` (string, required): Invoice ID or code

##### finalize()

Finalize a draft invoice.

```php
public function finalize(string $id): array
```

**Parameters:**

- `id` (string, required): Invoice ID or code

##### archive()

Archive an invoice. Invoice will no longer be fetched on list or returned on verify.

```php
public function archive(string $id): array
```

**Parameters:**

- `id` (string, required): Invoice ID or code

##### stats()

Get invoice metrics for dashboard.

```php
public function stats(): array
```

##### update()

Update an invoice.

```php
public function update(string $id, array $params): array
```

**Parameters:**

- `id` (string, required): Invoice ID or code
- `customer` (string, optional): Customer code or email
- `description` (string, optional): Updated description
- `due_date` (string, optional): Updated due date
- `line_items` (array, optional): Updated line items
- `tax` (array, optional): Updated tax items

---

### Verification

Verify customer account details and card information.

#### Methods

##### resolveAccount()

Confirm an account belongs to the right customer.

```php
public function resolveAccount(array $params): array
```

**Parameters:**

- `account_number` (string, required): Account number
- `bank_code` (string, required): Bank code

**Example:**

```php
$verification = $paystack->verification->resolveAccount([
    'account_number' => '0123456789',
    'bank_code' => '044'
]);
```

##### validateAccount()

Confirm the authenticity of a customer's account number before sending money.

```php
public function validateAccount(array $params): array
```

**Parameters:**

- `account_name` (string, required): Customer's account name
- `account_number` (string, required): Customer's account number
- `account_type` (string, required): Account type (personal, business)
- `bank_code` (string, required): Bank code
- `country_code` (string, required): Country code
- `document_type` (string, required): Document type
- `document_number` (string, optional): Document number

##### resolveCardBin()

Get more information about a customer's card.

```php
public function resolveCardBin(string $bin): array
```

**Parameters:**

- `bin` (string, required): First 6 digits of card

**Example:**

```php
$cardInfo = $paystack->verification->resolveCardBin('539983');
```

---

### Dedicated Virtual Accounts

Create and manage dedicated virtual accounts for customers.

#### Methods

##### create()

Create a dedicated virtual account for an existing customer.

```php
public function create(array $params): array
```

**Parameters:**

- `customer` (string, required): Customer ID or code
- `preferred_bank` (string, optional): Preferred bank slug
- `subaccount` (string, optional): Subaccount code
- `split_code` (string, optional): Split code
- `first_name` (string, optional): Customer's first name
- `last_name` (string, optional): Customer's last name
- `phone` (string, optional): Customer's phone number

**Example:**

```php
$dva = $paystack->dedicatedVirtualAccounts->create([
    'customer' => 'CUS_customercode',
    'preferred_bank' => 'wema-bank'
]);
```

##### assign()

Assign a dedicated virtual account to a customer.

```php
public function assign(array $params): array
```

**Parameters:**

- `email` (string, required): Customer's email
- `first_name` (string, required): Customer's first name
- `last_name` (string, required): Customer's last name
- `phone` (string, required): Customer's phone number
- `preferred_bank` (string, required): Preferred bank slug
- `country` (string, optional): Country code

##### all()

List dedicated virtual accounts available on your integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `active` (bool, optional): Filter by active status
- `currency` (string, optional): Currency code
- `perPage` (int, optional): Number of accounts per page
- `page` (int, optional): Page number

##### find()

Get details of a dedicated virtual account.

```php
public function find(string $dedicatedAccountId): array
```

**Parameters:**

- `dedicatedAccountId` (string, required): Dedicated account ID

##### requery()

Requery dedicated virtual account for new transactions.

```php
public function requery(array $params): array
```

**Parameters:**

- `account_number` (string, required): Virtual account number
- `provider_slug` (string, required): Bank provider slug
- `date` (string, optional): Date to requery (YYYY-MM-DD)

##### deactivate()

Deactivate a dedicated virtual account.

```php
public function deactivate(string $dedicatedAccountId): array
```

**Parameters:**

- `dedicatedAccountId` (string, required): Dedicated account ID

##### split()

Split a dedicated virtual account transaction with one or more accounts.

```php
public function split(array $params): array
```

**Parameters:**

- `customer` (string, required): Customer ID or code
- `preferred_bank` (string, optional): Preferred bank slug
- `subaccount` (string, optional): Subaccount code
- `split_code` (string, optional): Split code

##### removeSplit()

Remove a split payment account from a dedicated virtual account.

```php
public function removeSplit(array $params): array
```

**Parameters:**

- `account_number` (string, required): Dedicated virtual account number

##### getProviders()

Get available bank providers for dedicated virtual accounts.

```php
public function getProviders(): array
```

---

### Terminals

Manage Paystack Terminals for in-person payments.

#### Methods

##### sendEvent()

Send an event from your application to the Paystack Terminal.

```php
public function sendEvent(string $terminalId, array $params): array
```

**Parameters:**

- `terminalId` (string, required): Terminal ID
- `type` (string, required): Event type (invoice, transaction)
- `action` (string, required): Event action (process, view, print)
- `data` (array, required): Event data

**Example:**

```php
$event = $paystack->terminals->sendEvent('terminal_id', [
    'type' => 'invoice',
    'action' => 'process',
    'data' => [
        'id' => '12345',
        'reference' => 'INV_ref'
    ]
]);
```

##### fetchEventStatus()

Check the status of an event sent to the Terminal.

```php
public function fetchEventStatus(string $terminalId, string $eventId): array
```

**Parameters:**

- `terminalId` (string, required): Terminal ID
- `eventId` (string, required): Event ID

##### fetchTerminalStatus()

Check the availability of a Terminal before sending an event to it.

```php
public function fetchTerminalStatus(string $terminalId): array
```

**Parameters:**

- `terminalId` (string, required): Terminal ID

##### all()

List the Terminals available on your integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of terminals per page
- `next` (string, optional): Cursor for next page
- `previous` (string, optional): Cursor for previous page

##### find()

Get the details of a Terminal.

```php
public function find(string $terminalId): array
```

**Parameters:**

- `terminalId` (string, required): Terminal ID

##### update()

Update the details of a Terminal.

```php
public function update(string $terminalId, array $params): array
```

**Parameters:**

- `terminalId` (string, required): Terminal ID
- `name` (string, optional): Terminal name
- `address` (string, optional): Terminal address

##### commission()

Activate your debug device by linking it to your integration.

```php
public function commission(array $params): array
```

**Parameters:**

- `serial_number` (string, required): Device serial number

##### decommission()

Unlink your debug device from your integration.

```php
public function decommission(array $params): array
```

**Parameters:**

- `serial_number` (string, required): Device serial number

---

### Virtual Terminals

Manage Virtual Terminals for WhatsApp payments.

#### Methods

##### create()

Create a Virtual Terminal on your integration.

```php
public function create(array $params): array
```

**Parameters:**

- `type` (string, required): Terminal type
- `name` (string, optional): Terminal name
- `description` (string, optional): Terminal description

**Example:**

```php
$virtualTerminal = $paystack->virtualTerminals->create([
    'type' => 'terminal',
    'name' => 'WhatsApp Terminal',
    'description' => 'Terminal for WhatsApp payments'
]);
```

##### all()

List Virtual Terminals on your integration.

```php
public function all(array $params = []): array
```

**Parameters:**

- `perPage` (int, optional): Number of terminals per page
- `page` (int, optional): Page number

##### find()

Fetch a Virtual Terminal on your integration.

```php
public function find(string $code): array
```

**Parameters:**

- `code` (string, required): Terminal code

##### update()

Update a Virtual Terminal on your integration.

```php
public function update(string $code, array $params): array
```

**Parameters:**

- `code` (string, required): Terminal code
- `name` (string, optional): Updated terminal name
- `description` (string, optional): Updated description

##### deactivate()

Deactivate a Virtual Terminal on your integration.

```php
public function deactivate(string $code): array
```

**Parameters:**

- `code` (string, required): Terminal code

##### assignDestination()

Add a destination (WhatsApp number) to a Virtual Terminal.

```php
public function assignDestination(string $code, array $params): array
```

**Parameters:**

- `code` (string, required): Terminal code
- `destination` (string, required): WhatsApp number

##### unassignDestination()

Unassign a destination (WhatsApp number) from a Virtual Terminal.

```php
public function unassignDestination(string $code, array $params): array
```

**Parameters:**

- `code` (string, required): Terminal code
- `destination` (string, required): WhatsApp number

##### addSplitCode()

Add a split code to a Virtual Terminal.

```php
public function addSplitCode(string $code, array $params): array
```

**Parameters:**

- `code` (string, required): Terminal code
- `split_code` (string, required): Split code

##### removeSplitCode()

Remove a split code from a Virtual Terminal.

```php
public function removeSplitCode(string $code, array $params): array
```

**Parameters:**

- `code` (string, required): Terminal code
- `split_code` (string, required): Split code

---

### Integration

Manage integration settings and configurations.

#### Methods

##### fetchTimeout()

Fetch the payment session timeout on your integration.

```php
public function fetchTimeout(): array
```

**Example:**

```php
$timeout = $paystack->integration->fetchTimeout();
```

##### updateTimeout()

Update the payment session timeout on your integration.

```php
public function updateTimeout(array $params): array
```

**Parameters:**

- `timeout` (int, required): Timeout in seconds

**Example:**

```php
$result = $paystack->integration->updateTimeout([
    'timeout' => 1800 // 30 minutes
]);
```

---

### Miscellaneous

Utility methods for banks, countries, and states.

#### Methods

##### listBanks()

Get a list of all supported banks and their properties.

```php
public function listBanks(array $params = []): array
```

**Parameters:**

- `country` (string, optional): Country code (default: nigeria)
- `use_cursor` (bool, optional): Use cursor for pagination
- `perPage` (int, optional): Number of banks per page
- `pay_with_bank_transfer` (bool, optional): Filter banks that support transfer
- `pay_with_bank` (bool, optional): Filter banks that support bank payment
- `enabled_for_verification` (bool, optional): Filter banks enabled for account verification

**Example:**

```php
$banks = $paystack->miscellaneous->listBanks([
    'country' => 'nigeria',
    'pay_with_bank_transfer' => true
]);
```

##### listCountries()

Get a list of countries that Paystack currently supports.

```php
public function listCountries(): array
```

**Example:**

```php
$countries = $paystack->miscellaneous->listCountries();
```

##### listStates()

Get a list of states for a country for address verification.

```php
public function listStates(array $params): array
```

**Parameters:**

- `country` (string, required): Country code

**Example:**

```php
$states = $paystack->miscellaneous->listStates([
    'country' => 'NG'
]);
```

---

This documentation provides comprehensive coverage of the Paystack PHP SDK API. For more detailed examples and use cases, refer to the [Getting Started Guide](getting-started.md) and [Advanced Usage](advanced-usage.md) documentation.