<?php

namespace App\Http\Requests\HR;

use Illuminate\Validation\Rule;

class UpdateEmployeeEmploymentRequest extends EmployeeRequest
{
    public function rules(): array
    {
        $employment = $this->employee()->currentEmployment;

        $employeeCodeRule = Rule::unique('employments', 'employee_code');

        if ($employment) {
            $employeeCodeRule->ignore($employment->id);
        }

        return [
            'employee_code' => ['required', 'string', 'max:50', $employeeCodeRule],

            'branch_id' => ['nullable', 'integer', 'exists:branches,id', 'required_with:department_id,team_id'],

            'department_id' => [
                'nullable',
                'integer',
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

            'work_type' => ['required', Rule::in(['onsite', 'remote', 'hybrid'])],
            'joining_date' => ['required', 'date'],

            'weekend_days' => ['nullable', 'array'],
            'weekend_days.*' => [
                'required', 'string',
                Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
            ],
        ];
    }
}
