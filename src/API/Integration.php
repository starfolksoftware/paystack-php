<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Integration extends ApiAbstract
{
    /**
     * Fetch the payment session timeout on your integration
     * 
     * @return array
     */
    public function fetchTimeout(): array
    {
        $response = $this->httpClient->get('/integration/payment_session_timeout');

        return ResponseMediator::getContent($response);
    }

    /**
     * Update the payment session timeout on your integration
     * 
     * @param array $params
     * @return array
     */
    public function updateTimeout(array $params): array
    {
        $response = $this->httpClient->put('/integration/payment_session_timeout', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }
}