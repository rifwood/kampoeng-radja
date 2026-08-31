<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaBeritaRequest;
use App\Http\Requests\Admin\UpdateMediaBeritaRequest;
use App\Models\MediaBerita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MediaBeritaController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/MediaBerita/Index', [
            'items' => MediaBerita::query()
                ->orderByDesc('tanggal_publish')
                ->orderByDesc('id')
                ->get()
                ->map(fn (MediaBerita $item): array => $this->serialize($item)),
            'user' => $this->userPayload($request),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Admin/MediaBerita/Create', [
            'user' => $this->userPayload($request),
        ]);
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

        return $this->redirectAfterMutation($request)
            ->with('success', 'Media & Berita berhasil ditambahkan.');
    }

    public function edit(Request $request, MediaBerita $mediaBerita): Response
    {
        return Inertia::render('Admin/MediaBerita/Edit', [
            'item' => $this->serialize($mediaBerita),
            'user' => $this->userPayload($request),
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

        return $this->redirectAfterMutation($request)
            ->with('success', 'Media & Berita berhasil diperbarui.');
    }

    public function destroy(Request $request, MediaBerita $mediaBerita): RedirectResponse
    {
        $path = $mediaBerita->foto;

        DB::transaction(fn () => $mediaBerita->delete());
        $this->deleteFileIfUnused($path);

        return $this->redirectAfterMutation($request)
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
            'tanggal_publish_iso' => $item->tanggal_publish?->toIso8601String(),
        ];
    }

    private function deleteFileIfUnused(string $path): void
    {
        $isStillUsed = MediaBerita::query()->where('foto', $path)->exists();

        if (! $isStillUsed) {
            Storage::disk('public')->delete($path);
        }
    }

    private function redirectAfterMutation(Request $request): RedirectResponse
    {
        return $request->routeIs('dashboard.cms.home.*')
            ? to_route('dashboard.cms.home')
            : to_route('admin.media-berita.index');
    }

    /**
     * @return array{name: string, initials: string, roleName: string, roleLabel: string}
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
