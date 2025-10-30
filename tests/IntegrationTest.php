<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use StarfolkSoftware\Paystack\Options\Integration\UpdateTimeoutOptions;

final class IntegrationTest extends TestCase
{
    public function testFetchTimeout(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment session timeout retrieved',
            'data' => [
                'payment_session_timeout' => 30,
                'invoice_limit' => 1000,
                'invoice_limit_currency' => 'NGN'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->integration->fetchTimeout();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/integration/payment_session_timeout', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateTimeout(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment session timeout updated',
            'data' => [
                'payment_session_timeout' => 60,
                'invoice_limit' => 1000,
                'invoice_limit_currency' => 'NGN'
            ]
        ];

        $expectedBody = json_encode([
            'timeout' => 60
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new UpdateTimeoutOptions([
            'timeout' => 60
        ]);
        $data = $this->client()->integration->updateTimeout($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/integration/payment_session_timeout', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}