<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import PublicLayout from '../Layouts/PublicLayout.vue';
import LazyImage from '../Components/Base/LazyImage.vue';

const props = defineProps({ news: Array, promotions: Array, partners: Array, featuredRides: Array });
const expandedNews = ref(null);
const temporaryNews = [
  { foto_url: '/assets/figma/figma-news-1.png', category: 'Baru', categoryClass: 'bg-[#f59e0b]', title: 'Sensasi Terbang dengan Flying Fox Terbaru Kami!', excerpt: 'Uji adrenalin Anda dengan wahana Flying Fox terpanjang di area ini. Menawarkan pemandangan menakjubkan dari ketinggian.', detail: 'Nikmati pengalaman meluncur di atas area rekreasi keluarga dengan pendampingan petugas. [FIGMA SEMENTARA: Detail lengkap menunggu konten resmi.]' },
  { foto_url: '/assets/figma/figma-news-2.png', category: 'Keluarga', categoryClass: 'bg-[#0052a5]', title: 'Tips Liburan Seru Bersama Keluarga di Akhir Pekan', excerpt: 'Temukan panduan lengkap untuk memaksimalkan kunjungan Anda bersama keluarga. Mulai dari rute wahana terbaik…', detail: 'Rencanakan kunjungan keluarga secara nyaman dengan memilih wahana sesuai usia dan kebutuhan. [FIGMA SEMENTARA: Detail lengkap menunggu konten resmi.]' },
  { foto_url: '/assets/figma/figma-news-3.png', category: 'Corporate', categoryClass: 'bg-[#15803d]', title: 'Paket Team Building untuk Perusahaan Kini Tersedia', excerpt: 'Tingkatkan kerjasama tim perusahaan Anda dengan paket acara khusus kami. Fasilitas lengkap, instruktur profesional, dan suasana…', detail: 'Paket team building dapat disesuaikan untuk kebutuhan organisasi. [FIGMA SEMENTARA: Detail lengkap menunggu konten resmi.]' },
];
const newsCards = computed(() => props.news?.length ? props.news.map((item) => ({
  foto_url: item.foto_url,
  category: 'Berita',
  categoryClass: 'bg-[#0052a5]',
  title: item.title,
  excerpt: item.description.length > 150 ? `${item.description.slice(0, 147)}...` : item.description,
  detail: item.description,
})) : temporaryNews);
const promoTrack = ref(null);
const promoBanners = [
  { poster_url: '/assets/promotions/promo-honda-agustus-2026.png', title: 'Promo Honda Kampoeng Radja — Agustus 2026', description: '[FIGMA SEMENTARA: Detail promo menunggu konten CMS.]', link_wa: 'https://wa.me/6281289909180' },
  { poster_url: '/assets/promotions/promo-ulang-tahun-agustus-2026.png', title: 'Promo Spesial Lahir Bulan Agustus 2026', description: '[FIGMA SEMENTARA: Detail promo menunggu konten CMS.]', link_wa: 'https://wa.me/6281289909180' },
];
const promoCards = computed(() => props.promotions?.length ? props.promotions : promoBanners);
const loopedPromoCards = computed(() => [...promoCards.value, ...promoCards.value, ...promoCards.value]);
const featuredFallback = [
  { title: 'Waterpark', description: 'Kolam renang luas dengan berbagai jenis perosotan seru untuk anak-anak dan dewasa.', image: '/assets/temporary/hero-waterpark-v2.png', label: 'Keluarga', tone: 'bg-[#fce7f3] text-[#be185d]' },
  { title: 'Flying Fox', description: 'Rasakan sensasi meluncur dari ketinggian melintasi area taman yang hijau dan asri.', image: '/assets/figma/figma-news-1.png', label: 'Adrenalin', tone: 'bg-[#fee2e2] text-[#b91c1c]' },
  { title: 'Go Kart', description: 'Pacu kecepatanmu di sirkuit mini yang dirancang aman untuk pengalaman balapan yang seru.', image: '/assets/figma/figma-news-3.png', label: 'Populer', tone: 'bg-[#fef9c3] text-[#854d0e]' },
];
const featuredCards = computed(() => props.featuredRides?.length ? props.featuredRides.map((ride) => ({ title: ride.title || '[PERLU KONTEN RESMI: Wahana]', description: ride.description || '[PERLU KONTEN RESMI: Deskripsi wahana]', image: ride.photo_path, label: ride.labels?.[0]?.name || 'Wahana', tone: 'bg-[#e0f2fe] text-[#0369a1]' })) : featuredFallback);
const movePromo = (direction) => {
  const track = promoTrack.value;
  if (!track) return;
  const cardWidth = Math.min(360, track.clientWidth * 0.78) + 20;
  track.scrollBy({ left: direction * cardWidth, behavior: 'smooth' });
  window.setTimeout(() => {
    if (track.scrollLeft <= 2 && direction < 0) track.scrollTo({ left: track.scrollWidth / 2, behavior: 'instant' });
    if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2 && direction > 0) track.scrollTo({ left: track.clientWidth, behavior: 'instant' });
  }, 350);
};
onMounted(() => { if (promoTrack.value) promoTrack.value.scrollLeft = promoTrack.value.clientWidth; });
</script>

<template>
  <Head title="Kampoeng Radja"><meta name="description" content="[PLACEHOLDER: Deskripsi resmi Kampoeng Radja]" /></Head>
  <PublicLayout>
    <section class="home-hero relative z-10 flex h-[720px] items-center overflow-visible bg-[#304755]">
      <img src="/assets/temporary/hero-waterpark-v2.png" alt="[FIGMA SEMENTARA] Area waterpark Kampoeng Radja" class="absolute inset-0 h-full w-full object-cover" /><div class="absolute inset-0 bg-black/40"></div>
      <div class="relative mx-auto w-full max-w-[1120px] px-5 text-white lg:px-0"></div>
      <div class="absolute -bottom-12 left-1/2 grid h-[90px] w-[min(90%,1152px)] -translate-x-1/2 grid-cols-2 items-center rounded-xl border border-[#c2c6d4]/30 bg-[#f8f9fa] p-5 shadow-[0_10px_15px_-3px_rgba(0,0,0,.1),0_4px_6px_-4px_rgba(0,0,0,.1)] lg:grid-cols-4"><div v-for="(item,index) in [{value:'20+',label:'Wahana Seru',icon:'⌖',tone:'bg-[#d7e2ff] text-[#003f87]'}, {value:'100K+',label:'Pengunjung/Tahun',icon:'♟',tone:'bg-[#ffdcc3] text-[#904d00]'}, {value:'15+',label:'Tahun Pengalaman',icon:'✦',tone:'bg-[#ffd9df] text-[#86003a]'}, {value:'Keluarga',label:'Ramah & Edukatif',icon:'♟',tone:'bg-[#dcfce7] text-[#166534]'}]" :key="item.value" :class="index < 3 ? 'lg:border-r' : ''" class="flex items-center gap-4 border-[#c2c6d4]/50 px-3 first:pl-0 last:pr-0"><span :class="item.tone" class="grid h-10 w-10 place-items-center rounded-full text-sm font-bold" aria-hidden="true">{{ item.icon }}</span><span><strong class="block font-heading text-2xl leading-[30px] text-[#003f87]">{{ item.value }}</strong><small class="block text-xs leading-[18px] text-[#424752]">{{ item.label }}</small></span></div></div>
    </section>
    <section class="bg-[#f8f9fa] px-5 pb-20 pt-32 lg:px-0"><div class="mx-auto max-w-[1120px]"><h2 class="font-heading text-[28px] font-bold text-[#062a59]">Media & Berita</h2><p class="mt-2 text-sm text-[#4b5563]">Informasi terbaru seputar Kampoeng Radja.</p><div class="mt-5 grid items-start gap-5 md:grid-cols-3"><article v-for="(newsItem,index) in newsCards" :key="newsItem.title" class="overflow-hidden rounded-xl border border-[#e1e2eb] bg-white"><div class="relative"><LazyImage :src="newsItem.foto_url" :alt="newsItem.title" class-name="h-[198px] w-full object-cover" /><span :class="newsItem.categoryClass" class="absolute left-3 top-3 rounded-full px-3 py-1 text-[10px] text-white">{{ newsItem.category }}</span></div><div class="p-[18px]"><h3 class="font-heading text-xl font-bold leading-6 text-[#062a59]">{{ newsItem.title }}</h3><p class="mt-3 text-sm leading-5 text-[#4b5563]">{{ newsItem.excerpt }}</p><p v-if="expandedNews === index" class="mt-4 border-t border-[#e1e2eb] pt-4 text-sm leading-6 text-[#4b5563]">{{ newsItem.detail }}</p><button type="button" class="mt-4 h-9 w-full rounded-md border border-[#0063c7] text-xs font-medium text-[#0063c7]" :aria-expanded="expandedNews === index" @click="expandedNews = expandedNews === index ? null : index">{{ expandedNews === index ? 'Tutup Detail' : 'Lihat Detail' }}</button></div></article></div></div></section>
    <section class="overflow-hidden bg-[#003f87] px-5 py-16 text-white lg:px-0"><div class="mx-auto max-w-[1120px]"><h2 class="mt-2 text-center font-heading text-4xl font-extrabold lg:text-[48px]">Event Dan Promo</h2><div class="relative mt-10"><button type="button" aria-label="Banner sebelumnya" class="absolute left-0 top-1/2 z-10 grid h-10 w-10 -translate-x-3 -translate-y-1/2 place-items-center rounded-full bg-white text-[#003f87] shadow" @click="movePromo(-1)">‹</button><div ref="promoTrack" class="hide-scrollbar flex snap-x snap-mandatory items-stretch gap-5 overflow-x-auto scroll-smooth pb-3"><article v-for="(promo,index) in loopedPromoCards" :key="`${promo.id || promo.title}-${index}`" class="flex w-[min(78vw,360px)] shrink-0 snap-center flex-col overflow-hidden rounded-2xl bg-white p-3 text-[#062a59] shadow-lg"><img :src="promo.poster_url" :alt="promo.title" class="aspect-[4/5] w-full rounded-xl object-cover" /><p class="pt-3 text-sm font-semibold leading-[22px]">{{ promo.title }}</p><p class="min-h-[42px] flex-1 py-2 text-xs leading-5 text-[#4b5563]">{{ promo.description }}</p><a v-if="promo.link_wa" :href="promo.link_wa" target="_blank" rel="noopener" :aria-label="`Lihat detail ${promo.title} melalui WhatsApp`" class="flex h-10 w-full items-center justify-center gap-2 rounded-lg bg-[#0063c7] text-center text-sm font-semibold text-white">Lihat Detail <span aria-hidden="true">→</span></a><span v-else aria-disabled="true" class="flex h-10 w-full cursor-not-allowed items-center justify-center rounded-lg bg-[#cbd5e1] text-center text-sm font-semibold text-[#475569]">Detail belum tersedia</span></article></div><button type="button" aria-label="Banner berikutnya" class="absolute right-0 top-1/2 z-10 grid h-10 w-10 translate-x-3 -translate-y-1/2 place-items-center rounded-full bg-white text-[#003f87] shadow" @click="movePromo(1)">›</button></div></div></section>
    <section class="bg-[#f1f3f5] px-5 py-20 lg:px-0"><div class="mx-auto max-w-[1120px]"><div class="flex flex-wrap items-end justify-between gap-5"><div><h2 class="font-heading text-[28px] font-bold text-[#191c1e]">Wahana Unggulan</h2><p class="mt-2 text-sm text-[#434655]">Jelajahi berbagai wahana seru yang siap menguji adrenalin dan memberikan keceriaan.</p></div><Link :href="route('wahana')" class="text-sm font-bold text-[#005cc8]">Lihat Semua Wahana →</Link></div><div class="mt-8 grid gap-6 md:grid-cols-3"><article v-for="ride in featuredCards" :key="ride.title" class="overflow-hidden rounded-[24px] border border-[#e0e3e5] bg-white shadow-[0_1px_2px_rgba(0,0,0,.05)]"><div class="relative"><LazyImage :src="ride.image" :alt="ride.title" class-name="h-[137px] w-full object-cover" /><span :class="ride.tone" class="absolute left-3 top-3 rounded-full px-3 py-1 text-[10px] font-bold">{{ ride.label }}</span></div><div class="p-[18px]"><h3 class="font-heading text-lg font-bold text-[#191c1e]">{{ ride.title }}</h3><p class="mt-2 min-h-10 text-xs leading-4 text-[#434655]">{{ ride.description }}</p><Link :href="route('wahana')" class="mt-4 block w-full rounded-md border border-[#0063c7] py-2 text-center text-xs font-medium text-[#0063c7]">Detail Wahana</Link></div></article></div></div></section>
    <section class="overflow-hidden border-t-[3px] border-[#1685ed] bg-[#dedede] px-5 py-10 lg:px-0"><div class="mx-auto max-w-[1120px]"><h2 class="text-center font-heading text-xl font-bold text-[#191c1e]">Sponsorship</h2><div class="mt-8 overflow-hidden"><div class="flex w-max items-center gap-16 motion-reduce:animate-none animate-marquee"><div v-for="(item,index) in [...(partners?.length ? partners : [{name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}]), ...(partners?.length ? partners : [{name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}])]" :key="`${item.id || item.name}-${index}`" class="grid h-12 w-28 shrink-0 place-items-center"><LazyImage v-if="item.logo" :src="item.logo" :alt="item.name" class-name="max-h-10 max-w-24 object-contain" /><span v-else class="text-center text-xs font-semibold text-[#5e6470]">{{ item.name }}</span></div></div></div></div></section>
    <section class="bg-[#f9f9ff] px-5 py-[120px] lg:px-0"><div class="mx-auto h-[450px] max-w-[1120px] overflow-hidden rounded-2xl border-2 border-[#e1e2eb] shadow-[0_1px_2px_rgba(0,0,0,.05)]"><iframe title="Lokasi Kampoeng Radja" class="h-[446px] w-full border-0" src="https://www.google.com/maps?q=kampoeng+radja&amp;z=14&amp;t=m&amp;hl=en&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div></section>
  </PublicLayout>
</template>

<style scoped>
/* Stakeholder refresh: fun blue-orange treatment without decorative assets. */
.home-hero > div.absolute.inset-0 {
  background: linear-gradient(110deg, rgba(2, 43, 91, .68) 0%, rgba(0, 91, 181, .34) 48%, rgba(255, 126, 30, .18) 100%);
}

.home-hero > div:last-child {
  overflow: hidden;
  border: 2px solid rgba(255, 255, 255, .95);
  background: linear-gradient(110deg, #ffffff 0%, #f2f8ff 62%, #fff3e7 100%);
  box-shadow: 0 18px 42px rgba(1, 55, 112, .2);
}

.home-hero > div:last-child::before {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  content: '';
  background: linear-gradient(90deg, #0878de 0 68%, #ff8a1f 68% 100%);
}

section:nth-of-type(2) {
  background:
    radial-gradient(circle at 4% 12%, rgba(255, 157, 66, .12) 0 72px, transparent 73px),
    radial-gradient(circle at 96% 88%, rgba(45, 169, 234, .13) 0 128px, transparent 129px),
    #f7fbff !important;
}

section:nth-of-type(2) h2 {
  padding-left: 14px;
  border-left: 6px solid #ff8a1f;
  color: #063b76;
}

section:nth-of-type(2) article {
  border-color: #d4e7f8;
  border-radius: 20px;
  box-shadow: 0 12px 30px rgba(4, 69, 133, .1);
  transition: transform .25s ease, box-shadow .25s ease;
}

section:nth-of-type(2) article:nth-child(2) {
  border-top: 4px solid #ff9d42;
}

section:nth-of-type(2) article:nth-child(1),
section:nth-of-type(2) article:nth-child(3) {
  border-top: 4px solid #2da9ea;
}

section:nth-of-type(2) article:hover {
  transform: translateY(-5px);
  box-shadow: 0 18px 36px rgba(4, 69, 133, .16);
}

section:nth-of-type(2) article button {
  border-width: 2px;
  border-color: #ff8a1f;
  border-radius: 10px;
  color: #d65f00;
  transition: color .2s ease, background-color .2s ease;
}

section:nth-of-type(2) article button:hover {
  color: #fff;
  background: #ff8a1f;
}

section:nth-of-type(3) {
  background: linear-gradient(145deg, #063b76 0%, #075ba9 52%, #0878de 100%) !important;
  border-top: 6px solid #ff9d42;
}

section:nth-of-type(3) h2 {
  text-shadow: 0 5px 18px rgba(0, 31, 70, .28);
}

section:nth-of-type(3) article {
  border: 3px solid rgba(255, 157, 66, .85);
  border-radius: 24px;
  box-shadow: 0 18px 34px rgba(0, 28, 65, .28);
}

section:nth-of-type(3) article a {
  background: linear-gradient(90deg, #ff7a18, #ff9d42);
}

section:nth-of-type(3) > div > div > button {
  border: 3px solid #ffca58;
  color: #063b76;
  background: #fff;
}

section:nth-of-type(4) {
  background: linear-gradient(135deg, #fff8ef 0%, #eef8ff 58%, #dff2ff 100%) !important;
  border-top: 1px solid #cde9fb;
}

section:nth-of-type(4) h2 {
  color: #063b76;
}

section:nth-of-type(4) > div > div:first-child > a {
  color: #d65f00;
}

section:nth-of-type(4) article {
  overflow: hidden;
  border: 2px solid #fff;
  border-radius: 26px;
  box-shadow: 0 14px 28px rgba(4, 69, 133, .13);
  transition: transform .25s ease, box-shadow .25s ease;
}

section:nth-of-type(4) article:nth-child(2) {
  box-shadow: 0 14px 28px rgba(219, 105, 12, .14);
}

section:nth-of-type(4) article:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 38px rgba(4, 69, 133, .2);
}

section:nth-of-type(4) article a {
  border-width: 2px;
  border-color: #0878de;
  border-radius: 10px;
  font-weight: 700;
  transition: color .2s ease, background-color .2s ease;
}

section:nth-of-type(4) article a:hover {
  color: #fff;
  background: #0878de;
}

section:nth-of-type(5) {
  border-top-color: #ff8a1f !important;
  background: linear-gradient(90deg, #e7f5ff, #fff5e8, #e7f5ff) !important;
}

section:nth-of-type(5) h2 {
  color: #063b76;
}

section:nth-of-type(6) {
  background: linear-gradient(180deg, #e9f6ff 0%, #f8fbff 100%) !important;
}

section:nth-of-type(6) > div {
  border-color: #ff9d42;
  border-width: 4px;
  box-shadow: 0 20px 45px rgba(4, 69, 133, .18);
}

/* Stakeholder-requested curved transition: one wide, shallow CSS ellipse. */
section:nth-of-type(2) {
  position: relative;
  z-index: 1;
}

section:nth-of-type(2)::after {
  position: absolute;
  z-index: 1;
  bottom: -58px;
  left: 50%;
  width: 160%;
  height: 128px;
  content: '';
  pointer-events: none;
  background: #f7fbff;
  border-radius: 0 0 50% 50% / 0 0 100% 100%;
  transform: translateX(-50%);
}

section:nth-of-type(3) {
  position: relative;
  z-index: 0;
}

section:nth-of-type(3) > div {
  position: relative;
  z-index: 2;
}

@media (max-width: 1023px) {
  .home-hero > div:last-child {
    height: auto;
    min-height: 108px;
    row-gap: 12px;
  }

  section:nth-of-type(2) article:hover,
  section:nth-of-type(4) article:hover {
    transform: none;
  }

  section:nth-of-type(2)::after {
    bottom: -34px;
    width: 180%;
    height: 84px;
  }
}
</style>
