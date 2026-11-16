<?php

namespace App\Http\Requests\HR;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('employee.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        if (
            $this->input('employment_status') === 'probation'
            && $this->filled('joining_date')
            && $this->filled('probation_period_months')
        ) {
            try {
                $this->merge([
                    'probation_end_date' => Carbon::parse($this->input('joining_date'))
                        ->addMonthsNoOverflow((int) $this->input('probation_period_months'))
                        ->toDateString(),
                ]);
            } catch (\Throwable) {
                // Let the date validation rule report invalid joining dates.
            }
        }
    }

    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Employee Account
            |--------------------------------------------------------------------------
            */

            'first_name' => ['required', 'string', 'max:100'],

            'last_name' => ['required', 'string', 'max:100'],

            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],

            'phone' => ['nullable', 'string', 'max:32'],

            'password' => ['required', 'string', 'min:8'],

            /*
            |--------------------------------------------------------------------------
            | Employee Code & Organization
            |--------------------------------------------------------------------------
            */

            'employee_code' => ['required', 'string', 'max:50', Rule::unique('employments', 'employee_code')],

            'branch_id' => ['nullable', 'integer', 'exists:branches,id', 'required_with:department_id,team_id'],

            'department_id' => [
                'nullable',
                'integer',
                'required_with:team_id',
                Rule::exists('departments', 'id')->where(function ($query) {
                    if ($this->filled('branch_id')) {
                        $query->where('branch_id', $this->input('branch_id'));
                    }
                }),
            ],

            'team_id' => [
                'nullable',
                'integer',
                Rule::exists('teams', 'id')->where(function ($query) {
                    if ($this->filled('department_id')) {
                        $query->where('department_id', $this->input('department_id'));
                    }
                }),
            ],

            'designation_id' => ['nullable', 'integer', 'exists:designations,id'],

            /*
            |--------------------------------------------------------------------------
            | Employment Details
            |--------------------------------------------------------------------------
            */

            'work_type' => ['required', Rule::in(['onsite', 'remote', 'hybrid'])],

            'joining_date' => ['required', 'date'],

            'weekend_days' => ['nullable', 'array'],

            'weekend_days.*' => ['required', 'string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],

            /*
            |--------------------------------------------------------------------------
            | Employment Status
            |--------------------------------------------------------------------------
            */

            'employment_status' => ['required', Rule::in(['probation', 'permanent', 'resigned', 'terminated'])],

            /*
            |--------------------------------------------------------------------------
            | Probation
            |--------------------------------------------------------------------------
            */

            'probation_period_months' => ['exclude_unless:employment_status,probation', 'required', 'integer', 'between:3,6'],

            'probation_end_date' => ['exclude_unless:employment_status,probation', 'required', 'date', 'after:joining_date'],

            /*
            |--------------------------------------------------------------------------
            | Resignation
            |--------------------------------------------------------------------------
            */

            'resignation_date' => ['exclude_unless:employment_status,resigned', 'required', 'date', 'after_or_equal:joining_date'],

            /*
            |--------------------------------------------------------------------------
            | Last Working Day
            |--------------------------------------------------------------------------
            */

            'last_working_day' => ['exclude_unless:employment_status,resigned,terminated', 'required', 'date', 'after_or_equal:joining_date'],

            /*
            |--------------------------------------------------------------------------
            | Emergency Contact
            |--------------------------------------------------------------------------
            */

            'emergency_contact_name' => ['nullable', 'string', 'max:150'],

            'emergency_contact_phone' => ['nullable', 'string', 'max:32'],

         
             /*
            |--------------------------------------------------------------------------
            | Salary
            |--------------------------------------------------------------------------
            */

            'salary_amount' => ['required', 'numeric', 'min:0'],

        ];
    }
}
