<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Terminal as TerminalOptions;

class Terminal extends ApiAbstract
{
    /**
     * Send an event from your application to the Paystack Terminal
     * 
     * @param string $terminalId
     * @param array $params
     * @return array
     */
    public function sendEvent(string $terminalId, array $params): array
    {
        $options = new TerminalOptions\SendEventOptions($params);

        $response = $this->httpClient->post("/terminal/{$terminalId}/event", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Check the status of an event sent to the Terminal
     * 
     * @param string $terminalId
     * @param string $eventId
     * @return array
     */
    public function fetchEventStatus(string $terminalId, string $eventId): array
    {
        $response = $this->httpClient->get("/terminal/{$terminalId}/event/{$eventId}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Check the availiability of a Terminal before sending an event to it
     * 
     * @param string $terminalId
     * @return array
     */
    public function fetchTerminalStatus(string $terminalId): array
    {
        $response = $this->httpClient->get("/terminal/{$terminalId}/presence");

        return ResponseMediator::getContent($response);
    }

    /**
     * List the Terminals available on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new TerminalOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/terminal', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Get the details of a Terminal
     * 
     * @param string $terminalId
     * @return array
     */
    public function find(string $terminalId): array
    {
        $response = $this->httpClient->get("/terminal/{$terminalId}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update the details of a Terminal
     * 
     * @param string $terminalId
     * @param array $params
     * @return array
     */
    public function update(string $terminalId, array $params): array
    {
        $options = new TerminalOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/terminal/{$terminalId}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Activate your debug device by linking it to your integration
     * 
     * @param array $params
     * @return array
     */
    public function commission(array $params): array
    {
        $options = new TerminalOptions\CommissionOptions($params);

        $response = $this->httpClient->post('/terminal/commission_device', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Unlink your debug device from your integration
     * 
     * @param array $params
     * @return array
     */
    public function decommission(array $params): array
    {
        $options = new TerminalOptions\DecommissionOptions($params);

        $response = $this->httpClient->post('/terminal/decommission_device', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}