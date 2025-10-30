<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Settlement as SettlementOptions;

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
        $options = new SettlementOptions\ReadAllOptions($params);
        $query = $options->all();

        $requestOptions = [];
        if (!empty($query)) {
            $requestOptions['query'] = $query;
        }

        $response = $this->httpClient->get('/settlement', $requestOptions);

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
        $options = new SettlementOptions\TransactionsOptions($params);
        $query = $options->all();

        $requestOptions = [];
        if (!empty($query)) {
            $requestOptions['query'] = $query;
        }

        $response = $this->httpClient->get("/settlement/{$id}/transactions", $requestOptions);

        return ResponseMediator::getContent($response);
    }
}