<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\UseCases\FindRange;

use App\Domains\TimeBreaker\VO\TimeRange;
use App\Http\Actions\Api\V1\TimeBreaker\Find\FindRangeRequest;

final class FindRangeDTO
{
    public function __construct(
        private TimeRange $range,
    ) {
    }

    public static function fromRequest(FindRangeRequest $request): self
    {
        return new self(
            TimeRange::fromStrings($request->start_date, $request->end_date),
        );
    }

    public function getRange(): TimeRange
    {
        return $this->range;
    }
}
