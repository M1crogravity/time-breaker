<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\Models;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;
use Belamov\PostgresRange\Casts\TimestampRangeCast;
use Belamov\PostgresRange\Ranges\TimestampRange;
use Database\Factories\RangeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Domains\TimeBreaker\Models\Range
 *
 * @property int $id
 * @property TimestampRange|string $range
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\TimeBreaker\Models\Unit[] $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder|Range newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Range newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Range query()
 * @method static \Illuminate\Database\Eloquent\Builder|Range whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Range whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Range whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Range extends Model
{
    use HasFactory;

    protected $casts = [
        'range' => TimestampRangeCast::class,
    ];

    public static function fromTimeRange(TimeRange $timeRange): self
    {
        $range = new self();
        $range->range = $timeRange->toTimestampRange();

        return $range;
    }

    protected static function newFactory(): RangeFactory
    {
        return RangeFactory::new();
    }

    public function addNewUnit(TimeIntervals $intervals, TimeUnits $units): void
    {
        $unit = Unit::makeFromSplitting($intervals, $units);
        $unit->range()->associate($this);
        $this->units->add($unit);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
