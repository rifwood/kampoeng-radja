<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    use EmployeeRequestRules;

    public function authorize(): bool
    {
        return $this->user()?->role()->value('nama_role') === 'super_admin';
    }

    public function rules(): array
    {
        return $this->employeeRules($this->route('karyawan')?->id);
    }
}
