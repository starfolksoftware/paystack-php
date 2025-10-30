<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Verification extends ApiAbstract
{
    /**
     * Confirm an account belongs to the right customer
     * 
     * @param array $params
     * @return array
     */
    public function resolveAccount(array $params): array
    {
        $response = $this->httpClient->get('/bank/resolve', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Confirm the authenticity of a customer's account number before sending money
     * 
     * @param array $params
     * @return array
     */
    public function validateAccount(array $params): array
    {
        $response = $this->httpClient->post('/bank/validate', body: json_encode($params));

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