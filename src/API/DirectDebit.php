<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class DirectDebit extends ApiAbstract
{
    /**
     * Trigger an activation charge on pending mandates on behalf of your customers
     * 
     * @param array $params
     * @return array
     */
    public function triggerActivationCharge(array $params): array
    {
        $response = $this->httpClient->put('/directdebit/activation-charge', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Get the list of direct debit mandates on your integration
     * 
     * @param array $params
     * @return array
     */
    public function listMandateAuthorizations(array $params = []): array
    {
        $response = $this->httpClient->get('/directdebit/mandate-authorizations', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }
}