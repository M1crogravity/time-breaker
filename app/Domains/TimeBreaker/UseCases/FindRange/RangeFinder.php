<?php

namespace App\Domains\TimeBreaker\UseCases\FindRange;

use App\Domains\TimeBreaker\Models\Range;

interface RangeFinder
{
    public function find(FindRangeDTO $dto): Range;
}
