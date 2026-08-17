<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class ExitEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role()->value('nama_role') === 'super_admin';
    }

    public function rules(): array
    {
        return ['tanggal_keluar' => ['required', 'date', 'after_or_equal:'.$this->route('karyawan')->tanggal_masuk->toDateString(), 'before_or_equal:today']];
    }
}
