<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker;

use App\Domains\TimeBreaker\IntervalSplitter\CarbonIntervalSplitter;
use App\Domains\TimeBreaker\IntervalSplitter\IntervalSplitter;
use App\Domains\TimeBreaker\UseCases\BreakTime\BreakTimeUseCase;
use App\Domains\TimeBreaker\UseCases\BreakTime\TimeBreaker;
use App\Domains\TimeBreaker\UseCases\FindRange\FindRangeUseCase;
use App\Domains\TimeBreaker\UseCases\FindRange\RangeFinder;
use App\Storage\Repositories\Postgres\RangePostgresRepository;
use App\Storage\Repositories\RangeRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class TimeBreakerProvider extends ServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        TimeBreaker::class => BreakTimeUseCase::class,
        RangeFinder::class => FindRangeUseCase::class,
    ];

    public function register(): void
    {
        $this->app->when(BreakTimeUseCase::class)
            ->needs(IntervalSplitter::class)
            ->give(CarbonIntervalSplitter::class);
        $this->app->when([BreakTimeUseCase::class, FindRangeUseCase::class])
            ->needs(RangeRepository::class)
            ->give(RangePostgresRepository::class);
    }

    public function provides(): array
    {
        return [
            TimeBreaker::class,
            RangeFinder::class,
        ];
    }
}
