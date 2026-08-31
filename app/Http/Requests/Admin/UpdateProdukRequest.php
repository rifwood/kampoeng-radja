<?php

namespace App\Http\Requests\Admin;

class UpdateProdukRequest extends StoreProdukRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
