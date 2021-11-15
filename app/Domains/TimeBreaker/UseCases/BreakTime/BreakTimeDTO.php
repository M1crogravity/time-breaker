<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\UseCases\BreakTime;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Http\Actions\Api\V1\TimeBreaker\BreakTime\BreakTimeRequest;

final class BreakTimeDTO
{
    public function __construct(
        private TimeRange $range,
        private TimeIntervals $intervals,
    ) {
    }

    public static function fromRequest(BreakTimeRequest $request): self
    {
        return new self(
            TimeRange::fromStrings($request->start_date, $request->end_date),
            TimeIntervals::fromArray($request->intervals),
        );
    }

    public function getRange(): TimeRange
    {
        return $this->range;
    }

    public function getIntervals(): TimeIntervals
    {
        return $this->intervals;
    }
}
