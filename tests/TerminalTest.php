<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class TerminalTest extends TestCase
{
    public function testSendEvent(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Event sent successfully',
            'data' => [
                'id' => 'evt_abc123def456',
                'type' => 'invoice',
                'action' => 'process',
                'data' => [
                    'id' => 12345,
                    'amount' => 50000,
                    'reference' => 'INV_abc123'
                ],
                'terminal_id' => 'term_abc123',
                'status' => 'pending',
                'created_at' => '2023-11-16T12:00:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'type' => 'invoice',
            'action' => 'process',
            'data' => [
                'id' => 12345,
                'amount' => 50000,
                'reference' => 'INV_abc123'
            ]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->sendEvent('term_abc123', [
            'type' => 'invoice',
            'action' => 'process',
            'data' => [
                'id' => 12345,
                'amount' => 50000,
                'reference' => 'INV_abc123'
            ]
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/terminal/term_abc123/event', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testFetchEventStatus(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Event status retrieved',
            'data' => [
                'id' => 'evt_abc123def456',
                'type' => 'invoice',
                'action' => 'process',
                'terminal_id' => 'term_abc123',
                'status' => 'success',
                'created_at' => '2023-11-16T12:00:00.000Z',
                'updated_at' => '2023-11-16T12:01:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->fetchEventStatus('term_abc123', 'evt_abc123def456');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/terminal/term_abc123/event/evt_abc123def456', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchTerminalStatus(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Terminal status retrieved',
            'data' => [
                'online' => true,
                'available' => true,
                'last_seen_at' => '2023-11-16T12:00:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->fetchTerminalStatus('term_abc123');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/terminal/term_abc123/presence', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListTerminals(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Terminals retrieved',
            'data' => [
                [
                    'id' => 'term_abc123',
                    'serial_number' => 'PAX123456789',
                    'device_make' => 'PAX',
                    'terminal_id' => 'term_abc123',
                    'integration' => 463433,
                    'domain' => 'test',
                    'name' => 'Main Terminal',
                    'address' => '123 Main Street, Lagos',
                    'status' => 'active'
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
        
        $data = $this->client()->terminals->all(['perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/terminal', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Terminal retrieved',
            'data' => [
                'id' => 'term_abc123',
                'serial_number' => 'PAX123456789',
                'device_make' => 'PAX',
                'terminal_id' => 'term_abc123',
                'integration' => 463433,
                'domain' => 'test',
                'name' => 'Main Terminal',
                'address' => '123 Main Street, Lagos',
                'status' => 'active',
                'created_at' => '2023-11-15T10:00:00.000Z',
                'updated_at' => '2023-11-16T12:00:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->find('term_abc123');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/terminal/term_abc123', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Terminal updated',
            'data' => [
                'id' => 'term_abc123',
                'name' => 'Updated Terminal Name',
                'address' => '456 New Street, Lagos'
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Updated Terminal Name',
            'address' => '456 New Street, Lagos'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->update('term_abc123', [
            'name' => 'Updated Terminal Name',
            'address' => '456 New Street, Lagos'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/terminal/term_abc123', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testCommissionTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Device commissioned successfully',
            'data' => [
                'terminal_id' => 'term_abc123',
                'device_make' => 'PAX',
                'serial_number' => 'PAX123456789',
                'status' => 'active'
            ]
        ];

        $expectedBody = json_encode([
            'serial_number' => 'PAX123456789'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->commission([
            'serial_number' => 'PAX123456789'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/terminal/commission_device', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testDecommissionTerminal(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Device decommissioned successfully',
            'data' => [
                'terminal_id' => 'term_abc123',
                'serial_number' => 'PAX123456789',
                'status' => 'inactive'
            ]
        ];

        $expectedBody = json_encode([
            'serial_number' => 'PAX123456789'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->terminals->decommission([
            'serial_number' => 'PAX123456789'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/terminal/decommission_device', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}