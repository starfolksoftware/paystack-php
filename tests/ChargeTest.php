<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use StarfolkSoftware\Paystack\Options\Charge\{
    CreateOptions, 
    SubmitPinOptions,
    SubmitOtpOptions,
    SubmitPhoneOptions,
    SubmitBirthdayOptions,
    SubmitAddressOptions
};

final class ChargeTest extends TestCase
{
    public function testCreateCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge attempted',
            'data' => [
                'id' => 2009945086,
                'domain' => 'test',
                'status' => 'send_pin',
                'reference' => 'T563902343_1628168464',
                'amount' => 10000,
                'currency' => 'NGN',
                'channel' => 'card',
                'metadata' => [],
                'display_text' => 'Please enter your 4-digit PIN to continue'
            ]
        ];

        $expectedBody = json_encode([
            'email' => 'customer@email.com',
            'amount' => '10000',
            'card' => [
                'number' => '4084084084084081',
                'cvv' => '408',
                'expiry_month' => '12',
                'expiry_year' => '2030'
            ]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new CreateOptions([
            'email' => 'customer@email.com',
            'amount' => '10000',
            'card' => [
                'number' => '4084084084084081',
                'cvv' => '408',
                'expiry_month' => '12',
                'expiry_year' => '2030'
            ]
        ]);
        $data = $this->client()->charges->create($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/charge', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testSubmitPin(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge attempted',
            'data' => [
                'id' => 2009945086,
                'status' => 'send_otp',
                'reference' => 'T563902343_1628168464',
                'display_text' => 'Please enter the OTP sent to your mobile number ***-***-4321'
            ]
        ];

        $expectedBody = json_encode([
            'pin' => '1234',
            'reference' => 'T563902343_1628168464'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new SubmitPinOptions([
            'pin' => '1234',
            'reference' => 'T563902343_1628168464'
        ]);
        $data = $this->client()->charges->submitPin($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/charge/submit_pin', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testSubmitOtp(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge completed',
            'data' => [
                'id' => 2009945086,
                'status' => 'success',
                'reference' => 'T563902343_1628168464',
                'amount' => 10000,
                'currency' => 'NGN',
                'channel' => 'card'
            ]
        ];

        $expectedBody = json_encode([
            'otp' => '123456',
            'reference' => 'T563902343_1628168464'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new SubmitOtpOptions([
            'otp' => '123456',
            'reference' => 'T563902343_1628168464'
        ]);
        $data = $this->client()->charges->submitOtp($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/charge/submit_otp', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testSubmitPhone(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge attempted',
            'data' => [
                'id' => 2009945086,
                'status' => 'send_otp',
                'reference' => 'T563902343_1628168464'
            ]
        ];

        $expectedBody = json_encode([
            'phone' => '+2348012345678',
            'reference' => 'T563902343_1628168464'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new SubmitPhoneOptions([
            'phone' => '+2348012345678',
            'reference' => 'T563902343_1628168464'
        ]);
        $data = $this->client()->charges->submitPhone($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/charge/submit_phone', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testSubmitBirthday(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge attempted',
            'data' => [
                'id' => 2009945086,
                'status' => 'success',
                'reference' => 'T563902343_1628168464'
            ]
        ];

        $expectedBody = json_encode([
            'birthday' => '1990-01-01',
            'reference' => 'T563902343_1628168464'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new SubmitBirthdayOptions([
            'birthday' => '1990-01-01',
            'reference' => 'T563902343_1628168464'
        ]);
        $data = $this->client()->charges->submitBirthday($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/charge/submit_birthday', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testSubmitAddress(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge attempted',
            'data' => [
                'id' => 2009945086,
                'status' => 'success',
                'reference' => 'T563902343_1628168464'
            ]
        ];

        $expectedBody = json_encode([
            'address' => '123 Main Street',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'zipcode' => '100001',
            'reference' => 'T563902343_1628168464'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new SubmitAddressOptions([
            'address' => '123 Main Street',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'zipcode' => '100001',
            'reference' => 'T563902343_1628168464'
        ]);
        $data = $this->client()->charges->submitAddress($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/charge/submit_address', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testCheckPendingCharge(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Charge retrieved',
            'data' => [
                'id' => 2009945086,
                'status' => 'success',
                'reference' => 'T563902343_1628168464',
                'amount' => 10000,
                'currency' => 'NGN',
                'channel' => 'card'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->charges->checkPending('T563902343_1628168464');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/charge/T563902343_1628168464', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}