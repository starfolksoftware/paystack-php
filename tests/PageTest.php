<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class PageTest extends TestCase
{
    public function testCreatePage(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Page created',
            'data' => [
                'name' => 'Test Payment Page',
                'description' => 'A test payment page for demonstration',
                'integration' => 463433,
                'domain' => 'test',
                'slug' => 'test-payment-page',
                'currency' => 'NGN',
                'type' => 'donation',
                'collect_phone' => false,
                'active' => true,
                'published' => true,
                'migrate' => false,
                'id' => 12345,
                'created_at' => '2023-11-16T10:30:00.000Z',
                'updated_at' => '2023-11-16T10:30:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Test Payment Page',
            'description' => 'A test payment page for demonstration',
            'amount' => 50000,
            'slug' => 'test-payment-page',
            'redirect_url' => 'https://example.com/success'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->pages->create([
            'name' => 'Test Payment Page',
            'description' => 'A test payment page for demonstration',
            'amount' => 50000,
            'slug' => 'test-payment-page',
            'redirect_url' => 'https://example.com/success'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/page', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListPages(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Pages retrieved',
            'data' => [
                [
                    'id' => 12345,
                    'name' => 'Test Payment Page',
                    'description' => 'A test payment page for demonstration',
                    'slug' => 'test-payment-page',
                    'currency' => 'NGN',
                    'type' => 'donation',
                    'active' => true,
                    'published' => true
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
        
        $data = $this->client()->pages->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/page', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchPage(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Page retrieved',
            'data' => [
                'id' => 12345,
                'name' => 'Test Payment Page',
                'description' => 'A test payment page for demonstration',
                'slug' => 'test-payment-page',
                'currency' => 'NGN',
                'type' => 'donation',
                'collect_phone' => false,
                'active' => true,
                'published' => true,
                'amount' => 50000,
                'redirect_url' => 'https://example.com/success',
                'products' => [],
                'created_at' => '2023-11-16T10:30:00.000Z',
                'updated_at' => '2023-11-16T10:30:00.000Z'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->pages->find('test-payment-page');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/page/test-payment-page', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdatePage(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Page updated',
            'data' => [
                'id' => 12345,
                'name' => 'Updated Payment Page',
                'description' => 'An updated test payment page',
                'slug' => 'test-payment-page',
                'active' => false
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Updated Payment Page',
            'description' => 'An updated test payment page',
            'active' => false
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->pages->update('12345', [
            'name' => 'Updated Payment Page',
            'description' => 'An updated test payment page',
            'active' => false
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/page/12345', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testCheckSlugAvailability(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Slug is available',
            'data' => [
                'available' => true
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->pages->checkSlugAvailability('my-new-page');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/page/check_slug_availability/my-new-page', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testAddProducts(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Products added to page',
            'data' => [
                'id' => 12345,
                'products' => [
                    [
                        'product_id' => 67890,
                        'name' => 'Test Product',
                        'description' => 'A test product',
                        'price' => 25000,
                        'currency' => 'NGN'
                    ]
                ]
            ]
        ];

        $expectedBody = json_encode([
            'product' => [67890, 54321]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->pages->addProducts('12345', [
            'product' => [67890, 54321]
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/page/12345/product', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}