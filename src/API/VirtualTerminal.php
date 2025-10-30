<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\VirtualTerminal as VirtualTerminalOptions;

class VirtualTerminal extends ApiAbstract
{
    /**
     * Create a Virtual Terminal on your integration
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new VirtualTerminalOptions\CreateOptions($params);

        $response = $this->httpClient->post('/virtual_terminal', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * List Virtual Terminals on your integration
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        $options = new VirtualTerminalOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/virtual_terminal', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Fetch a Virtual Terminal on your integration
     * 
     * @param string $code
     * @return array
     */
    public function find(string $code): array
    {
        $response = $this->httpClient->get("/virtual_terminal/{$code}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Update a Virtual Terminal on your integration
     * 
     * @param string $code
     * @param array $params
     * @return array
     */
    public function update(string $code, array $params): array
    {
        $options = new VirtualTerminalOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/virtual_terminal/{$code}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Deactivate a Virtual Terminal on your integration
     * 
     * @param string $code
     * @return array
     */
    public function deactivate(string $code): array
    {
        $response = $this->httpClient->put("/virtual_terminal/{$code}/deactivate");

        return ResponseMediator::getContent($response);
    }

    /**
     * Add a destination (WhatsApp number) to a Virtual Terminal on your integration
     * 
     * @param string $code
     * @param array $params
     * @return array
     */
    public function assignDestination(string $code, array $params): array
    {
        $options = new VirtualTerminalOptions\AssignDestinationOptions($params);

        $response = $this->httpClient->post("/virtual_terminal/{$code}/destination/assign", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Unassign a destination (WhatsApp Number) from a Virtual Terminal on your integration
     * 
     * @param string $code
     * @param array $params
     * @return array
     */
    public function unassignDestination(string $code, array $params): array
    {
        $options = new VirtualTerminalOptions\UnassignDestinationOptions($params);

        $response = $this->httpClient->post("/virtual_terminal/{$code}/destination/unassign", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Add a split code to a Virtual Terminal on your integration
     * 
     * @param string $code
     * @param array $params
     * @return array
     */
    public function addSplitCode(string $code, array $params): array
    {
        $options = new VirtualTerminalOptions\SplitCodeOptions($params);

        $response = $this->httpClient->put("/virtual_terminal/{$code}/split_code", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Remove a split code from a Virtual Terminal on your integration
     * 
     * @param string $code
     * @param array $params
     * @return array
     */
    public function removeSplitCode(string $code, array $params): array
    {
        $options = new VirtualTerminalOptions\SplitCodeOptions($params);

        $response = $this->httpClient->delete("/virtual_terminal/{$code}/split_code", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}