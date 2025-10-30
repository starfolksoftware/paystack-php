<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack;

use Http\Mock\Client as MockClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;

/**
 * PHP Paystack client.
 * 
 * @author Faruk Nasir <faruk@starfolksoftware.com>
 *
 * Website: http://github.com/starfolksoftware/paystack-php
 */
class Client
{
    /** @var ClientBuilder $clientBuilder */
    private ClientBuilder $clientBuilder;
    
    /** @var string $apiVersion */
    private string $apiVersion;

    /**
     * Intantiate the client class
     * 
     * @param array $opts
     * 
     * @return void
     */
    public function __construct(array $opts = [])
    {
        $options = new Options($opts);

        $this->apiVersion = $options->getApiVersion();

        $this->clientBuilder = $options->getClientBuilder();

        $this->clientBuilder->addPlugin(new BaseUriPlugin($options->getUri()));

        $this->clientBuilder->addPlugin(
            new HeaderDefaultsPlugin(
                [
                    'User-Agent' => sprintf(
                        'Paystack SDK by Starfolk Software %s (http://github.com/starfolksoftware/paystack-php).',
                        $options->getApiVersion()
                    ),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', $options->getSecretKey()),
                ]
            )
        );
    }

    /**
     * Get http client
     * 
     * @return HttpMethodsClientInterface
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    /**
     * Customer API
     * 
     * @return API\Customer
     */
    public function customers(): API\Customer
    {
        return new API\Customer($this);
    }

    /**
     * Invoice API
     * 
     * @return API\Invoice
     */
    public function invoices(): API\Invoice
    {
        return new API\Invoice($this);
    }

    /**
     * Plan API
     * 
     * @return API\Plan
     */
    public function plans(): API\Plan
    {
        return new API\Plan($this);
    }

    /**
     * Subscription API
     * 
     * @return API\Subscription
     */
    public function subscriptions(): API\Subscription
    {
        return new API\Subscription($this);
    }

    /**
     * Transaction API
     * 
     * @return API\Transaction
     */
    public function transactions(): API\Transaction
    {
        return new API\Transaction($this);
    }

    /**
     * PaymentRequest API
     * 
     * @return API\PaymentRequest
     */
    public function paymentRequests(): API\PaymentRequest
    {
        return new API\PaymentRequest($this);
    }

    /**
     * Split API
     * 
     * @return API\Split
     */
    public function splits(): API\Split
    {
        return new API\Split($this);
    }

    /**
     * Terminal API
     * 
     * @return API\Terminal
     */
    public function terminals(): API\Terminal
    {
        return new API\Terminal($this);
    }

    /**
     * VirtualTerminal API
     * 
     * @return API\VirtualTerminal
     */
    public function virtualTerminals(): API\VirtualTerminal
    {
        return new API\VirtualTerminal($this);
    }

    /**
     * ApplePay API
     * 
     * @return API\ApplePay
     */
    public function applePay(): API\ApplePay
    {
        return new API\ApplePay($this);
    }

    /**
     * Subaccount API
     * 
     * @return API\Subaccount
     */
    public function subaccounts(): API\Subaccount
    {
        return new API\Subaccount($this);
    }

    /**
     * Product API
     * 
     * @return API\Product
     */
    public function products(): API\Product
    {
        return new API\Product($this);
    }

    /**
     * DirectDebit API
     * 
     * @return API\DirectDebit
     */
    public function directDebit(): API\DirectDebit
    {
        return new API\DirectDebit($this);
    }

    /**
     * Integration API
     * 
     * @return API\Integration
     */
    public function integration(): API\Integration
    {
        return new API\Integration($this);
    }

    /**
     * Miscellaneous API
     * 
     * @return API\Miscellaneous
     */
    public function miscellaneous(): API\Miscellaneous
    {
        return new API\Miscellaneous($this);
    }

    /**
     * Verification API
     * 
     * @return API\Verification
     */
    public function verification(): API\Verification
    {
        return new API\Verification($this);
    }

    /**
     * Transfer API
     * 
     * @return API\Transfer
     */
    public function transfers(): API\Transfer
    {
        return new API\Transfer($this);
    }

    /**
     * Transfer Recipient API
     * 
     * @return API\TransferRecipient
     */
    public function transferRecipients(): API\TransferRecipient
    {
        return new API\TransferRecipient($this);
    }

    /**
     * Transfer Control API
     * 
     * @return API\TransferControl
     */
    public function transferControl(): API\TransferControl
    {
        return new API\TransferControl($this);
    }

    /**
     * Charge API
     * 
     * @return API\Charge
     */
    public function charges(): API\Charge
    {
        return new API\Charge($this);
    }

    /**
     * Dispute API
     * 
     * @return API\Dispute
     */
    public function disputes(): API\Dispute
    {
        return new API\Dispute($this);
    }

    /**
     * Refund API
     * 
     * @return API\Refund
     */
    public function refunds(): API\Refund
    {
        return new API\Refund($this);
    }

    /**
     * Settlement API
     * 
     * @return API\Settlement
     */
    public function settlements(): API\Settlement
    {
        return new API\Settlement($this);
    }

    /**
     * Bulk Charge API
     * 
     * @return API\BulkCharge
     */
    public function bulkCharges(): API\BulkCharge
    {
        return new API\BulkCharge($this);
    }

    /**
     * Page API
     * 
     * @return API\Page
     */
    public function pages(): API\Page
    {
        return new API\Page($this);
    }

    /**
     * Dedicated Virtual Account API
     * 
     * @return API\DedicatedVirtualAccount
     */
    public function dedicatedVirtualAccounts(): API\DedicatedVirtualAccount
    {
        return new API\DedicatedVirtualAccount($this);
    }

    /**
     * Read data from inaccessible (protected or private) 
     * or non-existing properties.
     * 
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }
    }
}
