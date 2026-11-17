<?php

namespace App\Http\Requests\HR;

class UpdateEmployeeDocumentsRequest extends EmployeeRequest
{
    public function rules(): array
    {
        $fileRules = [
            'nullable',
            'file',
            'mimes:png,jpg,jpeg,svg,webp,pdf,doc,docx',
            'max:10240', // 10 MB
        ];

        return [
            'documents' => ['sometimes', 'array'],
            'documents.cv' => $fileRules,
            'documents.nid' => $fileRules,
        ];
    }
}
