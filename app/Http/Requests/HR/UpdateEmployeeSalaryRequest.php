<?php

namespace App\Http\Requests\HR;

use Illuminate\Validation\Rule;

class UpdateEmployeeSalaryRequest extends EmployeeRequest
{
    public function rules(): array
    {
        return [
            'salary_amount' => ['required', 'numeric', 'min:0'],
            'currency_code' => ['required', 'string', 'size:3'],
            'salary_type' => ['required', Rule::in(['monthly', 'hourly', 'weekly', 'annual'])],

            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
