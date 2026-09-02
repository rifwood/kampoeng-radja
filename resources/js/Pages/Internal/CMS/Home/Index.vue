<script setup>
import CmsSectionCard from '@/Components/Internal/CMS/Home/CmsSectionCard.vue';
import HeroManager from '@/Components/Internal/CMS/Home/HeroManager.vue';
import MediaBeritaManager from '@/Components/Internal/CMS/Home/MediaBeritaManager.vue';
import PartnerManager from '@/Components/Internal/CMS/Home/PartnerManager.vue';
import PromoManager from '@/Components/Internal/CMS/Home/PromoManager.vue';
import ProductManager from '@/Components/Internal/CMS/Home/ProductManager.vue';
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    user: { type: Object, required: true },
    hero: { type: Object, default: null },
    newsItems: { type: Array, required: true },
    promotions: { type: Array, required: true },
    promoSummary: { type: Object, required: true },
    products: { type: Array, required: true },
    partners: { type: Array, required: true },
});

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


            <div class="space-y-3">
                <CmsSectionCard title="Hero" description="Konten utama pada bagian paling atas Beranda." icon="HR" :default-open="true">
                    <HeroManager :hero="hero" />
                </CmsSectionCard>
                <CmsSectionCard title="Media & Berita" description="Kelola berita terbaru yang tampil pada Beranda dan halaman publik." icon="MB">
                    <MediaBeritaManager :items="newsItems" />
                </CmsSectionCard>

                <CmsSectionCard title="Promo" description="Kelola promo yang tampil pada carousel Beranda." icon="PM" :default-open="true">
                    <PromoManager :promotions="promotions" :summary="promoSummary" />
                </CmsSectionCard>

                <CmsSectionCard title="Produk" description="Kelola showcase paket, fasilitas, dan aktivitas Kampoeng Radja." icon="PR">
                    <ProductManager :products="products" />
                </CmsSectionCard>
                <CmsSectionCard title="Mitra" description="Kelola logo Mitra yang ditampilkan pada Beranda." icon="MT">
                    <PartnerManager :partners="partners" />
                </CmsSectionCard>
            </div>
        </div>
    </InternalDashboardLayout>
</template>
