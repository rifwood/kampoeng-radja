<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Seed the minimum CORE data and development administrator account.
     */
    public function run(): void
    {
        $departemen = Departemen::firstOrCreate([
            'nama_departemen' => 'IT',
        ]);

        $jabatan = Jabatan::firstOrCreate([
            'nama_jabatan' => 'Admin Sistem',
        ]);

        $roles = collect(['super_admin', 'admin', 'user'])
            ->mapWithKeys(fn (string $name): array => [
                $name => Role::firstOrCreate(['nama_role' => $name]),
            ]);

        $karyawan = Karyawan::updateOrCreate(
            ['nik' => 'ADMIN001'],
            [
                'nama' => 'Admin Sistem',
                'tanggal_lahir' => '2000-01-01',
                'tempat_lahir' => 'Jambi',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kampoeng Radja',
                'agama' => 'islam',
                'status_perkawinan' => 'belum kawin',
                'pendidikan' => 'S1',
                'jabatan_id' => $jabatan->id,
                'departemen_id' => $departemen->id,
                'status_keaktifan' => 'aktif',
                'status_kerja' => 'kontrak',
                'tanggal_masuk' => '2026-01-01',
                'tanggal_keluar' => null,
                'no_hp' => '080000000000',
                'foto_ktp' => null,
            ],
        );

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'karyawan_id' => $karyawan->id,
                'role_id' => $roles->get('super_admin')->id,
                'pin' => '123456',
                'is_active' => true,
                'must_change_pin' => false,
            ],
        );
    }
}
