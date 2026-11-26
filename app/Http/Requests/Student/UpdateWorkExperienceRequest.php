<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * When the user picks "No work experience" the role cards are hidden but
     * still present in the DOM. Ignore them instead of validating stale rows.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('status') === 'no') {
            $this->request->remove('roles');
        }
    }

    public function rules(): array
    {
        return [
            'status'              => ['sometimes', 'required', 'string', 'in:yes,no'],
            'roles'               => ['sometimes', 'nullable', 'array'],
            'roles.*.title'       => ['required', 'string', 'max:255'],
            'roles.*.organization'=> ['required', 'string', 'max:255'],
            'roles.*.start_date'  => ['required', 'string', 'max:20'],
            'roles.*.end_date'    => ['nullable', 'string', 'max:20'],
            'roles.*.is_current'  => ['nullable', 'boolean'],
            'roles.*.description' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'roles.*.title'        => 'role / position title',
            'roles.*.organization' => 'organization / company',
            'roles.*.start_date'   => 'start date',
        ];
    }
}
