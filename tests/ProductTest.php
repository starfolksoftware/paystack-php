<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class ProductTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Product successfully created',
            'data' => [
                'name' => 'Puff Puff',
                'description' => 'Flour-based ball that is fried',
                'product_code' => 'PROD_wbkemh',
                'price' => 2000,
                'currency' => 'NGN',
                'quantity' => 7,
                'quantity_sold' => null,
                'type' => 'good',
                'image_path' => null,
                'file_path' => null,
                'is_shippable' => false,
                'unlimited' => true,
                'integration' => 463433,
                'domain' => 'test',
                'active' => true,
                'in_stock' => true,
                'id' => 72,
                'created_at' => '2020-06-29T16:06:05.000Z',
                'updated_at' => '2020-06-29T16:06:05.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Puff Puff',
            'description' => 'Flour-based ball that is fried',
            'price' => 2000,
            'currency' => 'NGN',
            'unlimited' => true,
            'quantity' => 7
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->products->create([
            'name' => 'Puff Puff',
            'description' => 'Flour-based ball that is fried',
            'price' => 2000,
            'currency' => 'NGN',
            'unlimited' => true,
            'quantity' => 7
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/product', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListProducts(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Products retrieved',
            'data' => [
                [
                    'id' => 72,
                    'name' => 'Puff Puff',
                    'description' => 'Flour-based ball that is fried',
                    'product_code' => 'PROD_wbkemh',
                    'price' => 2000,
                    'currency' => 'NGN',
                    'quantity' => 7,
                    'type' => 'good',
                    'active' => true,
                    'in_stock' => true
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
        
        $data = $this->client()->products->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/product', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchProduct(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Product retrieved',
            'data' => [
                'id' => 72,
                'name' => 'Puff Puff',
                'description' => 'Flour-based ball that is fried',
                'product_code' => 'PROD_wbkemh',
                'price' => 2000,
                'currency' => 'NGN',
                'quantity' => 7,
                'quantity_sold' => null,
                'type' => 'good',
                'image_path' => null,
                'file_path' => null,
                'is_shippable' => false,
                'unlimited' => true,
                'active' => true,
                'in_stock' => true
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->products->find('72');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/product/72', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateProduct(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Product successfully updated',
            'data' => [
                'id' => 72,
                'name' => 'Updated Puff Puff',
                'description' => 'Updated Flour-based ball that is fried',
                'product_code' => 'PROD_wbkemh',
                'price' => 2500,
                'currency' => 'NGN',
                'quantity' => 10,
                'type' => 'good',
                'active' => true,
                'in_stock' => true
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Updated Puff Puff',
            'description' => 'Updated Flour-based ball that is fried',
            'price' => 2500,
            'quantity' => 10
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->products->update('72', [
            'name' => 'Updated Puff Puff',
            'description' => 'Updated Flour-based ball that is fried',
            'price' => 2500,
            'quantity' => 10
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/product/72', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}