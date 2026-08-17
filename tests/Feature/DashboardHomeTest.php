<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Departemen;
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
            ->where('employeeSummary.total', 3)
            ->where('employeeSummary.active', 2)
            ->where('employeeSummary.presentToday', 1)
            ->where('employeeSummary.absentToday', 1)
            ->where('attendanceSummary.hadir.percentage', 50)
            ->where('attendanceSummary.izin.percentage', 0)
            ->where('attendanceSummary.alpha.percentage', 50)
            ->where('ownAttendance', null)
            ->has('latestEmployees', 3)
            ->where('calendar.today', '2026-08-16'));
    }

    public function test_admin_dashboard_is_strictly_scoped_to_authenticated_department(): void
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

        $otherDepartment = Departemen::create(['nama_departemen' => 'Di Luar Scope']);
        $outside = User::factory()->create();
        $outside->karyawan->update([
            'departemen_id' => $otherDepartment->id,
            'nama' => 'Rahasia Departemen Lain',
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
            ->where('permissions.canViewAttendance', false)
            ->where('employeeSummary.total', 3)
            ->where('employeeSummary.active', 2)
            ->where('employeeSummary.presentToday', 1)
            ->where('employeeSummary.absentToday', 1)
            ->where('attendanceSummary.hadir.percentage', 50)
            ->where('attendanceSummary.izin.percentage', 50)
            ->where('attendanceSummary.alpha.percentage', 0)
            ->has('latestEmployees', 3)
            ->where('latestEmployees.0.name', 'Nonaktif Satu Departemen')
            ->where('latestEmployees.1.name', 'Rekan Satu Departemen')
            ->where('latestEmployees', fn ($employees) => ! collect($employees)->pluck('name')->contains('Rahasia Departemen Lain')));
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
            ->where('permissions.canViewAttendance', false)
            ->where('employeeSummary', null)
            ->where('attendanceSummary', null)
            ->where('ownAttendance.status', 'I')
            ->where('ownAttendance.label', 'Izin')
            ->where('ownAttendance.note', 'Izin keluarga')
            ->has('latestEmployees', 0));
    }

    public function test_latest_employees_are_ordered_by_join_date_then_id_descending(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $superAdmin->karyawan->update(['tanggal_masuk' => '2026-01-01']);
        $olderId = User::factory()->create();
        $olderId->karyawan->update(['nama' => 'Masuk Sama ID Lama', 'tanggal_masuk' => '2026-08-15']);
        $newerId = User::factory()->create();
        $newerId->karyawan->update(['nama' => 'Masuk Sama ID Baru', 'tanggal_masuk' => '2026-08-15']);

        $this->actingAs($superAdmin)->get('/dashboard')->assertInertia(fn (Assert $page) => $page
            ->where('latestEmployees.0.name', 'Masuk Sama ID Baru')
            ->where('latestEmployees.1.name', 'Masuk Sama ID Lama'));
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
