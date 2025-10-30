<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class TransferRecipient extends ApiAbstract
{
    /**
     * Create a transfer recipient
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $response = $this->httpClient->post('/transferrecipient', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Create multiple transfer recipients in batches
     * 
     * @param array $params
     * @return array
     */
    public function bulkCreate(array $params): array
    {
        $response = $this->httpClient->post('/transferrecipient/bulk', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * List transfer recipients available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $response = $this->httpClient->get('/transferrecipient', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Fetch the details of a transfer recipient
     * 
     * @param string $idOrCode
     * @return array
     */
    public function find(string $idOrCode): array
    {
        $response = $this->httpClient->get("/transferrecipient/{$idOrCode}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update an existing recipient
     * 
     * @param string $idOrCode
     * @param array $params
     * @return array
     */
    public function update(string $idOrCode, array $params): array
    {
        $response = $this->httpClient->put("/transferrecipient/{$idOrCode}", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Delete a transfer recipient (sets the transfer recipient to inactive)
     * 
     * @param string $idOrCode
     * @return array
     */
    public function delete(string $idOrCode): array
    {
        $response = $this->httpClient->delete("/transferrecipient/{$idOrCode}");

        return ResponseMediator::getContent($response);
    }
}