<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineProps({
    user: {
        type: Object,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
});

const page = usePage();
const deleteTarget = ref(null);
const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);
const dateLabel = (item) => new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'long',
    timeStyle: 'short',
    timeZone: 'Asia/Jakarta',
}).format(new Date(item.tanggal_publish_iso || item.tanggal_publish));

const destroyItem = () => {
    if (!deleteTarget.value) return;

    router.delete(route('dashboard.cms.media.destroy', deleteTarget.value.id), {
        preserveScroll: true,
        onSuccess: () => { deleteTarget.value = null; },
    });
};
</script>

<template>
    <Head title="Media & Berita" />

    <InternalDashboardLayout :user="user" title="CMS / Media & Berita">
        <div class="mx-auto max-w-[1380px] px-4 py-5 sm:px-6 lg:px-7">
            <header class="mb-5 flex flex-col gap-4 border-b border-slate-200 pb-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#1769e0]">Content Management System</p>
                    <h2 class="mt-1 text-[22px] font-bold leading-tight text-[#172554]">Kelola Media &amp; Berita</h2>
                    <p class="mt-1 max-w-2xl text-xs leading-5 text-slate-500">Kelola berita yang ditampilkan pada Beranda dan halaman publik Media &amp; Berita.</p>
                </div>
                <Link :href="route('dashboard.cms.media.create')" class="inline-flex h-10 shrink-0 items-center justify-center rounded-lg bg-[#0756d8] px-4 text-sm font-bold text-white transition hover:bg-[#0648b5] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-300">+ Tambah Berita</Link>
            </header>

            <div v-if="successMessage" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">{{ successMessage }}</div>
            <div v-if="errorMessage" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">{{ errorMessage }}</div>

            <div v-if="items.length" class="space-y-3">
                <article v-for="item in items" :key="item.id" class="grid gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-[0_1px_2px_rgba(15,23,42,0.04)] sm:grid-cols-[168px_minmax(0,1fr)_auto] sm:items-center">
                    <img :src="item.foto_url" :alt="item.judul" class="aspect-[3/2] h-auto w-full rounded-lg border border-slate-200 object-cover sm:h-28 sm:w-[168px]" />
                    <div class="min-w-0 self-start sm:self-center">
                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#1769e0]">{{ dateLabel(item) }}</p>
                        <h3 class="mt-1 text-base font-bold leading-6 text-[#172554]">{{ item.judul }}</h3>
                        <p class="mt-1.5 line-clamp-3 text-sm leading-5 text-slate-500">{{ item.deskripsi }}</p>
                    </div>
                    <div class="flex items-center gap-2 sm:self-center">
                        <Link :href="route('dashboard.cms.media.edit', item.id)" class="inline-flex h-9 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 px-3 text-xs font-bold text-[#0756d8] transition hover:bg-blue-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200">Edit</Link>
                        <button type="button" class="inline-flex h-9 items-center justify-center rounded-lg border border-red-200 bg-white px-3 text-xs font-bold text-red-600 transition hover:bg-red-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-200" @click="deleteTarget = item">Hapus</button>
                    </div>
                </article>
            </div>

            <section v-else class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <h3 class="text-base font-bold text-[#172554]">Tidak ada Media &amp; Berita.</h3>
                <p class="mt-2 text-sm text-slate-500">Tambahkan berita pertama untuk ditampilkan pada website Kampoeng Radja.</p>
                <Link :href="route('dashboard.cms.media.create')" class="mt-5 inline-flex h-10 items-center justify-center rounded-lg bg-[#0756d8] px-4 text-sm font-bold text-white hover:bg-[#0648b5]">+ Tambah Berita</Link>
            </section>
        </div>

        <div v-if="deleteTarget" class="fixed inset-0 z-[70] grid place-items-center bg-slate-950/45 p-4" role="presentation" @click.self="deleteTarget = null">
            <section class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="delete-media-title">
                <h2 id="delete-media-title" class="text-lg font-bold text-[#172554]">Hapus Media &amp; Berita?</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Berita <strong>{{ deleteTarget.judul }}</strong> dan file fotonya akan dihapus dari website.</p>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" class="h-9 rounded-lg border border-slate-300 px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50" @click="deleteTarget = null">Batal</button>
                    <button type="button" class="h-9 rounded-lg bg-red-600 px-4 text-sm font-bold text-white hover:bg-red-700" @click="destroyItem">Hapus Berita</button>
                </div>
            </section>
        </div>
    </InternalDashboardLayout>
</template>
