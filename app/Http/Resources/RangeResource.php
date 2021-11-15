<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domains\TimeBreaker\Models\Range;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Range */
final class RangeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'start_date' => $this->range->from(),
            'end_date' => $this->range->to(),
            'units' => UnitResource::collection($this->units),
        ];
    }

}
