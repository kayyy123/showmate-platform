<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        $id = $this->route('store');

        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:stores,slug,{$id}",
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'shopee' => 'nullable|string|max:255',
            'tokopedia' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
