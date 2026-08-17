<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartemenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role()->value('nama_role') === 'super_admin';
    }

    public function rules(): array
    {
        return ['nama_departemen' => ['required', 'string', 'max:100', Rule::unique('departemen')->ignore($this->route('departemen'))]];
    }
}
