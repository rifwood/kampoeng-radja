<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGaleriEventRequest;
use App\Http\Requests\Admin\UpdateGaleriEventRequest;
use App\Models\GaleriEvent;
use App\Models\GaleriEventFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class GaleriEventController extends Controller
{
    public function index(Request $request): Response
    {
        $items = GaleriEvent::query()
            ->with('photos')
            ->orderByDesc('tanggal_event')
            ->orderByDesc('id')
            ->get()
            ->map(fn (GaleriEvent $event): array => $this->serialize($event));

        return Inertia::render('Internal/CMS/Gallery/Index', [
            'items' => $items,
            'user' => $this->userPayload($request),
        ]);
    }

    public function store(StoreGaleriEventRequest $request): RedirectResponse
    {
        $paths = collect($request->file('fotos', []))
            ->map(fn ($photo): string => $photo->store('galeri-event', 'public'));
        $captions = collect($request->validated('new_photo_captions', []));

        try {
            DB::transaction(function () use ($request, $paths, $captions): void {
                $event = GaleriEvent::create([
                    ...$this->validatedContent($request),
                    'created_by' => $request->user()->id,
                    'updated_by' => null,
                ]);

                $paths->each(fn (string $path, int $index) => $event->photos()->create([
                    'foto' => $path,
                    'caption' => $this->cleanCaption($captions->get($index)),
                    'urutan' => $index,
                    'created_by' => $request->user()->id,
                    'updated_by' => null,
                ]));
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($paths->all());

            throw $exception;
        }

        return to_route('dashboard.cms.gallery.index')
            ->with('success', 'Galeri Event berhasil ditambahkan.');
    }

    public function update(UpdateGaleriEventRequest $request, GaleriEvent $galeriEvent): RedirectResponse
    {
        $galeriEvent->load('photos');
        $existingPlan = collect($request->validated('existing_photos', []))->values();
        $this->ensurePhotosBelongToAlbum($existingPlan, $galeriEvent);

        $newPaths = collect($request->file('fotos', []))
            ->map(fn ($photo): string => $photo->store('galeri-event', 'public'));
        $newCaptions = collect($request->validated('new_photo_captions', []));
        $removedPaths = collect();

        try {
            DB::transaction(function () use (
                $request,
                $galeriEvent,
                $existingPlan,
                $newPaths,
                $newCaptions,
                &$removedPaths,
            ): void {
                $existingById = $galeriEvent->photos->keyBy('id');
                $keptIds = $existingPlan->pluck('id')->map(fn ($id): int => (int) $id);

                $galeriEvent->photos
                    ->reject(fn (GaleriEventFoto $photo): bool => $keptIds->contains($photo->id))
                    ->each(function (GaleriEventFoto $photo) use (&$removedPaths): void {
                        $removedPaths->push($photo->foto);
                        $photo->delete();
                    });

                $existingPlan->each(function (array $item, int $index) use ($existingById, $request): void {
                    $existingById->get((int) $item['id'])->update([
                        'caption' => $this->cleanCaption($item['caption'] ?? null),
                        'urutan' => $index,
                        'updated_by' => $request->user()->id,
                    ]);
                });

                $newPaths->each(function (string $path, int $offset) use (
                    $galeriEvent,
                    $existingPlan,
                    $newCaptions,
                    $request,
                ): void {
                    $galeriEvent->photos()->create([
                        'foto' => $path,
                        'caption' => $this->cleanCaption($newCaptions->get($offset)),
                        'urutan' => $existingPlan->count() + $offset,
                        'created_by' => $request->user()->id,
                        'updated_by' => null,
                    ]);
                });

                $galeriEvent->update([
                    ...$this->validatedContent($request),
                    'updated_by' => $request->user()->id,
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($newPaths->all());

            throw $exception;
        }

        $removedPaths->unique()->each(fn (string $path) => $this->deletePhotoIfUnused($path));

        return to_route('dashboard.cms.gallery.index')
            ->with('success', 'Galeri Event berhasil diperbarui.');
    }

    public function destroy(GaleriEvent $galeriEvent): RedirectResponse
    {
        $paths = $galeriEvent->photos()->pluck('foto')->filter()->unique();

        DB::transaction(fn () => $galeriEvent->delete());
        $paths->each(fn (string $path) => $this->deletePhotoIfUnused($path));

        return to_route('dashboard.cms.gallery.index')
            ->with('success', 'Galeri Event dan seluruh fotonya berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(GaleriEvent $event): array
    {
        $photos = $event->photos->map(fn (GaleriEventFoto $photo): array => [
            'id' => $photo->id,
            'url' => Storage::disk('public')->url($photo->foto),
            'caption' => $photo->caption,
            'urutan' => $photo->urutan,
        ])->values();

        return [
            'id' => $event->id,
            'nama_event' => $event->nama_event,
            'tanggal_event' => $event->tanggal_event?->toDateString(),
            'deskripsi' => $event->deskripsi,
            'cover_url' => $photos->first()['url'] ?? null,
            'photo_count' => $photos->count(),
            'photos' => $photos,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function validatedContent(StoreGaleriEventRequest|UpdateGaleriEventRequest $request): array
    {
        return [
            'nama_event' => $request->validated('nama_event'),
            'tanggal_event' => $request->validated('tanggal_event'),
            'deskripsi' => $request->validated('deskripsi'),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $plan
     */
    private function ensurePhotosBelongToAlbum(Collection $plan, GaleriEvent $event): void
    {
        $allowedIds = $event->photos->pluck('id')->map(fn ($id): int => (int) $id);
        $requestedIds = $plan->pluck('id')->map(fn ($id): int => (int) $id);

        if ($requestedIds->diff($allowedIds)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'existing_photos' => 'Daftar foto Galeri Event tidak valid.',
            ]);
        }
    }

    private function cleanCaption(mixed $caption): ?string
    {
        $value = trim((string) ($caption ?? ''));

        return $value === '' ? null : $value;
    }

    private function deletePhotoIfUnused(string $path): void
    {
        if (! GaleriEventFoto::query()->where('foto', $path)->exists()) {
            Storage::disk('public')->delete($path);
        }
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
