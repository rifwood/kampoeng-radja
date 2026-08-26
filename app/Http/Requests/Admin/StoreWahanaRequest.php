<?php

namespace App\Http\Requests\Admin;

use App\Models\Wahana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWahanaRequest extends FormRequest
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
            'nama_wahana' => ['required', 'string', 'max:150'],
            'deskripsi_singkat' => ['required', 'string', 'max:255'],
            'fotos' => ['required', 'array', 'min:1'],
            'fotos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'label' => ['nullable', 'array'],
            'label.*' => ['string', 'distinct', Rule::in(Wahana::LABELS)],
            'is_active' => ['required', 'boolean'],
            'is_unggulan' => ['required', 'boolean'],
            'urutan_tampil' => ['required', 'integer', 'min:0'],
        ];
    }
}
