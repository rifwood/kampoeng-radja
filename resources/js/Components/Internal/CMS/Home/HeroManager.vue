<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    hero: { type: Object, default: null },
});

const videoPreview = ref(null);
const posterPreview = ref(null);
const videoInput = ref(null);
const posterInput = ref(null);

const form = useForm({
    video: null,
    poster: null,
    eyebrow: props.hero?.eyebrow ?? 'Selamat Datang di',
    judul: props.hero?.judul ?? 'Kampoeng Radja',
    tagline: props.hero?.tagline ?? 'Tempat Bermain, Belajar, dan Rekreasi untuk Semua',
    deskripsi: props.hero?.deskripsi ?? 'Nikmati beragam wahana seru, atraksi menarik, dan pengalaman berkesan bersama keluarga dan sahabat.',
    cta_primary_label: props.hero?.cta_primary_label ?? 'Jelajahi Wahana',
    cta_primary_url: props.hero?.cta_primary_url ?? '/wahana',
    cta_secondary_label: props.hero?.cta_secondary_label ?? 'Tentang Kami',
    cta_secondary_url: props.hero?.cta_secondary_url ?? '/tentang-kami',
});

const activeVideoUrl = computed(() => videoPreview.value ?? props.hero?.video_url ?? null);
const activePosterUrl = computed(() => posterPreview.value ?? props.hero?.poster_url ?? '/assets/temporary/hero-waterpark-v2.png');

const revokePreview = (preview) => {
    if (preview.value?.startsWith('blob:')) URL.revokeObjectURL(preview.value);
    preview.value = null;
};

const selectVideo = (event) => {
    revokePreview(videoPreview);
    form.video = event.target.files?.[0] ?? null;
    if (form.video) videoPreview.value = URL.createObjectURL(form.video);
};

const selectPoster = (event) => {
    revokePreview(posterPreview);
    form.poster = event.target.files?.[0] ?? null;
    if (form.poster) posterPreview.value = URL.createObjectURL(form.poster);
};

const submit = () => {
    form.transform((data) => ({ ...data, _method: 'patch' })).post(route('dashboard.cms.home.hero.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            revokePreview(videoPreview);
            revokePreview(posterPreview);
            form.video = null;
            form.poster = null;
            if (videoInput.value) videoInput.value.value = '';
            if (posterInput.value) posterInput.value.value = '';
        },
        onFinish: () => form.transform((data) => data),
    });
};

onBeforeUnmount(() => {
    revokePreview(videoPreview);
    revokePreview(posterPreview);
});
</script>

<template>
    <form class="space-y-6 p-4 sm:p-5" @submit.prevent="submit">
        <div>
            <h3 class="text-sm font-bold text-[#172554]">Hero Beranda</h3>
            <p class="mt-1 text-xs leading-5 text-slate-500">Kelola video, fallback visual, dan konten utama pada halaman Beranda.</p>
        </div>

        <div class="grid gap-5 xl:grid-cols-2">
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                <div class="border-b border-slate-200 bg-white px-4 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#1769e0]">Video Hero</p>
                    <p class="mt-1 text-xs text-slate-500">MP4 atau WebM, maksimal 30 MB.</p>
                </div>
                <div class="p-4">
                    <div class="aspect-video overflow-hidden rounded-lg border border-slate-200 bg-slate-900">
                        <video v-if="activeVideoUrl" :key="activeVideoUrl" :src="activeVideoUrl" :poster="activePosterUrl" controls preload="metadata" class="h-full w-full object-cover" />
                        <div v-else class="grid h-full place-items-center px-5 text-center text-xs leading-5 text-slate-300">Belum ada video. Poster akan digunakan sebagai fallback Hero.</div>
                    </div>
                    <label class="mt-3 inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border border-blue-200 bg-white px-4 text-xs font-bold text-[#0756d8] transition hover:bg-blue-50 focus-within:ring-2 focus-within:ring-blue-200">
                        {{ activeVideoUrl ? 'Ganti Video' : 'Pilih Video' }}
                        <input ref="videoInput" class="sr-only" type="file" accept="video/mp4,video/webm" @change="selectVideo" />
                    </label>
                    <p v-if="form.video" class="mt-2 truncate text-xs text-slate-500">Dipilih: {{ form.video.name }}</p>
                    <p v-if="form.errors.video" class="mt-2 text-xs font-medium text-red-600">{{ form.errors.video }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                <div class="border-b border-slate-200 bg-white px-4 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[#1769e0]">Poster / Fallback</p>
                    <p class="mt-1 text-xs text-slate-500">JPG, JPEG, PNG, atau WebP, maksimal 5 MB.</p>
                </div>
                <div class="p-4">
                    <div class="aspect-video overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                        <img :src="activePosterUrl" alt="Preview poster Hero Beranda" class="h-full w-full object-cover" />
                    </div>
                    <label class="mt-3 inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border border-blue-200 bg-white px-4 text-xs font-bold text-[#0756d8] transition hover:bg-blue-50 focus-within:ring-2 focus-within:ring-blue-200">
                        {{ props.hero?.poster_url || form.poster ? 'Ganti Poster' : 'Pilih Poster' }}
                        <input ref="posterInput" class="sr-only" type="file" accept="image/jpeg,image/png,image/webp" @change="selectPoster" />
                    </label>
                    <p v-if="form.poster" class="mt-2 truncate text-xs text-slate-500">Dipilih: {{ form.poster.name }}</p>
                    <p v-if="form.errors.poster" class="mt-2 text-xs font-medium text-red-600">{{ form.errors.poster }}</p>
                </div>
            </section>
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
            <div class="grid gap-4 lg:grid-cols-2">
                <label class="block lg:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">Eyebrow / Teks Kecil</span>
                    <input v-model="form.eyebrow" type="text" maxlength="100" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.eyebrow" class="mt-1 block text-xs text-red-600">{{ form.errors.eyebrow }}</span>
                </label>

                <label class="block lg:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">Judul Utama <span class="text-red-500">*</span></span>
                    <input v-model="form.judul" type="text" maxlength="150" required class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.judul" class="mt-1 block text-xs text-red-600">{{ form.errors.judul }}</span>
                </label>

                <label class="block lg:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">Tagline</span>
                    <input v-model="form.tagline" type="text" maxlength="255" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.tagline" class="mt-1 block text-xs text-red-600">{{ form.errors.tagline }}</span>
                </label>

                <label class="block lg:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">Deskripsi Singkat</span>
                    <textarea v-model="form.deskripsi" rows="4" maxlength="2000" class="mt-1.5 w-full rounded-lg border-slate-300 text-sm leading-6 text-slate-800 focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.deskripsi" class="mt-1 block text-xs text-red-600">{{ form.errors.deskripsi }}</span>
                </label>
            </div>
        </section>

        <div class="grid gap-5 lg:grid-cols-2">
            <fieldset class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <legend class="px-1 text-xs font-bold text-[#172554]">CTA Utama</legend>
                <label class="mt-2 block">
                    <span class="text-xs font-semibold text-slate-700">Label</span>
                    <input v-model="form.cta_primary_label" type="text" maxlength="100" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.cta_primary_label" class="mt-1 block text-xs text-red-600">{{ form.errors.cta_primary_label }}</span>
                </label>
                <label class="mt-3 block">
                    <span class="text-xs font-semibold text-slate-700">Link</span>
                    <input v-model="form.cta_primary_url" type="text" placeholder="/wahana" maxlength="2048" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.cta_primary_url" class="mt-1 block text-xs text-red-600">{{ form.errors.cta_primary_url }}</span>
                </label>
            </fieldset>

            <fieldset class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <legend class="px-1 text-xs font-bold text-[#172554]">CTA Sekunder</legend>
                <label class="mt-2 block">
                    <span class="text-xs font-semibold text-slate-700">Label</span>
                    <input v-model="form.cta_secondary_label" type="text" maxlength="100" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.cta_secondary_label" class="mt-1 block text-xs text-red-600">{{ form.errors.cta_secondary_label }}</span>
                </label>
                <label class="mt-3 block">
                    <span class="text-xs font-semibold text-slate-700">Link</span>
                    <input v-model="form.cta_secondary_url" type="text" placeholder="/tentang-kami" maxlength="2048" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" />
                    <span v-if="form.errors.cta_secondary_url" class="mt-1 block text-xs text-red-600">{{ form.errors.cta_secondary_url }}</span>
                </label>
            </fieldset>
        </div>

        <div class="flex justify-end border-t border-slate-200 pt-5">
            <button type="submit" :disabled="form.processing" class="inline-flex h-10 items-center justify-center rounded-lg bg-[#0756d8] px-5 text-sm font-bold text-white transition hover:bg-[#0648b5] disabled:cursor-not-allowed disabled:opacity-60">
                {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
        </div>
    </form>
</template>
