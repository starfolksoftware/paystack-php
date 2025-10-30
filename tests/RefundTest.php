<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class RefundTest extends TestCase
{
    public function testCreateRefund(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Refund has been queued for processing',
            'data' => [
                'transaction' => [
                    'id' => 2009945086,
                    'domain' => 'test',
                    'status' => 'success',
                    'reference' => 'T563902343_1628168464',
                    'amount' => 10000,
                    'currency' => 'NGN',
                    'channel' => 'card'
                ],
                'integration' => 463433,
                'deducted_amount' => 0,
                'channel' => null,
                'merchant_note' => 'Defective product',
                'customer_note' => 'Product is defective',
                'status' => 'pending',
                'refunded_by' => 'hello@example.com',
                'expected_at' => '2025-10-30T14:55:19.000Z',
                'currency' => 'NGN',
                'domain' => 'test',
                'amount' => 5000,
                'fully_deducted' => false,
                'id' => 1,
                'created_at' => '2025-10-30T14:55:19.000Z',
                'updated_at' => '2025-10-30T14:55:19.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'transaction' => 'T563902343_1628168464',
            'amount' => 5000,
            'merchant_note' => 'Defective product',
            'customer_note' => 'Product is defective'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->refunds->create([
            'transaction' => 'T563902343_1628168464',
            'amount' => 5000,
            'merchant_note' => 'Defective product',
            'customer_note' => 'Product is defective'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/refund', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListRefunds(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Refunds retrieved',
            'data' => [
                [
                    'id' => 1,
                    'amount' => 5000,
                    'currency' => 'NGN',
                    'status' => 'processed',
                    'merchant_note' => 'Defective product',
                    'customer_note' => 'Product is defective',
                    'transaction' => [
                        'id' => 2009945086,
                        'reference' => 'T563902343_1628168464',
                        'amount' => 10000,
                        'currency' => 'NGN',
                        'status' => 'success'
                    ],
                    'created_at' => '2025-10-30T14:55:19.000Z'
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
        
        $data = $this->client()->refunds->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/refund', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchRefund(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Refund retrieved',
            'data' => [
                'id' => 1,
                'amount' => 5000,
                'currency' => 'NGN',
                'status' => 'processed',
                'merchant_note' => 'Defective product',
                'customer_note' => 'Product is defective',
                'transaction' => [
                    'id' => 2009945086,
                    'reference' => 'T563902343_1628168464',
                    'amount' => 10000,
                    'currency' => 'NGN',
                    'status' => 'success',
                    'channel' => 'card'
                ],
                'integration' => 463433,
                'deducted_amount' => 0,
                'channel' => null,
                'refunded_by' => 'hello@example.com',
                'expected_at' => '2025-10-30T14:55:19.000Z',
                'domain' => 'test',
                'fully_deducted' => false,
                'created_at' => '2025-10-30T14:55:19.000Z',
                'updated_at' => '2025-10-30T14:55:19.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->refunds->find('1');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/refund/1', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}