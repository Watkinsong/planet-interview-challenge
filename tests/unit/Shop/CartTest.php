<?php

use PHPUnit\Framework\TestCase;
use Planet\InterviewChallenge\Domain\Shop\Cart;
use Planet\InterviewChallenge\Domain\Shop\CartItem;

class CartTest extends TestCase
{
    private ?Cart $object;

    public function setUp(): void
    {
        $this->object = new Cart();
    }

    public function tearrDown(): void
    {
        unset($this->object);
        $this->object = null;
    }

    public function testGetState(): void
    {
        $this->object->addItem(new CartItem(12300, -2));
        $state = $this->object->getState();

        $expected = [
            (object)[
                'price'   => 12300,
                'expires' => -2,
            ]
        ];
        $this->assertEquals(1, count(json_decode($state)));
        $this->assertEquals($expected, json_decode($state));
    }
}
