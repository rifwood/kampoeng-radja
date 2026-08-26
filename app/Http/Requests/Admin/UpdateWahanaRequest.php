<?php

namespace App\Http\Requests\Admin;

use App\Models\Wahana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWahanaRequest extends FormRequest
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
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'existing_photo_order' => ['nullable', 'array'],
            'existing_photo_order.*' => ['string', 'distinct'],
            'label' => ['nullable', 'array'],
            'label.*' => ['string', 'distinct', Rule::in(Wahana::LABELS)],
            'is_active' => ['required', 'boolean'],
            'is_unggulan' => ['required', 'boolean'],
            'urutan_tampil' => ['required', 'integer', 'min:0'],
        ];
    }
}
