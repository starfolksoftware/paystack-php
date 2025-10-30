<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\DirectDebit\{
    TriggerActivationChargeOptions,
    ListMandateAuthorizationsOptions
};

class DirectDebit extends ApiAbstract
{
    /**
     * Trigger an activation charge on pending mandates on behalf of your customers
     * 
     * @param TriggerActivationChargeOptions|array $options
     * @return array
     */
    public function triggerActivationCharge(TriggerActivationChargeOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new TriggerActivationChargeOptions($options);
        }

        $response = $this->httpClient->put('/directdebit/activation-charge', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Get the list of direct debit mandates on your integration
     * 
     * @param ListMandateAuthorizationsOptions|array $options
     * @return array
     */
    public function listMandateAuthorizations(ListMandateAuthorizationsOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new ListMandateAuthorizationsOptions($options);
        }

        $requestOptions = [];
        $optionsArray = $options->all();
        if (!empty($optionsArray)) {
            $requestOptions['query'] = $optionsArray;
        }

        $response = $this->httpClient->get('/directdebit/mandate-authorizations', $requestOptions);

        return ResponseMediator::getContent($response);
    }
}