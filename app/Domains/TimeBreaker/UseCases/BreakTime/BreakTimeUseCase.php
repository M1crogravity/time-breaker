<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\UseCases\BreakTime;

use App\Domains\TimeBreaker\IntervalSplitter\IntervalSplitter;
use App\Domains\TimeBreaker\Models\Range;
use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Storage\Repositories\RangeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class BreakTimeUseCase implements TimeBreaker
{
    public function __construct(
        private IntervalSplitter $splitter,
        private RangeRepository $rangeRepository,
    ) {
    }

    public function breakRange(BreakTimeDTO $dto): Range
    {
        $timeRange = $dto->getRange();
        $intervals = $dto->getIntervals();
        $range = $this->getRange($timeRange, $intervals);
        if ($range->units->isNotEmpty()) {
            return $range;
        }
        $units = $this->splitter->split($timeRange, $intervals);
        $range->addNewUnit($intervals, $units);
        $this->rangeRepository->saveWithUnits($range);

        return $range;
    }

    /** @throws ModelNotFoundException */
    private function getRange(TimeRange $timeRange, TimeIntervals $intervals): Range
    {
        try {
            $range = $this->rangeRepository->findOneByRangeWithUnit($timeRange->toTimestampRange(), (string)$intervals);
        } catch (ModelNotFoundException) {
            $range = Range::fromTimeRange($timeRange);
        }

        return $range;
    }
}
