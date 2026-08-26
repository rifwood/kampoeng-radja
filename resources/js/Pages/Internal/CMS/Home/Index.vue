<script setup>
import CmsSectionCard from '@/Components/Internal/CMS/Home/CmsSectionCard.vue';
import HeroManager from '@/Components/Internal/CMS/Home/HeroManager.vue';
import PromoManager from '@/Components/Internal/CMS/Home/PromoManager.vue';
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    user: { type: Object, required: true },
    hero: { type: Object, default: null },
    promotions: { type: Array, required: true },
    promoSummary: { type: Object, required: true },
});

const page = usePage();
const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
const placeholders = [
    { title: 'Hero', icon: 'HR', description: 'Konten utama pada bagian paling atas Beranda.' },
    { title: 'Informasi Panduan', icon: 'IP', description: 'Informasi singkat dan panduan untuk pengunjung.' },
    { title: 'Media & Berita', icon: 'MB', description: 'Pilihan berita yang ditampilkan pada Beranda.' },
    { title: 'Produk', icon: 'PR', description: 'Showcase paket, fasilitas, dan aktivitas Kampoeng Radja.' },
    { title: 'Wahana Unggulan', icon: 'WU', description: 'Pilihan wahana unggulan pada halaman utama.' },
    { title: 'Mitra', icon: 'MT', description: 'Logo mitra yang ditampilkan kepada pengunjung.' },
    { title: 'Map', icon: 'MP', description: 'Lokasi Kampoeng Radja pada peta Beranda.' },
];
</script>

<template>
    <Head title="Kelola Beranda" />
    <InternalDashboardLayout :user="user" title="CMS / Beranda">
        <div class="mx-auto max-w-[1380px] px-4 py-5 sm:px-6 lg:px-7">
            <div class="mb-5 border-b border-slate-200 pb-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#1769e0]">Content Management System</p>
                <h2 class="mt-1 text-[22px] font-bold leading-tight text-[#172554]">Kelola Beranda</h2>
                <p class="mt-1 max-w-2xl text-xs leading-5 text-slate-500">Kelola konten yang tampil pada halaman utama Kampoeng Radja.</p>
            </div>

            <div v-if="success" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">{{ success }}</div>
            <div v-if="error" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">{{ error }}</div>

            <div class="space-y-3">
                <CmsSectionCard :title="placeholders[0].title" :description="placeholders[0].description" :icon="placeholders[0].icon" :default-open="true">
                    <HeroManager :hero="hero" />
                </CmsSectionCard>
                <CmsSectionCard :title="placeholders[1].title" :description="placeholders[1].description" :icon="placeholders[1].icon"><p class="px-5 py-6 text-sm text-slate-500">Pengelolaan section ini akan dikembangkan pada tahap berikutnya.</p></CmsSectionCard>
                <CmsSectionCard :title="placeholders[2].title" :description="placeholders[2].description" :icon="placeholders[2].icon"><p class="px-5 py-6 text-sm text-slate-500">Pengelolaan section ini akan dikembangkan pada tahap berikutnya.</p></CmsSectionCard>

                <CmsSectionCard title="Promo" description="Kelola promo yang tampil pada carousel Beranda." icon="PM" :default-open="true">
                    <PromoManager :promotions="promotions" :summary="promoSummary" />
                </CmsSectionCard>

                <CmsSectionCard v-for="item in placeholders.slice(3)" :key="item.title" :title="item.title" :description="item.description" :icon="item.icon">
                    <p class="px-5 py-6 text-sm text-slate-500">Pengelolaan section ini akan dikembangkan pada tahap berikutnya.</p>
                </CmsSectionCard>
            </div>
        </div>
    </InternalDashboardLayout>
</template>
