<?php

declare(strict_types=1);

namespace App\Http\Actions\Api\V1\TimeBreaker\Find;

use App\Domains\TimeBreaker\UseCases\FindRange\FindRangeDTO;
use App\Domains\TimeBreaker\UseCases\FindRange\RangeFinder;
use App\Http\Actions\Action;
use App\Http\Resources\RangeResource;

final class FindRangeAction extends Action
{
    public function __construct(
        private RangeFinder $rangeFinder,
    ) {
    }

    public function __invoke(FindRangeRequest $request): RangeResource
    {
        $dto = FindRangeDTO::fromRequest($request);
        $range = $this->rangeFinder->find($dto);

        return new RangeResource($range);
    }
}
