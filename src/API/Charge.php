<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Charge as ChargeOptions;

class Charge extends ApiAbstract
{
    /**
     * Initiate a payment by integrating the payment channel of choice
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new ChargeOptions\CreateOptions($params);

        $response = $this->httpClient->post('/charge', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit PIN to continue a charge
     * 
     * @param array $params
     * @return array
     */
    public function submitPin(array $params): array
    {
        $options = new ChargeOptions\SubmitPinOptions($params);

        $response = $this->httpClient->post('/charge/submit_pin', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit OTP to complete a charge
     * 
     * @param array $params
     * @return array
     */
    public function submitOtp(array $params): array
    {
        $options = new ChargeOptions\SubmitOtpOptions($params);

        $response = $this->httpClient->post('/charge/submit_otp', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit phone when requested
     * 
     * @param array $params
     * @return array
     */
    public function submitPhone(array $params): array
    {
        $options = new ChargeOptions\SubmitPhoneOptions($params);

        $response = $this->httpClient->post('/charge/submit_phone', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit birthday when requested
     * 
     * @param array $params
     * @return array
     */
    public function submitBirthday(array $params): array
    {
        $options = new ChargeOptions\SubmitBirthdayOptions($params);

        $response = $this->httpClient->post('/charge/submit_birthday', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit address to continue charge
     * 
     * @param array $params
     * @return array
     */
    public function submitAddress(array $params): array
    {
        $options = new ChargeOptions\SubmitAddressOptions($params);

        $response = $this->httpClient->post('/charge/submit_address', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Check pending charge
     * 
     * @param string $reference
     * @return array
     */
    public function checkPending(string $reference): array
    {
        $response = $this->httpClient->get("/charge/{$reference}");

        return ResponseMediator::getContent($response);
    }
}