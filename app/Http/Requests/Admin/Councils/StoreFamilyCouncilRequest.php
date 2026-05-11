<?php

namespace App\Http\Requests\Admin\Councils;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyCouncilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('councils.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'              => $this->filled('name') ? trim((string) $this->input('name')) : null,
            'description'       => $this->filled('description') ? (string) $this->input('description') : null,
            'address'           => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'google_map_url'    => $this->filled('google_map_url') ? trim((string) $this->input('google_map_url')) : null,
            'working_days_from' => $this->filled('working_days_from') ? trim((string) $this->input('working_days_from')) : null,
            'working_days_to'   => $this->filled('working_days_to') ? trim((string) $this->input('working_days_to')) : null,
            'is_active'         => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'address'           => ['nullable', 'string', 'max:500'],
            'google_map_url'    => ['nullable', 'url', 'max:1000'],
            'working_days_from' => ['nullable', 'string', 'max:50'],
            'working_days_to'   => ['nullable', 'string', 'max:50'],
            'is_active'         => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'اسم المكان مطلوب.',
            'name.max'           => 'اسم المكان طويل جداً (الحد الأقصى 255 حرف).',
            'google_map_url.url' => 'رابط جوجل ماب غير صالح.',
        ];
    }
}
