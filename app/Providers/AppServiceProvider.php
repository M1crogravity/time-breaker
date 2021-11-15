<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Date;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Date::use(CarbonImmutable::class);
    }

    public function boot(): void
    {
        //
    }
}
