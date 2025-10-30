<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Transfer as TransferOptions;

class Transfer extends ApiAbstract
{
    /**
     * Send money to your customers
     * 
     * @param array $params
     * @return array
     */
    public function initiate(array $params): array
    {
        $options = new TransferOptions\InitiateOptions($params);

        $response = $this->httpClient->post('/transfer', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Finalize an initiated transfer
     * 
     * @param array $params
     * @return array
     */
    public function finalize(array $params): array
    {
        $response = $this->httpClient->post('/transfer/finalize_transfer', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Batch multiple transfers in a single request
     * 
     * @param array $params
     * @return array
     */
    public function bulk(array $params): array
    {
        $response = $this->httpClient->post('/transfer/bulk', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * List the transfers made on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new TransferOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/transfer', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a transfer on your integration
     * 
     * @param string $idOrCode
     * @return array
     */
    public function find(string $idOrCode): array
    {
        $response = $this->httpClient->get("/transfer/{$idOrCode}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Verify the status of a transfer on your integration
     * 
     * @param string $reference
     * @return array
     */
    public function verify(string $reference): array
    {
        $response = $this->httpClient->get("/transfer/verify/{$reference}");

        return ResponseMediator::getContent($response);
    }
}