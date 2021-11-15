<?php

declare(strict_types=1);

namespace Tests\Unit\Storage\Repositories\Postgres;

use App\Domains\TimeBreaker\Models\Range;
use App\Domains\TimeBreaker\Models\Unit;
use App\Domains\TimeBreaker\VO\TimeInterval\TimeIntervals;
use App\Domains\TimeBreaker\VO\TimeUnit\TimeUnits;
use App\Storage\Repositories\Postgres\RangePostgresRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

final class RangePostgresRepositoryTest extends TestCase
{
    private Collection $ranges;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ranges = Range::factory()
            ->has(Unit::factory()->count(3))
            ->count(5)
            ->create();
    }

    public function test_it_finds_range_with_unit(): void
    {
        $repository = $this->getRepository();
        /** @var Range $searchingRange */
        $searchingRange = $this->ranges->random();
        /** @var Unit $searchingUnit */
        $searchingUnit = $searchingRange->units->random();
        $range = $repository->findOneByRangeWithUnit($searchingRange->range, $searchingUnit->intervals);
        $this->assertTrue($range->is($searchingRange));
        $this->assertCount(1, $range->units);
        $this->assertTrue($range->units->first()->is($searchingUnit));
    }

    public function test_it_throws_exception_when_no_range_found(): void
    {
        $repository = $this->getRepository();
        $this->expectException(ModelNotFoundException::class);
        $repository->findOneByRangeWithUnit('[2020-01-01 00:00:00,2020-02-02 23:00:00]', 'wrong interval');
    }

    public function test_it_finds_range_without_unit(): void
    {
        $repository = $this->getRepository();
        /** @var Range $searchingRange */
        $searchingRange = $this->ranges->random();
        $range = $repository->findOneByRangeWithUnit($searchingRange->range, 'wrong interval');
        $this->assertTrue($range->is($searchingRange));
        $this->assertCount(0, $range->units);
    }

    public function test_it_saves_new_range_with_units(): void
    {
        $repository = $this->getRepository();
        /** @var Range $range */
        $range = Range::factory()->make();
        $range->addNewUnit(
            TimeIntervals::fromArray(['2m', 'm', 'd', '2h']),
            TimeUnits::fromArray(['2m' => 1, 'm' => 0, 'd' => 0, '2h' => 6.25])
        );
        /** @var Unit $unit */
        $unit = $range->units->first();
        $repository->saveWithUnits($range);
        $saved = $repository->findOneByRangeWithUnit($range->range, $unit->intervals);
        $this->assertTrue($saved->is($range));
        $this->assertTrue($saved->units->first()->is($unit));
    }

    public function test_it_saves_new_unit(): void
    {
        $repository = $this->getRepository();
        /** @var Range $range */
        $range = $this->ranges->random();
        $range->addNewUnit(
            TimeIntervals::fromArray(['2m', 'm', 'd', '2h']),
            TimeUnits::fromArray(['2m' => 1, 'm' => 0, 'd' => 0, '2h' => 6.25])
        );
        /** @var Unit $unit */
        $unit = $range->units->first();
        $repository->saveWithUnits($range);
        $saved = $repository->findOneByRangeWithUnit($range->range, $unit->intervals);
        $this->assertTrue($saved->is($range));
        $this->assertTrue($saved->units->first()->is($unit));
    }

    private function getRepository(): RangePostgresRepository
    {
        return new RangePostgresRepository();
    }
}
