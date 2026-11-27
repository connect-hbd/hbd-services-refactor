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

    /**
     * The study plan is optional. Empty rows added by the "Add Preference"
     * button, or a blank target degree, mean "not edited" and are dropped.
     */
    protected function prepareForValidation(): void
    {
        if (blank($this->input('target_degree'))) {
            $this->request->remove('target_degree');
        }

        $choices = $this->input('choices');

        if (! is_array($choices)) {
            return;
        }

        $choices = array_values(array_filter($choices, function ($choice) {
            if (! is_array($choice)) {
                return false;
            }

            return filled($choice['program'] ?? null)
                || filled($choice['country'] ?? null)
                || filled($choice['start_date'] ?? null)
                || filled($choice['summary'] ?? null);
        }));

        if ($choices === []) {
            $this->request->remove('choices');
        } else {
            $this->merge(['choices' => $choices]);
        }
    }

    public function rules(): array
    {
        return [
            'target_degree' => [
                'sometimes',
                'nullable',
                'string',
                Rule::in(["Master's Degree", "Bachelor's Degree", "PhD / Doctorate", "Postgraduate Diploma"]),
            ],

            /*
            |--------------------------------------------------------------------------
            | University / Program Choices (optional)
            |--------------------------------------------------------------------------
            */
            'choices'               => ['sometimes', 'nullable', 'array'],
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
