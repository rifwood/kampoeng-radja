<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    articles: {
        type: Array,
        default: () => [],
    },
    canLogin: Boolean,
});

const search = ref('');

const fallbackArticles = [
    { id: 'fallback-1', title: '[FIGMA SEMENTARA] Festival Budaya Nusantara', description: 'Konten sementara ditampilkan sampai Media & Berita tersedia dari CMS.', tanggal_publish: '2024-10-12T00:00:00+07:00', foto_url: '/assets/figma/figma-news-1.png' },
    { id: 'fallback-2', title: '[FIGMA SEMENTARA] Informasi Kampoeng Radja', description: 'Konten sementara ditampilkan sampai Media & Berita tersedia dari CMS.', tanggal_publish: '2024-10-05T00:00:00+07:00', foto_url: '/assets/figma/figma-news-2.png' },
    { id: 'fallback-3', title: '[FIGMA SEMENTARA] Kabar Terbaru Kampoeng Radja', description: 'Konten sementara ditampilkan sampai Media & Berita tersedia dari CMS.', tanggal_publish: '2024-09-28T00:00:00+07:00', foto_url: '/assets/figma/figma-news-3.png' },
];

const displayedArticles = computed(() => props.articles.length ? props.articles : fallbackArticles);
const featuredArticle = computed(() => displayedArticles.value[0]);
const filteredArticles = computed(() => displayedArticles.value.filter((article) => {
    const keyword = search.value.trim().toLowerCase();
    return `${article.title} ${article.description}`.toLowerCase().includes(keyword);
}));
const formatDate = (value) => new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' }).format(new Date(value));
</script>

<template>
    <Head title="Media & Berita" />
    <div class="min-h-screen bg-[#f8f9fa] font-body text-[#191c1d] antialiased">
        <nav class="sticky top-0 z-50 w-full bg-white/90 shadow-sm backdrop-blur-md">
            <div class="mx-auto flex max-w-[1280px] items-center justify-between px-6 py-4 lg:px-12">
                <Link :href="route('home')" class="font-heading text-xl font-extrabold text-[#003f87]">Wisata Kampoeng Radja</Link>
                <div class="hidden items-center gap-8 md:flex">
                    <Link :href="route('home')" class="text-sm font-medium text-[#424752] transition-colors hover:text-[#003f87]">Beranda</Link>
                    <a :href="`${route('home')}#tentang`" class="text-sm font-medium text-[#424752] transition-colors hover:text-[#003f87]">Tentang Kami</a>
                    <a :href="`${route('home')}#galeri`" class="text-sm font-medium text-[#424752] transition-colors hover:text-[#003f87]">Galeri</a>
                    <Link :href="route('berita')" class="border-b-2 border-[#003f87] pb-1 text-sm font-bold text-[#003f87]">Media &amp; Berita</Link>
                    <a :href="`${route('home')}#kontak`" class="text-sm font-medium text-[#424752] transition-colors hover:text-[#003f87]">Kontak &amp; Lokasi</a>
                </div>
                <a :href="canLogin ? route('login') : '#'" class="flex items-center gap-2 rounded-full bg-[#fd8b00] px-5 py-2 text-sm font-bold text-[#603100] transition-transform active:scale-95">Login <span class="material-symbols-outlined text-lg">login</span></a>
            </div>
        </nav>

        <main>
            <section class="relative overflow-hidden bg-[#e9f0ff] px-6 py-16 md:py-20">
                <div class="absolute -right-20 -top-28 h-80 w-80 rounded-full border-[32px] border-[#acc7ff]/50"></div>
                <div class="absolute -bottom-28 left-[8%] h-56 w-56 rounded-full bg-[#ffd9df]/60"></div>
                <div class="relative mx-auto max-w-[860px] text-center">
                    <span class="mb-3 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#0056b3] shadow-sm"><span class="material-symbols-outlined text-base">campaign</span> Tetap Terhubung</span>
                    <h1 class="font-heading text-4xl font-extrabold leading-tight text-[#003f87] md:text-5xl">Media &amp; Berita</h1>
                    <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-[#424752] md:text-lg">Temukan kabar terbaru, keseruan event, dan informasi pilihan dari Wisata Kampoeng Radja.</p>
                </div>
            </section>

            <section class="mx-auto max-w-[1280px] px-6 py-12 lg:px-12 lg:py-16">
                <article class="grid overflow-hidden rounded-3xl bg-white shadow-lg shadow-[#003f87]/10 lg:grid-cols-2">
                    <div class="relative min-h-[280px] overflow-hidden lg:min-h-[400px]"><img :src="featuredArticle.foto_url" :alt="featuredArticle.title" class="h-full w-full object-cover" /><div class="absolute inset-0 bg-gradient-to-t from-[#003f87]/40 to-transparent"></div><span class="absolute left-6 top-6 rounded-full bg-[#003f87] px-4 py-1.5 text-xs font-bold text-white">Berita Terbaru</span></div>
                    <div class="flex flex-col justify-center p-7 md:p-10"><p class="mb-4 flex items-center gap-2 text-sm font-bold text-[#727784]"><span class="material-symbols-outlined text-lg">calendar_month</span>{{ formatDate(featuredArticle.tanggal_publish) }}</p><h2 class="font-heading text-3xl font-extrabold leading-tight text-[#003f87]">{{ featuredArticle.title }}</h2><p class="mt-5 leading-relaxed text-[#424752]">{{ featuredArticle.description }}</p><a href="#daftar-berita" class="mt-7 flex w-fit items-center gap-2 rounded-xl bg-[#003f87] px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-[#0056b3]">Lihat Berita Lainnya <span class="material-symbols-outlined text-lg">arrow_forward</span></a></div>
                </article>

                <div id="daftar-berita" class="mt-16 scroll-mt-24">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div><span class="text-xs font-bold uppercase tracking-[0.16em] text-[#fd8b00]">Kabar Terbaru</span><h2 class="mt-2 font-heading text-3xl font-extrabold text-[#003f87]">Jelajahi Berita Kami</h2></div>
                        <label class="relative block w-full lg:w-80"><span class="sr-only">Cari berita</span><span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#727784]">search</span><input v-model="search" type="search" placeholder="Cari berita..." class="w-full rounded-xl border border-[#c2c6d4] bg-white py-3 pl-11 pr-4 text-sm outline-none transition focus:border-[#0056b3] focus:ring-2 focus:ring-[#acc7ff]" /></label>
                    </div>
                    <div v-if="filteredArticles.length" class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3"><article v-for="article in filteredArticles" :key="article.id" class="group flex flex-col overflow-hidden rounded-2xl border border-[#c2c6d4]/70 bg-white transition-all hover:-translate-y-1 hover:shadow-xl"><div class="relative h-52 overflow-hidden"><img :src="article.foto_url" :alt="article.title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" /><span class="absolute left-4 top-4 rounded-full bg-[#003f87] px-3 py-1 text-xs font-bold text-white">Berita</span></div><div class="flex flex-1 flex-col p-6"><p class="flex items-center gap-1.5 text-xs font-bold text-[#727784]"><span class="material-symbols-outlined text-base">calendar_month</span>{{ formatDate(article.tanggal_publish) }}</p><h3 class="mt-3 font-heading text-xl font-bold leading-snug text-[#191c1d] transition-colors group-hover:text-[#003f87]">{{ article.title }}</h3><p class="mt-3 flex-1 text-sm leading-relaxed text-[#424752]">{{ article.description }}</p></div></article></div>
                    <div v-else class="mt-9 rounded-2xl border border-dashed border-[#c2c6d4] bg-white px-6 py-16 text-center"><span class="material-symbols-outlined text-4xl text-[#727784]">search_off</span><p class="mt-3 font-heading text-lg font-bold text-[#191c1d]">Berita tidak ditemukan</p><button type="button" class="mt-4 text-sm font-bold text-[#003f87]" @click="search = ''">Tampilkan semua berita</button></div>
                </div>
            </section>
        </main>

        <footer class="mt-8 bg-[#003f87] px-6 pb-6 pt-14 text-white lg:px-12">
            <div class="mx-auto grid max-w-[1280px] gap-10 md:grid-cols-2 lg:grid-cols-4"><div><h2 class="font-heading text-xl font-bold">Wisata Kampoeng Radja</h2><p class="mt-4 text-sm leading-relaxed text-white/80">Destinasi wisata keluarga dengan beragam wahana seru dan pengalaman tak terlupakan.</p></div><div><h3 class="font-bold">Menu Utama</h3><div class="mt-4 space-y-3 text-sm text-white/80"><Link :href="route('home')" class="block hover:text-white">Beranda</Link><a :href="`${route('home')}#tentang`" class="block hover:text-white">Tentang Kami</a><a :href="`${route('home')}#galeri`" class="block hover:text-white">Galeri</a></div></div><div><h3 class="font-bold">Informasi</h3><div class="mt-4 space-y-3 text-sm text-white/80"><a href="#" class="block hover:text-white">Harga Tiket</a><a href="#" class="block hover:text-white">Jam Operasional</a><a href="#" class="block hover:text-white">FAQ</a></div></div><div><h3 class="font-bold">Kontak Kami</h3><div class="mt-4 space-y-3 text-sm leading-relaxed text-white/80"><p class="flex gap-2"><span class="material-symbols-outlined text-lg">location_on</span>Jl. Kampoeng Radja No. 1, Desa Wisata</p><p class="flex gap-2"><span class="material-symbols-outlined text-lg">phone</span>(021) 1234 5678</p><p class="flex gap-2"><span class="material-symbols-outlined text-lg">mail</span>info@kampoengradja.com</p></div></div></div>
            <div class="mx-auto mt-10 flex max-w-[1280px] flex-col gap-3 border-t border-white/20 pt-6 text-center text-sm text-white/70 md:flex-row md:justify-between md:text-left"><p>© 2024 Wisata Kampoeng Radja. All Rights Reserved.</p><div class="flex justify-center gap-4"><a href="#">Kebijakan Privasi</a><a href="#">Syarat &amp; Ketentuan</a></div></div>
        </footer>
    </div>
</template>
