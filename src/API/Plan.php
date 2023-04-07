<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Plan as PlanOptions;

class Plan extends ApiAbstract
{
    /**
     * Creates a new plan
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new PlanOptions\CreateOptions($params);

        $response = $this->httpClient->post('/plan', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves all plans
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params): array
    {
        $options = new PlanOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/plan', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves a plan
     * 
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/plan/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Updates a plan
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function update(string $id, array $params): array
    {
        $options = new PlanOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/plan/{$id}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}
