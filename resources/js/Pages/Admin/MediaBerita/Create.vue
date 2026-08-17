<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MediaBeritaForm from './Partials/MediaBeritaForm.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    judul: '',
    deskripsi: '',
    foto: null,
    tanggal_publish: '',
});

const submit = () => {
    form.post(route('admin.media-berita.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Tambah Media & Berita" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold leading-tight text-gray-800">Tambah Media &amp; Berita</h1>
        </template>

        <section class="py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
                    <MediaBeritaForm :form="form" submit-label="Simpan Berita" @submit="submit">
                        <template #cancel>
                            <Link :href="route('admin.media-berita.index')" class="rounded-md px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Batal</Link>
                        </template>
                    </MediaBeritaForm>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
