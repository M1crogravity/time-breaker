<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\VO\TimeInterval;

use Stringable;

final class TimeIntervals implements Stringable
{
    private array $intervals;

    public function __construct(TimeInterval ...$intervals)
    {
        $this->intervals = $intervals;
    }

    public static function fromArray(array $intervals): self
    {
        $timeIntervals = array_map(
            fn(string $interval): TimeInterval => TimeInterval::fromString($interval),
            $intervals
        );
        usort(
            $timeIntervals,
            fn (TimeInterval $alice, TimeInterval $bob): int => $bob->compare($alice)
        );
        return new self(...$timeIntervals);
    }

    /** @return TimeInterval[] */
    public function getIntervals(): array
    {
        return $this->intervals;
    }

    public function lastKey(): int
    {
        return count($this->intervals) - 1;
    }

    public function __toString(): string
    {
        return implode(',', $this->intervals);
    }
}
