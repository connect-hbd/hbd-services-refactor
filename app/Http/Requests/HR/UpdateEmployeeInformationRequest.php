<?php

namespace App\Http\Requests\HR;

use Illuminate\Validation\Rule;

class UpdateEmployeeInformationRequest extends EmployeeRequest
{
    public function rules(): array
    {
        $user = $this->employee()->user;

        $emailRule = Rule::unique('users', 'email');

        if ($user) {
            $emailRule->ignore($user->id);
        }

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'phone' => ['nullable', 'string', 'max:32'],
            'password' => ['nullable', 'string', 'min:8'],

            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'blood_group' => ['nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'religion' => ['nullable', 'string', 'max:64'],
            'national_id' => ['nullable', 'string'],

            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:32'],
        ];
    }
}
