<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\Models;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Domains\TimeBreaker\Models\Unit
 *
 * @property int $id
 * @property int $range_id
 * @property string $intervals
 * @property mixed $result
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Domains\TimeBreaker\Models\Range $range
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereIntervals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereRangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Unit extends Model
{
    use HasFactory;

    protected $casts = [
        'result' => 'array',
    ];

    public static function makeFromSplitting(TimeIntervals $intervals, TimeUnits $units): self
    {
        $unit = new self();
        $unit->intervals = (string)$intervals;
        $unit->result = $units->toArray();

        return $unit;
    }

    protected static function newFactory(): UnitFactory
    {
        return UnitFactory::new();
    }

    public function range(): BelongsTo
    {
        return $this->belongsTo(Range::class);
    }
}
