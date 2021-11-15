<?php

declare(strict_types=1);

namespace App\Http\Actions\Api\V1\TimeBreaker\Find;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $start_date
 * @property-read string $end_date
 */
final class FindRangeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date', 'before:end_date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }
}
