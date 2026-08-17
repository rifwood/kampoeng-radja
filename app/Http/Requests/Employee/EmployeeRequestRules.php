<?php

namespace App\Http\Requests\Employee;

use Illuminate\Validation\Rule;

trait EmployeeRequestRules
{
    /** @return array<string, mixed> */
    protected function employeeRules(?int $employeeId = null): array
    {
        return [
            'nik' => ['required', 'string', 'max:20', Rule::unique('karyawan', 'nik')->ignore($employeeId)],
            'nama' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'alamat' => ['required', 'string'],
            'agama' => ['required', Rule::in(['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu'])],
            'status_perkawinan' => ['required', Rule::in(['belum kawin', 'kawin', 'cerai hidup', 'cerai mati'])],
            'pendidikan' => ['required', Rule::in(['SD', 'SMP', 'SMA', 'MAN', 'SMK', 'D3', 'D4', 'S1', 'S2', 'S3'])],
            'jabatan_id' => ['required', 'integer', Rule::exists('jabatan', 'id')],
            'departemen_id' => ['nullable', 'integer', Rule::exists('departemen', 'id')],
            'status_keaktifan' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'status_kerja' => ['required', Rule::in(['kontrak', 'magang', 'buruh', 'freelance'])],
            'tanggal_masuk' => ['required', 'date', 'before_or_equal:today'],
            'tanggal_keluar' => ['nullable', 'date', 'after_or_equal:tanggal_masuk', 'before_or_equal:today'],
            'no_hp' => ['required', 'string', 'max:20'],
            'foto_ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
