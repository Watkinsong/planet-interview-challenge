<?php

use PHPUnit\Framework\TestCase;
use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Domain\Shop\Service\CartItemExpirationService;
use Planet\InterviewChallenge\Service\DateTimeFactory;

class CartItemTest extends TestCase
{
    protected ?CartItemExpirationService $cartItemExpirationService;
    protected ?DateTimeFactory $dateTimeFactory;

    public function tearDown(): void
    {
        unset($this->cartItemExpirationService);
        $this->cartItemExpirationService = null;

        unset($this->dateTimeFactory);
        $this->dateTimeFactory = null;
    }

    public function testIsAvailable(): void
    {
        $now = new DateTimeImmutable();

        $this->dateTimeFactory = $this->getMockBuilder(DateTimeFactory::class)
            ->getMock();

        $dateTimeFactoryNowMock = $this->dateTimeFactory->method('now');
        $dateTimeFactoryNowMock->willReturn($now);

        $this->cartItemExpirationService = new CartItemExpirationService($this->dateTimeFactory);

        $object = new CartItem(
            123,
            $this->cartItemExpirationService->generateExpiration(CartItemExpirationService::MODE_NO_LIMIT)
        );
        $this->assertTrue($this->cartItemExpirationService->isAvailable($object));

        $object = new CartItem(
            123,
            $this->cartItemExpirationService->generateExpiration(
                CartItemExpirationService::MODE_NO_LIMIT,
                1
            )
        );
        $this->assertTrue($this->cartItemExpirationService->isAvailable($object));

        $object = new CartItem(
            123,
            $this->cartItemExpirationService->generateExpiration(
                CartItemExpirationService::MODE_SECONDS,
                60
            )
        );
        $this->assertFalse($this->cartItemExpirationService->isAvailable($object));

        $now = $now->modify('+30 seconds');
        $dateTimeFactoryNowMock->willReturn($now);
        $this->assertFalse($this->cartItemExpirationService->isAvailable($object));

        $now = $now->modify('+30 seconds');
        $dateTimeFactoryNowMock->willReturn($now);
        $this->assertTrue($this->cartItemExpirationService->isAvailable($object));
    }
}
