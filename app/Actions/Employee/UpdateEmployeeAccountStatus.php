<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateEmployeeAccountStatus
{
    public function handle(Karyawan $employee, bool $isActive): void
    {
        DB::transaction(function () use ($employee, $isActive): void {
            $lockedEmployee = Karyawan::query()->lockForUpdate()->findOrFail($employee->id);
            $account = $lockedEmployee->user()->lockForUpdate()->first();

            if (! $account) {
                throw ValidationException::withMessages([
                    'account' => 'Karyawan ini belum memiliki akun.',
                ]);
            }

            if ($isActive && $lockedEmployee->status_keaktifan !== 'aktif') {
                throw ValidationException::withMessages([
                    'account' => 'Akun tidak dapat diaktifkan karena Karyawan berstatus nonaktif.',
                ]);
            }

            $account->update(['is_active' => $isActive]);
        });
    }
}
