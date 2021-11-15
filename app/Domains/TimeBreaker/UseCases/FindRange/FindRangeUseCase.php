<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\UseCases\FindRange;

use App\Domains\TimeBreaker\Models\Range;
use App\Storage\Repositories\RangeRepository;

final class FindRangeUseCase implements RangeFinder
{
    public function __construct(
        private RangeRepository $repository,
    ) {
    }

    public function find(FindRangeDTO $dto): Range
    {
        return $this->repository->findOneByRangeWithUnits($dto->getRange()->toTimestampRange());
    }
}
