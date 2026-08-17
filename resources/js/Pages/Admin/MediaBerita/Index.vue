<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    items: {
        type: Array,
        required: true,
    },
});

const page = usePage();
const successMessage = computed(() => page.props.flash?.success);
const dateLabel = (value) => new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short',
}).format(new Date(value));

const destroyItem = (item) => {
    if (! window.confirm(`Hapus berita “${item.judul}”? Foto terkait juga akan dihapus.`)) {
        return;
    }

    router.delete(route('admin.media-berita.destroy', item.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Media & Berita" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-xl font-semibold leading-tight text-gray-800">Media &amp; Berita</h1>
                <Link :href="route('admin.media-berita.create')" class="rounded-md bg-primary-blue px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Tambah Berita</Link>
            </div>
        </template>

        <section class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="successMessage" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                    {{ successMessage }}
                </div>

                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div v-if="items.length" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal Publish</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="item in items" :key="item.id">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <img :src="item.foto_url" :alt="item.judul" class="h-16 w-24 rounded-md object-cover" />
                                    </td>
                                    <td class="max-w-md px-6 py-4 font-medium text-gray-900">{{ item.judul }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ dateLabel(item.tanggal_publish) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <Link :href="route('admin.media-berita.edit', item.id)" class="font-semibold text-primary-blue hover:underline">Edit</Link>
                                        <button type="button" class="ml-4 font-semibold text-red-600 hover:underline" @click="destroyItem(item)">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="px-6 py-16 text-center">
                        <h2 class="text-lg font-semibold text-gray-900">Belum ada Media &amp; Berita</h2>
                        <p class="mt-2 text-sm text-gray-500">Tambahkan berita pertama untuk ditampilkan di website.</p>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
