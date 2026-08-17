<?php

namespace Database\Factories;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current plain PIN supplied to the model's hashed cast.
     */
    protected static string $pin = '123456';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'karyawan_id' => fn () => Karyawan::create([
                'nik' => fake()->unique()->numerify('####################'),
                'nama' => fake()->name(),
                'tanggal_lahir' => fake()->date(),
                'tempat_lahir' => fake()->city(),
                'jenis_kelamin' => fake()->randomElement(['L', 'P']),
                'alamat' => fake()->address(),
                'agama' => fake()->randomElement(['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu']),
                'status_perkawinan' => fake()->randomElement(['belum kawin', 'kawin', 'cerai hidup', 'cerai mati']),
                'pendidikan' => fake()->randomElement(['SD', 'SMP', 'SMA', 'MAN', 'SMK', 'D3', 'D4', 'S1', 'S2', 'S3']),
                'jabatan_id' => Jabatan::firstOrCreate(['nama_jabatan' => 'Pengujian'])->id,
                'departemen_id' => Departemen::firstOrCreate(['nama_departemen' => 'Pengujian'])->id,
                'status_keaktifan' => 'aktif',
                'status_kerja' => 'kontrak',
                'tanggal_masuk' => now()->toDateString(),
                'no_hp' => fake()->numerify('08##########'),
            ])->id,
            'role_id' => fn () => Role::firstOrCreate(['nama_role' => 'User'])->id,
            'username' => fake()->unique()->userName(),
            'pin' => static::$pin,
            'is_active' => true,
            'must_change_pin' => false,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
