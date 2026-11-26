<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImmigrationHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * "No visa refusal" disables the refusal inputs. They are still submitted
     * as empty values, so they must be ignored rather than persisted.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('status') === 'no') {
            $this->request->remove('refusals');
        }
    }

    public function rules(): array
    {
        return [
            'status'              => ['sometimes', 'required', 'string', 'in:yes,no'],
            'refusals'            => ['sometimes', 'nullable', 'array'],
            'refusals.*.id'       => ['nullable', 'integer'],
            'refusals.*.sort_order'=> ['nullable', 'integer', 'min:0'],
            'refusals.*.country'  => ['required', 'string', 'max:100'],
            'refusals.*.refusal_date' => ['nullable', 'date'],
            'refusals.*.grounds'  => ['nullable', 'string', 'max:3000'],

            // Optional uploaded refusal letter for each record
            'refusals.*.document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'refusals.*.country'      => 'country',
            'refusals.*.refusal_date' => 'refusal date',
            'refusals.*.grounds'      => 'grounds for refusal',
        ];
    }
}
