<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domains\TimeBreaker\Models\Range;
use App\Domains\TimeBreaker\Models\Unit;
use Tests\TestCase;

final class FindRangeTest extends TestCase
{
    use AssertsRange;

    public function test_it_responds_with_range(): void
    {
        /** @var Range $range */
        $range = Range::factory()
            ->has(Unit::factory()->count(3))
            ->create();
        $response = $this->json('GET', 'api/v1/time-breaker', [
            'start_date' => $range->range->from()->toDateTimeString(),
            'end_date' => $range->range->to()->toDateTimeString(),
        ]);
        $response->assertStatus(200);
        $this->assertRangeStructure($response);
    }
}
