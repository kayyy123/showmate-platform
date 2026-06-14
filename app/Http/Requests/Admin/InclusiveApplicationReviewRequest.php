<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InclusiveApplicationReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_note' => 'nullable|string|max:2000',
            'rejection_reason' => 'required_if:action,reject|string|max:2000',
        ];
    }
}
