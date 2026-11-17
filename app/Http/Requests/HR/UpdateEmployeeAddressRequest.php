<?php

namespace App\Http\Requests\HR;

use App\Models\City;
use Illuminate\Validation\Rule;

class UpdateEmployeeAddressRequest extends EmployeeRequest
{
    protected function prepareForValidation(): void
    {
        // Keep the country field consistent when only a city is submitted.
        if ($this->filled('city_id') && ! $this->filled('country_id')) {
            $city = City::query()->find($this->input('city_id'));

            if ($city) {
                $this->merge(['country_id' => $city->country_id]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'country_id' => ['nullable', 'exists:countries,id'],
            'city_id' => [
                'nullable',
                Rule::exists('cities', 'id')->where(function ($query) {
                    if ($this->filled('country_id')) {
                        $query->where('country_id', $this->input('country_id'));
                    }
                }),
            ],
            'address' => ['nullable', 'string'],
        ];
    }
}
