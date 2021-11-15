<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;

trait AssertsRange
{
    private function assertRangeStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'data' => [
                'start_date',
                'end_date',
                'units' => [
                    '*' => [
                        'intervals',
                        'result',
                    ]
                ]
            ]
        ]);
    }
}
