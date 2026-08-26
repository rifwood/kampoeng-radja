<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeHeroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'video' => ['nullable', 'file', 'mimes:mp4,webm', 'max:30720'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'judul' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'cta_primary_label' => ['nullable', 'string', 'max:100', 'required_with:cta_primary_url'],
            'cta_primary_url' => ['nullable', 'string', 'max:2048', 'required_with:cta_primary_label', $this->safeDestinationRule()],
            'cta_secondary_label' => ['nullable', 'string', 'max:100', 'required_with:cta_secondary_url'],
            'cta_secondary_url' => ['nullable', 'string', 'max:2048', 'required_with:cta_secondary_label', $this->safeDestinationRule()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $fields = [
            'eyebrow',
            'judul',
            'tagline',
            'deskripsi',
            'cta_primary_label',
            'cta_primary_url',
            'cta_secondary_label',
            'cta_secondary_url',
        ];

        $this->merge(collect($fields)
            ->filter(fn (string $field): bool => $this->has($field))
            ->mapWithKeys(function (string $field): array {
                $value = $this->input($field);

                return [$field => is_string($value) && trim($value) === '' ? null : (is_string($value) ? trim($value) : $value)];
            })
            ->all());
    }

    public function messages(): array
    {
        return [
            'video.mimes' => 'Video Hero harus berformat MP4 atau WebM.',
            'video.max' => 'Ukuran Video Hero maksimal 30 MB.',
            'poster.mimes' => 'Poster harus berformat JPG, JPEG, PNG, atau WebP.',
            'poster.max' => 'Ukuran poster maksimal 5 MB.',
            'cta_primary_label.required_with' => 'Label CTA Utama wajib diisi ketika link tersedia.',
            'cta_primary_url.required_with' => 'Link CTA Utama wajib diisi ketika label tersedia.',
            'cta_secondary_label.required_with' => 'Label CTA Sekunder wajib diisi ketika link tersedia.',
            'cta_secondary_url.required_with' => 'Link CTA Sekunder wajib diisi ketika label tersedia.',
        ];
    }

    private function safeDestinationRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (! is_string($value) || $value === '') {
                return;
            }

            $isInternal = str_starts_with($value, '/') && ! str_starts_with($value, '//');
            $scheme = parse_url($value, PHP_URL_SCHEME);
            $isExternal = filter_var($value, FILTER_VALIDATE_URL)
                && in_array(strtolower((string) $scheme), ['http', 'https'], true);

            if (! $isInternal && ! $isExternal) {
                $fail('Link CTA harus berupa path internal seperti /wahana atau URL http/https yang valid.');
            }
        };
    }
}
