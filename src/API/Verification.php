<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Verification\{
    ResolveAccountOptions,
    ValidateAccountOptions
};

class Verification extends ApiAbstract
{
    /**
     * Confirm an account belongs to the right customer
     * 
     * @param ResolveAccountOptions|array $options
     * @return array
     */
    public function resolveAccount(ResolveAccountOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ResolveAccountOptions($options);
        }

        $params = $options->all();
        $queryString = http_build_query($params);
        
        $response = $this->httpClient->get("/bank/resolve?{$queryString}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Confirm the authenticity of a customer's account number before sending money
     * 
     * @param ValidateAccountOptions|array $options
     * @return array
     */
    public function validateAccount(ValidateAccountOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ValidateAccountOptions($options);
        }

        $response = $this->httpClient->post('/bank/validate', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Get more information about a customer's card
     * 
     * @param string $bin
     * @return array
     */
    public function resolveCardBin(string $bin): array
    {
        $response = $this->httpClient->get("/decision/bin/{$bin}");

        return ResponseMediator::getContent($response);
    }
}
