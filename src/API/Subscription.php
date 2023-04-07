<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Subscription as SubscriptionOptions;

class Subscription extends ApiAbstract
{
    /**
     * Creates a new subscription
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new SubscriptionOptions\CreateOptions($params);

        $response = $this->httpClient->post('/subscription', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves all subscriptions
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params): array
    {
        $options = new SubscriptionOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/subscription', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves a subscription
     * 
     * @param string $id
     * 
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/subscription/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Enable a subscription
     * 
     * @param string $code Subscription code
     * @param string $token Email token
     * @return array
     */
    public function enable(string $code, string $token): array
    {
        $response = $this->httpClient->post("/subscription/enable", body: json_encode(['code' => $code, 'token' => $token]));

        return ResponseMediator::getContent($response);
    }

    /**
     * Disable a subscription
     * 
     * @param string $code Subscription code
     * @param string $token Email token
     * @return array
     */
    public function disable(string $code, string $token): array
    {
        $response = $this->httpClient->post("/subscription/disable", body: json_encode(['code' => $code, 'token' => $token]));

        return ResponseMediator::getContent($response);
    }

    /**
     * Generate a link for updating the card on a subscription
     * 
     * @param string $code Subscription code
     * @return array
     */
    public function genSubCardUpdateLink(string $code): array
    {
        $response = $this->httpClient->get("/subscription/{$code}/manage/link");

        return ResponseMediator::getContent($response);
    }

    /**
     * Email a customer a link for updating the card on their subscription
     * 
     * @param string $code Subscription code
     * @return array
     */
    public function sendSubCardUpdateLink(string $code): array
    {
        $response = $this->httpClient->get("/subscription/{$code}/manage/email");

        return ResponseMediator::getContent($response);
    }
}
