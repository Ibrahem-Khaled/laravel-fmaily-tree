<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'title'       => ['sometimes', 'string', 'max:255'],
            'content'     => ['sometimes', 'string'],
            'status'      => ['sometimes', 'in:published,draft'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'person_id'   => ['sometimes', 'nullable', 'exists:persons,id'],

            'images'      => ['sometimes', 'array', 'max:10'],
            'images.*'    => ['file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096', 'dimensions:min_width=200,min_height=200'],

            'attachments'   => ['sometimes', 'array', 'max:10'],
            'attachments.*' => [
                'file',
                'max:10240',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ],

            // روابط فيديو (يوتيوب)
            'videos'       => ['sometimes', 'array', 'max:20'],
            'videos.*'     => ['nullable', 'string', 'max:255'],
            'videos_text'  => ['sometimes', 'nullable', 'string', 'max:5000'],
        ];
    }
}
