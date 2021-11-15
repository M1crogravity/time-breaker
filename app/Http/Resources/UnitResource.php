<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domains\TimeBreaker\Models\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Unit */
final class UnitResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'intervals' => $this->intervals,
            'result' => $this->result,
        ];
    }
}
