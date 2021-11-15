<?php

namespace App\Storage\Repositories;

use App\Domains\TimeBreaker\Models\Range;
use Belamov\PostgresRange\Ranges\TimestampRange;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

interface RangeRepository
{
    /** @throws ModelNotFoundException */
    public function findOneByRangeWithUnit(string|TimestampRange $range, string $intervals): Range;
    /** @throws QueryException */
    public function saveWithUnits(Range $range): void;
    /** @throws ModelNotFoundException */
    public function findOneByRangeWithUnits(string|TimestampRange $range): Range;
}
