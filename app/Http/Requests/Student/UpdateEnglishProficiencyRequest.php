<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnglishProficiencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * The English section is toggled client side. Hidden inputs must not be
     * validated or persisted when the student did not use them.
     */
    protected function prepareForValidation(): void
    {
        $status = $this->input('status');

        if (! in_array($status, ['will', 'yes'], true)) {
            foreach (['status', 'planned_exam_name', 'exam_date', 'exams'] as $field) {
                $this->request->remove($field);
            }

            return;
        }

        if ($status === 'will') {
            $this->request->remove('exams');
        } else {
            $this->request->remove('planned_exam_name');
            $this->request->remove('exam_date');
        }
    }

    public function rules(): array
    {
        return [
            'status'            => ['sometimes', 'required', 'string', 'in:will,yes'],
            'planned_exam_name' => ['sometimes', 'required_if:status,will', 'string', 'max:100'],
            'exam_date'         => ['sometimes', 'nullable', 'date'],

            'exams'              => ['sometimes', 'nullable', 'array'],
            'exams.*.id'         => ['nullable', 'integer'],
            'exams.*.exam_name'  => ['required', 'string', 'max:100'],
            'exams.*.year'       => ['nullable', 'digits:4', 'integer', 'min:1950', 'max:2100'],
            'exams.*.scores'     => ['nullable', 'array'],
            'exams.*.scores.*'   => ['nullable', 'numeric'],
        ];
    }

    public function attributes(): array
    {
        return [
            'exams.*.exam_name' => 'exam name',
            'exams.*.year'      => 'exam year',
        ];
    }
}
