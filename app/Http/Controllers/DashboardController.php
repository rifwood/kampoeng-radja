<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $authUser = $request->user()->loadMissing([
            'role:id,nama_role',
            'karyawan.jabatan:id,nama_jabatan',
        ]);
        $employee = $authUser->karyawan;

        abort_unless($employee && $authUser->role, 403);

        $today = CarbonImmutable::today('Asia/Jakarta');
        $roleName = mb_strtolower($authUser->role->nama_role);
        $showsOrganizationWidgets = in_array($roleName, ['super_admin', 'admin'], true);
        $canViewAttendance = $roleName === 'super_admin';

        $employeeSummary = null;
        $attendanceSummary = null;
        $latestEmployees = [];

        if ($showsOrganizationWidgets) {
            $scope = $this->employeeScope($roleName, $employee->departemen_id);
            $activeScope = (clone $scope)->where('status_keaktifan', 'aktif');

            $totalEmployees = (clone $scope)->count();
            $activeEmployees = (clone $activeScope)->count();
            $attendanceCounts = Absensi::query()
                ->whereDate('tanggal_absensi', $today)
                ->whereIn('karyawan_id', (clone $activeScope)->select('id'))
                ->selectRaw("SUM(CASE WHEN status_kehadiran = 'H' THEN 1 ELSE 0 END) AS hadir")
                ->selectRaw("SUM(CASE WHEN status_kehadiran = 'I' THEN 1 ELSE 0 END) AS izin")
                ->selectRaw("SUM(CASE WHEN status_kehadiran = 'A' THEN 1 ELSE 0 END) AS alpha")
                ->first();

            $hadir = (int) ($attendanceCounts?->hadir ?? 0);
            $izin = (int) ($attendanceCounts?->izin ?? 0);
            $alpha = (int) ($attendanceCounts?->alpha ?? 0);

            $employeeSummary = [
                'total' => $totalEmployees,
                'active' => $activeEmployees,
                'presentToday' => $hadir,
                'absentToday' => $izin + $alpha,
            ];
            $attendanceSummary = [
                'activeEmployees' => $activeEmployees,
                'hadir' => $this->attendanceMetric($hadir, $activeEmployees),
                'izin' => $this->attendanceMetric($izin, $activeEmployees),
                'alpha' => $this->attendanceMetric($alpha, $activeEmployees),
            ];
            $latestEmployees = (clone $scope)
                ->select(['id', 'nama', 'jabatan_id', 'tanggal_masuk'])
                ->with('jabatan:id,nama_jabatan')
                ->orderByDesc('tanggal_masuk')
                ->orderByDesc('id')
                ->limit(3)
                ->get()
                ->map(fn (Karyawan $latestEmployee): array => [
                    'id' => $latestEmployee->id,
                    'name' => $latestEmployee->nama,
                    'initials' => $this->initials($latestEmployee->nama),
                    'position' => $latestEmployee->jabatan?->nama_jabatan,
                    'joinedAt' => $latestEmployee->tanggal_masuk?->toDateString(),
                ])
                ->all();
        }

        $ownAttendance = null;

        if (! $showsOrganizationWidgets) {
            $record = Absensi::query()
                ->where('karyawan_id', $employee->id)
                ->whereDate('tanggal_absensi', $today)
                ->first(['status_kehadiran', 'keterangan']);

            $ownAttendance = [
                'status' => $record?->status_kehadiran,
                'label' => match ($record?->status_kehadiran) {
                    'H' => 'Hadir',
                    'I' => 'Izin',
                    'A' => 'Alpha',
                    default => 'Belum diinput',
                },
                'note' => $record?->keterangan,
            ];
        }

        return Inertia::render('Internal/Dashboard/Index', [
            'user' => [
                'name' => $employee->nama,
                'initials' => $this->initials($employee->nama),
                'position' => $employee->jabatan?->nama_jabatan,
                'roleName' => $roleName,
                'roleLabel' => str($roleName)->replace('_', ' ')->title()->toString(),
                'viewBadge' => mb_strtoupper(str_replace('_', ' ', $roleName)).' VIEW',
            ],
            'permissions' => [
                'showsOrganizationWidgets' => $showsOrganizationWidgets,
                'canViewAttendance' => $canViewAttendance,
            ],
            'employeeSummary' => $employeeSummary,
            'attendanceSummary' => $attendanceSummary,
            'ownAttendance' => $ownAttendance,
            'latestEmployees' => $latestEmployees,
            'calendar' => $this->calendar($today),
        ]);
    }

    private function employeeScope(string $roleName, ?int $departmentId): Builder
    {
        $query = Karyawan::query();

        if ($roleName === 'admin') {
            if ($departmentId === null) {
                return $query->whereRaw('1 = 0');
            }

            return $query->where('departemen_id', $departmentId);
        }

        return $query;
    }

    /**
     * @return array{count: int, percentage: float}
     */
    private function attendanceMetric(int $count, int $activeEmployees): array
    {
        return [
            'count' => $count,
            'percentage' => $activeEmployees === 0
                ? 0.0
                : round(($count / $activeEmployees) * 100, 1),
        ];
    }

    /**
     * @return array{today: string, monthLabel: string, days: array<int, array{date: string, day: int, isCurrentMonth: bool, isToday: bool}>}
     */
    private function calendar(CarbonImmutable $today): array
    {
        $cursor = $today->startOfMonth()->startOfWeek(CarbonInterface::SUNDAY);
        $end = $today->endOfMonth()->endOfWeek(CarbonInterface::SATURDAY);
        $days = [];

        while ($cursor->lessThanOrEqualTo($end)) {
            $days[] = [
                'date' => $cursor->toDateString(),
                'day' => $cursor->day,
                'isCurrentMonth' => $cursor->month === $today->month,
                'isToday' => $cursor->isSameDay($today),
            ];
            $cursor = $cursor->addDay();
        }

        return [
            'today' => $today->toDateString(),
            'monthLabel' => $today->locale('id')->translatedFormat('F Y'),
            'days' => $days,
        ];
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
