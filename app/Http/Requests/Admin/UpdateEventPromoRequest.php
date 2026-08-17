<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventPromoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi_singkat' => ['required', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'link_wa' => ['nullable', 'string', 'max:255'],
        ];
    }
}
