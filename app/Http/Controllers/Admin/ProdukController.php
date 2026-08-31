<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProdukRequest;
use App\Http\Requests\Admin\UpdateProdukRequest;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProdukController extends Controller
{
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $thumbnail = $request->file('thumbnail')->store('products', 'public');
        $heroImage = null;

        try {
            $heroImage = $request->file('hero_image')->store('products', 'public');
            Produk::create([
                ...$request->safe()->only(['nama', 'deskripsi_singkat', 'deskripsi_lengkap', 'urutan_tampil']),
                'thumbnail' => $thumbnail,
                'hero_image' => $heroImage,
                'is_active' => $request->boolean('is_active'),
                'created_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete(array_filter([$thumbnail, $heroImage]));

            throw $exception;
        }

        return to_route('dashboard.cms.home')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(UpdateProdukRequest $request, Produk $produk): RedirectResponse
    {
        $oldThumbnail = $produk->thumbnail;
        $oldHeroImage = $produk->hero_image;
        $newThumbnail = $request->file('thumbnail')?->store('products', 'public');
        $newHeroImage = $request->file('hero_image')?->store('products', 'public');

        try {
            $produk->update([
                ...$request->safe()->only(['nama', 'deskripsi_singkat', 'deskripsi_lengkap', 'urutan_tampil']),
                'thumbnail' => $newThumbnail ?? $oldThumbnail,
                'hero_image' => $newHeroImage ?? $oldHeroImage,
                'is_active' => $request->boolean('is_active'),
                'updated_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete(array_filter([$newThumbnail, $newHeroImage]));

            throw $exception;
        }

        if ($newThumbnail) {
            $this->deleteIfUnused($oldThumbnail);
        }
        if ($newHeroImage) {
            $this->deleteIfUnused($oldHeroImage);
        }

        return to_route('dashboard.cms.home')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $paths = [$produk->thumbnail, $produk->hero_image];
        DB::transaction(fn () => $produk->delete());

        foreach (array_unique($paths) as $path) {
            $this->deleteIfUnused($path);
        }

        return to_route('dashboard.cms.home')->with('success', 'Produk berhasil dihapus.');
    }

    private function deleteIfUnused(string $path): void
    {
        $isUsed = Produk::query()
            ->where('thumbnail', $path)
            ->orWhere('hero_image', $path)
            ->exists();

        if (! $isUsed) {
            Storage::disk('public')->delete($path);
        }
    }
}
