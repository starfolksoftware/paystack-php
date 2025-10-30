<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class TransactionTest extends TestCase
{
    public function testInitializeTransaction(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => 'https://checkout.paystack.com/0peioxfhpn',
                'access_code' => '0peioxfhpn',
                'reference' => 'T563902343_1628168464'
            ]
        ];

        $expectedBody = json_encode([
            'email' => 'customer@email.com',
            'amount' => '10000',
            'reference' => 'T563902343_1628168464'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->initialize([
            'email' => 'customer@email.com',
            'amount' => '10000',
            'reference' => 'T563902343_1628168464'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transaction/initialize', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testVerifyTransaction(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'id' => 2009945086,
                'domain' => 'test',
                'status' => 'success',
                'reference' => 'T563902343_1628168464',
                'amount' => 10000,
                'currency' => 'NGN',
                'channel' => 'card',
                'fees' => 150,
                'authorization' => [
                    'authorization_code' => 'AUTH_6tmt288t0o',
                    'bin' => '408408',
                    'last4' => '4081',
                    'exp_month' => '12',
                    'exp_year' => '2030',
                    'channel' => 'card',
                    'card_type' => 'visa',
                    'bank' => 'TEST BANK',
                    'country_code' => 'NG',
                    'brand' => 'visa',
                    'reusable' => true,
                    'signature' => 'SIG_MRn7q9jJeqEWV3fD25vD'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->verify('T563902343_1628168464');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transaction/verify/T563902343_1628168464', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListTransactions(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transactions retrieved',
            'data' => [
                [
                    'id' => 2009945086,
                    'domain' => 'test',
                    'status' => 'success',
                    'reference' => 'T563902343_1628168464',
                    'amount' => 10000,
                    'currency' => 'NGN',
                    'channel' => 'card'
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
        
        $data = $this->client()->transactions->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transaction', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchTransaction(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transaction retrieved',
            'data' => [
                'id' => 2009945086,
                'domain' => 'test',
                'status' => 'success',
                'reference' => 'T563902343_1628168464',
                'amount' => 10000,
                'currency' => 'NGN',
                'channel' => 'card'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->find('2009945086');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transaction/2009945086', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testChargeAuthorization(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge attempted',
            'data' => [
                'id' => 2009945086,
                'domain' => 'test',
                'status' => 'success',
                'reference' => 'T563902343_1628168464',
                'amount' => 10000,
                'currency' => 'NGN',
                'channel' => 'card',
                'authorization' => [
                    'authorization_code' => 'AUTH_6tmt288t0o'
                ]
            ]
        ];

        $expectedBody = json_encode([
            'authorization_code' => 'AUTH_6tmt288t0o',
            'email' => 'customer@email.com',
            'amount' => '10000'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->charge([
            'authorization_code' => 'AUTH_6tmt288t0o',
            'email' => 'customer@email.com',
            'amount' => '10000'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transaction/charge_authorization', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testCheckAuthorization(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Authorization details retrieved',
            'data' => [
                'amount' => 10000,
                'currency' => 'NGN',
                'transaction_date' => '2024-10-30T14:55:19.000Z',
                'status' => 'success'
            ]
        ];

        $expectedBody = json_encode([
            'amount' => '10000',
            'email' => 'customer@email.com',
            'authorization_code' => 'AUTH_6tmt288t0o',
            'currency' => 'NGN'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->checkAuthorization(
            '10000',
            'customer@email.com',
            'AUTH_6tmt288t0o',
            'NGN'
        );

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transaction/check_authorization', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testGetTransactionTimeline(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Timeline retrieved',
            'data' => [
                'time_spent' => 5,
                'attempts' => 1,
                'authentication' => 'pin',
                'errors' => 0,
                'success' => true,
                'mobile' => false,
                'input' => [],
                'channel' => 'card',
                'history' => [
                    [
                        'type' => 'action',
                        'message' => 'Attempted to pay',
                        'time' => 7
                    ]
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->timeline('2009945086');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transaction/timeline/2009945086', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testGetTransactionTotals(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transaction totals',
            'data' => [
                'total_transactions' => 1,
                'unique_customers' => 1,
                'total_volume' => 10000,
                'total_volume_by_currency' => [
                    [
                        'currency' => 'NGN',
                        'amount' => 10000
                    ]
                ],
                'pending_transfers' => 0,
                'pending_transfers_by_currency' => []
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transactions->stats(['page' => 1]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transaction/totals', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}