<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use StarfolkSoftware\Paystack\Client;
use Http\Mock\Client as MockClient;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

class PaymentRequestTest extends BaseTestCase
{
    private Client $client;
    private MockClient $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new MockClient();
        
        $this->client = new Client([
            'secretKey' => 'secret',
            'clientBuilder' => new \StarfolkSoftware\Paystack\ClientBuilder($this->httpClient),
        ]);
    }

    public function testCreatePaymentRequest(): void
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

        $expectedBody = json_encode([
            'description' => 'a test invoice',
            'line_items' => [
                ['name' => 'item 1', 'amount' => 20000],
                ['name' => 'item 2', 'amount' => 20000]
            ],
            'tax' => [
                ['name' => 'VAT', 'amount' => 2000]
            ],
            'customer' => 'CUS_xwaj0txjryg393b',
            'due_date' => '2020-07-08'
        ]);

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode($responseData));
        $stream->rewind();

        $response = new Response($stream, 200, ['Content-Type' => 'application/json']);
        
        $this->httpClient->addResponse($response);
        
        $data = $this->client->paymentRequests->create([
            'description' => 'a test invoice',
            'line_items' => [
                ['name' => 'item 1', 'amount' => 20000],
                ['name' => 'item 2', 'amount' => 20000]
            ],
            'tax' => [
                ['name' => 'VAT', 'amount' => 2000]
            ],
            'customer' => 'CUS_xwaj0txjryg393b',
            'due_date' => '2020-07-08'
        ]);

        $sentRequest = $this->httpClient->getLastRequest();
        
        $sentBody = $sentRequest->getBody()->__toString();
        
        $this->assertEquals('POST', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest', $sentRequest->getUri()->getPath());
        $this->assertEquals($expectedBody, $sentBody);
        $this->assertEquals($responseData, $data);
    }

    public function testListPaymentRequests(): void
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
        
        $this->httpClient->addResponse($response);
        
        $data = $this->client->paymentRequests->all(['page' => 1, 'perPage' => 50]);

        $sentRequest = $this->httpClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }

    public function testFetchPaymentRequest(): void
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
        
        $this->httpClient->addResponse($response);
        
        $data = $this->client->paymentRequests->fetch('PRQ_1weqqsn2wwzgft8');

        $sentRequest = $this->httpClient->getLastRequest();
        
        $this->assertEquals('GET', $sentRequest->getMethod());
        $this->assertEquals('/paymentrequest/PRQ_1weqqsn2wwzgft8', $sentRequest->getUri()->getPath());
        $this->assertEquals($responseData, $data);
    }
}
