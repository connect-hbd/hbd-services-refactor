<?php

namespace App\Http\Requests\HR;

use Illuminate\Validation\Rule;

class UpdateEmployeeInformationRequest extends EmployeeRequest
{
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],

            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'blood_group' => ['nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'religion' => ['nullable', 'string', 'max:64'],
            'national_id' => ['nullable', 'string'],

            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:32'],
        ];

        // The linked account credentials (email, phone, password) may only be
        // changed by a manager. Employees editing their own profile simply
        // never submit these keys, so they are not validated.
        if ($this->canManageEmployees()) {
            $user = $this->employee()->user;

            $emailRule = Rule::unique('users', 'email');

            if ($user) {
                $emailRule->ignore($user->id);
            }

            $rules['email'] = ['required', 'email', 'max:255', $emailRule];
            $rules['phone'] = ['nullable', 'string', 'max:32'];
            $rules['password'] = ['nullable', 'string', 'min:8'];
        }

        return $rules;
    }
}
