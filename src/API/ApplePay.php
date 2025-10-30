<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\ApplePay\{
    RegisterDomainOptions,
    ListDomainsOptions,
    UnregisterDomainOptions
};

class ApplePay extends ApiAbstract
{
    /**
     * Register a top-level domain or subdomain for your Apple Pay integration
     * 
     * @param RegisterDomainOptions|array $options
     * @return array
     */
    public function registerDomain(RegisterDomainOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new RegisterDomainOptions($options);
        }

        $response = $this->httpClient->post('/apple-pay/domain', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Lists all registered domains on your integration
     * 
     * @param ListDomainsOptions|array $options
     * @return array
     */
    public function listDomains(ListDomainsOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new ListDomainsOptions($options);
        }

        $requestOptions = [];
        $optionsArray = $options->all();
        if (!empty($optionsArray)) {
            $requestOptions['query'] = $optionsArray;
        }

        $response = $this->httpClient->get('/apple-pay/domain', $requestOptions);

        return ResponseMediator::getContent($response);
    }

    /**
     * Unregister a top-level domain or subdomain previously used for your Apple Pay integration
     * 
     * @param UnregisterDomainOptions|array $options
     * @return array
     */
    public function unregisterDomain(UnregisterDomainOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new UnregisterDomainOptions($options);
        }

        $response = $this->httpClient->delete('/apple-pay/domain', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}