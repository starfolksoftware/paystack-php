<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Subaccount as SubaccountOptions;

class Subaccount extends ApiAbstract
{
    /**
     * Create a subacount on your integration
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new SubaccountOptions\CreateOptions($params);

        $response = $this->httpClient->post('/subaccount', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * List subaccounts available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new SubaccountOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/subaccount', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a subaccount on your integration
     * 
     * @param string $idOrCode
     * @return array
     */
    public function find(string $idOrCode): array
    {
        $response = $this->httpClient->get("/subaccount/{$idOrCode}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update a subaccount details on your integration
     * 
     * @param string $idOrCode
     * @param array $params
     * @return array
     */
    public function update(string $idOrCode, array $params): array
    {
        $options = new SubaccountOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/subaccount/{$idOrCode}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}