<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\VO\TimeInterval;

use Carbon\CarbonInterval;
use Stringable;

final class TimeInterval implements Stringable
{
    private int $quantity;
    private string $interval;

    public const AVAILABLE_INTERVALS = [
        's' => 'seconds',
        'i' => 'minutes',
        'h' => 'hours',
        'd' => 'days',
        'm' => 'months',
    ];

    public function __construct(int $quantity, string $interval)
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('wrong quantity');
        }
        if (!isset(self::AVAILABLE_INTERVALS[$interval])) {
            throw new \InvalidArgumentException('wrong interval');
        }
        $this->quantity = $quantity;
        $this->interval = $interval;
    }

    public static function fromString(string $timeInterval): self
    {
        $timeInterval = trim($timeInterval);
        $interval = mb_substr($timeInterval, -1, 1);
        $quantity = mb_substr($timeInterval, 0, -1);
        if ($quantity === "") {
            $quantity = 1;
        }
        return new self((int)$quantity, $interval);
    }

    public function compare(self $timeInterval): int
    {
        return $this->toCarbonInterval()->compare($timeInterval->toCarbonInterval());
    }

    public function toCarbonInterval(): CarbonInterval
    {
        return CarbonInterval::make("{$this->quantity}{$this->getAvailableInterval()}");
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getInterval(): string
    {
        return $this->interval;
    }

    public function getAvailableInterval(): string
    {
        return self::AVAILABLE_INTERVALS[$this->interval];
    }

    public function __toString(): string
    {
        return "{$this->quantity}{$this->interval}";
    }
}
