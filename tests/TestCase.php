<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Tests;

use Http\Mock\Client;
use StarfolkSoftware\Paystack\Client as PaystackClient;
use StarfolkSoftware\Paystack\ClientBuilder;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Client $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = new Client();
    }

    protected function client(): PaystackClient
    {
        return new PaystackClient([
            'clientBuilder' => new ClientBuilder($this->mockClient),
            'secretKey' => 'secret',
        ]);
    }
}