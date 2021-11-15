<?php

namespace App\Domains\TimeBreaker\UseCases\BreakTime;

use App\Domains\TimeBreaker\Models\Range;

interface TimeBreaker
{
    public function breakRange(BreakTimeDTO $dto): Range;
}
