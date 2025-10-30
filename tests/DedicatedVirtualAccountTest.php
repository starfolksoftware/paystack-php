<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class DedicatedVirtualAccountTest extends TestCase
{
    public function testCreateDedicatedVirtualAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual account created',
            'data' => [
                'bank' => [
                    'name' => 'Test Bank',
                    'id' => 1,
                    'slug' => 'test-bank'
                ],
                'account_name' => 'ACME Corp/John Doe',
                'account_number' => '9991234567',
                'assigned' => true,
                'currency' => 'NGN',
                'metadata' => null,
                'active' => true,
                'id' => 45678,
                'created_at' => '2023-11-16T15:00:00.000Z',
                'updated_at' => '2023-11-16T15:00:00.000Z',
                'assignment' => [
                    'integration' => 463433,
                    'assignee_id' => 87654,
                    'assignee_type' => 'Customer',
                    'expired' => false,
                    'account_type' => 'PAY-WITH-BANK-TRANSFER',
                    'assigned_at' => '2023-11-16T15:00:00.000Z'
                ],
                'customer' => [
                    'id' => 87654,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com',
                    'customer_code' => 'CUS_abc123def456'
                ]
            ]
        ];

        $expectedBody = json_encode([
            'customer' => 'CUS_abc123def456',
            'preferred_bank' => 'test-bank'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->create([
            'customer' => 'CUS_abc123def456',
            'preferred_bank' => 'test-bank'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListDedicatedVirtualAccounts(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual accounts retrieved',
            'data' => [
                [
                    'bank' => [
                        'name' => 'Test Bank',
                        'id' => 1,
                        'slug' => 'test-bank'
                    ],
                    'account_name' => 'ACME Corp/John Doe',
                    'account_number' => '9991234567',
                    'assigned' => true,
                    'currency' => 'NGN',
                    'active' => true,
                    'id' => 45678,
                    'created_at' => '2023-11-16T15:00:00.000Z',
                    'customer' => [
                        'id' => 87654,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com'
                    ]
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
        
        $data = $this->client()->dedicatedVirtualAccounts->all(['currency' => 'NGN']);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchDedicatedVirtualAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual account retrieved',
            'data' => [
                'transactions' => [
                    [
                        'id' => 12345,
                        'domain' => 'test',
                        'status' => 'success',
                        'reference' => 'dva_abc123def456',
                        'amount' => 100000,
                        'currency' => 'NGN',
                        'paid_at' => '2023-11-16T16:00:00.000Z',
                        'channel' => 'bank_transfer'
                    ]
                ],
                'subscriptions' => [],
                'authorizations' => [],
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+2348012345678',
                'metadata' => null,
                'domain' => 'test',
                'customer_code' => 'CUS_abc123def456',
                'id' => 87654,
                'integration' => 463433,
                'created_at' => '2023-11-15T10:00:00.000Z',
                'updated_at' => '2023-11-16T16:00:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->find('45678');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account/45678', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testRequeryDedicatedVirtualAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual account requeried',
            'data' => [
                'transactions' => [
                    [
                        'id' => 12346,
                        'domain' => 'test',
                        'status' => 'success',
                        'reference' => 'dva_new123def456',
                        'amount' => 50000,
                        'currency' => 'NGN',
                        'paid_at' => '2023-11-16T17:00:00.000Z',
                        'channel' => 'bank_transfer'
                    ]
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->requery([
            'account_number' => '9991234567',
            'provider_slug' => 'test-bank',
            'date' => '2023-11-16'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account/requery', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testDeactivateDedicatedVirtualAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual account deactivated',
            'data' => [
                'bank' => [
                    'name' => 'Test Bank',
                    'id' => 1,
                    'slug' => 'test-bank'
                ],
                'account_name' => 'ACME Corp/John Doe',
                'account_number' => '9991234567',
                'assigned' => false,
                'active' => false,
                'id' => 45678
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->deactivate('45678');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('DELETE', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account/45678', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testSplitDedicatedVirtualAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual account split successfully',
            'data' => [
                'id' => 45678,
                'split_config' => [
                    'subaccount' => 'ACCT_8f4s1eq7ml6rlzj',
                    'share' => 20
                ]
            ]
        ];

        $expectedBody = json_encode([
            'customer' => 'CUS_abc123def456',
            'subaccount' => 'ACCT_8f4s1eq7ml6rlzj',
            'split_code' => 'SPL_e7jnRLtzla'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->split([
            'customer' => 'CUS_abc123def456',
            'subaccount' => 'ACCT_8f4s1eq7ml6rlzj',
            'split_code' => 'SPL_e7jnRLtzla'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account/split', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testRemoveSplitFromDedicatedVirtualAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Split removed from dedicated virtual account',
            'data' => [
                'id' => 45678,
                'split_config' => null
            ]
        ];

        $expectedBody = json_encode([
            'account_number' => '9991234567'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->removeSplit([
            'account_number' => '9991234567'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('DELETE', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account/split', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testGetProviders(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dedicated virtual account providers retrieved',
            'data' => [
                [
                    'provider_slug' => 'test-bank',
                    'bank_id' => 1,
                    'bank_name' => 'Test Bank'
                ],
                [
                    'provider_slug' => 'wema-bank',
                    'bank_id' => 2,
                    'bank_name' => 'Wema Bank'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->dedicatedVirtualAccounts->getProviders();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dedicated_account/available_providers', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}