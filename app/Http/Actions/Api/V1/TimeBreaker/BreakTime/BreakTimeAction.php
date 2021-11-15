<?php

declare(strict_types=1);

namespace App\Http\Actions\Api\V1\TimeBreaker\BreakTime;

use App\Domains\TimeBreaker\UseCases\BreakTime\BreakTimeDTO;
use App\Domains\TimeBreaker\UseCases\BreakTime\TimeBreaker;
use App\Http\Actions\Action;
use App\Http\Resources\RangeResource;

final class BreakTimeAction extends Action
{
    public function __construct(
        private TimeBreaker $timeBreaker
    ) {
    }

    public function __invoke(BreakTimeRequest $request): RangeResource
    {
        $dto = BreakTimeDTO::fromRequest($request);
        $range = $this->timeBreaker->breakRange($dto);

        return new RangeResource($range);
    }
}
