<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\DevelopmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_active_user_role_is_redirected_from_legacy_admin_entry_to_dashboard(): void
    {
        $user = $this->userWithRole('user');

        $this->actingAs($user)->get('/admin')->assertRedirect('/dashboard');
    }

    public function test_admin_role_is_redirected_from_legacy_admin_entry_to_dashboard(): void
    {
        $user = $this->userWithRole('admin');

        $this->actingAs($user)->get('/admin')->assertRedirect('/dashboard');
    }

    public function test_super_admin_role_is_redirected_from_legacy_admin_entry_to_dashboard(): void
    {
        $user = $this->userWithRole('super_admin');

        $this->actingAs($user)->get('/admin')->assertRedirect('/dashboard');
    }

    public function test_inactive_authenticated_user_is_forbidden_from_admin_area(): void
    {
        $user = $this->userWithRole('admin', false);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_development_seeder_creates_valid_idempotent_admin_account(): void
    {
        $this->seed(DevelopmentSeeder::class);
        $this->seed(DevelopmentSeeder::class);

        $admin = User::query()
            ->with(['karyawan.jabatan', 'karyawan.departemen', 'role'])
            ->where('username', 'admin')
            ->sole();

        $this->assertSame('ADMIN001', $admin->karyawan->nik);
        $this->assertSame('Admin Sistem', $admin->karyawan->nama);
        $this->assertSame('Admin Sistem', $admin->karyawan->jabatan->nama_jabatan);
        $this->assertSame('IT', $admin->karyawan->departemen->nama_departemen);
        $this->assertSame('super_admin', $admin->role->nama_role);
        $this->assertTrue($admin->is_active);
        $this->assertTrue(Hash::check('123456', $admin->getRawOriginal('pin')));
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('karyawan', 1);
        $this->assertDatabaseCount('role', 3);
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
