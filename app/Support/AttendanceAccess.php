<?php

namespace App\Support;

use App\Models\User;

class AttendanceAccess
{
    /**
     * @return array{canView: bool, canManage: bool, canExport: bool}
     */
    public function for(?User $user): array
    {
        if (! $user || ! $user->is_active) {
            return $this->denied();
        }

        $roleName = mb_strtolower((string) $user->role()->value('nama_role'));
        $canView = in_array($roleName, ['super_admin', 'admin', 'user'], true);
        $isSuperAdmin = $roleName === 'super_admin';

        return [
            'canView' => $canView,
            'canManage' => $isSuperAdmin,
            'canExport' => $isSuperAdmin,
        ];
    }

    /**
     * @return array{canView: false, canManage: false, canExport: false}
     */
    private function denied(): array
    {
        return [
            'canView' => false,
            'canManage' => false,
            'canExport' => false,
        ];
    }
}
