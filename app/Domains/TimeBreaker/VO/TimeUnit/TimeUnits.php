<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\VO\TimeUnit;

use Illuminate\Contracts\Support\Arrayable;
use Stringable;

use function array_reduce;

final class TimeUnits implements Arrayable, Stringable
{
    private array $units;

    public function __construct(TimeUnit ...$units)
    {
        $this->units = $units;
    }

    public static function fromArray(array $timeUnits): self
    {
        $units = array_map(
            fn(string $unit, int|float $amount): TimeUnit => TimeUnit::fromBasics($unit, $amount),
            array_keys($timeUnits),
            array_values($timeUnits)
        );
        return new self(...$units);
    }

    public static function createEmpty(): self
    {
        return new self(...[]);
    }

    public function getUnits(): array
    {
        return $this->units;
    }

    public function push(TimeUnit $timeUnit): void
    {
        $this->units[] = $timeUnit;
    }

    public function isSame(TimeUnits $units): bool
    {
        return $this->toArray() === $units->toArray();
    }

    public function toArray(): array
    {
        return array_reduce(
            $this->units,
            function (array $carry, TimeUnit $unit): array {
                $carry[] = $unit->toArray();

                return $carry;
            },
            []
        );
    }

    public function __toString(): string
    {
        return implode(',', $this->units);
    }
}
