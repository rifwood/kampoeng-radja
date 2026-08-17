<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreJabatanRequest;
use App\Http\Requests\Employee\UpdateJabatanRequest;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;

class JabatanController extends Controller
{
    public function store(StoreJabatanRequest $request): RedirectResponse
    {
        Jabatan::create($request->validated());

        return back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(UpdateJabatanRequest $request, Jabatan $jabatan): RedirectResponse
    {
        $jabatan->update($request->validated());

        return back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        if ($jabatan->karyawan()->exists()) {
            return back()->with('error', 'Tidak dapat dihapus karena data masih digunakan.');
        }
        $jabatan->delete();

        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
