<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Dispute\{
    ReadAllOptions,
    UpdateOptions,
    AddEvidenceOptions,
    GetUploadUrlOptions,
    ResolveOptions,
    ExportOptions
};

class Dispute extends ApiAbstract
{
    /**
     * List disputes filed against you
     * 
     * @param ReadAllOptions|array $options
     * @return array
     */
    public function all(ReadAllOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new ReadAllOptions($options);
        }

        $response = $this->httpClient->get('/dispute', [
            'query' => $options->all()
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
     * @param UpdateOptions|array $options
     * @return array
     */
    public function update(string $id, UpdateOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new UpdateOptions($options);
        }

        $response = $this->httpClient->put("/dispute/{$id}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Add evidence to a dispute
     * 
     * @param string $id
     * @param AddEvidenceOptions|array $options
     * @return array
     */
    public function addEvidence(string $id, AddEvidenceOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new AddEvidenceOptions($options);
        }

        $response = $this->httpClient->post("/dispute/{$id}/evidence", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Get upload URL for a dispute file
     * 
     * @param string $id
     * @param GetUploadUrlOptions|array $options
     * @return array
     */
    public function getUploadUrl(string $id, GetUploadUrlOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new GetUploadUrlOptions($options);
        }

        $response = $this->httpClient->post("/dispute/{$id}/upload_url", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Resolve a dispute
     * 
     * @param string $id
     * @param ResolveOptions|array $options
     * @return array
     */
    public function resolve(string $id, ResolveOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ResolveOptions($options);
        }

        $response = $this->httpClient->put("/dispute/{$id}/resolve", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Export disputes
     * 
     * @param ExportOptions|array $options
     * @return array
     */
    public function export(ExportOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new ExportOptions($options);
        }

        $response = $this->httpClient->get('/dispute/export', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }
}