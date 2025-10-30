<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class SettlementTest extends TestCase
{
    public function testListSettlements(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Settlements retrieved',
            'data' => [
                [
                    'id' => 123456,
                    'domain' => 'test',
                    'status' => 'success',
                    'currency' => 'NGN',
                    'integration' => 463433,
                    'total_amount' => 500000,
                    'effective_amount' => 485000,
                    'total_fees' => 15000,
                    'total_processed' => 500000,
                    'deductions' => 0,
                    'settlement_date' => '2023-11-15T00:00:00.000Z',
                    'settled_by' => 'Auto Settlement'
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
        
        $data = $this->client()->settlements->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/settlement', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListSettlementsWithNoParams(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Settlements retrieved',
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
        
        $data = $this->client()->settlements->all();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/settlement', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testGetSettlementTransactions(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Settlement transactions retrieved',
            'data' => [
                [
                    'id' => 54321,
                    'domain' => 'test',
                    'status' => 'success',
                    'reference' => 'txn_12345abcde',
                    'amount' => 50000,
                    'message' => null,
                    'gateway_response' => 'Successful',
                    'paid_at' => '2023-11-14T14:30:00.000Z',
                    'created_at' => '2023-11-14T14:30:00.000Z',
                    'channel' => 'card',
                    'currency' => 'NGN',
                    'ip_address' => '192.168.1.1',
                    'metadata' => [],
                    'log' => null,
                    'fees' => 750,
                    'fees_split' => null,
                    'authorization' => [
                        'authorization_code' => 'AUTH_abcd1234',
                        'bin' => '408408',
                        'last4' => '4081',
                        'exp_month' => '12',
                        'exp_year' => '2030',
                        'channel' => 'card',
                        'card_type' => 'visa DEBIT',
                        'bank' => 'Test Bank',
                        'country_code' => 'NG',
                        'brand' => 'visa',
                        'reusable' => true,
                        'signature' => 'SIG_abc123'
                    ],
                    'customer' => [
                        'id' => 98765,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'customer_code' => 'CUS_abc123def',
                        'phone' => '+2348012345678',
                        'metadata' => [],
                        'risk_action' => 'default'
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
        
        $data = $this->client()->settlements->getTransactions('123456', ['page' => 1]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/settlement/123456/transactions', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testGetSettlementTransactionsWithNoParams(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Settlement transactions retrieved',
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
        
        $data = $this->client()->settlements->getTransactions('123456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/settlement/123456/transactions', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}