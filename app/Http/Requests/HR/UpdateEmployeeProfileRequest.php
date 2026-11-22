<?php

namespace App\Http\Requests\HR;

/**
 * Validates the "Employee Information" profile section: biography,
 * experience and free-form list fields stored on the employee record.
 *
 * Both managers and employees (for their own record) may edit these fields,
 * so the base EmployeeRequest authorization is used as-is.
 */
class UpdateEmployeeProfileRequest extends EmployeeRequest
{
    public function rules(): array
    {
        return [
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio' => ['nullable', 'string', 'max:2000'],

            'education' => ['nullable', 'string', 'max:250'],
            'institution' => ['nullable', 'string', 'max:100'],

            'work_destinations' => ['nullable', 'array'],
            'work_destinations.*' => ['nullable', 'string', 'max:100'],

            'languages' => ['nullable', 'array'],
            'languages.*' => ['nullable', 'string', 'max:50'],

            'expertise' => ['nullable', 'array'],
            'expertise.*' => ['nullable', 'string', 'max:100'],
        ];
    }
}
