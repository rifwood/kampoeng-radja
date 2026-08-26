<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use App\Models\Role;
use Illuminate\Validation\ValidationException;

class SyncEmployeeAccountRole
{
    public function __construct(private readonly ResolveEmployeeAccountRole $roleResolver) {}

    public function handle(Karyawan $employee): bool
    {
        $employee->loadMissing('jabatan:id,nama_jabatan');
        $account = $employee->user()->first();

        if (! $account) {
            return false;
        }

        $roleName = $this->roleResolver->handle($employee->jabatan?->nama_jabatan);
        if (! $roleName) {
            throw ValidationException::withMessages([
                'jabatan_id' => 'Role untuk jabatan ini belum ditentukan.',
            ]);
        }

        $role = Role::query()->whereRaw('LOWER(nama_role) = ?', [$roleName])->first();
        if (! $role) {
            throw ValidationException::withMessages([
                'jabatan_id' => "Master role {$roleName} belum tersedia.",
            ]);
        }

        if ($account->role_id === $role->id) {
            return false;
        }

        $account->update(['role_id' => $role->id]);

        return true;
    }
}
