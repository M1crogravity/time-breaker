<?php

declare(strict_types=1);

namespace App\Http\Actions\Api\V1\TimeBreaker\BreakTime;

use App\Domains\TimeBreaker\VO\TimeInterval\TimeInterval;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $start_date
 * @property-read string $end_date
 * @property-read array $intervals
 */
final class BreakTimeRequest extends FormRequest
{
    public function rules(): array
    {
        $availableIntervals = array_keys(TimeInterval::AVAILABLE_INTERVALS);
        $regexIntervals = implode('|', $availableIntervals);

        return [
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'intervals' => ['required', 'array'],
            'intervals.*' => ['required_with:intervals', 'string', 'distinct', "regex:/^(1\d+|[2-9]\d*)?({$regexIntervals}){1}$/"]
        ];
    }
}
