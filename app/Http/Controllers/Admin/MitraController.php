<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMitraRequest;
use App\Http\Requests\Admin\UpdateMitraRequest;
use App\Models\Mitra;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MitraController extends Controller
{
    public function store(StoreMitraRequest $request): RedirectResponse
    {
        $path = $request->file('logo')->store('partners', 'public');

        try {
            Mitra::create([
                ...$request->safe()->only(['nama_brand', 'urutan_tampil']),
                'logo' => $path,
                'is_active' => $request->boolean('is_active'),
                'created_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);
            throw $exception;
        }

        return to_route('dashboard.cms.home')->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function update(UpdateMitraRequest $request, Mitra $mitra): RedirectResponse
    {
        $oldPath = $mitra->logo;
        $newPath = $request->file('logo')?->store('partners', 'public');

        try {
            $mitra->update([
                ...$request->safe()->only(['nama_brand', 'urutan_tampil']),
                'logo' => $newPath ?? $oldPath,
                'is_active' => $request->boolean('is_active'),
                'updated_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }
            throw $exception;
        }

        if ($newPath) {
            $this->deleteIfUnused($oldPath);
        }

        return to_route('dashboard.cms.home')->with('success', 'Mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra): RedirectResponse
    {
        $path = $mitra->logo;
        DB::transaction(fn () => $mitra->delete());
        $this->deleteIfUnused($path);

        return to_route('dashboard.cms.home')->with('success', 'Mitra berhasil dihapus.');
    }

    private function deleteIfUnused(string $path): void
    {
        if (! Mitra::query()->where('logo', $path)->exists()) {
            Storage::disk('public')->delete($path);
        }
    }
}
