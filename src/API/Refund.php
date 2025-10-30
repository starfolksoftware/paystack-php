<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Refund extends ApiAbstract
{
    /**
     * Initiate a refund on a successful transaction
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $response = $this->httpClient->post('/refund', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * List refunds available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $response = $this->httpClient->get('/refund', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a refund
     * 
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/refund/{$id}");

        return ResponseMediator::getContent($response);
    }
}