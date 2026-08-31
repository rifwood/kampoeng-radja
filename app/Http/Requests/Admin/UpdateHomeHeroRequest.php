<?php

namespace App\Http\Requests\Admin;

use App\Models\HomeHero;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'video' => [
                Rule::requiredIf(fn (): bool => ! HomeHero::query()->whereNotNull('video_path')->exists()),
                'nullable',
                'file',
                'mimes:mp4,webm',
                'max:30720',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'video.required' => 'Video Hero wajib dipilih.',
            'video.mimes' => 'Video Hero harus berformat MP4 atau WebM.',
            'video.max' => 'Ukuran Video Hero maksimal 30 MB.',
        ];
    }
}
