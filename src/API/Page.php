<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Page extends ApiAbstract
{
    /**
     * Create a payment page
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $response = $this->httpClient->post('/page', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * List payment pages available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $response = $this->httpClient->get('/page', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get details of a payment page
     * 
     * @param string $idOrSlug
     * @return array
     */
    public function find(string $idOrSlug): array
    {
        $response = $this->httpClient->get("/page/{$idOrSlug}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update a payment page
     * 
     * @param string $idOrSlug
     * @param array $params
     * @return array
     */
    public function update(string $idOrSlug, array $params): array
    {
        $response = $this->httpClient->put("/page/{$idOrSlug}", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Check the availability of a slug for a payment page
     * 
     * @param string $slug
     * @return array
     */
    public function checkSlugAvailability(string $slug): array
    {
        $response = $this->httpClient->get("/page/check_slug_availability/{$slug}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Add products to a payment page
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function addProducts(string $id, array $params): array
    {
        $response = $this->httpClient->post("/page/{$id}/product", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }
}