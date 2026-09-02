<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import MediaBeritaForm from './Partials/MediaBeritaForm.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useConfirmation } from '@/composables/useConfirmation';

const props = defineProps({
    user: { type: Object, required: true },
    item: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    _method: 'patch',
    judul: props.item.judul,
    deskripsi: props.item.deskripsi,
    foto: null,
    tanggal_publish: props.item.tanggal_publish,
});
const { confirm } = useConfirmation();

const submit = async () => {
    const confirmed = await confirm({ type: 'edit', title: 'Edit Berita', message: 'Apakah Anda yakin ingin menyimpan perubahan Berita ini?', confirmText: 'Ya, Simpan' });
    if (!confirmed) return;
    form.post(route('dashboard.cms.media.update', props.item.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Media & Berita" />

    <InternalDashboardLayout :user="user" title="CMS / Media & Berita">
        <section class="mx-auto max-w-[980px] px-4 py-5 sm:px-6 lg:px-7">
            <header class="mb-5 border-b border-slate-200 pb-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#1769e0]">Media &amp; Berita</p>
                <h2 class="mt-1 text-[22px] font-bold leading-tight text-[#172554]">Edit Berita</h2>
                <p class="mt-1 text-xs leading-5 text-slate-500">Perbarui konten berita tanpa perlu mengganti foto jika visual lama masih digunakan.</p>
            </header>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-[0_1px_2px_rgba(15,23,42,0.04)] sm:p-6">
                    <MediaBeritaForm
                        :form="form"
                        :current-image-url="item.foto_url"
                        submit-label="Perbarui Berita"
                        @submit="submit"
                    >
                        <template #cancel>
                            <Link :href="route('dashboard.cms.media.index')" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</Link>
                        </template>
                    </MediaBeritaForm>
            </div>
        </section>
    </InternalDashboardLayout>
</template>
