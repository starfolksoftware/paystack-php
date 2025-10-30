<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class DirectDebitTest extends TestCase
{
    public function testTriggerActivationCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Activation charge triggered successfully',
            'data' => [
                'mandate' => [
                    'id' => 12345,
                    'mandate_code' => 'MANDATE_abc123def456',
                    'status' => 'pending',
                    'customer' => [
                        'id' => 87654,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com'
                    ],
                    'bank' => [
                        'name' => 'Test Bank',
                        'code' => '044'
                    ],
                    'account_number' => '0123456789',
                    'account_name' => 'John Doe'
                ],
                'activation_charge' => [
                    'id' => 54321,
                    'amount' => 100,
                    'currency' => 'NGN',
                    'status' => 'pending',
                    'reference' => 'actv_abc123def456'
                ]
            ]
        ];

        $expectedBody = json_encode([
            'mandate_code' => 'MANDATE_abc123def456'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->directDebit->triggerActivationCharge([
            'mandate_code' => 'MANDATE_abc123def456'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/directdebit/activation-charge', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListMandateAuthorizations(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Direct debit mandate authorizations retrieved',
            'data' => [
                [
                    'id' => 12345,
                    'mandate_code' => 'MANDATE_abc123def456',
                    'status' => 'active',
                    'customer' => [
                        'id' => 87654,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'customer_code' => 'CUS_abc123def456'
                    ],
                    'bank' => [
                        'name' => 'Test Bank',
                        'code' => '044'
                    ],
                    'account_number' => '0123456789',
                    'account_name' => 'John Doe',
                    'created_at' => '2023-11-16T19:00:00.000Z',
                    'updated_at' => '2023-11-16T19:05:00.000Z'
                ],
                [
                    'id' => 12346,
                    'mandate_code' => 'MANDATE_def456ghi789',
                    'status' => 'pending',
                    'customer' => [
                        'id' => 87655,
                        'first_name' => 'Jane',
                        'last_name' => 'Smith',
                        'email' => 'jane.smith@example.com',
                        'customer_code' => 'CUS_def456ghi789'
                    ],
                    'bank' => [
                        'name' => 'Another Bank',
                        'code' => '058'
                    ],
                    'account_number' => '9876543210',
                    'account_name' => 'Jane Smith',
                    'created_at' => '2023-11-16T19:10:00.000Z',
                    'updated_at' => '2023-11-16T19:10:00.000Z'
                ]
            ],
            'meta' => [
                'total' => 2,
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
        
        $data = $this->client()->directDebit->listMandateAuthorizations(['status' => 'active']);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/directdebit/mandate-authorizations', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListMandateAuthorizationsWithNoParams(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Direct debit mandate authorizations retrieved',
            'data' => [],
            'meta' => [
                'total' => 0,
                'skipped' => 0,
                'perPage' => 50,
                'page' => 1,
                'pageCount' => 0
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->directDebit->listMandateAuthorizations();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/directdebit/mandate-authorizations', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}