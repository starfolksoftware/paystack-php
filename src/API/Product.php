<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Product as ProductOptions;

class Product extends ApiAbstract
{
    /**
     * Create a product on your integration
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new ProductOptions\CreateOptions($params);

        $response = $this->httpClient->post('/product', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * List products available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new ProductOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/product', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a product on your integration
     * 
     * @param string $id
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/product/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update a product details on your integration
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function update(string $id, array $params): array
    {
        $options = new ProductOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/product/{$id}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}