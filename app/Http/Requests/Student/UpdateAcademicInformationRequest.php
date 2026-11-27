<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * The English proficiency section is toggled client side. Hidden inputs
     * must not be validated or persisted when the user did not use them.
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
            /*
            |--------------------------------------------------------------------------
            | Highest Qualification
            |--------------------------------------------------------------------------
            */
            'highest_degree'      => ['sometimes', 'required', 'string', 'max:255'],
            'highest_institution' => ['sometimes', 'required', 'string', 'max:255'],
            'highest_grad_date'   => ['sometimes', 'required', 'date'],
            'highest_gpa'         => ['sometimes', 'required', 'string', 'max:20'],
            'highest_max_gpa'     => ['sometimes', 'nullable', 'string', 'max:20'],

            /*
            |--------------------------------------------------------------------------
            | Education History (dynamic rows)
            |--------------------------------------------------------------------------
            */
            'education_history'               => ['sometimes', 'nullable', 'array'],
            'education_history.*.degree'      => ['nullable', 'string', 'max:255'],
            'education_history.*.institution' => ['nullable', 'string', 'max:255'],
            'education_history.*.country'     => ['nullable', 'string', 'max:100'],
            'education_history.*.year'        => ['nullable', 'digits:4', 'integer', 'min:1950', 'max:2100'],
            'education_history.*.grade'       => ['nullable', 'string', 'max:50'],

            /*
            |--------------------------------------------------------------------------
            | English Proficiency (will / yes)
            |--------------------------------------------------------------------------
            */
            'status'            => ['sometimes', 'required', 'string', 'in:will,yes'],
            'planned_exam_name' => ['sometimes', 'required_if:status,will', 'string', 'max:100'],
            'exam_date'         => ['sometimes', 'nullable', 'date'],

            'exams'                    => ['sometimes', 'nullable', 'array'],
            'exams.*.id'               => ['nullable', 'integer'],
            'exams.*.exam_name'        => ['required', 'string', 'max:100'],
            'exams.*.year'             => ['nullable', 'digits:4', 'integer', 'min:1950', 'max:2100'],
            'exams.*.scores'           => ['nullable', 'array'],
            'exams.*.scores.*'         => ['nullable', 'numeric'],
        ];
    }

    public function attributes(): array
    {
        return [
            'highest_degree'      => 'degree name',
            'highest_institution' => 'institution',
            'highest_grad_date'   => 'graduation date',
            'highest_gpa'         => 'GPA score',
            'exams.*.exam_name'   => 'exam name',
            'exams.*.year'        => 'exam year',
        ];
    }
}
