<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'person_id'   => ['nullable', 'exists:persons,id'],
            'status'      => ['required', 'in:published,draft'],
            'images.*'    => ['nullable', 'image', 'max:4096'],
        ];
    }
}
