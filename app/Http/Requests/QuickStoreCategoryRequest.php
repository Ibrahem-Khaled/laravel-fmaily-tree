<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickStoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'max:4096'],
        ];
    }
}
