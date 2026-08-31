<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StorePenempatanRequest;
use App\Http\Requests\Employee\UpdatePenempatanRequest;
use App\Models\Penempatan;
use Illuminate\Http\RedirectResponse;

class PenempatanController extends Controller
{
    public function store(StorePenempatanRequest $request): RedirectResponse
    {
        Penempatan::create($request->validated());

        return back()->with('success', 'Penempatan berhasil ditambahkan.');
    }

    public function update(UpdatePenempatanRequest $request, Penempatan $penempatan): RedirectResponse
    {
        $penempatan->update($request->validated());

        return back()->with('success', 'Penempatan berhasil diperbarui.');
    }

    public function destroy(Penempatan $penempatan): RedirectResponse
    {
        if ($penempatan->karyawan()->exists()) {
            return back()->with('error', 'Tidak dapat dihapus karena data masih digunakan.');
        }

        $penempatan->delete();

        return back()->with('success', 'Penempatan berhasil dihapus.');
    }
}
