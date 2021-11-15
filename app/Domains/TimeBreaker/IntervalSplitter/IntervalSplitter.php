<?php

namespace App\Domains\TimeBreaker\IntervalSplitter;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;

interface IntervalSplitter
{
    public function split(TimeRange $range, TimeIntervals $intervals): TimeUnits;
}
