<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class Miscellaneous extends ApiAbstract
{
    /**
     * Get a list of all supported banks and their properties
     * 
     * @param array $params
     * @return array
     */
    public function listBanks(array $params = []): array
    {
        $response = $this->httpClient->get('/bank', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Gets a list of countries that Paystack currently supports
     * 
     * @return array
     */
    public function listCountries(): array
    {
        $response = $this->httpClient->get('/country');

        return ResponseMediator::getContent($response);
    }

    /**
     * Get a list of states for a country for address verification
     * 
     * @param array $params
     * @return array
     */
    public function listStates(array $params): array
    {
        $response = $this->httpClient->get('/address_verification/states', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }
}