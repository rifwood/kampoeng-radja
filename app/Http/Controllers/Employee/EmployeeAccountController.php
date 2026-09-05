<?php

namespace App\Http\Controllers\Employee;

use App\Actions\Employee\CreateEmployeeAccount;
use App\Actions\Employee\UpdateEmployeeAccountStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreEmployeeAccountRequest;
use App\Http\Requests\Employee\UpdateEmployeeAccountStatusRequest;
use App\Models\Karyawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class EmployeeAccountController extends Controller
{
    public function store(
        StoreEmployeeAccountRequest $request,
        Karyawan $karyawan,
        CreateEmployeeAccount $action,
    ): RedirectResponse {
        $action->handle($karyawan, $request->safe()->only(['username', 'pin']));

        return back()->with('success', 'Akun Karyawan berhasil dibuat. Pengguna wajib mengganti PIN saat login pertama.');
    }

    public function updateStatus(
        UpdateEmployeeAccountStatusRequest $request,
        Karyawan $karyawan,
        UpdateEmployeeAccountStatus $action,
    ): RedirectResponse {
        $action->handle($karyawan, $request->boolean('is_active'));

        return back()->with('success', $request->boolean('is_active')
            ? 'Akun Karyawan berhasil diaktifkan.'
            : 'Akun Karyawan berhasil dinonaktifkan.');
    }

    public function resetPin(Karyawan $karyawan): RedirectResponse
    {
        $account = $karyawan->user()->firstOrFail();

        $account->update([
            'pin' => Hash::make('123456'),
            'must_change_pin' => true,
        ]);

        return back()->with(
            'success',
            'PIN berhasil direset. PIN sementara Karyawan adalah 123456 dan wajib diganti saat login berikutnya.',
        );
    }
}
