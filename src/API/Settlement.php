<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Settlement extends ApiAbstract
{
    /**
     * List settlements made to your settlement accounts
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $response = $this->httpClient->get('/settlement', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get the transactions that make up a particular settlement
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function getTransactions(string $id, array $params = []): array
    {
        $response = $this->httpClient->get("/settlement/{$id}/transactions", [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }
}