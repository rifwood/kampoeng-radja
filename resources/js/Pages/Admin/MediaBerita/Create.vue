<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import MediaBeritaForm from './Partials/MediaBeritaForm.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useConfirmation } from '@/composables/useConfirmation';

defineProps({
    user: { type: Object, required: true },
});

const form = useForm({
    judul: '',
    deskripsi: '',
    foto: null,
    tanggal_publish: '',
});
const { confirm } = useConfirmation();

const submit = async () => {
    const confirmed = await confirm({ type: 'save', title: 'Simpan Berita', message: 'Apakah Anda yakin ingin menyimpan Berita ini?', confirmText: 'Ya, Simpan' });
    if (!confirmed) return;
    form.post(route('dashboard.cms.media.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Tambah Media & Berita" />

    <InternalDashboardLayout :user="user" title="CMS / Media & Berita">
        <section class="mx-auto max-w-[980px] px-4 py-5 sm:px-6 lg:px-7">
            <header class="mb-5 border-b border-slate-200 pb-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#1769e0]">Media &amp; Berita</p>
                <h2 class="mt-1 text-[22px] font-bold leading-tight text-[#172554]">Tambah Berita</h2>
                <p class="mt-1 text-xs leading-5 text-slate-500">Tambahkan konten berita baru untuk halaman publik Kampoeng Radja.</p>
            </header>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04)] sm:p-6">
                    <MediaBeritaForm :form="form" submit-label="Simpan Berita" @submit="submit">
                        <template #cancel>
                            <Link :href="route('dashboard.cms.media.index')" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</Link>
                        </template>
                    </MediaBeritaForm>
            </div>
        </section>
    </InternalDashboardLayout>
</template>
