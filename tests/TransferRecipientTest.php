<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class TransferRecipientTest extends TestCase
{
    public function testCreateTransferRecipient(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Transfer recipient created successfully',
            'data' => [
                'active' => true,
                'created_at' => '2020-09-14T14:54:48.000Z',
                'currency' => 'NGN',
                'domain' => 'test',
                'id' => 8690817,
                'integration' => 463433,
                'name' => 'ABDUL-HALEEM ISHAQ',
                'recipient_code' => 'RCP_gx2wn530m0i3w3m',
                'type' => 'nuban',
                'updated_at' => '2020-09-14T14:54:48.000Z',
                'is_deleted' => false,
                'details' => [
                    'authorization_code' => null,
                    'account_number' => '0123456789',
                    'account_name' => 'ABDUL-HALEEM ISHAQ',
                    'bank_code' => '044',
                    'bank_name' => 'Access Bank'
                ]
            ]
        ];

        $expectedBody = json_encode([
            'type' => 'nuban',
            'name' => 'ABDUL-HALEEM ISHAQ',
            'account_number' => '0123456789',
            'bank_code' => '044',
            'currency' => 'NGN'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferRecipients->create([
            'type' => 'nuban',
            'name' => 'ABDUL-HALEEM ISHAQ',
            'account_number' => '0123456789',
            'bank_code' => '044',
            'currency' => 'NGN'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transferrecipient', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testBulkCreateTransferRecipients(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Recipients added successfully',
            'data' => [
                'success' => [
                    [
                        'name' => 'ABDUL-HALEEM ISHAQ',
                        'account_number' => '0123456789',
                        'bank_code' => '044',
                        'currency' => 'NGN',
                        'type' => 'nuban',
                        'recipient_code' => 'RCP_gx2wn530m0i3w3m'
                    ]
                ],
                'errors' => []
            ]
        ];

        $expectedBody = json_encode([
            'batch' => [
                [
                    'type' => 'nuban',
                    'name' => 'ABDUL-HALEEM ISHAQ',
                    'account_number' => '0123456789',
                    'bank_code' => '044',
                    'currency' => 'NGN'
                ],
                [
                    'type' => 'nuban',
                    'name' => 'JOHN DOE',
                    'account_number' => '0987654321',
                    'bank_code' => '058',
                    'currency' => 'NGN'
                ]
            ]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferRecipients->bulkCreate([
            'batch' => [
                [
                    'type' => 'nuban',
                    'name' => 'ABDUL-HALEEM ISHAQ',
                    'account_number' => '0123456789',
                    'bank_code' => '044',
                    'currency' => 'NGN'
                ],
                [
                    'type' => 'nuban',
                    'name' => 'JOHN DOE',
                    'account_number' => '0987654321',
                    'bank_code' => '058',
                    'currency' => 'NGN'
                ]
            ]
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transferrecipient/bulk', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListTransferRecipients(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Recipients retrieved',
            'data' => [
                [
                    'id' => 8690817,
                    'name' => 'ABDUL-HALEEM ISHAQ',
                    'recipient_code' => 'RCP_gx2wn530m0i3w3m',
                    'type' => 'nuban',
                    'currency' => 'NGN',
                    'active' => true,
                    'details' => [
                        'account_number' => '0123456789',
                        'account_name' => 'ABDUL-HALEEM ISHAQ',
                        'bank_code' => '044',
                        'bank_name' => 'Access Bank'
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
        
        $data = $this->client()->transferRecipients->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transferrecipient', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchTransferRecipient(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Recipient retrieved',
            'data' => [
                'id' => 8690817,
                'name' => 'ABDUL-HALEEM ISHAQ',
                'recipient_code' => 'RCP_gx2wn530m0i3w3m',
                'type' => 'nuban',
                'currency' => 'NGN',
                'active' => true,
                'is_deleted' => false,
                'details' => [
                    'account_number' => '0123456789',
                    'account_name' => 'ABDUL-HALEEM ISHAQ',
                    'bank_code' => '044',
                    'bank_name' => 'Access Bank'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferRecipients->find('RCP_gx2wn530m0i3w3m');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/transferrecipient/RCP_gx2wn530m0i3w3m', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateTransferRecipient(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Recipient updated',
            'data' => [
                'id' => 8690817,
                'name' => 'UPDATED ABDUL-HALEEM ISHAQ',
                'recipient_code' => 'RCP_gx2wn530m0i3w3m',
                'type' => 'nuban',
                'currency' => 'NGN',
                'active' => true,
                'details' => [
                    'account_number' => '0123456789',
                    'account_name' => 'UPDATED ABDUL-HALEEM ISHAQ',
                    'bank_code' => '044',
                    'bank_name' => 'Access Bank'
                ]
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'UPDATED ABDUL-HALEEM ISHAQ'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferRecipients->update('RCP_gx2wn530m0i3w3m', [
            'name' => 'UPDATED ABDUL-HALEEM ISHAQ'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/transferrecipient/RCP_gx2wn530m0i3w3m', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testDeleteTransferRecipient(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Recipient set as inactive'
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferRecipients->delete('RCP_gx2wn530m0i3w3m');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('DELETE', $sentRequest->getMethod());
        $this->assertEquals('/transferrecipient/RCP_gx2wn530m0i3w3m', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}