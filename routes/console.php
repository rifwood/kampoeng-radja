<?php

use App\Actions\Employee\ResolveEmployeeAccountRole;
use App\Actions\Employee\SyncEmployeeAccountRole;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('employees:sync-account-roles {--apply : Terapkan perubahan role ke database}', function () {
    $resolver = app(ResolveEmployeeAccountRole::class);
    $synchronizer = app(SyncEmployeeAccountRole::class);
    $apply = (bool) $this->option('apply');
    $mismatches = 0;
    $updated = 0;
    $skipped = 0;

    User::query()
        ->with(['role:id,nama_role', 'karyawan.jabatan:id,nama_jabatan'])
        ->orderBy('id')
        ->each(function (User $account) use ($resolver, $synchronizer, $apply, &$mismatches, &$updated, &$skipped): void {
            $employee = $account->karyawan;
            $roleName = $resolver->handle($employee?->jabatan?->nama_jabatan);

            if (! $employee || ! $roleName) {
                $this->warn("Lewati akun {$account->username}: Karyawan/Jabatan tidak memiliki mapping role.");
                $skipped++;

                return;
            }

            if (mb_strtolower($account->role?->nama_role ?? '') === $roleName) {
                return;
            }

            $mismatches++;
            $this->line("{$account->username}: {$account->role?->nama_role} -> {$roleName}");

            if ($apply && $synchronizer->handle($employee)) {
                $updated++;
            }
        });

    if (! $apply) {
        $this->info("Dry run selesai: {$mismatches} akun perlu disinkronkan; {$skipped} akun dilewati.");
        if ($mismatches > 0) {
            $this->comment('Jalankan kembali dengan --apply untuk menerapkan perubahan.');
        }

        return;
    }

    $this->info("Sinkronisasi selesai: {$updated} akun diperbarui; {$skipped} akun dilewati.");
})->purpose('Audit atau sinkronkan role akun dengan Jabatan Karyawan secara idempotent');
