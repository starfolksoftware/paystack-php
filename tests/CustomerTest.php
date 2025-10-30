<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class CustomerTest extends TestCase
{
    public function testCreateCustomer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customer created',
            'data' => [
                'email' => 'customer@email.com',
                'integration' => 463433,
                'domain' => 'test',
                'customer_code' => 'CUS_xnxdt6s1zg5f4tx',
                'id' => 1173,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '08012345678'
            ]
        ];

        $expectedBody = json_encode([
            'email' => 'customer@email.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '08012345678'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->create([
            'email' => 'customer@email.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '08012345678'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/customer', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListCustomers(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customers retrieved',
            'data' => [
                [
                    'id' => 1173,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'customer@email.com',
                    'customer_code' => 'CUS_xnxdt6s1zg5f4tx',
                    'phone' => '08012345678',
                    'metadata' => null,
                    'risk_action' => 'default'
                ]
            ],
            'meta' => [
                'total' => 1,
                'skipped' => 0,
                'perPage' => 50,
                'page' => 1,
                'pageCount' => 1
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/customer', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchCustomer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customer retrieved',
            'data' => [
                'id' => 1173,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'customer@email.com',
                'customer_code' => 'CUS_xnxdt6s1zg5f4tx',
                'phone' => '08012345678',
                'metadata' => null,
                'risk_action' => 'default',
                'authorizations' => []
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->find('CUS_xnxdt6s1zg5f4tx');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/customer/CUS_xnxdt6s1zg5f4tx', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchCustomerByEmail(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customer retrieved',
            'data' => [
                'id' => 1173,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'customer@email.com',
                'customer_code' => 'CUS_xnxdt6s1zg5f4tx',
                'phone' => '08012345678',
                'metadata' => null,
                'risk_action' => 'default',
                'authorizations' => []
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->find('customer@email.com');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/customer/customer@email.com', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateCustomer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customer updated',
            'data' => [
                'id' => 1173,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'customer@email.com',
                'customer_code' => 'CUS_xnxdt6s1zg5f4tx',
                'phone' => '08087654321',
                'metadata' => null,
                'risk_action' => 'default'
            ]
        ];

        $expectedBody = json_encode([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '08087654321'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->update('CUS_xnxdt6s1zg5f4tx', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '08087654321'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/customer/CUS_xnxdt6s1zg5f4tx', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testValidateCustomer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customer Identification in progress',
            'data' => [
                'country' => 'NG',
                'type' => 'bvn',
                'value' => '***********',
                'verified' => false
            ]
        ];

        $expectedBody = json_encode([
            'country' => 'NG',
            'type' => 'bank_account',
            'value' => '12345678901',
            'account_number' => '1234567890',
            'bvn' => '12345678901',
            'bank_code' => '058',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->validate('CUS_xnxdt6s1zg5f4tx', [
            'country' => 'NG',
            'type' => 'bank_account',
            'value' => '12345678901',
            'account_number' => '1234567890',
            'bvn' => '12345678901',
            'bank_code' => '058',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/customer/CUS_xnxdt6s1zg5f4tx/identification', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testSetRiskAction(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Customer updated',
            'data' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'customer@email.com',
                'customer_code' => 'CUS_xnxdt6s1zg5f4tx',
                'phone' => '08012345678',
                'risk_action' => 'allow'
            ]
        ];

        $expectedBody = json_encode([
            'code' => 'CUS_xnxdt6s1zg5f4tx',
            'risk_action' => 'allow'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->setRiskAction('CUS_xnxdt6s1zg5f4tx', 'allow');

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/customer/set_risk_action', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testDeactivateAuthorization(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Authorization has been deactivated'
        ];

        $expectedBody = json_encode([
            'authorization_code' => 'AUTH_6tmt288t0o'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->customers->deactivateAuthorization('AUTH_6tmt288t0o');

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/customer/deactivate_authorization', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}