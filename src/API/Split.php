<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Split as SplitOptions;

class Split extends ApiAbstract
{
    /**
     * Create a split payment on your integration
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new SplitOptions\CreateOptions($params);

        $response = $this->httpClient->post('/split', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * List the transaction splits available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new SplitOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/split', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a split on your integration
     * 
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/split/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update a transaction split details on your integration
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function update(string $id, array $params): array
    {
        $options = new SplitOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/split/{$id}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Add a Subaccount to a Transaction Split, or update the share of an existing
     * Subaccount in a Transaction Split
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function addSubaccount(string $id, array $params): array
    {
        $options = new SplitOptions\AddSubaccountOptions($params);

        $response = $this->httpClient->post("/split/{$id}/subaccount/add", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Remove a subaccount from a transaction split
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function removeSubaccount(string $id, array $params): array
    {
        $options = new SplitOptions\RemoveSubaccountOptions($params);

        $response = $this->httpClient->post("/split/{$id}/subaccount/remove", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}