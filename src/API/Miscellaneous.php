<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Miscellaneous\{
    ListBanksOptions,
    ListStatesOptions
};

class Miscellaneous extends ApiAbstract
{
    /**
     * Get a list of all supported banks and their properties
     * 
     * @param ListBanksOptions|array $options
     * @return array
     */
    public function listBanks(ListBanksOptions|array $options = []): array
    {
        if (is_array($options)) {
            $options = new ListBanksOptions($options);
        }

        $requestOptions = [];
        $optionsArray = $options->all();
        if (!empty($optionsArray)) {
            $requestOptions['query'] = $optionsArray;
        }

        $response = $this->httpClient->get('/bank', $requestOptions);

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
     * @param ListStatesOptions|array $options
     * @return array
     */
    public function listStates(ListStatesOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ListStatesOptions($options);
        }

        $response = $this->httpClient->get('/address_verification/states', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }
}