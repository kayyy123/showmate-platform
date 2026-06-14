<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InclusiveProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_types' => 'required|array|min:1',
            'program_types.*' => [
                'required',
                'string',
                Rule::in(['owned_by_disabled', 'employs_disabled', 'sells_inclusive_products', 'accessibility_services', 'community_empowerment', 'other']),
            ],
            'other_program_type' => 'required_if:program_types.*,other|string|max:255',
            'description' => 'required|string|max:2000',
            'supporting_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'supporting_link' => 'nullable|url|max:500',
            'agreed' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'program_types.required' => 'Pilih minimal satu jenis program inklusif.',
            'program_types.min' => 'Pilih minimal satu jenis program inklusif.',
            'other_program_type.required_if' => 'Jelaskan jenis program lainnya.',
            'description.required' => 'Deskripsi program wajib diisi.',
            'supporting_file.max' => 'File maksimal 5MB.',
            'supporting_link.url' => 'Link bukti pendukung harus berupa URL yang valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'program_types' => 'jenis program',
            'other_program_type' => 'program lainnya',
            'description' => 'deskripsi program',
            'supporting_file' => 'bukti pendukung',
            'supporting_link' => 'link pendukung',
        ];
    }
}
