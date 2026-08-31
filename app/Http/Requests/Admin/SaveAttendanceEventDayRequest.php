<?php

namespace App\Http\Requests\Admin;

use App\Models\Karyawan;
use App\Support\AttendanceAccess;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveAttendanceEventDayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(AttendanceAccess::class)->for($this->user())['canManage'];
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date_format:Y-m-d'],
            'nama_event' => ['required', 'string', 'max:150'],
            'schedules' => ['required', 'array', 'min:1'],
            'schedules.*.jam_masuk' => ['required', 'date_format:H:i'],
            'schedules.*.member_ids' => ['required', 'array', 'min:1'],
            'schedules.*.member_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('karyawan', 'id')->where(fn ($query) => $query->where('status_keaktifan', 'aktif')),
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $date = $this->attendanceDate();
                $today = CarbonImmutable::today('Asia/Jakarta');

                if (! $date || ! $date->betweenIncluded($today->subDay(), $today)) {
                    $validator->errors()->add('tanggal', 'Hari Event hanya dapat diatur untuk hari ini atau kemarin.');
                }

                $memberIds = collect($this->input('schedules', []))
                    ->flatMap(fn ($schedule) => is_array($schedule) ? ($schedule['member_ids'] ?? []) : [])
                    ->filter(fn ($id) => is_numeric($id))
                    ->map(fn ($id) => (int) $id);

                if ($memberIds->duplicates()->isNotEmpty()) {
                    $validator->errors()->add('schedules', 'Satu karyawan hanya boleh berada dalam satu jadwal Panitia.');
                }

                $eligibleIds = Karyawan::query()
                    ->whereIn('id', $memberIds->unique())
                    ->whereDate('tanggal_masuk', '<=', ($date ?? $today)->toDateString())
                    ->pluck('id');

                if ($eligibleIds->count() !== $memberIds->unique()->count()) {
                    $validator->errors()->add('schedules', 'Panitia harus merupakan karyawan aktif yang sudah masuk pada tanggal tersebut.');
                }
            },
        ];
    }

    private function attendanceDate(): ?CarbonImmutable
    {
        $value = $this->input('tanggal');

        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            return null;
        }

        try {
            return CarbonImmutable::createFromFormat('!Y-m-d', $value, 'Asia/Jakarta');
        } catch (\Throwable) {
            return null;
        }
    }
}
