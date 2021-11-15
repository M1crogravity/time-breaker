<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\VO;

use Carbon\CarbonImmutable;

final class TimePoint
{
    public function __construct(
        private CarbonImmutable $value,
    ) {
    }

    public static function fromString(string $dateTime): self
    {
        return new self(CarbonImmutable::parse($dateTime));
    }

    public function getValue(): CarbonImmutable
    {
        return $this->value;
    }

    public function isGreater(self $timePoint): bool
    {
        return $this->value->greaterThan($timePoint->getValue());
    }
}
