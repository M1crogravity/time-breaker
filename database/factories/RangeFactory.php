<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\TimeBreaker\Models\Range;
use Belamov\PostgresRange\Ranges\TimestampRange;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

final class RangeFactory extends Factory
{
    protected $model = Range::class;

    public function definition(): array
    {
        $dateTime = new CarbonImmutable($this->faker->dateTime);

        return [
            'range' => new TimestampRange($dateTime, $dateTime->addMonths($this->faker->randomDigitNotZero()), '[', ']'),
        ];
    }
}
