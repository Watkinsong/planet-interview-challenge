<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop\Service;

use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Service\DateTimeFactory;

class CartItemExpirationService
{
    const MODE_NO_LIMIT = 0;

    const MODE_HOUR = 1;

    const MODE_MINUTE = 10;

    const MODE_SECONDS = 1000;

    private DateTimeFactory $dateTimeFactory;

    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function generateExpiration(int $mode, ?int $modifier = null): int
    {
        $now = $this->dateTimeFactory->now();

        switch ($mode) {
            case self::MODE_NO_LIMIT:
                return -2;
            case self::MODE_HOUR:
                return $now->modify('+1 hour')->getTimestamp();
            case self::MODE_MINUTE:
                return $now->modify(sprintf('+%d minutes', $modifier))->getTimestamp();
            case self::MODE_SECONDS:
                return $now->modify(sprintf('+%d seconds', $modifier))->getTimestamp();
            default:
                throw new \InvalidArgumentException(sprintf('Invalid mode: %d', $mode));
        }
    }

    public function isAvailable(CartItem $item): bool
    {
        $now = $this->dateTimeFactory->now();
        $timestamp = $now->getTimestamp();

        return $item->getExpires() <= $timestamp;
    }
}
