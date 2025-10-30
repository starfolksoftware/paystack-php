<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Dispute extends ApiAbstract
{
    /**
     * List disputes filed against you
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $response = $this->httpClient->get('/dispute', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a dispute
     * 
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/dispute/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update details of a dispute
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function update(string $id, array $params): array
    {
        $response = $this->httpClient->put("/dispute/{$id}", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Add evidence to a dispute
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function addEvidence(string $id, array $params): array
    {
        $response = $this->httpClient->post("/dispute/{$id}/evidence", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Get upload URL for a dispute file
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function getUploadUrl(string $id, array $params): array
    {
        $response = $this->httpClient->post("/dispute/{$id}/upload_url", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Resolve a dispute
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function resolve(string $id, array $params): array
    {
        $response = $this->httpClient->put("/dispute/{$id}/resolve", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Export disputes
     * 
     * @param array $params
     * @return array
     */
    public function export(array $params = []): array
    {
        $response = $this->httpClient->get('/dispute/export', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }
}