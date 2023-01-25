<?php

namespace StarfolkSoftware\Paystack\Tests;

use Laminas\Diactoros\Response\JsonResponse;

final class PlanTest extends TestCase
{
    public function testOk(): void
    {
        $this->assertEquals('ok', 'ok');
    }
}