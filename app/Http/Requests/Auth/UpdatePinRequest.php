<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->must_change_pin;
    }

    public function rules(): array
    {
        return [
            'pin' => ['required', 'digits:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'pin.digits' => 'PIN baru harus tepat 6 digit angka.',
            'pin.confirmed' => 'Konfirmasi PIN tidak sama.',
        ];
    }
}
