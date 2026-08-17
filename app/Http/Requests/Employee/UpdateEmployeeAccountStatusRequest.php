<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeAccountStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role()->value('nama_role') === 'super_admin';
    }

    public function rules(): array
    {
        return [
            'is_active' => ['required', 'boolean'],
        ];
    }
}
