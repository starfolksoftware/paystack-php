<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class SplitTest extends TestCase
{
    public function testCreateSplit(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Split created',
            'data' => [
                'id' => 142,
                'name' => 'Percentage Split',
                'type' => 'percentage',
                'currency' => 'NGN',
                'integration' => 463433,
                'domain' => 'test',
                'split_code' => 'SPL_e7jnRLtzla',
                'active' => true,
                'bearer_type' => 'account',
                'bearer_subaccount' => null,
                'subaccounts' => [
                    [
                        'subaccount' => [
                            'id' => 37,
                            'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj',
                            'business_name' => 'Sunshine Studios'
                        ],
                        'share' => 20
                    ]
                ],
                'total_subaccounts' => 1,
                'created_at' => '2020-06-30T11:42:29.000Z',
                'updated_at' => '2020-06-30T11:42:29.000Z'
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Percentage Split',
            'type' => 'percentage',
            'currency' => 'NGN',
            'subaccounts' => [
                [
                    'subaccount' => 'ACCT_8f4s1eq7ml6rlzj',
                    'share' => 20
                ]
            ]
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->splits->create([
            'name' => 'Percentage Split',
            'type' => 'percentage',
            'currency' => 'NGN',
            'subaccounts' => [
                [
                    'subaccount' => 'ACCT_8f4s1eq7ml6rlzj',
                    'share' => 20
                ]
            ]
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/split', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListSplits(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Splits retrieved',
            'data' => [
                [
                    'id' => 142,
                    'name' => 'Percentage Split',
                    'type' => 'percentage',
                    'currency' => 'NGN',
                    'split_code' => 'SPL_e7jnRLtzla',
                    'active' => true,
                    'total_subaccounts' => 1
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
        
        $data = $this->client()->splits->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/split', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchSplit(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Split retrieved',
            'data' => [
                'id' => 142,
                'name' => 'Percentage Split',
                'type' => 'percentage',
                'currency' => 'NGN',
                'split_code' => 'SPL_e7jnRLtzla',
                'active' => true,
                'bearer_type' => 'account',
                'subaccounts' => [
                    [
                        'subaccount' => [
                            'id' => 37,
                            'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj',
                            'business_name' => 'Sunshine Studios'
                        ],
                        'share' => 20
                    ]
                ],
                'total_subaccounts' => 1
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->splits->find('SPL_e7jnRLtzla');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/split/SPL_e7jnRLtzla', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testUpdateSplit(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Split updated',
            'data' => [
                'id' => 142,
                'name' => 'Updated Percentage Split',
                'type' => 'percentage',
                'currency' => 'NGN',
                'split_code' => 'SPL_e7jnRLtzla',
                'active' => false
            ]
        ];

        $expectedBody = json_encode([
            'name' => 'Updated Percentage Split',
            'active' => false
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->splits->update('SPL_e7jnRLtzla', [
            'name' => 'Updated Percentage Split',
            'active' => false
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('PUT', $sentRequest->getMethod());
        $this->assertEquals('/split/SPL_e7jnRLtzla', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testAddSubaccountToSplit(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subaccount added',
            'data' => [
                'id' => 142,
                'name' => 'Percentage Split',
                'type' => 'percentage',
                'split_code' => 'SPL_e7jnRLtzla',
                'subaccounts' => [
                    [
                        'subaccount' => [
                            'id' => 37,
                            'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj'
                        ],
                        'share' => 20
                    ],
                    [
                        'subaccount' => [
                            'id' => 38,
                            'subaccount_code' => 'ACCT_newaccount123'
                        ],
                        'share' => 30
                    ]
                ],
                'total_subaccounts' => 2
            ]
        ];

        $expectedBody = json_encode([
            'subaccount' => 'ACCT_newaccount123',
            'share' => 30
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->splits->addSubaccount('SPL_e7jnRLtzla', [
            'subaccount' => 'ACCT_newaccount123',
            'share' => 30
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/split/SPL_e7jnRLtzla/subaccount/add', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testRemoveSubaccountFromSplit(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Subaccount removed',
            'data' => [
                'id' => 142,
                'name' => 'Percentage Split',
                'type' => 'percentage',
                'split_code' => 'SPL_e7jnRLtzla',
                'subaccounts' => [
                    [
                        'subaccount' => [
                            'id' => 37,
                            'subaccount_code' => 'ACCT_8f4s1eq7ml6rlzj'
                        ],
                        'share' => 20
                    ]
                ],
                'total_subaccounts' => 1
            ]
        ];

        $expectedBody = json_encode([
            'subaccount' => 'ACCT_newaccount123'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->splits->removeSubaccount('SPL_e7jnRLtzla', [
            'subaccount' => 'ACCT_newaccount123'
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/split/SPL_e7jnRLtzla/subaccount/remove', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }
}