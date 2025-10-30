<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class TransferControlTest extends TestCase
{
    public function testCheckBalance(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Balance retrieved',
            'data' => [
                [
                    'currency' => 'NGN',
                    'balance' => 5000000
                ],
                [
                    'currency' => 'USD',
                    'balance' => 1500000
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferControl->checkBalance();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/balance', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testGetBalanceLedger(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Balance ledger retrieved',
            'data' => [
                [
                    'integration' => 463433,
                    'domain' => 'test',
                    'balance' => 5000000,
                    'currency' => 'NGN',
                    'difference' => 50000,
                    'reason' => 'Transfer',
                    'model_responsible' => 'Transfer',
                    'model_row' => 12345,
                    'created_at' => '2023-11-16T14:00:00.000Z'
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
        
        $data = $this->client()->transferControl->getBalanceLedger(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/balance/ledger', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testResendOtp(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'transfer_code' => 'TRF_abc123def456',
                'details' => 'OTP has been sent to your phone and email'
            ]
        ];

        $expectedBody = json_encode([
            'transfer_code' => 'TRF_abc123def456',
            'reason' => 'transfer'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferControl->resendOtp([
            'transfer_code' => 'TRF_abc123def456',
            'reason' => 'transfer'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer/resend_otp', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testDisableOtp(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'OTP has been sent to mobile number ending with 1234',
            'data' => [
                'status' => 'otp_sent',
                'details' => 'OTP requirement for transfers is being disabled. A confirmation OTP has been sent'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferControl->disableOtp();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer/disable_otp', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testEnableOtp(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'OTP requirement for transfers has been enabled',
            'data' => [
                'status' => 'enabled',
                'details' => 'OTP requirement for transfers has been enabled on your account'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferControl->enableOtp();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer/enable_otp', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFinalizeDisableOtp(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'OTP requirement for transfers has been disabled',
            'data' => [
                'status' => 'disabled',
                'details' => 'OTP requirement for transfers has been disabled on your account'
            ]
        ];

        $expectedBody = json_encode([
            'otp' => '123456'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->transferControl->finalizeDisableOtp([
            'otp' => '123456'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/transfer/disable_otp_finalize', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}