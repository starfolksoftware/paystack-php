<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class DedicatedVirtualAccount extends ApiAbstract
{
    /**
     * Create a dedicated virtual account for an existing customer
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $response = $this->httpClient->post('/dedicated_account', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * List dedicated virtual accounts available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $response = $this->httpClient->get('/dedicated_account', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a dedicated virtual account
     * 
     * @param string $dedicatedAccountId
     * @return array
     */
    public function find(string $dedicatedAccountId): array
    {
        $response = $this->httpClient->get("/dedicated_account/{$dedicatedAccountId}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Requery Dedicated Virtual Account for new transactions
     * 
     * @param array $params
     * @return array
     */
    public function requery(array $params): array
    {
        $response = $this->httpClient->get('/dedicated_account/requery', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Deactivate a dedicated virtual account
     * 
     * @param string $dedicatedAccountId
     * @return array
     */
    public function deactivate(string $dedicatedAccountId): array
    {
        $response = $this->httpClient->delete("/dedicated_account/{$dedicatedAccountId}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Split a dedicated virtual account transaction with one or more accounts
     * 
     * @param array $params
     * @return array
     */
    public function split(array $params): array
    {
        $response = $this->httpClient->post('/dedicated_account/split', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Remove a split payment account from a dedicated virtual account
     * 
     * @param array $params
     * @return array
     */
    public function removeSplit(array $params): array
    {
        $response = $this->httpClient->delete('/dedicated_account/split', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Get available bank providers for dedicated virtual accounts
     * 
     * @return array
     */
    public function getProviders(): array
    {
        $response = $this->httpClient->get('/dedicated_account/available_providers');

        return ResponseMediator::getContent($response);
    }
}