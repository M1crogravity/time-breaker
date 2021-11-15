<?php

declare(strict_types=1);

namespace App\Storage\Repositories\Postgres;

use App\Domains\TimeBreaker\Models\Range;
use App\Domains\TimeBreaker\Models\Unit;
use App\Storage\Repositories\RangeRepository;
use Belamov\PostgresRange\Ranges\TimestampRange;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;

final class RangePostgresRepository implements RangeRepository
{
    /** @throws ModelNotFoundException */
    public function findOneByRangeWithUnit(TimestampRange|string $range, string $intervals): Range
    {
        return Range::where('range', $range)
            ->with([
                'units' => fn(HasMany $query): HasMany => $query->where('intervals', $intervals)
            ])->firstOrFail();
    }

    /** @throws QueryException */
    public function saveWithUnits(Range $range): void
    {
        DB::transaction(function () use ($range) {
            $range->save();
            $range->units->each(function (Unit $unit) use ($range) {
                $unit->range()->associate($range);
                $unit->save();
            });
        });
    }

    /** @throws ModelNotFoundException */
    public function findOneByRangeWithUnits(TimestampRange|string $range): Range
    {
        return Range::where('range', $range)
            ->with(['units'])
            ->firstOrFail();
    }
}
