<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveAbsensiRequest;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AbsensiController extends Controller
{
    public function index(Request $request): Response
    {
        $today = CarbonImmutable::today('Asia/Jakarta');
        $selectedDate = $this->selectedDate($request, $today);
        $historicalEmployeeIds = Absensi::query()
            ->whereDate('tanggal_absensi', $selectedDate)
            ->select('karyawan_id');
        $employees = Karyawan::query()
            ->with('jabatan:id,nama_jabatan')
            ->where('status_keaktifan', 'aktif')
            ->where(function ($query) use ($selectedDate, $historicalEmployeeIds): void {
                $query->whereDate('tanggal_masuk', '<=', $selectedDate)
                    ->orWhereIn('id', $historicalEmployeeIds);
            })
            ->orderBy('nama')
            ->get();
        $attendance = Absensi::query()
            ->whereDate('tanggal_absensi', $selectedDate)
            ->whereIn('karyawan_id', $employees->pluck('id'))
            ->get()
            ->keyBy('karyawan_id');

        $employeePayload = $employees
            ->map(function (Karyawan $employee) use ($attendance): array {
                $record = $attendance->get($employee->id);

                return [
                    'id' => $employee->id,
                    'name' => $employee->nama,
                    'initials' => $this->initials($employee->nama),
                    'position' => $employee->jabatan->nama_jabatan,
                    'attendance' => $record ? [
                        'status' => $record->status_kehadiran,
                        'note' => $record->keterangan,
                    ] : null,
                ];
            });

        return Inertia::render('Internal/Absensi/Index', [
            'attendanceDate' => $selectedDate->toDateString(),
            'today' => $today->toDateString(),
            'isToday' => $selectedDate->isSameDay($today),
            'isSaved' => $employeePayload->isNotEmpty() && $attendance->count() === $employeePayload->count(),
            'employees' => $employeePayload,
            'user' => [
                'name' => $request->user()->karyawan()->value('nama'),
                'initials' => $this->initials($request->user()->karyawan()->value('nama')),
            ],
        ]);
    }

    public function store(SaveAbsensiRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            foreach ($validated['records'] as $record) {
                Absensi::query()->updateOrCreate(
                    [
                        'karyawan_id' => $record['karyawan_id'],
                        'tanggal_absensi' => $validated['tanggal_absensi'],
                    ],
                    [
                        'status_kehadiran' => $record['status_kehadiran'],
                        'keterangan' => $record['keterangan'] ?? null,
                    ],
                );
            }
        });

        return to_route('admin.absensi.index')
            ->with('success', 'Absensi hari ini berhasil disimpan.');
    }

    private function selectedDate(Request $request, CarbonImmutable $today): CarbonImmutable
    {
        $date = $request->string('tanggal')->toString();

        if ($date === '') {
            return $today;
        }

        abort_unless(
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1,
            404,
        );

        try {
            $selectedDate = CarbonImmutable::createFromFormat('!Y-m-d', $date);
        } catch (\Throwable) {
            abort(404);
        }

        abort_if($selectedDate->isFuture(), 404);

        return $selectedDate;
    }

    private function initials(string $name): string
    {
        return collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}
