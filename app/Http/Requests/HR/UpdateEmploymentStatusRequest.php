<?php

namespace App\Http\Requests\HR;

use Illuminate\Validation\Rule;
use Throwable;

class UpdateEmploymentStatusRequest extends EmployeeRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAny([
            'employee.status.change',
            'employee.edit',
            'employee.update',
            'employee.manage',
        ]) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('employment_status') !== 'probation') {
            return;
        }

        if (! $this->filled('probation_period_months') || $this->filled('probation_end_date')) {
            return;
        }

        $joiningDate = $this->employee()->currentEmployment?->joining_date;

        if (! $joiningDate) {
            return;
        }

        try {
            $this->merge([
                'probation_end_date' => $joiningDate->copy()
                    ->addMonthsNoOverflow((int) $this->input('probation_period_months'))
                    ->toDateString(),
            ]);
        } catch (Throwable) {
            // Let the date validation rules report the problem.
        }
    }

    public function rules(): array
    {
        $joiningDate = $this->employee()->currentEmployment?->joining_date?->toDateString();
        $afterJoining = $joiningDate ? ['after_or_equal:'.$joiningDate] : [];

        return [
            'employment_status' => ['required', Rule::in(['probation', 'permanent', 'resigned', 'terminated'])],

            'probation_period_months' => [
                'exclude_unless:employment_status,probation',
                'required', 'integer', 'between:3,6',
            ],
            'probation_end_date' => array_merge([
                'exclude_unless:employment_status,probation',
                'required', 'date',
            ], $afterJoining),

            'resignation_date' => array_merge([
                'exclude_unless:employment_status,resigned',
                'required', 'date',
            ], $afterJoining),

            'last_working_day' => array_merge([
                'exclude_unless:employment_status,resigned,terminated',
                'required', 'date',
            ], $afterJoining),
        ];
    }
}
