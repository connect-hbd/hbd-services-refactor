<?php

namespace App\Http\Requests\HR;

use Illuminate\Validation\Rule;

class UpdateEmployeeBankRequest extends EmployeeRequest
{
    public function rules(): array
    {
        return [
            'bank_account_id' => [
                'nullable',
                'integer',
                Rule::exists('bank_accounts', 'id')->where('employee_id', $this->employee()->id),
            ],

            'bank_name' => ['required', 'string', 'max:120'],
            'bank_branch' => ['nullable', 'string', 'max:120'],
            'account_name' => ['required', 'string', 'max:120'],
            'account_number' => ['required', 'string'],
            'routing_number' => ['nullable', 'string', 'max:50'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
