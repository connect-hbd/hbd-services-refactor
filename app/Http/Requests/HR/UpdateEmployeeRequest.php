<?php

namespace App\Http\Requests\HR;

use App\Models\hrm\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
      return $this->user()?->can('employee.create') ?? false;

    }

    public function rules(): array
    {
        $routeEmployee = $this->route('employee');
        $employee = $routeEmployee instanceof Employee
            ? $routeEmployee
            : Employee::query()->with('currentEmployment')->findOrFail($routeEmployee);

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->user_id)],
            'phone' => ['nullable', 'string', 'max:32'],
            'password' => ['nullable', 'string', 'min:8'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:32'],

            'employee_code' => ['required', 'string', 'max:50', Rule::unique('employments', 'employee_code')->ignore($employee->currentEmployment?->id)],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'department_id' => [
                'nullable', 'integer',
                Rule::exists('departments', 'id')->where(fn ($query) => $this->filled('branch_id') ? $query->where('branch_id', $this->input('branch_id')) : $query),
            ],
            'team_id' => [
                'nullable', 'integer',
                Rule::exists('teams', 'id')->where(fn ($query) => $this->filled('department_id') ? $query->where('department_id', $this->input('department_id')) : $query),
            ],
            'designation_id' => ['required', 'integer', 'exists:designations,id'],
            'work_type' => ['required', Rule::in(['onsite', 'remote', 'hybrid'])],
            'joining_date' => ['required', 'date'],
            'weekend_days' => ['nullable', 'array'],
            'weekend_days.*' => ['required', 'string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'employment_status' => ['required', Rule::in(['probation', 'permanent', 'resigned', 'terminated'])],
            'probation_period_months' => ['exclude_unless:employment_status,probation', 'required', 'integer', 'between:3,6'],
            'probation_end_date' => ['exclude_unless:employment_status,probation', 'required', 'date', 'after:joining_date'],
            'resignation_date' => ['exclude_unless:employment_status,resigned', 'required', 'date', 'after_or_equal:joining_date'],
            'last_working_day' => ['exclude_unless:employment_status,resigned,terminated', 'required', 'date', 'after_or_equal:joining_date'],
            //current salary
            'salary' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
