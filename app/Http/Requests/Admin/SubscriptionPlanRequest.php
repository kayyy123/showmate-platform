<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        $id = $this->route('subscription_plan');

        return [
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:subscription_plans,slug,{$id}",
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:0',
            'max_stores' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
        ];
    }
}
