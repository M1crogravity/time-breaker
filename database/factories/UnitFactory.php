<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\TimeBreaker\Models\Unit;
use App\Domains\TimeBreaker\VO\TimeInterval\TimeInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

final class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $intervals = $this->generateIntervals();

        return [
            'intervals' => implode(',', $intervals),
            'result' => $this->generateResult($intervals),
        ];
    }

    private function generateIntervals(): array
    {
        $available = array_keys(TimeInterval::AVAILABLE_INTERVALS);
        $intervals = $this->faker->randomElements($available, $this->faker->numberBetween(1, count($available)));

        return array_map(
            fn(string $interval): string => "{$this->faker->randomDigitNotZero()}{$interval}",
            $intervals
        );
    }

    private function generateResult(array $intervals): array
    {
        return array_reduce(
            $intervals,
            function (array $carry, string $interval): array {
                $carry[$interval] = $this->faker->randomDigit();

                return $carry;
            },
            []
        );
    }
}
