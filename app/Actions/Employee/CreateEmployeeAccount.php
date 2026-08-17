<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateEmployeeAccount
{
    public function __construct(private readonly ResolveEmployeeAccountRole $roleResolver) {}

    /** @param array{username:string,pin:string} $data */
    public function handle(Karyawan $employee, array $data): User
    {
        return DB::transaction(function () use ($employee, $data): User {
            $lockedEmployee = Karyawan::query()
                ->with('jabatan:id,nama_jabatan')
                ->lockForUpdate()
                ->findOrFail($employee->id);

            if ($lockedEmployee->user()->exists()) {
                throw ValidationException::withMessages([
                    'account' => 'Karyawan ini sudah memiliki akun.',
                ]);
            }

            $roleName = $this->roleResolver->handle($lockedEmployee->jabatan?->nama_jabatan);
            if (! $roleName) {
                throw ValidationException::withMessages([
                    'account' => 'Role untuk jabatan ini belum ditentukan.',
                ]);
            }

            $role = Role::query()->whereRaw('LOWER(nama_role) = ?', [$roleName])->first();
            if (! $role) {
                throw ValidationException::withMessages([
                    'account' => "Master role {$roleName} belum tersedia.",
                ]);
            }

            return User::create([
                'karyawan_id' => $lockedEmployee->id,
                'role_id' => $role->id,
                'username' => $data['username'],
                'pin' => $data['pin'],
                'is_active' => $lockedEmployee->status_keaktifan === 'aktif',
                'must_change_pin' => true,
            ]);
        });
    }
}
