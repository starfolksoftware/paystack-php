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
final class Client
{
    /** @var ClientBuilder $clientBuilder */
    private ClientBuilder $clientBuilder;

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
    protected function customers(): API\Customer
    {
        return new API\Customer($this);
    }

    /**
     * Invoice API
     * 
     * @return API\Invoice
     */
    protected function invoices(): API\Invoice
    {
        return new API\Invoice($this);
    }

    /**
     * Plan API
     * 
     * @return API\Plan
     */
    protected function plans(): API\Plan
    {
        return new API\Plan($this);
    }

    /**
     * Subscription API
     * 
     * @return API\Subscription
     */
    protected function subscriptions(): API\Subscription
    {
        return new API\Subscription($this);
    }

    /**
     * Transaction API
     * 
     * @return API\Transaction
     */
    protected function transactions(): API\Transaction
    {
        return new API\Transaction($this);
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
