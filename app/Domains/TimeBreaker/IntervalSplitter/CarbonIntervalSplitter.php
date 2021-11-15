<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\IntervalSplitter;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeInterval;
use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnit;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use LogicException;

final class CarbonIntervalSplitter implements IntervalSplitter
{
    public function split(TimeRange $range, TimeIntervals $intervals): TimeUnits
    {
        $units = TimeUnits::createEmpty();
        $period = $this->makePeriod($range);
        $last = $intervals->lastKey();
        foreach ($intervals->getIntervals() as $i => $interval) {
            $carbonInterval = $interval->toCarbonInterval();
            $period->setDateInterval($carbonInterval);
            $timeUnit = match ($i === $last) {
                true => new TimeUnit($interval, $this->getLastUnitAmount($period, $interval)),
                false => new TimeUnit($interval, $period->count()),
            };
            $units->push($timeUnit);
            $period = $period->setStartDate($period->getStartDate()->add($timeUnit->toCarbonInterval()));
        }
        return $units;
    }

    private function makePeriod(TimeRange $timeRange): CarbonPeriod
    {
        return $timeRange->toPeriod()
            ->setDateClass(CarbonImmutable::class)
            ->addFilter(function (CarbonInterface $current, int $key, CarbonPeriod $period): bool {
                return $current->add($period->interval)
                    ->lessThanOrEqualTo($period->getEndDate());
            }, 'withinRange');
    }

    private function getLastUnitAmount(CarbonPeriod $period, TimeInterval $interval): float
    {
        $resolver = $this->getResolver($interval);

        return $resolver($period->getStartDate(), $period->getEndDate()) / $interval->getQuantity();
    }

    /**
     * @param  TimeInterval  $interval
     * @return callable(CarbonInterface, CarbonInterface): float
     */
    private function getResolver(TimeInterval $interval): callable
    {
        return match ($interval->getInterval()) {
            's' => fn (CarbonInterface $start, CarbonInterface $end): float => $start->floatDiffInSeconds($end),
            'i' => fn (CarbonInterface $start, CarbonInterface $end): float => $start->floatDiffInMinutes($end),
            'h' => fn (CarbonInterface $start, CarbonInterface $end): float => $start->floatDiffInHours($end),
            'd' => fn (CarbonInterface $start, CarbonInterface $end): float => $start->floatDiffInDays($end),
            'm' => fn (CarbonInterface $start, CarbonInterface $end): float => $start->floatDiffInMonths($end),
            default => throw new LogicException('unsupported interval'),
        };
    }
}
