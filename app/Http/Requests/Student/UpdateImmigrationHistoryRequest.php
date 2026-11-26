<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImmigrationHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'refusals'              => ['required', 'array', 'min:1'],
            'refusals.*.country'    => ['required', 'string', 'max:100'],
            'refusals.*.date'       => ['required', 'date'],
            'refusals.*.grounds'    => ['required', 'string', 'max:3000'],

            // Optional uploaded refusal letter for each record
            'refusals.*.document'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'refusals.*.country'   => 'country',
            'refusals.*.date'      => 'refusal date',
            'refusals.*.grounds'   => 'grounds for refusal',
        ];
    }
}
