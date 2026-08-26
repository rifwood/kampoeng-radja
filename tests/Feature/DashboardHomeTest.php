<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\ClosingEvent;
use App\Models\Departemen;
use App\Models\JenisEvent;
use App\Models\Pic;
use App\Models\Role;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardHomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-16 09:00:00', 'Asia/Jakarta'));
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_guest_cannot_open_dashboard_home(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_super_admin_sees_company_wide_database_summary_and_dynamic_identity(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $superAdmin->karyawan->update(['nama' => 'Sari Purnama']);
        $second = User::factory()->create();
        $inactive = User::factory()->create();
        $inactive->karyawan->update(['status_keaktifan' => 'nonaktif']);

        Absensi::create([
            'karyawan_id' => $superAdmin->karyawan_id,
            'tanggal_absensi' => '2026-08-16',
            'status_kehadiran' => 'H',
            'jam_masuk' => '08:45',
            'jam_keluar' => '16:00',
        ]);
        Absensi::create([
            'karyawan_id' => $second->karyawan_id,
            'tanggal_absensi' => '2026-08-16',
            'status_kehadiran' => 'A',
        ]);

        $this->actingAs($superAdmin)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->component('Internal/Dashboard/Index')
            ->where('user.name', 'Sari Purnama')
            ->where('user.position', $superAdmin->karyawan->jabatan->nama_jabatan)
            ->where('user.roleName', 'super_admin')
            ->where('permissions.showsOrganizationWidgets', true)
            ->where('permissions.canViewAttendance', true)
            ->where('permissions.canViewClosingEvent', true)
            ->where('employeeSummary.total', 3)
            ->where('employeeSummary.active', 2)
            ->where('employeeSummary.presentToday', 1)
            ->where('employeeSummary.lateToday', 1)
            ->where('employeeSummary.absentToday', 1)
            ->where('attendanceSummary.hadir.percentage', 50)
            ->where('attendanceSummary.izin.percentage', 0)
            ->where('attendanceSummary.alpha.percentage', 50)
            ->where('attendanceSummary.terlambat.count', 1)
            ->where('attendanceSummary.pulangAwal.count', 1)
            ->where('ownAttendance', null)
            ->has('revenueChart.series', 31)
            ->where('revenueChart.summary.total', 0)
            ->where('closingEventSummary.eventsThisMonth', 0));
    }

    public function test_admin_dashboard_uses_the_same_company_wide_summary_as_super_admin(): void
    {
        $admin = $this->userWithRole('admin');
        $departmentId = $admin->karyawan->departemen_id;
        $admin->karyawan->update(['tanggal_masuk' => '2026-01-01']);
        $sameDepartment = User::factory()->create();
        $sameDepartment->karyawan->update([
            'departemen_id' => $departmentId,
            'nama' => 'Rekan Satu Departemen',
            'tanggal_masuk' => '2026-08-10',
        ]);
        $inactiveSameDepartment = User::factory()->create();
        $inactiveSameDepartment->karyawan->update([
            'departemen_id' => $departmentId,
            'nama' => 'Nonaktif Satu Departemen',
            'tanggal_masuk' => '2026-08-10',
            'status_keaktifan' => 'nonaktif',
        ]);

        $otherDepartment = Departemen::create(['nama_departemen' => 'Departemen Lain']);
        $outside = User::factory()->create();
        $outside->karyawan->update([
            'departemen_id' => $otherDepartment->id,
            'nama' => 'Rekan Departemen Lain',
            'tanggal_masuk' => '2026-08-15',
        ]);

        Absensi::create([
            'karyawan_id' => $admin->karyawan_id,
            'tanggal_absensi' => '2026-08-16',
            'status_kehadiran' => 'H',
        ]);
        Absensi::create([
            'karyawan_id' => $sameDepartment->karyawan_id,
            'tanggal_absensi' => '2026-08-16',
            'status_kehadiran' => 'I',
        ]);
        Absensi::create([
            'karyawan_id' => $outside->karyawan_id,
            'tanggal_absensi' => '2026-08-16',
            'status_kehadiran' => 'A',
        ]);

        $this->actingAs($admin)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->where('permissions.showsOrganizationWidgets', true)
            ->where('permissions.canViewAttendance', true)
            ->where('permissions.canViewClosingEvent', false)
            ->where('employeeSummary.total', 4)
            ->where('employeeSummary.active', 3)
            ->where('employeeSummary.presentToday', 1)
            ->where('employeeSummary.absentToday', 2)
            ->where('attendanceSummary.hadir.percentage', 33.3)
            ->where('attendanceSummary.izin.percentage', 33.3)
            ->where('attendanceSummary.alpha.percentage', 33.3)
            ->where('revenueChart', null)
            ->where('closingEventSummary', null));

        $admin->update(['role_id' => Role::firstOrCreate(['nama_role' => 'super_admin'])->id]);
        $this->actingAs($admin)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->where('employeeSummary.total', 4)
            ->where('employeeSummary.active', 3)
            ->where('employeeSummary.presentToday', 1)
            ->where('employeeSummary.absentToday', 2)
            ->where('attendanceSummary.hadir.percentage', 33.3)
            ->where('attendanceSummary.izin.percentage', 33.3)
            ->where('attendanceSummary.alpha.percentage', 33.3));
    }

    public function test_user_receives_only_self_scoped_attendance_and_no_organization_payload(): void
    {
        $user = $this->userWithRole('user');
        User::factory()->create();
        Absensi::create([
            'karyawan_id' => $user->karyawan_id,
            'tanggal_absensi' => '2026-08-16',
            'status_kehadiran' => 'I',
            'keterangan' => 'Izin keluarga',
        ]);

        $this->actingAs($user)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->component('Internal/Dashboard/Index')
            ->where('user.name', $user->karyawan->nama)
            ->where('user.position', $user->karyawan->jabatan->nama_jabatan)
            ->where('user.roleName', 'user')
            ->where('permissions.showsOrganizationWidgets', false)
            ->where('permissions.canViewAttendance', true)
            ->where('employeeSummary', null)
            ->where('attendanceSummary', null)
            ->where('ownAttendance.status', 'I')
            ->where('ownAttendance.label', 'Izin')
            ->where('ownAttendance.note', 'Izin keluarga')
            ->where('revenueChart', null)
            ->where('closingEventSummary', null));
    }

    public function test_revenue_chart_aggregates_closing_event_value_on_start_date_only(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $pic = Pic::create(['nama_pic' => 'PIC Dashboard']);
        $jenisEvent = JenisEvent::create(['jenis_event' => 'Event Dashboard']);

        foreach ([5_000_000, 8_000_000] as $value) {
            ClosingEvent::create([
                'pic_id' => $pic->id,
                'event_id' => $jenisEvent->id,
                'created_by' => $superAdmin->id,
                'tanggal' => '2026-08-05',
                'tanggal_selesai' => '2026-08-07',
                'konsumen' => 'Konsumen Dashboard',
                'kontak' => '081234567890',
                'jam_kedatangan' => '09:00',
                'konsumsi' => true,
                'jumlah_pengunjung' => 50,
                'harga_total' => $value,
            ]);
        }

        $this->actingAs($superAdmin)->get('/dashboard?month=8&year=2026')->assertInertia(fn (Assert $page) => $page
            ->where('revenueChart.series.4.value', 13_000_000)
            ->where('revenueChart.series.5.value', 0)
            ->where('revenueChart.summary.total', 13_000_000)
            ->where('revenueChart.summary.highestDay.date', '2026-08-05')
            ->where('closingEventSummary.eventsThisMonth', 2)
            ->where('closingEventSummary.visitorsThisMonth', 100));
    }

    public function test_inactive_authenticated_account_is_forbidden_from_dashboard_home(): void
    {
        $inactive = $this->userWithRole('user', false);

        $this->actingAs($inactive)->get('/dashboard')->assertForbidden();
        $this->actingAs($inactive)->get('/admin')->assertForbidden();
    }

    public function test_legacy_admin_entry_redirects_every_active_role_to_canonical_dashboard(): void
    {
        foreach (['super_admin', 'admin', 'user'] as $roleName) {
            $this->actingAs($this->userWithRole($roleName))
                ->get('/admin')
                ->assertRedirect('/dashboard');
        }
    }

    private function userWithRole(string $roleName, bool $isActive = true): User
    {
        $role = Role::firstOrCreate(['nama_role' => $roleName]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => $isActive,
        ]);
    }
}
