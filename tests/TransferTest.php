<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class TransferTest extends TestCase
{
    public function testInitiateTransfer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transfer requires OTP to continue',
            'data' => [
                'integration' => 463433,
                'domain' => 'test',
                'amount' => 3794800,
                'currency' => 'NGN',
                'source' => 'balance',
                'reason' => 'Calm down',
                'recipient' => 1943003,
                'status' => 'otp',
                'transfer_code' => 'TRF_vsyqdmlzble3uii',
                'id' => 14956454
            ]
        ];

        $expectedBody = json_encode([
            'source' => 'balance',
            'amount' => 3794800,
            'recipient' => 'RCP_gx2wn530m0i3w3m',
            'reason' => 'Calm down'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transfers->initiate([
            'source' => 'balance',
            'amount' => 3794800,
            'recipient' => 'RCP_gx2wn530m0i3w3m',
            'reason' => 'Calm down'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testFinalizeTransfer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transfer has been queued',
            'data' => [
                'domain' => 'test',
                'amount' => 3794800,
                'currency' => 'NGN',
                'reference' => '1jhbs3ozmen0k7y5q2',
                'source' => 'balance',
                'reason' => 'Calm down',
                'status' => 'success',
                'transfer_code' => 'TRF_vsyqdmlzble3uii',
                'id' => 14956454
            ]
        ];

        $expectedBody = json_encode([
            'transfer_code' => 'TRF_vsyqdmlzble3uii',
            'otp' => '928783'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transfers->finalize([
            'transfer_code' => 'TRF_vsyqdmlzble3uii',
            'otp' => '928783'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer/finalize_transfer', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testBulkTransfer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk transfer initiated',
            'data' => [
                [
                    'amount' => 100000,
                    'reference' => 'ref_001',
                    'status' => 'success',
                    'transfer_code' => 'TRF_1',
                    'recipient' => 1943003
                ],
                [
                    'amount' => 200000,
                    'reference' => 'ref_002',
                    'status' => 'success',
                    'transfer_code' => 'TRF_2',
                    'recipient' => 1943004
                ]
            ]
        ];

        $expectedBody = json_encode([
            'source' => 'balance',
            'transfers' => [
                [
                    'amount' => 100000,
                    'reference' => 'ref_001',
                    'reason' => 'Payment 1',
                    'recipient' => 'RCP_gx2wn530m0i3w3m'
                ],
                [
                    'amount' => 200000,
                    'reference' => 'ref_002',
                    'reason' => 'Payment 2',
                    'recipient' => 'RCP_gx2wn530m0i3w3n'
                ]
            ]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transfers->bulk([
            'source' => 'balance',
            'transfers' => [
                [
                    'amount' => 100000,
                    'reference' => 'ref_001',
                    'reason' => 'Payment 1',
                    'recipient' => 'RCP_gx2wn530m0i3w3m'
                ],
                [
                    'amount' => 200000,
                    'reference' => 'ref_002',
                    'reason' => 'Payment 2',
                    'recipient' => 'RCP_gx2wn530m0i3w3n'
                ]
            ]
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer/bulk', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListTransfers(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transfers retrieved',
            'data' => [
                [
                    'id' => 14956454,
                    'amount' => 3794800,
                    'currency' => 'NGN',
                    'reference' => '1jhbs3ozmen0k7y5q2',
                    'source' => 'balance',
                    'reason' => 'Calm down',
                    'status' => 'success',
                    'transfer_code' => 'TRF_vsyqdmlzble3uii'
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
        
        $data = $this->client()->transfers->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transfer', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchTransfer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transfer retrieved',
            'data' => [
                'id' => 14956454,
                'amount' => 3794800,
                'currency' => 'NGN',
                'reference' => '1jhbs3ozmen0k7y5q2',
                'source' => 'balance',
                'reason' => 'Calm down',
                'status' => 'success',
                'transfer_code' => 'TRF_vsyqdmlzble3uii',
                'recipient' => [
                    'id' => 1943003,
                    'name' => 'ABDUL-HALEEM ISHAQ',
                    'email' => 'ish@gmail.com'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transfers->find('TRF_vsyqdmlzble3uii');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transfer/TRF_vsyqdmlzble3uii', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testVerifyTransfer(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transfer retrieved',
            'data' => [
                'id' => 14956454,
                'amount' => 3794800,
                'currency' => 'NGN',
                'reference' => '1jhbs3ozmen0k7y5q2',
                'source' => 'balance',
                'reason' => 'Calm down',
                'status' => 'success',
                'transfer_code' => 'TRF_vsyqdmlzble3uii'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transfers->verify('1jhbs3ozmen0k7y5q2');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transfer/verify/1jhbs3ozmen0k7y5q2', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}