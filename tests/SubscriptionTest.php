<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class SubscriptionTest extends TestCase
{
    public function testCreateSubscription(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subscription successfully created',
            'data' => [
                'customer' => 1173,
                'plan' => 28,
                'integration' => 463433,
                'domain' => 'test',
                'start' => 1577836800,
                'status' => 'active',
                'quantity' => 1,
                'amount' => 50000,
                'authorization' => [
                    'authorization_code' => 'AUTH_6tmt288t0o',
                    'bin' => '408408',
                    'last4' => '4081',
                    'exp_month' => '12',
                    'exp_year' => '2030',
                    'channel' => 'card',
                    'card_type' => 'visa'
                ],
                'subscription_code' => 'SUB_vsyqdmlzble3uii',
                'email_token' => 'd7gofp6yppn3qz7',
                'id' => 9,
                'created_at' => '2020-01-01T09:00:00.000Z',
                'updated_at' => '2020-01-01T09:00:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'customer' => 'CUS_xnxdt6s1zg5f4tx',
            'plan' => 'PLN_gx2wn530m0i3w3m',
            'authorization' => 'AUTH_6tmt288t0o'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subscriptions->create([
            'customer' => 'CUS_xnxdt6s1zg5f4tx',
            'plan' => 'PLN_gx2wn530m0i3w3m',
            'authorization' => 'AUTH_6tmt288t0o'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/subscription', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListSubscriptions(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subscriptions retrieved',
            'data' => [
                [
                    'id' => 9,
                    'customer' => [
                        'id' => 1173,
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'customer@email.com',
                        'customer_code' => 'CUS_xnxdt6s1zg5f4tx'
                    ],
                    'plan' => [
                        'id' => 28,
                        'name' => 'Monthly retainer',
                        'plan_code' => 'PLN_gx2wn530m0i3w3m',
                        'amount' => 50000,
                        'interval' => 'monthly'
                    ],
                    'subscription_code' => 'SUB_vsyqdmlzble3uii',
                    'amount' => 50000,
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
        
        $data = $this->client()->subscriptions->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/subscription', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchSubscription(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subscription retrieved',
            'data' => [
                'id' => 9,
                'subscription_code' => 'SUB_vsyqdmlzble3uii',
                'amount' => 50000,
                'status' => 'active',
                'customer' => [
                    'id' => 1173,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'customer@email.com',
                    'customer_code' => 'CUS_xnxdt6s1zg5f4tx'
                ],
                'plan' => [
                    'id' => 28,
                    'name' => 'Monthly retainer',
                    'plan_code' => 'PLN_gx2wn530m0i3w3m',
                    'amount' => 50000,
                    'interval' => 'monthly'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subscriptions->find('SUB_vsyqdmlzble3uii');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/subscription/SUB_vsyqdmlzble3uii', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testEnableSubscription(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subscription enabled successfully'
        ];

        $expectedBody = json_encode([
            'code' => 'SUB_vsyqdmlzble3uii',
            'token' => 'd7gofp6yppn3qz7'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subscriptions->enable('SUB_vsyqdmlzble3uii', 'd7gofp6yppn3qz7');

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/subscription/enable', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testDisableSubscription(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subscription disabled successfully'
        ];

        $expectedBody = json_encode([
            'code' => 'SUB_vsyqdmlzble3uii',
            'token' => 'd7gofp6yppn3qz7'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subscriptions->disable('SUB_vsyqdmlzble3uii', 'd7gofp6yppn3qz7');

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/subscription/disable', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testGenerateSubscriptionUpdateLink(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Link generated',
            'data' => [
                'link' => 'https://paystack.com/subscription/manage/SUB_vsyqdmlzble3uii/d7gofp6yppn3qz7'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subscriptions->genSubCardUpdateLink('SUB_vsyqdmlzble3uii');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/subscription/SUB_vsyqdmlzble3uii/manage/link', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testSendSubscriptionUpdateLink(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Email sent'
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subscriptions->sendSubCardUpdateLink('SUB_vsyqdmlzble3uii');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/subscription/SUB_vsyqdmlzble3uii/manage/email', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}