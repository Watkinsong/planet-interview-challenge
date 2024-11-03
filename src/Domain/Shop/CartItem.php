<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Domain\Shop;

use stdClass;

class CartItem implements \JsonSerializable
{
    private int $expires;

    private int $price;

    public function __construct(int $price, int $expires)
    {
        $this->expires = $expires;
        $this->price = $price;
    }

    /**
     * Returns the state representation of the object.
     *
     * @return string State representation of the class, encoded in JSON.
     */
    public function getState(): string
    {
        $state = json_encode($this);

        if ($state === false) {
            throw new \RuntimeException('Could not JSON encode cart item state.');
        }

        return $state;
    }

    public function jsonSerialize(): object
    {
        $state = new StdClass();
        $state->price = $this->price;
        $state->expires = $this->expires;

        return $state;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }
}
