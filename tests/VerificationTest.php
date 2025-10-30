<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use StarfolkSoftware\Paystack\Options\Verification\{
    ResolveAccountOptions,
    ValidateAccountOptions
};

final class VerificationTest extends TestCase
{
    public function testResolveAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Account number resolved',
            'data' => [
                'account_number' => '0123456789',
                'account_name' => 'John Doe',
                'bank_id' => 1
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new ResolveAccountOptions([
            'account_number' => '0123456789',
            'bank_code' => '044'
        ]);
        $data = $this->client()->verification->resolveAccount($options);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bank/resolve', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testValidateAccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Account validation successful',
            'data' => [
                'verified' => true,
                'verification_code' => 'ABC123',
                'account_number' => '0123456789',
                'account_name' => 'John Doe',
                'bank_code' => '044',
                'bank_name' => 'Access Bank'
            ]
        ];

        $expectedBody = json_encode([
            'account_name' => 'John Doe',
            'account_number' => '0123456789',
            'account_type' => 'personal',
            'bank_code' => '044',
            'country_code' => 'NG',
            'document_type' => 'identityNumber',
            'document_number' => '12345678901'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new ValidateAccountOptions([
            'account_name' => 'John Doe',
            'account_number' => '0123456789',
            'account_type' => 'personal',
            'bank_code' => '044',
            'country_code' => 'NG',
            'document_type' => 'identityNumber',
            'document_number' => '12345678901'
        ]);
        $data = $this->client()->verification->validateAccount($options);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/bank/validate', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testResolveCardBin(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Card bin resolved',
            'data' => [
                'bin' => '408408',
                'brand' => 'visa',
                'sub_brand' => '',
                'country_code' => 'NG',
                'country_name' => 'Nigeria',
                'card_type' => 'DEBIT',
                'bank' => 'Test Bank',
                'linked_bank_id' => 1
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->verification->resolveCardBin('408408');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/decision/bin/408408', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}