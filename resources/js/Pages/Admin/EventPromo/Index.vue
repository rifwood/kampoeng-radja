<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useConfirmation } from '@/composables/useConfirmation';

defineProps({ items: { type: Array, required: true } });
const page = usePage();
const { confirm } = useConfirmation();
const successMessage = computed(() => page.props.flash?.success);
const destroyItem = async (item) => {
    const confirmed = await confirm({ type: 'delete', title: 'Hapus Promo', message: `Apakah Anda yakin ingin menghapus Promo “${item.judul}”?`, description: 'Poster terkait juga akan dihapus.', confirmText: 'Ya, Hapus' });
    if (!confirmed) return;
    router.delete(route('admin.event-promo.destroy', item.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Promo" />
    <AuthenticatedLayout>
        <template #header><div class="flex flex-wrap items-center justify-between gap-4"><h1 class="text-xl font-semibold leading-tight text-gray-800">Promo</h1><Link :href="route('admin.event-promo.create')" class="rounded-md bg-primary-blue px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Tambah Promo</Link></div></template>
        <section class="py-12"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div v-if="successMessage" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ successMessage }}</div>
            <div class="overflow-hidden rounded-xl bg-white shadow-sm"><div v-if="items.length" class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Poster</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Konten</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">WhatsApp</th><th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th></tr></thead>
                <tbody class="divide-y divide-gray-200 bg-white"><tr v-for="item in items" :key="item.id"><td class="px-6 py-4"><img :src="item.poster_url" :alt="item.judul" class="h-24 w-20 rounded-md object-cover" /></td><td class="max-w-md px-6 py-4"><p class="font-medium text-gray-900">{{ item.judul }}</p><p class="mt-1 text-sm text-gray-600">{{ item.deskripsi_singkat }}</p></td><td class="max-w-xs px-6 py-4 text-sm text-gray-600"><a v-if="item.link_wa_url" :href="item.link_wa_url" target="_blank" rel="noopener" class="break-all text-primary-blue hover:underline">{{ item.link_wa }}</a><span v-else>—</span></td><td class="whitespace-nowrap px-6 py-4 text-right text-sm"><Link :href="route('admin.event-promo.edit', item.id)" class="font-semibold text-primary-blue hover:underline">Edit</Link><button type="button" class="ml-4 font-semibold text-red-600 hover:underline" @click="destroyItem(item)">Hapus</button></td></tr></tbody>
            </table></div><div v-else class="px-6 py-16 text-center"><h2 class="text-lg font-semibold text-gray-900">Belum ada Promo</h2><p class="mt-2 text-sm text-gray-500">Tambahkan poster pertama untuk ditampilkan di Beranda.</p></div></div>
        </div></section>
    </AuthenticatedLayout>
</template>
