<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Transaction as TransactionOptions;

class Transaction extends ApiAbstract
{
    /**
     * Initialize Transaction
     * 
     * @param array $params
     * @return array
     */
    public function initialize(array $params): array
    {
        $options = new TransactionOptions\InitializeOptions($params);

        $response = $this->httpClient->post('/transaction/initialize', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Confirm the status of a transaction
     * 
     * @param string $reference
     * @return array
     */
    public function verify(string $reference): array
    {
        $response = $this->httpClient->get("/transaction/verify/{$reference}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves all transactions
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params): array
    {
        $options = new TransactionOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/transaction', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves a transaction
     * 
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/transaction/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * All authorizations marked as reusable can be charged with this endpoint whenever you need to receive payments.
     * 
     * @param array $params
     * @return array
     */
    public function charge(array $params): array
    {
        $options = new TransactionOptions\ChargeOptions($params);

        $response = $this->httpClient->post('/transaction/charge_authorization', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
    
    /**
     * All Mastercard and Visa authorizations can be checked with this endpoint to know if they have funds for the payment you seek.
     * 
     * @param string $amount Amount should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR
     * @param string $email Customer's email address
     * @param string $authorizationCode Valid authorization code to charge
     * @param string $currency Currency in which amount should be charged. Allowed values are: NGN, GHS, ZAR or USD
     * @return array
     */
    public function checkAuthorization(string $amount, string $email, string $authorizationCode, string $currency)
    {
        $response = $this->httpClient->post('/transaction/check_authorization', body: json_encode([
            'amount' => $amount,
            'email' => $email,
            'authorization_code' => $authorizationCode,
            'currency' => $currency,
        ]));

        return ResponseMediator::getContent($response);
    }

    /**
     * View the timeline of a transaction
     * 
     * @param string $id
     * @return array
     */
    public function timeline(string $id): array
    {
        $response = $this->httpClient->get("/transaction/timeline/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Total amount received on your account
     * 
     * @param array $params
     * @return array
     */
    public function stats(array $params): array
    {
        $options = new TransactionOptions\StatsOptions($params);

        $response = $this->httpClient->get('/transaction/totals', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Export Transactions
     * 
     * @param array $params
     * @return array
     */
    public function export(array $params): array
    {
        $options = new TransactionOptions\StatsOptions($params);

        $response = $this->httpClient->get('/transaction/export', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieve part of a payment from a customer.
     * 
     * @param array $params
     * @return array
     */
    public function debitPartially(array $params): array
    {
        $options = new TransactionOptions\PartialDebitOptions($params);

        $response = $this->httpClient->post('/transaction/partial_debit', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}