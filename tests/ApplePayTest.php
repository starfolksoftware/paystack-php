<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use StarfolkSoftware\Paystack\Options\ApplePay\{
    RegisterDomainOptions,
    ListDomainsOptions,
    UnregisterDomainOptions
};

final class ApplePayTest extends TestCase
{
    public function testRegisterDomain(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Domain registered successfully',
            'data' => [
                'domain' => 'example.com',
                'registered' => true,
                'created_at' => '2023-11-16T18:00:00.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'domainName' => 'example.com'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new RegisterDomainOptions([
            'domainName' => 'example.com'
        ]);
        $data = $this->client()->applePay->registerDomain($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/apple-pay/domain', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListDomains(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Apple Pay domains retrieved',
            'data' => [
                [
                    'domain' => 'example.com',
                    'registered' => true,
                    'created_at' => '2023-11-16T18:00:00.000Z'
                ],
                [
                    'domain' => 'shop.example.com',
                    'registered' => true,
                    'created_at' => '2023-11-16T18:05:00.000Z'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->applePay->listDomains();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/apple-pay/domain', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListDomainsWithParams(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Apple Pay domains retrieved',
            'data' => [
                [
                    'domain' => 'example.com',
                    'registered' => true,
                    'created_at' => '2023-11-16T18:00:00.000Z'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new ListDomainsOptions(['use_cursor' => false]);
        $data = $this->client()->applePay->listDomains($options);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/apple-pay/domain', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUnregisterDomain(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Domain unregistered successfully',
            'data' => [
                'domain' => 'old.example.com',
                'registered' => false
            ]
        ];

        $expectedBody = json_encode([
            'domainName' => 'old.example.com'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new UnregisterDomainOptions([
            'domainName' => 'old.example.com'
        ]);
        $data = $this->client()->applePay->unregisterDomain($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('DELETE', $sentRequest->getMethod());
        $this->assertEquals('/apple-pay/domain', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}