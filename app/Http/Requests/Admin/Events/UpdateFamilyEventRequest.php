<?php

namespace App\Http\Requests\Admin\Events;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('councils.update') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title'          => $this->filled('title') ? trim((string) $this->input('title')) : null,
            'description'    => $this->filled('description') ? (string) $this->input('description') : null,
            'city'           => $this->filled('city') ? trim((string) $this->input('city')) : null,
            'location'       => $this->filled('location') ? trim((string) $this->input('location')) : null,
            'location_name'  => $this->filled('location_name') ? trim((string) $this->input('location_name')) : null,
            'show_countdown' => $this->boolean('show_countdown'),
            'is_active'      => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'city'           => ['nullable', 'string', 'max:255'],
            'location'       => ['nullable', 'url', 'max:500'],
            'location_name'  => ['nullable', 'string', 'max:255'],
            'event_date'     => ['required', 'date'],
            'show_countdown' => ['boolean'],
            'is_active'      => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'      => 'اسم المناسبة مطلوب.',
            'title.max'           => 'اسم المناسبة طويل جداً (الحد الأقصى 255 حرف).',
            'location.url'        => 'رابط الموقع غير صالح.',
            'event_date.required' => 'تاريخ المناسبة مطلوب.',
            'event_date.date'     => 'تاريخ المناسبة غير صالح.',
        ];
    }
}
