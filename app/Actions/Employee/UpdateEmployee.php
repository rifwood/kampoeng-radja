<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UpdateEmployee
{
    public function __construct(private readonly SyncEmployeeAccountRole $syncAccountRole) {}

    public function handle(Karyawan $employee, array $data, ?UploadedFile $photo, ?UploadedFile $signature): Karyawan
    {
        $oldPath = $employee->foto_ktp;
        $oldSignaturePath = $employee->foto_tanda_tangan;
        $newPath = $photo?->store('employee-ktp', 'local');
        $newSignaturePath = $signature?->store('karyawan/tanda-tangan', 'public');
        $positionChanged = array_key_exists('jabatan_id', $data)
            && (int) $employee->jabatan_id !== (int) $data['jabatan_id'];

        try {
            DB::transaction(function () use ($employee, $data, $newPath, $newSignaturePath, $positionChanged): void {
                unset($data['foto_ktp'], $data['foto_tanda_tangan']);
                if ($newPath) {
                    $data['foto_ktp'] = $newPath;
                }
                if ($newSignaturePath) {
                    $data['foto_tanda_tangan'] = $newSignaturePath;
                }
                $employee->update($data);
                if ($positionChanged) {
                    $this->syncAccountRole->handle($employee->refresh());
                }

                if ($employee->status_keaktifan === 'nonaktif') {
                    $employee->user()->update(['is_active' => false]);
                }
            });
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
            if ($newSignaturePath) {
                Storage::disk('public')->delete($newSignaturePath);
            }

            throw $exception;
        }

        if ($newPath && $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }
        if ($newSignaturePath && $oldSignaturePath) {
            Storage::disk('public')->delete($oldSignaturePath);
        }

        return $employee->refresh();
    }
}
