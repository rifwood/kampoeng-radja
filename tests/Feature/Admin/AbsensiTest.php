<?php

namespace Tests\Feature\Admin;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Role;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-13 09:00:00', 'Asia/Jakarta'));
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_guest_is_redirected_from_absensi_page_and_endpoint(): void
    {
        $this->get('/admin/absensi')->assertRedirect(route('login'));
        $this->put('/admin/absensi', [])->assertRedirect(route('login'));
    }

    public function test_admin_and_user_roles_cannot_access_absensi(): void
    {
        foreach (['admin', 'user'] as $role) {
            $user = $this->userWithRole($role);

            $this->actingAs($user)->get('/admin/absensi')->assertForbidden();
            $this->actingAs($user)->put('/admin/absensi', [])->assertForbidden();
        }
    }

    public function test_super_admin_sees_only_active_employees_with_position_and_today_state(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $active = User::factory()->create()->karyawan;
        $inactive = User::factory()->create()->karyawan;
        $inactive->update(['status_keaktifan' => 'nonaktif']);

        $this->actingAs($superAdmin)->get('/admin/absensi')->assertInertia(fn (Assert $page) => $page
            ->component('Internal/Absensi/Index')
            ->where('attendanceDate', '2026-08-13')
            ->where('today', '2026-08-13')
            ->where('isToday', true)
            ->where('isSaved', false)
            ->has('employees', 2)
            ->where('employees.0.position', fn ($value) => is_string($value) && $value !== '')
            ->where('employees', fn ($employees) => collect($employees)->pluck('id')->contains($active->id)
                && ! collect($employees)->pluck('id')->contains($inactive->id)));
    }

    public function test_super_admin_can_store_attendance_for_every_active_employee(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        User::factory()->create();

        $records = $this->recordsForActiveEmployees(['H', 'I']);

        $this->actingAs($superAdmin)->put('/admin/absensi', [
            'tanggal_absensi' => '2026-08-13',
            'records' => $records,
        ])->assertRedirect(route('admin.absensi.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('absensi', 2);
        $this->assertDatabaseHas('absensi', [
            'karyawan_id' => $records[0]['karyawan_id'],
            'tanggal_absensi' => '2026-08-13',
            'status_kehadiran' => 'H',
            'keterangan' => null,
        ]);
        $this->assertDatabaseHas('absensi', [
            'karyawan_id' => $records[1]['karyawan_id'],
            'status_kehadiran' => 'I',
            'keterangan' => 'Izin keluarga',
        ]);
    }

    public function test_saving_today_again_updates_existing_records_without_duplicates(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $employeeId = $superAdmin->karyawan_id;
        Absensi::create([
            'karyawan_id' => $employeeId,
            'tanggal_absensi' => '2026-08-13',
            'status_kehadiran' => 'H',
        ]);

        $this->actingAs($superAdmin)->put('/admin/absensi', [
            'tanggal_absensi' => '2026-08-13',
            'records' => [[
                'karyawan_id' => $employeeId,
                'status_kehadiran' => 'A',
                'keterangan' => 'Keterangan administratif',
            ]],
        ])->assertRedirect(route('admin.absensi.index'));

        $this->assertDatabaseCount('absensi', 1);
        $this->assertDatabaseHas('absensi', [
            'karyawan_id' => $employeeId,
            'tanggal_absensi' => '2026-08-13',
            'status_kehadiran' => 'A',
            'keterangan' => 'Keterangan administratif',
        ]);
    }

    public function test_database_prevents_duplicate_employee_attendance_for_one_date(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $attributes = [
            'karyawan_id' => $superAdmin->karyawan_id,
            'tanggal_absensi' => '2026-08-13',
            'status_kehadiran' => 'H',
        ];
        Absensi::create($attributes);

        $this->expectException(UniqueConstraintViolationException::class);

        Absensi::create($attributes);
    }

    public function test_request_requires_every_active_employee_exactly_once_and_valid_status(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        $second = User::factory()->create()->karyawan;

        $this->actingAs($superAdmin)->put('/admin/absensi', [
            'tanggal_absensi' => '2026-08-13',
            'records' => [[
                'karyawan_id' => $superAdmin->karyawan_id,
                'status_kehadiran' => 'SAKIT',
                'keterangan' => str_repeat('a', 256),
            ]],
        ])->assertSessionHasErrors([
            'records',
            'records.0.status_kehadiran',
            'records.0.keterangan',
        ]);

        $this->assertDatabaseCount('absensi', 0);
        $this->assertNotSame($superAdmin->karyawan_id, $second->id);
    }

    public function test_backend_rejects_creating_or_updating_past_attendance(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        Absensi::create([
            'karyawan_id' => $superAdmin->karyawan_id,
            'tanggal_absensi' => '2026-08-12',
            'status_kehadiran' => 'H',
        ]);

        $this->actingAs($superAdmin)->put('/admin/absensi', [
            'tanggal_absensi' => '2026-08-12',
            'records' => [[
                'karyawan_id' => $superAdmin->karyawan_id,
                'status_kehadiran' => 'A',
                'keterangan' => 'Tidak boleh tersimpan',
            ]],
        ])->assertSessionHasErrors('tanggal_absensi');

        $this->assertDatabaseHas('absensi', [
            'karyawan_id' => $superAdmin->karyawan_id,
            'tanggal_absensi' => '2026-08-12',
            'status_kehadiran' => 'H',
            'keterangan' => null,
        ]);
    }

    public function test_past_attendance_is_exposed_as_read_only_and_future_dates_are_rejected(): void
    {
        $superAdmin = $this->userWithRole('super_admin');
        Absensi::create([
            'karyawan_id' => $superAdmin->karyawan_id,
            'tanggal_absensi' => '2026-08-12',
            'status_kehadiran' => 'I',
            'keterangan' => 'Izin keluarga',
        ]);

        $this->actingAs($superAdmin)
            ->get('/admin/absensi?tanggal=2026-08-12')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Internal/Absensi/Index')
                ->where('attendanceDate', '2026-08-12')
                ->where('isToday', false)
                ->where('isSaved', true)
                ->where('employees.0.attendance.status', 'I'));

        $this->actingAs($superAdmin)
            ->get('/admin/absensi?tanggal=2026-08-14')
            ->assertNotFound();
    }

    /**
     * @return array<int, array{karyawan_id: int, status_kehadiran: string, keterangan: string|null}>
     */
    private function recordsForActiveEmployees(array $statuses): array
    {
        return Karyawan::query()
            ->where('status_keaktifan', 'aktif')
            ->orderBy('id')
            ->get()
            ->values()
            ->map(fn (Karyawan $employee, int $index): array => [
                'karyawan_id' => $employee->id,
                'status_kehadiran' => $statuses[$index] ?? 'H',
                'keterangan' => $index === 1 ? 'Izin keluarga' : null,
            ])
            ->all();
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nama_role' => $roleName]);

        return User::factory()->create(['role_id' => $role->id]);
    }
}
