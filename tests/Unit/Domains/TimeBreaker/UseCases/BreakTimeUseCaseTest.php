<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\TimeBreaker\UseCases;

use App\Domains\TimeBreaker\IntervalSplitter\IntervalSplitter;
use App\Domains\TimeBreaker\Models\Range;
use App\Domains\TimeBreaker\Models\Unit;
use App\Domains\TimeBreaker\UseCases\BreakTime\BreakTimeDTO;
use App\Domains\TimeBreaker\UseCases\BreakTime\BreakTimeUseCase;
use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeRange;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;
use App\Storage\Repositories\RangeRepository;
use Belamov\PostgresRange\Ranges\TimestampRange;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;

final class BreakTimeUseCaseTest extends TestCase
{
    public function test_it_returns_known_range(): void
    {
        $timeRange = TimeRange::fromStrings('2020-01-01T00:00:00', '2020-03-01T12:30:00');
        $intervals = TimeIntervals::fromArray(['2m', 'm', 'd', '2h']);
        $range = Range::fromTimeRange($timeRange);
        $range->addNewUnit($intervals, TimeUnits::fromArray(['2m' => 1, 'm' => 0, 'd' => 0, '2h' => 6.25]));
        $repository = $this->getMockerRepo($range);
        $dto = new BreakTimeDTO($timeRange, $intervals);
        $useCase = new BreakTimeUseCase(Mockery::mock(IntervalSplitter::class), $repository);
        $actual = $useCase->breakRange($dto);
        $this->assertTrue($range->is($actual));
    }

    public function test_it_stores_units(): void
    {
        $timeRange = TimeRange::fromStrings('2020-01-01T00:00:00', '2020-03-01T12:30:00');
        $intervals = TimeIntervals::fromArray(['2m', 'm', 'd', '2h']);
        $timeUnits = TimeUnits::fromArray(['2m' => 1, 'm' => 0, 'd' => 0, '2h' => 6.25]);
        $range = Range::fromTimeRange($timeRange);
        $saving = null;
        /** @var Mockery\MockInterface|RangeRepository $repository */
        $repository = $this->getMockerRepo($range);
        $repository->shouldReceive('saveWithUnits')
            ->andReturnUsing(function (Range $range) use (&$saving): void {
                $saving = $range;
            });
        $splitter = Mockery::mock(IntervalSplitter::class);
        $splitter->shouldReceive('split')
            ->andReturn($timeUnits);
        $dto = new BreakTimeDTO($timeRange, $intervals);
        $useCase = new BreakTimeUseCase($splitter, $repository);
        $useCase->breakRange($dto);
        $this->assertTrue($saving->is($range));
        $this->assertCount(1, $saving->units);
        /** @var Unit $unit */
        $unit = $saving->units->first();
        $this->assertSame($unit->intervals, (string)$intervals);
        $this->assertSame($unit->result, $timeUnits->toArray());
    }

    public function test_it_stores_range_with_units(): void
    {
        $timeRange = TimeRange::fromStrings('2020-01-01T00:00:00', '2020-03-01T12:30:00');
        $intervals = TimeIntervals::fromArray(['2m', 'm', 'd', '2h']);
        $timeUnits = TimeUnits::fromArray(['2m' => 1, 'm' => 0, 'd' => 0, '2h' => 6.25]);
        $saving = null;
        $repository = Mockery::mock(RangeRepository::class);
        $repository->shouldReceive('findOneByRangeWithUnit')
            ->andThrow(new ModelNotFoundException());
        $repository->shouldReceive('saveWithUnits')
            ->andReturnUsing(function (Range $range) use (&$saving): void {
                $saving = $range;
            });
        $splitter = Mockery::mock(IntervalSplitter::class);
        $splitter->shouldReceive('split')
            ->andReturn($timeUnits);
        $dto = new BreakTimeDTO($timeRange, $intervals);
        $useCase = new BreakTimeUseCase($splitter, $repository);
        $useCase->breakRange($dto);
        $this->assertNotNull($saving);
        $this->assertSame($timeRange->toTimestampRange()->forSql(), $saving->range->forSql());
        $this->assertCount(1, $saving->units);
        /** @var Unit $unit */
        $unit = $saving->units->first();
        $this->assertSame($unit->intervals, (string)$intervals);
        $this->assertSame($unit->result, $timeUnits->toArray());
    }

    private function getMockerRepo(Range $range): RangeRepository
    {
        $repository = Mockery::mock(RangeRepository::class);
        $repository->shouldReceive('findOneByRangeWithUnit')
            ->andReturnUsing(function (TimestampRange $timestampRange, string $intervals) use ($range): Range {
                /** @var Range $searchRange */
                $searchRange = [
                    $range->range->forSql() => $range,
                ][$timestampRange->forSql()] ?? throw new ModelNotFoundException();
                $searchRange->setRelation(
                    'units',
                    $searchRange->units->filter(
                        fn(Unit $unit) => $unit->intervals === $intervals
                    )
                );

                return $searchRange;
            });

        return $repository;
    }
}
