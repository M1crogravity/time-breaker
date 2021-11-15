<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\TimeBreaker\IntervalSplitter;

use App\Domains\TimeBreaker\IntervalSplitter\CarbonIntervalSplitter;
use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;
use Tests\TestCase;

final class CarbonIntervalSplitterTest extends TestCase
{
    /** @dataProvider ranges */
    public function test_it_splits_range(TimeRange $range, TimeIntervals $intervals, TimeUnits $expected): void
    {
        $splitter = new CarbonIntervalSplitter();
        $units = $splitter->split($range, $intervals);
        $this->assertTrue($expected->isSame($units), "Actual {$units} is not the same as expected {$expected}");
    }

    public function ranges(): iterable
    {
        yield 'range from example' => [
            TimeRange::fromStrings('2020-01-01T00:00:00', '2020-03-01T12:30:00'),
            TimeIntervals::fromArray(['2m', 'm', 'd', '2h']),
            TimeUnits::fromArray(['2m' => 1, 'm' => 0, 'd' => 0, '2h' => 6.25]),
        ];
        yield 'range from another example' => [
            TimeRange::fromStrings('2020-01-01T00:00:00', '2020-04-29T12:30:00'),
            TimeIntervals::fromArray(['2m', 'm', 'd', '2h']),
            TimeUnits::fromArray(['2m' => 1, 'm' => 1, 'd' => 28, '2h' => 6.25]),
        ];
        //...
    }
}
