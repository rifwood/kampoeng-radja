<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenempatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role()->value('nama_role') === 'super_admin';
    }

    public function rules(): array
    {
        return ['nama_penempatan' => ['required', 'string', 'max:100', Rule::unique('penempatan')->ignore($this->route('penempatan'))]];
    }
}
