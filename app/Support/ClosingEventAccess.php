<?php

namespace App\Support;

use App\Models\User;

class ClosingEventAccess
{
    /**
     * @return array{canView:bool,canCreate:bool,canUpdate:bool,canDelete:bool,canExport:bool,canManageMaster:bool}
     */
    public function for(?User $user): array
    {
        if (! $user) {
            return $this->denied();
        }

        $user->loadMissing([
            'role:id,nama_role',
            'karyawan.jabatan:id,nama_jabatan',
            'karyawan.departemen:id,nama_departemen',
        ]);

        if (! $user->is_active || ! $user->karyawan || $user->karyawan->status_keaktifan !== 'aktif') {
            return $this->denied();
        }

        $role = $this->normalize($user->role?->nama_role);
        $position = $this->normalize($user->karyawan->jabatan?->nama_jabatan);
        $department = $this->normalize($user->karyawan->departemen?->nama_departemen);

        if ($role === 'super admin') {
            return ['canView' => true, 'canCreate' => true, 'canUpdate' => true, 'canDelete' => true, 'canExport' => true, 'canManageMaster' => true];
        }

        if ($role === 'user') {
            return ['canView' => true, 'canCreate' => false, 'canUpdate' => false, 'canDelete' => false, 'canExport' => false, 'canManageMaster' => false];
        }

        if (in_array($position, ['manajer', 'manager'], true)) {
            return ['canView' => true, 'canCreate' => true, 'canUpdate' => true, 'canDelete' => false, 'canExport' => true, 'canManageMaster' => false];
        }

        if ($position === 'supervisor' && in_array($department, ['marcom', 'marketing'], true)) {
            return ['canView' => true, 'canCreate' => true, 'canUpdate' => true, 'canDelete' => false, 'canExport' => true, 'canManageMaster' => false];
        }

        if ($department === 'marketing') {
            return ['canView' => true, 'canCreate' => true, 'canUpdate' => false, 'canDelete' => false, 'canExport' => true, 'canManageMaster' => false];
        }

        return $this->denied();
    }

    private function normalize(?string $value): string
    {
        return preg_replace('/\s+/', ' ', mb_strtolower(trim(str_replace('_', ' ', (string) $value)))) ?? '';
    }

    /** @return array{canView:bool,canCreate:bool,canUpdate:bool,canDelete:bool,canExport:bool,canManageMaster:bool} */
    private function denied(): array
    {
        return ['canView' => false, 'canCreate' => false, 'canUpdate' => false, 'canDelete' => false, 'canExport' => false, 'canManageMaster' => false];
    }
}
