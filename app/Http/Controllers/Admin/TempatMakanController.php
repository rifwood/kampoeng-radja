<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTempatMakanRequest;
use App\Http\Requests\Admin\UpdateTempatMakanRequest;
use App\Models\TempatMakan;
use App\Models\TempatMakanFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class TempatMakanController extends Controller
{
    public function store(StoreTempatMakanRequest $request): RedirectResponse
    {
        $paths = collect($request->file('fotos', []))
            ->map(fn ($photo): string => $photo->store('tempat-makan', 'public'));

        try {
            DB::transaction(function () use ($request, $paths): void {
                $tempatMakan = TempatMakan::create([
                    ...$this->validatedContent($request),
                    'created_by' => $request->user()->id,
                    'updated_by' => null,
                ]);

                $paths->each(fn (string $path, int $index) => $tempatMakan->photos()->create([
                    'foto' => $path,
                    'urutan' => $index,
                ]));

                $this->replaceMenuHighlights($tempatMakan, $request->validated('menu_highlights', []));
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($paths->all());
            throw $exception;
        }

        return to_route('dashboard.cms.wahana.index', ['tab' => 'tempat-makan'])
            ->with('success', 'Tempat makan berhasil disimpan.');
    }

    public function update(UpdateTempatMakanRequest $request, TempatMakan $tempatMakan): RedirectResponse
    {
        $tempatMakan->load(['photos', 'menuHighlights']);
        $keptPhotoIds = collect($request->validated('existing_photo_order', []))
            ->map(fn ($id): int => (int) $id)
            ->values();
        $this->validatePhotoPlan($tempatMakan, $keptPhotoIds, count($request->file('fotos', [])));

        $newPaths = collect($request->file('fotos', []))
            ->map(fn ($photo): string => $photo->store('tempat-makan', 'public'));
        $removedPaths = collect();

        try {
            DB::transaction(function () use ($request, $tempatMakan, $keptPhotoIds, $newPaths, &$removedPaths): void {
                $existingById = $tempatMakan->photos->keyBy('id');

                $tempatMakan->photos
                    ->reject(fn (TempatMakanFoto $photo): bool => $keptPhotoIds->contains($photo->id))
                    ->each(function (TempatMakanFoto $photo) use (&$removedPaths): void {
                        $removedPaths->push($photo->foto);
                        $photo->delete();
                    });

                $keptPhotoIds->each(fn (int $id, int $index) => $existingById->get($id)->update(['urutan' => $index]));

                $newPaths->each(fn (string $path, int $offset) => $tempatMakan->photos()->create([
                    'foto' => $path,
                    'urutan' => $keptPhotoIds->count() + $offset,
                ]));

                $tempatMakan->update([
                    ...$this->validatedContent($request),
                    'updated_by' => $request->user()->id,
                ]);

                $this->replaceMenuHighlights($tempatMakan, $request->validated('menu_highlights', []));
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newPaths->all());
            throw $exception;
        }

        $removedPaths->unique()->each(fn (string $path) => $this->deletePhotoIfUnused($path));

        return to_route('dashboard.cms.wahana.index', ['tab' => 'tempat-makan'])
            ->with('success', 'Tempat makan berhasil diperbarui.');
    }

    public function destroy(TempatMakan $tempatMakan): RedirectResponse
    {
        $paths = $tempatMakan->photos()->pluck('foto')->filter()->unique();

        DB::transaction(fn () => $tempatMakan->delete());
        $paths->each(fn (string $path) => $this->deletePhotoIfUnused($path));

        return to_route('dashboard.cms.wahana.index', ['tab' => 'tempat-makan'])
            ->with('success', 'Tempat makan berhasil dihapus.');
    }

    private function validatedContent(StoreTempatMakanRequest|UpdateTempatMakanRequest $request): array
    {
        return [
            'nama' => $request->validated('nama'),
            'kategori' => $request->validated('kategori'),
            'tagline' => $this->nullableString($request->validated('tagline')),
            'deskripsi' => $request->validated('deskripsi'),
            'jam_buka' => $request->validated('jam_buka'),
            'jam_tutup' => $request->validated('jam_tutup'),
            'kapasitas' => $request->validated('kapasitas'),
            'lokasi' => $this->nullableString($request->validated('lokasi')),
            'jenis_menu' => $this->nullableString($request->validated('jenis_menu')),
            'is_recommended' => $request->boolean('is_recommended'),
            'is_active' => $request->boolean('is_active'),
            'urutan_tampil' => (int) $request->validated('urutan_tampil'),
        ];
    }

    private function replaceMenuHighlights(TempatMakan $tempatMakan, array $items): void
    {
        $tempatMakan->menuHighlights()->delete();

        collect($items)
            ->map(fn ($item): string => trim((string) $item))
            ->filter()
            ->values()
            ->each(fn (string $item, int $index) => $tempatMakan->menuHighlights()->create([
                'nama_menu' => $item,
                'urutan' => $index,
            ]));
    }

    private function validatePhotoPlan(TempatMakan $tempatMakan, Collection $requestedIds, int $newPhotoCount): void
    {
        $allowedIds = $tempatMakan->photos->pluck('id')->map(fn ($id): int => (int) $id);

        if ($requestedIds->diff($allowedIds)->isNotEmpty()) {
            throw ValidationException::withMessages(['existing_photo_order' => 'Daftar foto Tempat Makan tidak valid.']);
        }

        if ($requestedIds->count() + $newPhotoCount < 1) {
            throw ValidationException::withMessages(['fotos' => 'Minimal satu foto harus tersedia.']);
        }
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : $value;
    }

    private function deletePhotoIfUnused(string $path): void
    {
        if (! TempatMakanFoto::query()->where('foto', $path)->exists()) {
            Storage::disk('public')->delete($path);
        }
    }
}
