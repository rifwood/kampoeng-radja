<script setup>
import LazyImage from '@/Components/Base/LazyImage.vue';

defineProps({
    place: { type: Object, required: true },
    activeIndex: { type: Number, default: 0 },
});

defineEmits(['select-photo', 'previous-photo', 'next-photo']);
</script>

<template>
    <article class="dining-panel overflow-hidden rounded-[26px] bg-[#064887] shadow-[0_22px_50px_rgba(4,55,108,.2)]">
        <div class="grid lg:grid-cols-[1.04fr_.96fr]">
            <div class="min-w-0 bg-[#10283b]">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-200 lg:h-full lg:min-h-[440px] lg:aspect-auto">
                    <LazyImage
                        v-if="place.photos?.[activeIndex]?.url"
                        :src="place.photos[activeIndex].url"
                        :alt="place.photos[activeIndex].alt || place.nama"
                        class-name="h-full w-full object-cover"
                    />
                    <div v-else class="grid h-full min-h-[300px] place-items-center bg-gradient-to-br from-slate-200 to-slate-300 px-6 text-center text-sm text-slate-500">
                        Foto tempat makan belum tersedia
                    </div>

                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/65 via-transparent to-slate-950/10" />
                    <span v-if="place.is_recommended" class="absolute left-5 top-5 rounded-full bg-[#ff952f] px-4 py-2 text-[10px] font-extrabold tracking-[.08em] text-white">
                        REKOMENDASI
                    </span>

                    <div v-if="place.photos?.length" class="absolute bottom-5 left-5 flex items-center gap-2">
                        <span class="rounded-full bg-slate-950/70 px-4 py-2 text-xs font-bold text-white">
                            {{ activeIndex + 1 }} / {{ place.photos.length }}
                        </span>
                        <button type="button" class="dining-gallery-nav" :disabled="place.photos.length < 2" :aria-label="`Foto sebelumnya untuk ${place.nama}`" @click="$emit('previous-photo')">‹</button>
                        <button type="button" class="dining-gallery-nav" :disabled="place.photos.length < 2" :aria-label="`Foto berikutnya untuk ${place.nama}`" @click="$emit('next-photo')">›</button>
                    </div>
                </div>

                <div v-if="place.photos?.length > 1" class="grid grid-cols-5 gap-2 bg-[#10283b] p-3">
                    <button
                        v-for="(photo, index) in place.photos.slice(0, 5)"
                        :key="photo.id || `${place.id}-${index}`"
                        type="button"
                        :aria-label="`Tampilkan foto ${index + 1} untuk ${place.nama}`"
                        :aria-pressed="activeIndex === index"
                        :class="activeIndex === index ? 'border-[#ff9d42] ring-2 ring-white/80' : 'border-white/30'"
                        class="aspect-[4/3] overflow-hidden rounded-lg border-2 bg-slate-700 focus:outline-none focus:ring-2 focus:ring-[#ff9d42]"
                        @click="$emit('select-photo', index)"
                    >
                        <LazyImage :src="photo.url" :alt="photo.alt || ''" class-name="h-full w-full object-cover" />
                    </button>
                </div>
            </div>

            <div class="flex min-w-0 flex-col justify-center bg-[linear-gradient(145deg,#0754a0_0%,#063d79_100%)] p-6 text-white sm:p-8 lg:p-10">
                <div class="flex items-center gap-3 text-xs font-extrabold uppercase tracking-[.12em] text-[#ffb15f]">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-white text-[#0754a0]" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 3v8m-2-8v5a2 2 0 0 0 4 0V3m-2 8v10M17 3v18m0-18c-2 2-3 5-3 8h3" /></svg>
                    </span>
                    {{ place.kategori }}
                </div>

                <h2 class="mt-4 font-heading text-3xl font-extrabold leading-tight sm:text-4xl">{{ place.nama }}</h2>
                <p v-if="place.tagline" class="mt-2 text-lg font-semibold italic text-[#ff9d42]">{{ place.tagline }}</p>
                <p v-if="place.deskripsi" class="mt-4 text-sm leading-7 text-blue-50/90">{{ place.deskripsi }}</p>

                <dl class="mt-7 grid gap-4 border-b border-white/15 pb-6 sm:grid-cols-2 xl:grid-cols-4">
                    <div v-if="place.jam_operasional"><dt class="text-[10px] font-bold uppercase tracking-wide text-blue-100/75">Jam Operasional</dt><dd class="mt-1 text-xs font-semibold">{{ place.jam_operasional }}</dd></div>
                    <div v-if="place.kapasitas"><dt class="text-[10px] font-bold uppercase tracking-wide text-blue-100/75">Kapasitas</dt><dd class="mt-1 text-xs font-semibold">{{ place.kapasitas }}</dd></div>
                    <div v-if="place.lokasi"><dt class="text-[10px] font-bold uppercase tracking-wide text-blue-100/75">Lokasi Area</dt><dd class="mt-1 text-xs font-semibold">{{ place.lokasi }}</dd></div>
                    <div v-if="place.jenis_menu"><dt class="text-[10px] font-bold uppercase tracking-wide text-blue-100/75">Menu Andalan</dt><dd class="mt-1 text-xs font-semibold">{{ place.jenis_menu }}</dd></div>
                </dl>

                <div v-if="place.menu_highlights?.length" class="mt-5">
                    <h3 class="text-xs font-bold">Menu Highlight</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span v-for="menu in place.menu_highlights" :key="menu" class="rounded-full border border-[#ff9d42] px-3 py-1.5 text-[10px] font-semibold text-[#ffd0a2]">{{ menu }}</span>
                    </div>
                </div>

            </div>
        </div>
    </article>
</template>

<style scoped>
.dining-gallery-nav {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, .75);
    border-radius: 999px;
    color: #0754a0;
    background: #fff;
    font-size: 24px;
    line-height: 1;
    transition: background-color .18s ease, transform .18s ease;
}
.dining-gallery-nav:hover:not(:disabled) { background: #fff1e3; transform: scale(1.04); }
.dining-gallery-nav:disabled { cursor: default; opacity: .45; }
@media (max-width: 639px) {
    .dining-panel { border-radius: 20px; }
    .dining-gallery-nav { width: 34px; height: 34px; }
}
</style>
