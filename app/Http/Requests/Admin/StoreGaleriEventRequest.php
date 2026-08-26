<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGaleriEventRequest extends FormRequest
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
            'nama_event' => ['required', 'string', 'max:150'],
            'tanggal_event' => ['required', 'date'],
            'deskripsi' => ['required', 'string'],
            'fotos' => ['required', 'array', 'min:1'],
            'fotos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'new_photo_captions' => ['nullable', 'array'],
            'new_photo_captions.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fotos.required' => 'Minimal satu foto wajib ditambahkan untuk Galeri Event baru.',
            'fotos.min' => 'Minimal satu foto wajib ditambahkan untuk Galeri Event baru.',
            'fotos.*.max' => 'Ukuran setiap foto maksimal 5 MB.',
        ];
    }
}
