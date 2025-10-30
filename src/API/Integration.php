<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Integration\UpdateTimeoutOptions;

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
     * @param UpdateTimeoutOptions|array $options
     * @return array
     */
    public function updateTimeout(UpdateTimeoutOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new UpdateTimeoutOptions($options);
        }

        $response = $this->httpClient->put('/integration/payment_session_timeout', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}