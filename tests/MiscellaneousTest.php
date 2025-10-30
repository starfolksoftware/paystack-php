<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use StarfolkSoftware\Paystack\Options\Miscellaneous\{
    ListBanksOptions,
    ListStatesOptions
};

final class MiscellaneousTest extends TestCase
{
    public function testListBanks(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Banks retrieved',
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Access Bank',
                    'slug' => 'access-bank',
                    'code' => '044',
                    'longcode' => '044150149',
                    'gateway' => 'emandate',
                    'pay_with_bank' => false,
                    'active' => true,
                    'country' => 'Nigeria',
                    'currency' => 'NGN',
                    'type' => 'nuban',
                    'is_deleted' => false,
                    'created_at' => '2016-07-14T10:04:29.000Z',
                    'updated_at' => '2023-01-01T12:00:00.000Z'
                ],
                [
                    'id' => 2,
                    'name' => 'Citibank Nigeria',
                    'slug' => 'citibank-nigeria',
                    'code' => '023',
                    'longcode' => '023150005',
                    'gateway' => null,
                    'pay_with_bank' => false,
                    'active' => true,
                    'country' => 'Nigeria',
                    'currency' => 'NGN',
                    'type' => 'nuban',
                    'is_deleted' => false,
                    'created_at' => '2016-07-14T10:04:29.000Z',
                    'updated_at' => '2023-01-01T12:00:00.000Z'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new ListBanksOptions(['country' => 'nigeria']);
        $data = $this->client()->miscellaneous->listBanks($options);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bank', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListBanksWithNoParams(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Banks retrieved',
            'data' => []
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->miscellaneous->listBanks();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/bank', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListCountries(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Countries retrieved',
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Nigeria',
                    'iso_code' => 'NG',
                    'default_currency_code' => 'NGN',
                    'integration_defaults' => [],
                    'relationships' => [
                        'currency' => [
                            'type' => 'currency',
                            'data' => [
                                'code' => 'NGN',
                                'name' => 'Nigerian Naira'
                            ]
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Ghana',
                    'iso_code' => 'GH',
                    'default_currency_code' => 'GHS',
                    'integration_defaults' => [],
                    'relationships' => [
                        'currency' => [
                            'type' => 'currency',
                            'data' => [
                                'code' => 'GHS',
                                'name' => 'Ghanaian Cedi'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->miscellaneous->listCountries();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/country', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testListStates(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'States retrieved',
            'data' => [
                [
                    'name' => 'Abia',
                    'slug' => 'abia',
                    'abbreviation' => 'AB'
                ],
                [
                    'name' => 'Adamawa',
                    'slug' => 'adamawa', 
                    'abbreviation' => 'AD'
                ],
                [
                    'name' => 'Akwa Ibom',
                    'slug' => 'akwa-ibom',
                    'abbreviation' => 'AK'
                ],
                [
                    'name' => 'Anambra',
                    'slug' => 'anambra',
                    'abbreviation' => 'AN'
                ],
                [
                    'name' => 'Bauchi',
                    'slug' => 'bauchi',
                    'abbreviation' => 'BA'
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $options = new ListStatesOptions(['country' => 'NG']);
        $data = $this->client()->miscellaneous->listStates($options);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/address_verification/states', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}