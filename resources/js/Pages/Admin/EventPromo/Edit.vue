<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EventPromoForm from './Partials/EventPromoForm.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useConfirmation } from '@/composables/useConfirmation';

const props = defineProps({ item: { type: Object, required: true } });
const form = useForm({ _method: 'patch', judul: props.item.judul, deskripsi_singkat: props.item.deskripsi_singkat, deskripsi_lengkap: props.item.deskripsi_lengkap || '', poster: null, tanggal_mulai: props.item.tanggal_mulai || '', tanggal_selesai: props.item.tanggal_selesai || '', link_wa: props.item.link_wa || '', is_active: props.item.is_active, urutan_tampil: props.item.urutan_tampil });
const { confirm } = useConfirmation();
const submit = async () => { const ok = await confirm({ type: 'edit', title: 'Edit Promo', message: 'Apakah Anda yakin ingin menyimpan perubahan Promo ini?', confirmText: 'Ya, Simpan' }); if (ok) form.post(route('admin.event-promo.update', props.item.id), { forceFormData: true }); };
</script>

<template>
    <Head title="Edit Promo" />
    <AuthenticatedLayout>
        <template #header><h1 class="text-xl font-semibold leading-tight text-gray-800">Edit Promo</h1></template>
        <section class="py-12"><div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8"><div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
            <EventPromoForm :form="form" :current-poster-url="item.poster_url" submit-label="Perbarui Promo" @submit="submit"><template #cancel><Link :href="route('admin.event-promo.index')" class="rounded-md px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Batal</Link></template></EventPromoForm>
        </div></div></section>
    </AuthenticatedLayout>
</template>
