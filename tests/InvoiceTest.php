<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

final class InvoiceTest extends TestCase
{
    public function testCreateInvoice(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment request created',
            'data' => [
                'id' => 3136406,
                'domain' => 'test',
                'amount' => 42000,
                'currency' => 'NGN',
                'due_date' => '2020-07-08T00:00:00.000Z',
                'has_invoice' => true,
                'invoice_number' => 1,
                'description' => 'a test invoice',
                'request_code' => 'PRQ_1weqqsn2wwzgft8',
                'status' => 'pending',
                'paid' => false,
            ]
        ];

        $dateTime = new \DateTime('2020-07-08');
        $expectedBody = json_encode([
            'description' => 'a test invoice',
            'amount' => 42000,
            'line_items' => [
                ['name' => 'item 1', 'amount' => 20000],
                ['name' => 'item 2', 'amount' => 20000]
            ],
            'tax' => [
                ['name' => 'VAT', 'amount' => 2000]
            ],
            'customer' => 'CUS_xwaj0txjryg393b',
            'due_date' => $dateTime
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->create([
            'description' => 'a test invoice',
            'amount' => 42000,
            'line_items' => [
                ['name' => 'item 1', 'amount' => 20000],
                ['name' => 'item 2', 'amount' => 20000]
            ],
            'tax' => [
                ['name' => 'VAT', 'amount' => 2000]
            ],
            'customer' => 'CUS_xwaj0txjryg393b',
            'due_date' => $dateTime
        ]);

        $sentRequest = $this->mockClient->getLastRequest();
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListInvoices(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment requests retrieved',
            'data' => [
                [
                    'id' => 3136406,
                    'domain' => 'test',
                    'amount' => 42000,
                    'currency' => 'NGN',
                    'request_code' => 'PRQ_1weqqsn2wwzgft8',
                    'status' => 'pending',
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
        
        $data = $this->client()->invoices->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchInvoice(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment request retrieved',
            'data' => [
                'id' => 3136406,
                'domain' => 'test',
                'amount' => 42000,
                'currency' => 'NGN',
                'request_code' => 'PRQ_1weqqsn2wwzgft8',
                'status' => 'pending',
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->find('PRQ_1weqqsn2wwzgft8');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/PRQ_1weqqsn2wwzgft8', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testVerifyInvoice(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment request retrieved',
            'data' => [
                'id' => 3136406,
                'domain' => 'test',
                'amount' => 42000,
                'currency' => 'NGN',
                'request_code' => 'PRQ_1weqqsn2wwzgft8',
                'status' => 'success',
                'paid' => true,
                'transactions' => [
                    [
                        'id' => 2009945086,
                        'status' => 'success',
                        'reference' => 'T563902343_1628168464',
                        'amount' => 42000
                    ]
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->verify('PRQ_1weqqsn2wwzgft8');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/verify/PRQ_1weqqsn2wwzgft8', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testNotifyInvoice(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Notification sent'
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->notify('PRQ_1weqqsn2wwzgft8');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/notify/PRQ_1weqqsn2wwzgft8', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFinalizeInvoice(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment request finalized',
            'data' => [
                'id' => 3136406,
                'request_code' => 'PRQ_1weqqsn2wwzgft8',
                'status' => 'pending'
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->finalize('PRQ_1weqqsn2wwzgft8');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/finalize/PRQ_1weqqsn2wwzgft8', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testArchiveInvoice(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment request archived'
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->archive('PRQ_1weqqsn2wwzgft8');

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/archive/PRQ_1weqqsn2wwzgft8', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testInvoiceStats(): void
    {
        $responseData = [
            'status' => true,
            'message' => 'Payment request totals',
            'data' => [
                'pending' => [
                    'count' => 5,
                    'amount' => 500000
                ],
                'successful' => [
                    'count' => 3,
                    'amount' => 300000
                ],
                'total' => [
                    'count' => 8,
                    'amount' => 800000
                ]
            ]
        ];

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->mockClient->addResponse($response);
        
        $data = $this->client()->invoices->stats();

        $sentRequest = $this->mockClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/totals', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}