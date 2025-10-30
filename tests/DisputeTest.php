<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class DisputeTest extends TestCase
{
    public function testListDisputes(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Disputes retrieved successfully',
            'data' => [
                [
                    'id' => 827179,
                    'refund_amount' => 0,
                    'currency' => 'NGN',
                    'status' => 'pending',
                    'resolution' => null,
                    'domain' => 'test',
                    'transaction' => [
                        'id' => 54321,
                        'domain' => 'test',
                        'status' => 'success',
                        'reference' => 'txn_12345abcde',
                        'amount' => 50000,
                        'currency' => 'NGN'
                    ],
                    'transaction_reference' => 'txn_12345abcde',
                    'category' => 'chargeback',
                    'customer' => [
                        'id' => 98765,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com'
                    ],
                    'bin' => '408408',
                    'last4' => '4081',
                    'dcc' => false,
                    'created_at' => '2023-11-15T10:30:00.000Z',
                    'updated_at' => '2023-11-15T10:30:00.000Z'
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
        
        $data = $this->client()->disputes->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dispute', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchDispute(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dispute retrieved successfully',
            'data' => [
                'id' => 827179,
                'refund_amount' => 0,
                'currency' => 'NGN',
                'status' => 'pending',
                'resolution' => null,
                'domain' => 'test',
                'transaction' => [
                    'id' => 54321,
                    'domain' => 'test',
                    'status' => 'success',
                    'reference' => 'txn_12345abcde',
                    'amount' => 50000,
                    'currency' => 'NGN'
                ],
                'category' => 'chargeback',
                'customer' => [
                    'id' => 98765,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com'
                ],
                'evidence' => [],
                'created_at' => '2023-11-15T10:30:00.000Z',
                'updated_at' => '2023-11-15T10:30:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->disputes->find('827179');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dispute/827179', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateDispute(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dispute updated successfully',
            'data' => [
                'id' => 827179,
                'refund_amount' => 25000,
                'currency' => 'NGN',
                'status' => 'pending',
                'resolution' => null,
                'domain' => 'test'
            ]
        ];

        $expectedBody = json_encode([
            'refund_amount' => 25000
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->disputes->update('827179', [
            'refund_amount' => 25000
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/dispute/827179', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testAddEvidence(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Evidence added successfully',
            'data' => [
                'id' => 827179,
                'status' => 'pending',
                'evidence' => [
                    [
                        'delivery_address' => '123 Main Street, Lagos, Nigeria',
                        'delivery_date' => '2023-11-10'
                    ]
                ]
            ]
        ];

        $expectedBody = json_encode([
            'customer_email' => 'john.doe@example.com',
            'customer_name' => 'John Doe',
            'customer_phone' => '+2348012345678',
            'delivery_address' => '123 Main Street, Lagos, Nigeria',
            'delivery_date' => '2023-11-10'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->disputes->addEvidence('827179', [
            'customer_email' => 'john.doe@example.com',
            'customer_name' => 'John Doe',
            'customer_phone' => '+2348012345678',
            'delivery_address' => '123 Main Street, Lagos, Nigeria',
            'delivery_date' => '2023-11-10'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/dispute/827179/evidence', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testGetUploadUrl(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Upload URL generated successfully',
            'data' => [
                'upload_url' => 'https://files.paystack.co/dispute/827179/upload?signature=abc123def456',
                'upload_filename' => 'evidence_receipt.pdf'
            ]
        ];

        $expectedBody = json_encode([
            'upload_filename' => 'evidence_receipt.pdf'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->disputes->getUploadUrl('827179', [
            'upload_filename' => 'evidence_receipt.pdf'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/dispute/827179/upload_url', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testResolveDispute(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Dispute resolved successfully',
            'data' => [
                'id' => 827179,
                'status' => 'resolved',
                'resolution' => 'merchant_accepted',
                'resolved_at' => '2023-11-16T14:30:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'resolution' => 'merchant_accepted',
            'message' => 'Customer contacted and resolved amicably',
            'refund_amount' => 0,
            'uploaded_filename' => 'evidence_receipt.pdf'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->disputes->resolve('827179', [
            'resolution' => 'merchant_accepted',
            'message' => 'Customer contacted and resolved amicably',
            'refund_amount' => 0,
            'uploaded_filename' => 'evidence_receipt.pdf'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/dispute/827179/resolve', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testExportDisputes(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Export successful',
            'data' => [
                'export_id' => 'exp_abc123def456',
                'path' => '/exports/disputes/2023/11/disputes_export_20231116.csv'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->disputes->export(['from' => '2023-11-01', 'to' => '2023-11-16']);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/dispute/export', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}