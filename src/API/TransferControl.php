<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;

class TransferControl extends ApiAbstract
{
    /**
     * Fetch the available balance on your integration
     * 
     * @return array
     */
    public function checkBalance(): array
    {
        $response = $this->httpClient->get('/balance');

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieve your balance ledger
     * 
     * @param array $params
     * @return array
     */
    public function getBalanceLedger(array $params = []): array
    {
        $response = $this->httpClient->get('/balance/ledger', [
            'query' => $params
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Generate a new OTP and send to customer in the event they are having trouble receiving one
     * 
     * @param array $params
     * @return array
     */
    public function resendOtp(array $params): array
    {
        $response = $this->httpClient->post('/transfer/resend_otp', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Disable OTP requirement for transfers
     * 
     * @return array
     */
    public function disableOtp(): array
    {
        $response = $this->httpClient->post('/transfer/disable_otp');

        return ResponseMediator::getContent($response);
    }

    /**
     * Enable OTP requirement for transfers  
     * 
     * @return array
     */
    public function enableOtp(): array
    {
        $response = $this->httpClient->post('/transfer/enable_otp');

        return ResponseMediator::getContent($response);
    }

    /**
     * Finalize disabling of OTP requirement for transfers
     * 
     * @param array $params
     * @return array
     */
    public function finalizeDisableOtp(array $params): array
    {
        $response = $this->httpClient->post('/transfer/disable_otp_finalize', body: json_encode($params));

        return ResponseMediator::getContent($response);
    }
}