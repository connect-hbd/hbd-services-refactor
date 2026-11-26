<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentReferralRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'known_by' => [
                'required',
                'string',
                'max:100',
            ],

            'known_by_details' => [
                'nullable',
                'string',
            ],

            'employee' => [
                'nullable',
                'string',
                'max:255',
            ],

            'expo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'permission' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'known_by.required' => 'Please select how you heard about us.',
            'known_by.max' => 'The referral source may not exceed 100 characters.',
            'permission.boolean' => 'Invalid permission value.',
        ];
    }
}
