<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\VO\TimeUnit;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeInterval;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Support\Arrayable;
use Stringable;

final class TimeUnit implements Arrayable, Stringable
{
    private TimeInterval $interval;
    private int|float $amount;

    public function __construct(
        TimeInterval $interval,
        int|float $amount,
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException('amount less then 0');
        }
        $this->interval = $interval;
        $this->amount = $amount;
    }

    public static function fromBasics(string $interval, int|float $amount): self
    {
        return new self(
            TimeInterval::fromString($interval),
            $amount,
        );
    }

    public function toCarbonInterval(): CarbonInterval
    {
        $amount = $this->amount * $this->interval->getQuantity();
        return CarbonInterval::make("{$amount}{$this->interval->getAvailableInterval()}");
    }

    public function getInterval(): TimeInterval
    {
        return $this->interval;
    }

    public function getAmount(): int|float
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [(string)$this->interval => $this->amount];
    }

    public function __toString(): string
    {
        return "{$this->interval}:{$this->amount}";
    }
}
