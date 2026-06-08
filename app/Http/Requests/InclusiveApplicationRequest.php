<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InclusiveApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'disability_type' => 'required|string|max:100',
            'identity_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'support_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'disability_type' => 'jenis disabilitas',
            'identity_document' => 'KTP',
            'support_document' => 'surat keterangan disabilitas',
            'description' => 'deskripsi',
        ];
    }
}
