<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWahanaRequest;
use App\Http\Requests\Admin\UpdateWahanaRequest;
use App\Models\TempatMakan;
use App\Models\TempatMakanFoto;
use App\Models\Wahana;
use App\Models\WahanaFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class WahanaController extends Controller
{
    private const MAX_FEATURED = 3;

    public function index(Request $request): Response
    {
        $items = Wahana::query()
            ->with(['fotos' => fn ($query) => $query->orderBy('urutan')->orderBy('id')])
            ->orderBy('urutan_tampil')
            ->orderBy('id')
            ->get()
            ->map(fn (Wahana $wahana): array => $this->serialize($wahana));

        return Inertia::render('Internal/CMS/Wahana/Index', [
            'items' => $items,
            'labels' => Wahana::LABELS,
            'featuredLimit' => self::MAX_FEATURED,
            'featuredCount' => $items->where('is_active', true)->where('is_unggulan', true)->count(),
            'diningItems' => TempatMakan::query()
                ->with(['photos', 'menuHighlights'])
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (TempatMakan $tempatMakan): array => $this->serializeDiningPlace($tempatMakan)),
            'diningCategories' => TempatMakan::CATEGORIES,
            'initialTab' => $request->query('tab') === 'tempat-makan' ? 'tempat-makan' : 'wahana',
            'user' => $this->userPayload($request),
        ]);
    }

    public function store(StoreWahanaRequest $request): RedirectResponse
    {
        $this->ensureFeaturedCapacity($request->boolean('is_active'), $request->boolean('is_unggulan'));
        $paths = collect($request->file('fotos', []))
            ->map(fn ($photo): string => $photo->store('wahana', 'public'));

        try {
            DB::transaction(function () use ($request, $paths): void {
                $wahana = Wahana::create([
                    ...$this->validatedContent($request),
                    'foto' => $paths->first(),
                    'created_by' => $request->user()->id,
                    'updated_by' => null,
                ]);

                $paths->each(fn (string $path, int $index) => $wahana->fotos()->create([
                    'foto' => $path,
                    'urutan' => $index,
                ]));
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($paths->all());

            throw $exception;
        }

        return to_route('dashboard.cms.wahana.index')
            ->with('success', 'Wahana berhasil ditambahkan.');
    }

    public function update(UpdateWahanaRequest $request, Wahana $wahana): RedirectResponse
    {
        $this->ensureFeaturedCapacity(
            $request->boolean('is_active'),
            $request->boolean('is_unggulan'),
            $wahana,
        );

        $wahana->load(['fotos' => fn ($query) => $query->orderBy('urutan')->orderBy('id')]);
        $photoPlan = $this->photoPlan($request, $wahana);
        $newPaths = collect($request->file('fotos', []))
            ->map(fn ($photo): string => $photo->store('wahana', 'public'));
        $removedPaths = collect();

        try {
            DB::transaction(function () use ($request, $wahana, $photoPlan, $newPaths, &$removedPaths): void {
                $existingByKey = $wahana->fotos->keyBy(fn (WahanaFoto $photo): string => (string) $photo->id);
                $keptKeys = $photoPlan['keys'];

                $wahana->fotos
                    ->reject(fn (WahanaFoto $photo): bool => $keptKeys->contains((string) $photo->id))
                    ->each(function (WahanaFoto $photo) use (&$removedPaths): void {
                        $removedPaths->push($photo->foto);
                        $photo->delete();
                    });

                $orderedPaths = collect();
                foreach ($keptKeys as $index => $key) {
                    if ($key === 'legacy') {
                        $legacyPhoto = $wahana->fotos()->create([
                            'foto' => $wahana->foto,
                            'urutan' => $index,
                        ]);
                        $orderedPaths->push($legacyPhoto->foto);

                        continue;
                    }

                    $photo = $existingByKey->get($key);
                    $photo->update(['urutan' => $index]);
                    $orderedPaths->push($photo->foto);
                }

                $newPaths->each(function (string $path, int $offset) use ($wahana, $keptKeys, $orderedPaths): void {
                    $wahana->fotos()->create([
                        'foto' => $path,
                        'urutan' => $keptKeys->count() + $offset,
                    ]);
                    $orderedPaths->push($path);
                });

                if (! $orderedPaths->contains($wahana->foto)) {
                    $removedPaths->push($wahana->foto);
                }

                $wahana->update([
                    ...$this->validatedContent($request),
                    'foto' => $orderedPaths->first(),
                    'updated_by' => $request->user()->id,
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newPaths->all());

            throw $exception;
        }

        $removedPaths->unique()->each(fn (string $path) => $this->deletePhotoIfUnused($path));

        return to_route('dashboard.cms.wahana.index')
            ->with('success', 'Wahana berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, Wahana $wahana): RedirectResponse
    {
        $newStatus = ! $wahana->is_active;
        $this->ensureFeaturedCapacity($newStatus, $wahana->is_unggulan, $wahana);

        $wahana->update([
            'is_active' => $newStatus,
            'updated_by' => $request->user()->id,
        ]);

        return to_route('dashboard.cms.wahana.index')
            ->with('success', $newStatus ? 'Wahana berhasil diaktifkan.' : 'Wahana berhasil dinonaktifkan.');
    }

    public function destroy(Wahana $wahana): RedirectResponse
    {
        $paths = $wahana->fotos()->pluck('foto')->push($wahana->foto)->filter()->unique();

        DB::transaction(fn () => $wahana->delete());
        $paths->each(fn (string $path) => $this->deletePhotoIfUnused($path));

        return to_route('dashboard.cms.wahana.index')
            ->with('success', 'Wahana berhasil dihapus.');
    }

    /**
     * @return array<string, bool|int|string|array<int, mixed>>
     */
    private function serialize(Wahana $wahana): array
    {
        $photos = $wahana->fotos->isNotEmpty()
            ? $wahana->fotos->map(fn (WahanaFoto $photo): array => [
                'id' => $photo->id,
                'key' => (string) $photo->id,
                'url' => Storage::disk('public')->url($photo->foto),
                'urutan' => $photo->urutan,
                'is_legacy' => false,
            ])->values()
            : collect([[
                'id' => null,
                'key' => 'legacy',
                'url' => Storage::disk('public')->url($wahana->foto),
                'urutan' => 0,
                'is_legacy' => true,
            ]]);

        return [
            'id' => $wahana->id,
            'nama_wahana' => $wahana->nama_wahana,
            'deskripsi_singkat' => $wahana->deskripsi_singkat,
            'foto_url' => $photos->first()['url'],
            'photos' => $photos,
            'labels' => $wahana->labels(),
            'is_active' => $wahana->is_active,
            'is_unggulan' => $wahana->is_unggulan,
            'urutan_tampil' => $wahana->urutan_tampil,
        ];
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    private function validatedContent(StoreWahanaRequest|UpdateWahanaRequest $request): array
    {
        $labels = collect($request->validated('label', []))
            ->map(fn (string $label): string => trim($label))
            ->unique()
            ->values();

        return [
            'nama_wahana' => $request->validated('nama_wahana'),
            'deskripsi_singkat' => $request->validated('deskripsi_singkat'),
            'label' => $labels->isEmpty() ? null : $labels->implode(','),
            'is_active' => $request->boolean('is_active'),
            'is_unggulan' => $request->boolean('is_unggulan'),
            'urutan_tampil' => (int) $request->validated('urutan_tampil'),
        ];
    }

    /**
     * @return array{keys: Collection<int, string>}
     */
    private function photoPlan(UpdateWahanaRequest $request, Wahana $wahana): array
    {
        $requestedKeys = collect($request->validated('existing_photo_order', []))
            ->map(fn (string $key): string => trim($key))
            ->values();
        $allowedKeys = $wahana->fotos->isNotEmpty()
            ? $wahana->fotos->map(fn (WahanaFoto $photo): string => (string) $photo->id)
            : collect(['legacy']);

        if ($requestedKeys->diff($allowedKeys)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'existing_photo_order' => 'Daftar foto Wahana tidak valid.',
            ]);
        }

        $newPhotoCount = count($request->file('fotos', []));
        if ($requestedKeys->count() + $newPhotoCount < 1) {
            throw ValidationException::withMessages([
                'fotos' => 'Minimal satu foto harus tersedia untuk setiap Wahana.',
            ]);
        }

        return ['keys' => $requestedKeys];
    }

    private function ensureFeaturedCapacity(bool $isActive, bool $isFeatured, ?Wahana $current = null): void
    {
        if (! $isActive || ! $isFeatured) {
            return;
        }

        $featuredCount = Wahana::query()
            ->where('is_active', true)
            ->where('is_unggulan', true)
            ->when($current, fn ($query) => $query->whereKeyNot($current->getKey()))
            ->count();

        if ($featuredCount >= self::MAX_FEATURED) {
            throw ValidationException::withMessages([
                'is_unggulan' => 'Maksimal 3 Wahana dapat ditampilkan sebagai Wahana Unggulan.',
            ]);
        }
    }

    private function deletePhotoIfUnused(string $path): void
    {
        $isUsedAsLegacy = Wahana::query()->where('foto', $path)->exists();
        $isUsedAsChild = WahanaFoto::query()->where('foto', $path)->exists();

        if (! $isUsedAsLegacy && ! $isUsedAsChild) {
            Storage::disk('public')->delete($path);
        }
    }

    private function serializeDiningPlace(TempatMakan $tempatMakan): array
    {
        $photos = $tempatMakan->photos->map(fn (TempatMakanFoto $photo): array => [
            'id' => $photo->id,
            'url' => Storage::disk('public')->url($photo->foto),
            'urutan' => $photo->urutan,
        ])->values();

        return [
            'id' => $tempatMakan->id,
            'nama' => $tempatMakan->nama,
            'kategori' => $tempatMakan->kategori,
            'tagline' => $tempatMakan->tagline,
            'deskripsi' => $tempatMakan->deskripsi,
            'jam_buka' => $tempatMakan->jam_buka ? substr($tempatMakan->jam_buka, 0, 5) : null,
            'jam_tutup' => $tempatMakan->jam_tutup ? substr($tempatMakan->jam_tutup, 0, 5) : null,
            'kapasitas' => $tempatMakan->kapasitas,
            'lokasi' => $tempatMakan->lokasi,
            'jenis_menu' => $tempatMakan->jenis_menu,
            'is_recommended' => $tempatMakan->is_recommended,
            'is_active' => $tempatMakan->is_active,
            'urutan_tampil' => $tempatMakan->urutan_tampil,
            'cover_url' => $photos->first()['url'] ?? null,
            'photos' => $photos,
            'menu_highlights' => $tempatMakan->menuHighlights->pluck('nama_menu')->values(),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function userPayload(Request $request): array
    {
        $name = $request->user()->karyawan()->value('nama') ?? $request->user()->username;
        $roleName = $request->user()->role()->value('nama_role') ?? 'user';

        return [
            'name' => $name,
            'initials' => collect(preg_split('/\s+/', trim($name)))
                ->filter()
                ->take(2)
                ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode(''),
            'roleName' => $roleName,
            'roleLabel' => str($roleName)->replace('_', ' ')->title()->toString(),
        ];
    }
}
