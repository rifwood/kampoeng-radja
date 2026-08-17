<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaBeritaRequest extends FormRequest
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
            'deskripsi' => ['required', 'string'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'tanggal_publish' => ['required', 'date'],
        ];
    }
}
