<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Conditionally rendered inputs are still submitted by the browser when
     * hidden with x-show. Drop them here so that they never overwrite stored
     * data when the user did not actually interact with them.
     */
    protected function prepareForValidation(): void
    {
        if ($this->boolean('same_address')) {
            foreach (['permanent_address', 'permanent_city_id', 'permanent_country_id', 'permanent_post_code'] as $field) {
                $this->request->remove($field);
            }
        }
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;
        $studentId = $this->user()?->student?->id;

        return [
            /*
            |--------------------------------------------------------------------------
            | Account (User)
            |--------------------------------------------------------------------------
            */
            'name'  => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($userId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Student Information
            |--------------------------------------------------------------------------
            */
            'date_of_birth' => ['sometimes', 'required', 'date', 'before:today'],

            'gender' => ['sometimes', 'required', 'string', Rule::in(['Male', 'Female', 'Other'])],

            'marital_status' => ['sometimes', 'required', 'string', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],

            /*
            |--------------------------------------------------------------------------
            | Passport
            |--------------------------------------------------------------------------
            */
            'passport_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                Rule::unique('students', 'passport_number')->ignore($studentId),
            ],

            'passport_issue_date'  => ['sometimes', 'nullable', 'date'],
            'passport_expiry_date' => ['sometimes', 'nullable', 'date', 'after:passport_issue_date'],

            /*
            |--------------------------------------------------------------------------
            | Alternative contacts
            |--------------------------------------------------------------------------
            */
            'alt_phone'            => ['sometimes', 'nullable', 'array', 'max:2'],
            'alt_phone.*.value'    => ['sometimes', 'nullable', 'string', 'max:20'],
            'alt_phone.*.is_whatsapp' => ['sometimes', 'boolean'],

            'alt_email'   => ['sometimes', 'nullable', 'array', 'max:2'],
            'alt_email.*' => ['sometimes', 'nullable', 'email', 'max:191'],

            /*
            |--------------------------------------------------------------------------
            | Study destination
            |--------------------------------------------------------------------------
            */
            'destination_id'   => ['sometimes', 'nullable', 'array'],
            'destination_id.*' => ['string', 'max:100'],

            /*
            |--------------------------------------------------------------------------
            | Current address
            |--------------------------------------------------------------------------
            */
            'current_address'     => ['sometimes', 'required', 'string', 'max:1000'],
            'current_city_id'    => ['sometimes', 'nullable', 'uuid', 'exists:cities,id'],
            'current_country_id' => ['sometimes', 'nullable', 'uuid', 'exists:countries,id'],
            'current_post_code'   => ['sometimes', 'nullable', 'string', 'max:20'],

            /*
            |--------------------------------------------------------------------------
            | Same address / permanent address
            |--------------------------------------------------------------------------
            */
            'same_address' => ['sometimes', 'boolean'],

            'permanent_address'    => ['sometimes', 'nullable', 'string', 'max:1000'],
            'permanent_city_id'    => ['sometimes', 'nullable', 'uuid', 'exists:cities,id'],
            'permanent_country_id' => ['sometimes', 'nullable', 'uuid', 'exists:countries,id'],
            'permanent_post_code'  => ['sometimes', 'nullable', 'string', 'max:20'],
        ];
    }
}
