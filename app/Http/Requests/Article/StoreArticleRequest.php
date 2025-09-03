<?php

namespace App\Http\Requests\Article;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'status'      => ['required', 'in:published,draft'],
            'category_id' => ['required', 'exists:categories,id'],
            'person_id'   => ['nullable', 'exists:persons,id'],

            // صور متعددة
            'images'      => ['sometimes', 'array', 'max:10'],
            'images.*'    => ['file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096', 'dimensions:min_width=200,min_height=200'],

            // مرفقات متعددة
            'attachments'   => ['sometimes', 'array', 'max:10'],
            'attachments.*' => [
                'file',
                'max:10240',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.image' => 'الملفات المختارة للصور يجب أن تكون صورًا.',
            'images.*.mimes' => 'امتدادات الصور المسموح بها: jpeg,jpg,png,webp.',
            'images.*.max'   => 'أقصى حجم للصورة 4MB.',
            'attachments.*.max' => 'أقصى حجم للمرفق 10MB.',
        ];
    }
}
