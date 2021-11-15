<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class BreakTimeTest extends TestCase
{
    use AssertsRange;

    public function test_it_responds_with_range(): void
    {
        $response = $this->postJson('api/v1/time-breaker', [
            'start_date' => '2020-01-01T00:00:00',
            'end_date' => '2020-03-01T12:30:00',
            'intervals' => ['2m', 'm', 'd', '2h'],
        ]);
        $response->assertStatus(201);
        $this->assertRangeStructure($response);
    }
}
