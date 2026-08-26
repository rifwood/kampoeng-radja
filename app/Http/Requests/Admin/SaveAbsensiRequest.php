<?php

namespace App\Http\Requests\Admin;

use App\Models\Karyawan;
use App\Support\AttendanceAccess;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(AttendanceAccess::class)->for($this->user())['canManage'];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $attendanceDate = $this->attendanceDate() ?? CarbonImmutable::today('Asia/Jakarta');

        return [
            'tanggal_absensi' => ['required', 'date_format:Y-m-d'],
            'records' => ['required', 'array', 'min:1'],
            'records.*.karyawan_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('karyawan', 'id')->where(fn ($query) => $query
                    ->where('status_keaktifan', 'aktif')
                    ->whereDate('tanggal_masuk', '<=', $attendanceDate->toDateString())),
            ],
            'records.*.status_kehadiran' => ['required', Rule::in(['H', 'I', 'A'])],
            'records.*.jam_masuk' => ['nullable', 'date_format:H:i'],
            'records.*.jam_keluar' => ['nullable', 'date_format:H:i'],
            'records.*.keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $today = CarbonImmutable::today('Asia/Jakarta');
                $yesterday = $today->subDay();
                $attendanceDate = $this->attendanceDate();

                if ($attendanceDate?->isAfter($today)) {
                    $validator->errors()->add('tanggal_absensi', 'Absensi tanggal masa depan tidak dapat diinput.');
                } elseif ($attendanceDate?->isBefore($yesterday)) {
                    $validator->errors()->add(
                        'tanggal_absensi',
                        'Absensi hanya dapat diinput atau diubah pada hari berjalan dan satu hari sebelumnya.',
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
                    ->whereDate('tanggal_masuk', '<=', ($attendanceDate ?? $today)->toDateString())
                    ->orderBy('id')
                    ->pluck('id');

                if ($submittedIds->all() !== $activeIds->all()) {
                    $validator->errors()->add(
                        'records',
                        'Absensi harus mencakup seluruh karyawan aktif tepat satu kali.',
                    );
                }

                foreach ($this->input('records', []) as $index => $record) {
                    if (! is_array($record) || ($record['status_kehadiran'] ?? null) !== 'H') {
                        continue;
                    }

                    $entryTime = $record['jam_masuk'] ?? null;
                    $exitTime = $record['jam_keluar'] ?? null;

                    if ($entryTime && preg_match('/^([01]\\d|2[0-3]):[0-5]\\d$/', $entryTime) === 1 && $entryTime > '12:00') {
                        $validator->errors()->add(
                            "records.{$index}.jam_masuk",
                            'Jam masuk maksimal pukul 12:00.',
                        );
                    }

                    if ($entryTime && $exitTime && $exitTime < $entryTime) {
                        $validator->errors()->add(
                            "records.{$index}.jam_keluar",
                            'Jam keluar tidak boleh lebih awal dari jam masuk.',
                        );
                    }
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'records' => collect($this->input('records', []))
                ->map(function ($record) {
                    if (! is_array($record)) {
                        return $record;
                    }

                    $isPresent = ($record['status_kehadiran'] ?? null) === 'H';

                    return [
                        ...$record,
                        'jam_masuk' => $isPresent && filled($record['jam_masuk'] ?? null)
                            ? (string) $record['jam_masuk']
                            : null,
                        'jam_keluar' => $isPresent && filled($record['jam_keluar'] ?? null)
                            ? (string) $record['jam_keluar']
                            : null,
                        'keterangan' => filled($record['keterangan'] ?? null)
                            ? trim((string) $record['keterangan'])
                            : null,
                    ];
                })
                ->all(),
        ]);
    }

    private function attendanceDate(): ?CarbonImmutable
    {
        $date = $this->input('tanggal_absensi');

        if (! is_string($date) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) !== 1) {
            return null;
        }

        try {
            return CarbonImmutable::createFromFormat('!Y-m-d', $date, 'Asia/Jakarta');
        } catch (\Throwable) {
            return null;
        }
    }
}
