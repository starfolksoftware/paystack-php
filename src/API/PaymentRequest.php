<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\PaymentRequest as PaymentRequestOptions;
use StarfolkSoftware\Paystack\Response\PaystackResponse;
use StarfolkSoftware\Paystack\Response\PaginatedResponse;

class PaymentRequest extends ApiAbstract
{
    /**
     * Create a payment request
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new PaymentRequestOptions\CreateOptions($params);

        $response = $this->httpClient->post('/paymentrequest', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Create a payment request (typed response)
     * 
     * @param array $params
     * @return PaystackResponse
     */
    public function createTyped(array $params): PaystackResponse
    {
        $options = new PaymentRequestOptions\CreateOptions($params);

        $response = $this->httpClient->post('/paymentrequest', body: json_encode($options->all()));

        return ResponseMediator::getPaymentRequestResponse($response);
    }

    /**
     * List payment requests
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new PaymentRequestOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/paymentrequest', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * List payment requests (typed response)
     * 
     * @param array $params
     * @return PaginatedResponse
     */
    public function allTyped(array $params = []): PaginatedResponse
    {
        $options = new PaymentRequestOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/paymentrequest', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getPaymentRequestListResponse($response);
    }

    /**
     * Fetch a payment request
     * 
     * @param string $idOrCode
     * @return array
     */
    public function fetch(string $idOrCode): array
    {
        $response = $this->httpClient->get("/paymentrequest/{$idOrCode}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Verify a payment request
     * 
     * @param string $code
     * @return array
     */
    public function verify(string $code): array
    {
        $response = $this->httpClient->get("/paymentrequest/verify/{$code}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Send notification for a payment request
     * 
     * @param string $code
     * @return array
     */
    public function sendNotification(string $code): array
    {
        $response = $this->httpClient->post("/paymentrequest/notify/{$code}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Get payment request totals
     * 
     * @return array
     */
    public function totals(): array
    {
        $response = $this->httpClient->get("/paymentrequest/totals");

        return ResponseMediator::getContent($response);
    }

    /**
     * Finalize a draft payment request
     * 
     * @param string $code
     * @param array $params
     * @return array
     */
    public function finalize(string $code, array $params = []): array
    {
        $response = $this->httpClient->post("/paymentrequest/finalize/{$code}", body: json_encode($params));

        return ResponseMediator::getContent($response);
    }

    /**
     * Update a payment request
     * 
     * @param string $idOrCode
     * @param array $params
     * @return array
     */
    public function update(string $idOrCode, array $params): array
    {
        $options = new PaymentRequestOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/paymentrequest/{$idOrCode}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Archive a payment request
     * 
     * @param string $code
     * @return array
     */
    public function archive(string $code): array
    {
        $response = $this->httpClient->post("/paymentrequest/archive/{$code}");

        return ResponseMediator::getContent($response);
    }
}
