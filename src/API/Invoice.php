<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Invoice as InvoiceOptions;

class Invoice extends ApiAbstract
{
    /**
     * Creates a new invoice
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new InvoiceOptions\CreateOptions($params);

        $response = $this->httpClient->post('/paymentrequest', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves all invoices
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params): array
    {
        $options = new InvoiceOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/paymentrequest', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves a invoice
     * 
     * @param string $id The invoice ID or code you want to fetch
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/paymentrequest/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Verify details of an invoice on your integration.
     * 
     * @param string $id The invoice ID or code you want to fetch
     * @return array
     */
    public function verify(string $id): array
    {
        $response = $this->httpClient->get("/paymentrequest/verify/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Send notification of an invoice to your customers.
     * 
     * @param string $id The invoice ID or code you want to fetch
     * @return array
     */
    public function notify(string $id): array
    {
        $response = $this->httpClient->post("/paymentrequest/notify/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Finalize a Draft Invoice.
     * 
     * @param string $id The invoice ID or code you want to fetch
     * @return array
     */
    public function finalize(string $id): array
    {
        $response = $this->httpClient->post("/paymentrequest/finalize/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Archive an invoice. Invoice will no longer be fetched on list or returned on verify.
     * 
     * @param string $id The invoice ID or code you want to fetch
     * @return array
     */
    public function archive(string $id): array
    {
        $response = $this->httpClient->post("/paymentrequest/archive/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Get invoice metrics for dashboard
     * 
     * @return array
     */
    public function stats(): array
    {
        $response = $this->httpClient->get("/paymentrequest/totals");

        return ResponseMediator::getContent($response);
    }

    /**
     * Updates a invoice
     * 
     * @param string $id
     * @param array $params
     * @return array
     */
    public function update(string $id, array $params): array
    {
        $options = new InvoiceOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/paymentrequest/{$id}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }
}