<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class VirtualTerminalTest extends TestCase
{
    public function testCreateVirtualTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Virtual Terminal created',
            'data' => [
                'code' => 'VT_abc123def456',
                'name' => 'Test Virtual Terminal',
                'description' => 'A test virtual terminal for payments',
                'currency' => 'NGN',
                'merchant_category_code' => '5411',
                'split_code' => null,
                'active' => true,
                'destinations' => [],
                'id' => 54321,
                'integration' => 463433,
                'domain' => 'test',
                'created_at' => '2023-11-16T20:00:00.000Z',
                'updated_at' => '2023-11-16T20:00:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Test Virtual Terminal',
            'description' => 'A test virtual terminal for payments',
            'currency' => 'NGN',
            'merchant_category_code' => '5411'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->create([
            'name' => 'Test Virtual Terminal',
            'description' => 'A test virtual terminal for payments',
            'currency' => 'NGN',
            'merchant_category_code' => '5411'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListVirtualTerminals(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Virtual Terminals retrieved',
            'data' => [
                [
                    'code' => 'VT_abc123def456',
                    'name' => 'Test Virtual Terminal',
                    'description' => 'A test virtual terminal for payments',
                    'amount' => 100000,
                    'currency' => 'NGN',
                    'active' => true,
                    'id' => 54321,
                    'created_at' => '2023-11-16T20:00:00.000Z'
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
        
        $data = $this->client()->virtualTerminals->all(['perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchVirtualTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Virtual Terminal retrieved',
            'data' => [
                'code' => 'VT_abc123def456',
                'name' => 'Test Virtual Terminal',
                'description' => 'A test virtual terminal for payments',
                'currency' => 'NGN',
                'merchant_category_code' => '5411',
                'split_code' => null,
                'active' => true,
                'destinations' => [
                    [
                        'id' => 12345,
                        'destination' => '+2348012345678',
                        'type' => 'whatsapp'
                    ]
                ],
                'id' => 54321,
                'integration' => 463433,
                'domain' => 'test',
                'created_at' => '2023-11-16T20:00:00.000Z',
                'updated_at' => '2023-11-16T20:05:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->find('VT_abc123def456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateVirtualTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Virtual Terminal updated',
            'data' => [
                'code' => 'VT_abc123def456',
                'name' => 'Updated Virtual Terminal',
                'description' => 'An updated virtual terminal description',
                'currency' => 'USD',
                'merchant_category_code' => '5399'
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Updated Virtual Terminal',
            'description' => 'An updated virtual terminal description',
            'currency' => 'USD',
            'merchant_category_code' => '5399'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->update('VT_abc123def456', [
            'name' => 'Updated Virtual Terminal',
            'description' => 'An updated virtual terminal description',
            'currency' => 'USD',
            'merchant_category_code' => '5399'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testDeactivateVirtualTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Virtual Terminal deactivated',
            'data' => [
                'code' => 'VT_abc123def456',
                'active' => false
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->deactivate('VT_abc123def456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456/deactivate', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testAssignDestination(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Destination assigned successfully',
            'data' => [
                'code' => 'VT_abc123def456',
                'destinations' => [
                    [
                        'id' => 12345,
                        'destination' => '+2348012345678',
                        'type' => 'whatsapp'
                    ]
                ]
            ]
        ];

        $expectedBody = json_encode([
            'destination' => '+2348012345678'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->assignDestination('VT_abc123def456', [
            'destination' => '+2348012345678'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456/destination/assign', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testUnassignDestination(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Destination unassigned successfully',
            'data' => [
                'code' => 'VT_abc123def456',
                'destinations' => []
            ]
        ];

        $expectedBody = json_encode([
            'destination' => '+2348012345678'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->unassignDestination('VT_abc123def456', [
            'destination' => '+2348012345678'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456/destination/unassign', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testAddSplitCode(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Split code added successfully',
            'data' => [
                'code' => 'VT_abc123def456',
                'split_code' => 'SPL_e7jnRLtzla'
            ]
        ];

        $expectedBody = json_encode([
            'split_code' => 'SPL_e7jnRLtzla'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->addSplitCode('VT_abc123def456', [
            'split_code' => 'SPL_e7jnRLtzla'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456/split_code', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testRemoveSplitCode(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Split code removed successfully',
            'data' => [
                'code' => 'VT_abc123def456',
                'split_code' => null
            ]
        ];

        $expectedBody = json_encode([
            'split_code' => 'SPL_e7jnRLtzla'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->virtualTerminals->removeSplitCode('VT_abc123def456', [
            'split_code' => 'SPL_e7jnRLtzla'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('DELETE', $sentRequest->getMethod());
        $this->assertEquals('/virtual_terminal/VT_abc123def456/split_code', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}