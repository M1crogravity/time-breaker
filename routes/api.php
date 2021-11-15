<?php

Route::namespace('V1')
    ->prefix('v1')
    ->group(function () {
        Route::namespace('TimeBreaker')
            ->name('time-breaker.')
            ->prefix('time-breaker')
            ->group(function () {
                Route::post('/', 'BreakTime\BreakTimeAction')->name('break');
                Route::get('/', 'Find\FindRangeAction')->name('find');
            });
    });
