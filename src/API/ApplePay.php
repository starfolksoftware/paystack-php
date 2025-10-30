<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class ApplePay extends ApiAbstract
{
    /**
     * Register a top-level domain or subdomain for your Apple Pay integration
     * 
     * @param array $params
     * @return array
     */
    public function registerDomain(array $params): array
    {
        $response = $this->httpClient->post('/apple-pay/domain', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Lists all registered domains on your integration
     * 
     * @param array $params
     * @return array
     */
    public function listDomains(array $params = []): array
    {
        $requestOptions = [];
        if (!empty($params)) {
            $requestOptions['query'] = $params;
        }

        $response = $this->httpClient->get('/apple-pay/domain', $requestOptions);

        return ResponseMediator::getContent($response);
    }

    /**
     * Unregister a top-level domain or subdomain previously used for your Apple Pay integration
     * 
     * @param array $params
     * @return array
     */
    public function unregisterDomain(array $params): array
    {
        $response = $this->httpClient->delete('/apple-pay/domain', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }
}