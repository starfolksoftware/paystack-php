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
     * @param ChargeOptions\CreateOptions|array $options
     * @return array
     */
    public function create(ChargeOptions\CreateOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ChargeOptions\CreateOptions($options);
        }

        $response = $this->httpClient->post('/charge', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit PIN to continue a charge
     * 
     * @param ChargeOptions\SubmitPinOptions|array $options
     * @return array
     */
    public function submitPin(ChargeOptions\SubmitPinOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ChargeOptions\SubmitPinOptions($options);
        }

        $response = $this->httpClient->post('/charge/submit_pin', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit OTP to complete a charge
     * 
     * @param ChargeOptions\SubmitOtpOptions|array $options
     * @return array
     */
    public function submitOtp(ChargeOptions\SubmitOtpOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ChargeOptions\SubmitOtpOptions($options);
        }

        $response = $this->httpClient->post('/charge/submit_otp', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit phone when requested
     * 
     * @param ChargeOptions\SubmitPhoneOptions|array $options
     * @return array
     */
    public function submitPhone(ChargeOptions\SubmitPhoneOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ChargeOptions\SubmitPhoneOptions($options);
        }

        $response = $this->httpClient->post('/charge/submit_phone', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit birthday when requested
     * 
     * @param ChargeOptions\SubmitBirthdayOptions|array $options
     * @return array
     */
    public function submitBirthday(ChargeOptions\SubmitBirthdayOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ChargeOptions\SubmitBirthdayOptions($options);
        }

        $response = $this->httpClient->post('/charge/submit_birthday', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Submit address to continue charge
     * 
     * @param ChargeOptions\SubmitAddressOptions|array $options
     * @return array
     */
    public function submitAddress(ChargeOptions\SubmitAddressOptions|array $options): array
    {
        if (is_array($options)) {
            $options = new ChargeOptions\SubmitAddressOptions($options);
        }

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