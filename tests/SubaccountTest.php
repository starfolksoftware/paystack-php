<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class SubaccountTest extends TestCase
{
    public function testCreateSubaccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subaccount created',
            'data' => [
                'business_name' => 'Sunshine Stores',
                'account_number' => '0123456789',
                'percentage_charge' => 18.2,
                'description' => 'A store for sunshine',
                'primary_contact_email' => 'store@sunnystores.com',
                'primary_contact_name' => 'Store Owner',
                'primary_contact_phone' => '+234234234234',
                'metadata' => ['ref' => 'ref'],
                'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj',
                'is_verified' => false,
                'bank' => [
                    'id' => 9,
                    'name' => 'First City Monument Bank',
                    'slug' => 'first-city-monument-bank'
                ]
            ]
        ];

        $expectedBody = json_encode([
            'business_name' => 'Sunshine Stores',
            'settlement_bank' => '044',
            'account_number' => '0123456789',
            'percentage_charge' => 18.2,
            'description' => 'A store for sunshine'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subaccounts->create([
            'business_name' => 'Sunshine Stores',
            'settlement_bank' => '044',
            'account_number' => '0123456789',
            'percentage_charge' => 18.2,
            'description' => 'A store for sunshine'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/subaccount', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListSubaccounts(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subaccounts retrieved',
            'data' => [
                [
                    'id' => 37,
                    'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj',
                    'business_name' => 'Sunshine Stores',
                    'description' => 'A store for sunshine',
                    'primary_contact_name' => 'Store Owner',
                    'primary_contact_email' => 'store@sunnystores.com',
                    'primary_contact_phone' => '+234234234234',
                    'percentage_charge' => 18.2,
                    'is_verified' => false,
                    'settlement_bank' => 'First City Monument Bank',
                    'account_number' => '0123456789'
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
        
        $data = $this->client()->subaccounts->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/subaccount', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchSubaccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subaccount retrieved',
            'data' => [
                'id' => 37,
                'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj',
                'business_name' => 'Sunshine Stores',
                'description' => 'A store for sunshine',
                'primary_contact_name' => 'Store Owner',
                'primary_contact_email' => 'store@sunnystores.com',
                'primary_contact_phone' => '+234234234234',
                'percentage_charge' => 18.2,
                'is_verified' => false,
                'settlement_bank' => 'First City Monument Bank',
                'account_number' => '0123456789',
                'metadata' => ['ref' => 'ref']
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subaccounts->find('ACCT_8f4s1eq7ml6rlzj');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/subaccount/ACCT_8f4s1eq7ml6rlzj', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateSubaccount(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subaccount updated',
            'data' => [
                'id' => 37,
                'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj',
                'business_name' => 'Updated Sunshine Stores',
                'description' => 'An updated store for sunshine',
                'primary_contact_name' => 'Store Owner',
                'primary_contact_email' => 'store@sunnystores.com',
                'primary_contact_phone' => '+234234234234',
                'percentage_charge' => 20.0,
                'is_verified' => false,
                'settlement_bank' => 'First City Monument Bank',
                'account_number' => '0123456789'
            ]
        ];

        $expectedBody = json_encode([
            'business_name' => 'Updated Sunshine Stores',
            'description' => 'An updated store for sunshine',
            'percentage_charge' => 20.0
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->subaccounts->update('ACCT_8f4s1eq7ml6rlzj', [
            'business_name' => 'Updated Sunshine Stores',
            'description' => 'An updated store for sunshine',
            'percentage_charge' => 20.0
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/subaccount/ACCT_8f4s1eq7ml6rlzj', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}