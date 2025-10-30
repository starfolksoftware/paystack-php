<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use StarfolkSoftware\Paystack\Options\BulkCharge\{
    InitiateOptions,
    ReadAllOptions,
    GetChargesOptions
};

final class BulkChargeTest extends TestCase
{
    public function testInitiateBulkCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk charges initiated',
            'data' => [
                'batch_code' => 'BCH_abc123def456',
                'reference' => 'bulkcharge_abc123',
                'total_charges' => 2,
                'pending_charges' => 2,
                'status' => 'pending',
                'id' => 789,
                'integration' => 463433,
                'domain' => 'test',
                'batch_limit' => 200,
                'current_batch' => 1,
                'created_at' => '2023-11-16T11:00:00.000Z',
                'updated_at' => '2023-11-16T11:00:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'charges' => [
                [
                    'authorization' => 'AUTH_6tmt288t0o',
                    'amount' => 50000,
                    'reference' => 'bulk_ref_001'
                ],
                [
                    'authorization' => 'AUTH_abc123def456',
                    'amount' => 75000,
                    'reference' => 'bulk_ref_002'
                ]
            ]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new InitiateOptions([
            'charges' => [
                [
                    'authorization' => 'AUTH_6tmt288t0o',
                    'amount' => 50000,
                    'reference' => 'bulk_ref_001'
                ],
                [
                    'authorization' => 'AUTH_abc123def456',
                    'amount' => 75000,
                    'reference' => 'bulk_ref_002'
                ]
            ]
        ]);
        $data = $this->client()->bulkCharges->initiate($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/bulkcharge', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListBulkCharges(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk charges retrieved',
            'data' => [
                [
                    'batch_code' => 'BCH_abc123def456',
                    'reference' => 'bulkcharge_abc123',
                    'total_charges' => 2,
                    'pending_charges' => 0,
                    'status' => 'complete',
                    'id' => 789,
                    'created_at' => '2023-11-16T11:00:00.000Z',
                    'updated_at' => '2023-11-16T11:05:00.000Z'
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
        
        $options = new ReadAllOptions(['page' => 1, 'perPage' => 50]);
        $data = $this->client()->bulkCharges->all($options);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bulkcharge', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchBulkCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk charge retrieved',
            'data' => [
                'batch_code' => 'BCH_abc123def456',
                'reference' => 'bulkcharge_abc123',
                'total_charges' => 2,
                'pending_charges' => 0,
                'status' => 'complete',
                'id' => 789,
                'integration' => 463433,
                'domain' => 'test',
                'batch_limit' => 200,
                'current_batch' => 1,
                'created_at' => '2023-11-16T11:00:00.000Z',
                'updated_at' => '2023-11-16T11:05:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->bulkCharges->find('BCH_abc123def456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bulkcharge/BCH_abc123def456', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testGetBulkChargeCharges(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk charge charges retrieved',
            'data' => [
                [
                    'id' => 54321,
                    'domain' => 'test',
                    'status' => 'success',
                    'reference' => 'bulk_ref_001',
                    'amount' => 50000,
                    'currency' => 'NGN',
                    'bulk_charge' => 'BCH_abc123def456',
                    'customer' => [
                        'id' => 98765,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com'
                    ],
                    'created_at' => '2023-11-16T11:01:00.000Z'
                ],
                [
                    'id' => 54322,
                    'domain' => 'test',
                    'status' => 'success',
                    'reference' => 'bulk_ref_002',
                    'amount' => 75000,
                    'currency' => 'NGN',
                    'bulk_charge' => 'BCH_abc123def456',
                    'customer' => [
                        'id' => 98766,
                        'first_name' => 'Jane',
                        'last_name' => 'Smith',
                        'email' => 'jane.smith@example.com'
                    ],
                    'created_at' => '2023-11-16T11:01:30.000Z'
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
        
        $options = new GetChargesOptions(['page' => 1]);
        $data = $this->client()->bulkCharges->getCharges('BCH_abc123def456', $options);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bulkcharge/BCH_abc123def456/charges', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testPauseBulkCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk charge batch has been paused',
            'data' => [
                'batch_code' => 'BCH_abc123def456',
                'status' => 'paused',
                'total_charges' => 100,
                'pending_charges' => 50,
                'processed_charges' => 50
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->bulkCharges->pause('BCH_abc123def456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bulkcharge/pause/BCH_abc123def456', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testResumeBulkCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Bulk charge batch has been resumed',
            'data' => [
                'batch_code' => 'BCH_abc123def456',
                'status' => 'active',
                'total_charges' => 100,
                'pending_charges' => 50,
                'processed_charges' => 50
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->bulkCharges->resume('BCH_abc123def456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bulkcharge/resume/BCH_abc123def456', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}