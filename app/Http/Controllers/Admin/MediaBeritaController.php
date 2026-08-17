<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaBeritaRequest;
use App\Http\Requests\Admin\UpdateMediaBeritaRequest;
use App\Models\MediaBerita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MediaBeritaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/MediaBerita/Index', [
            'items' => MediaBerita::query()
                ->latest('tanggal_publish')
                ->get()
                ->map(fn (MediaBerita $item): array => $this->serialize($item)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/MediaBerita/Create');
    }

    public function store(StoreMediaBeritaRequest $request): RedirectResponse
    {
        $path = $request->file('foto')->store('media-berita', 'public');

        try {
            MediaBerita::create([
                ...$request->safe()->only(['judul', 'deskripsi', 'tanggal_publish']),
                'foto' => $path,
                'created_by' => $request->user()->id,
                'updated_by' => null,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return to_route('admin.media-berita.index')
            ->with('success', 'Media & Berita berhasil ditambahkan.');
    }

    public function edit(MediaBerita $mediaBerita): Response
    {
        return Inertia::render('Admin/MediaBerita/Edit', [
            'item' => $this->serialize($mediaBerita),
        ]);
    }

    public function update(UpdateMediaBeritaRequest $request, MediaBerita $mediaBerita): RedirectResponse
    {
        $oldPath = $mediaBerita->foto;
        $newPath = $request->file('foto')?->store('media-berita', 'public');

        try {
            $mediaBerita->update([
                ...$request->safe()->only(['judul', 'deskripsi', 'tanggal_publish']),
                'foto' => $newPath ?? $oldPath,
                'updated_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath) {
            $this->deleteFileIfUnused($oldPath);
        }

        return to_route('admin.media-berita.index')
            ->with('success', 'Media & Berita berhasil diperbarui.');
    }

    public function destroy(MediaBerita $mediaBerita): RedirectResponse
    {
        $path = $mediaBerita->foto;

        DB::transaction(fn () => $mediaBerita->delete());
        $this->deleteFileIfUnused($path);

        return to_route('admin.media-berita.index')
            ->with('success', 'Media & Berita berhasil dihapus.');
    }

    /**
     * @return array<string, int|string|null>
     */
    private function serialize(MediaBerita $item): array
    {
        return [
            'id' => $item->id,
            'judul' => $item->judul,
            'deskripsi' => $item->deskripsi,
            'foto' => $item->foto,
            'foto_url' => Storage::disk('public')->url($item->foto),
            'tanggal_publish' => $item->tanggal_publish?->format('Y-m-d\TH:i'),
        ];
    }

    private function deleteFileIfUnused(string $path): void
    {
        $isStillUsed = MediaBerita::query()->where('foto', $path)->exists();

        if (! $isStillUsed) {
            Storage::disk('public')->delete($path);
        }
    }
}
