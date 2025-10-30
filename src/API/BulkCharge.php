<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\BulkCharge as BulkChargeOptions;

class BulkCharge extends ApiAbstract
{
    /**
     * Send an array of objects with authorization codes and amount in kobo so we can process transactions as a batch
     * 
     * @param BulkChargeOptions\InitiateOptions|array $options
     * @return array
     */
    public function initiate(BulkChargeOptions\InitiateOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new BulkChargeOptions\InitiateOptions($options);
        }

        $response = $this->httpClient->post('/bulkcharge', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * List bulk charge batches created by the integration
     * 
     * @param BulkChargeOptions\ReadAllOptions|array $options
     * @return array
     */
    public function all(BulkChargeOptions\ReadAllOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new BulkChargeOptions\ReadAllOptions($options);
        }

        $response = $this->httpClient->get('/bulkcharge', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieve a specific batch code. It also returns useful information on its progress by way of the total_charges and pending_charges attributes
     * 
     * @param string $idOrCode
     * @return array
     */
    public function find(string $idOrCode): array
    {
        $response = $this->httpClient->get("/bulkcharge/{$idOrCode}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieve the charges associated with a specified batch code
     * 
     * @param string $idOrCode
     * @param BulkChargeOptions\GetChargesOptions|array $options
     * @return array
     */
    public function getCharges(string $idOrCode, BulkChargeOptions\GetChargesOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new BulkChargeOptions\GetChargesOptions($options);
        }

        $response = $this->httpClient->get("/bulkcharge/{$idOrCode}/charges", [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Pause processing a batch
     * 
     * @param string $batchCode
     * @return array
     */
    public function pause(string $batchCode): array
    {
        $response = $this->httpClient->get("/bulkcharge/pause/{$batchCode}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Resume processing a batch
     * 
     * @param string $batchCode
     * @return array
     */
    public function resume(string $batchCode): array
    {
        $response = $this->httpClient->get("/bulkcharge/resume/{$batchCode}");

        return ResponseMediator::getContent($response);
    }
}