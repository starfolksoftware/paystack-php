<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\API;

use StarfolkSoftware\Paystack\Abstracts\ApiAbstract;
use StarfolkSoftware\Paystack\HttpClient\Message\ResponseMediator;
use StarfolkSoftware\Paystack\Options\Customer as CustomerOptions;

final class Customer extends ApiAbstract
{
    /**
     * Creates a new customer
     * 
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $options = new CustomerOptions\CreateOptions($params);

        $response = $this->httpClient->post('/customer', body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves all customers
     * 
     * @param array $params
     * @return array
     */
    public function all(array $params): array
    {
        $options = new CustomerOptions\ReadAllOptions($params);

        $response = $this->httpClient->get('/customer', [
            'query' => $options->all()
        ]);

        return ResponseMediator::getContent($response);
    }

    /**
     * Retrieves a customer
     * 
     * @param string $id An email or customer code for the customer you want to fetch
     * @return array
     */
    public function find(string $id): array
    {
        $response = $this->httpClient->get("/customer/{$id}");

        return ResponseMediator::getContent($response);
    }

    /**
     * Updates a customer
     * 
     * @param string $id Customer's code
     * @param array $params
     * @return array
     */
    public function update(string $id, array $params): array
    {
        $options = new CustomerOptions\UpdateOptions($params);

        $response = $this->httpClient->put("/customer/{$id}", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Validate a customer's identity
     * 
     * @param string $code Subscription code
     * @param array $params Paras
     * @return array
     */
    public function validate(string $code, array $params): array
    {
        $options = new CustomerOptions\ValidateOptions($params);

        $response = $this->httpClient->post("/customer/{$code}/identification", body: json_encode($options->all()));

        return ResponseMediator::getContent($response);
    }

    /**
     * Whitelist or blacklist a customer on your integration
     * 
     * @param string $code Customer's code, or email address
     * @param string $riskAction One of the possible risk actions [ default, allow, deny ]. allow to whitelist. deny to blacklist. Customers start with a default risk action.
     * @return array
     */
    public function setRiskAction(string $code, string $riskAction = 'default'): array
    {
        $response = $this->httpClient->post("/customer/set_risk_action", body: json_encode(['code' => $code, 'risk_action' => $riskAction]));

        return ResponseMediator::getContent($response);
    }

    /**
     * Deactivate an authorization when the card needs to be forgotten
     * 
     * @param string $authorizationCode Authorization code to be deactivated
     * @return array
     */
    public function deactivateAuthorization(string $authorizationCode): array
    {
        $response = $this->httpClient->post("/customer/deactivate_authorization", body: json_encode(['authorization_code' => $authorizationCode]));

        return ResponseMediator::getContent($response);
    }
}
