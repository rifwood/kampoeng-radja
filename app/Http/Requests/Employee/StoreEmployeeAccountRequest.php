<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role()->value('nama_role') === 'super_admin';
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')],
            'pin' => ['required', 'digits:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'pin.digits' => 'PIN harus tepat 6 digit angka.',
            'pin.confirmed' => 'Konfirmasi PIN tidak sama.',
        ];
    }
}
