<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudyPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'target_degree' => [
                'sometimes',
                'required',
                'string',
                Rule::in(["Master's Degree", "Bachelor's Degree", "PhD / Doctorate", "Postgraduate Diploma"]),
            ],

            /*
            |--------------------------------------------------------------------------
            | University / Program Choices (at least one required)
            |--------------------------------------------------------------------------
            */
            'choices'               => ['sometimes', 'required', 'array', 'min:1'],
            'choices.*.program'     => ['required', 'string', 'max:255'],
            'choices.*.country'     => ['required', 'string', 'max:100'],
            'choices.*.start_date'  => ['required', 'string', 'max:50'],
            'choices.*.summary'     => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'choices.*.program'    => 'intended program',
            'choices.*.country'    => 'destination country',
            'choices.*.start_date' => 'study intake',
        ];
    }
}
