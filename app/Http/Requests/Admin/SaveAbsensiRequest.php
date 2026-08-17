<?php

namespace App\Http\Requests\Admin;

use App\Models\Karyawan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true
            && $this->user()->role()->value('nama_role') === 'super_admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tanggal_absensi' => ['required', 'date_format:Y-m-d'],
            'records' => ['required', 'array', 'min:1'],
            'records.*.karyawan_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('karyawan', 'id')->where(fn ($query) => $query
                    ->where('status_keaktifan', 'aktif')
                    ->whereDate('tanggal_masuk', '<=', now('Asia/Jakarta')->toDateString())),
            ],
            'records.*.status_kehadiran' => ['required', Rule::in(['H', 'I', 'A'])],
            'records.*.keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->input('tanggal_absensi') !== now('Asia/Jakarta')->toDateString()) {
                    $validator->errors()->add(
                        'tanggal_absensi',
                        'Absensi hanya dapat disimpan atau diubah pada hari berjalan.',
                    );
                }

                $submittedIds = collect($this->input('records', []))
                    ->pluck('karyawan_id')
                    ->filter(fn ($id) => is_numeric($id))
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values();

                $activeIds = Karyawan::query()
                    ->where('status_keaktifan', 'aktif')
                    ->whereDate('tanggal_masuk', '<=', now('Asia/Jakarta')->toDateString())
                    ->orderBy('id')
                    ->pluck('id');

                if ($submittedIds->all() !== $activeIds->all()) {
                    $validator->errors()->add(
                        'records',
                        'Absensi harus mencakup seluruh karyawan aktif tepat satu kali.',
                    );
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'records' => collect($this->input('records', []))
                ->map(fn ($record) => is_array($record) ? [
                    ...$record,
                    'keterangan' => filled($record['keterangan'] ?? null)
                        ? trim((string) $record['keterangan'])
                        : null,
                ] : $record)
                ->all(),
        ]);
    }
}
