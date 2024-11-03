<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Service;

class DateTimeFactory
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
