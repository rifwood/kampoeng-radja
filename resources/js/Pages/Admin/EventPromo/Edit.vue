<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EventPromoForm from './Partials/EventPromoForm.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ item: { type: Object, required: true } });
const form = useForm({ _method: 'patch', judul: props.item.judul, deskripsi_singkat: props.item.deskripsi_singkat, poster: null, link_wa: props.item.link_wa || '' });
const submit = () => form.post(route('admin.event-promo.update', props.item.id), { forceFormData: true });
</script>

<template>
    <Head title="Edit Event & Promotion" />
    <AuthenticatedLayout>
        <template #header><h1 class="text-xl font-semibold leading-tight text-gray-800">Edit Event &amp; Promotion</h1></template>
        <section class="py-12"><div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8"><div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
            <EventPromoForm :form="form" :current-poster-url="item.poster_url" submit-label="Perbarui Event/Promotion" @submit="submit"><template #cancel><Link :href="route('admin.event-promo.index')" class="rounded-md px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Batal</Link></template></EventPromoForm>
        </div></div></section>
    </AuthenticatedLayout>
</template>
