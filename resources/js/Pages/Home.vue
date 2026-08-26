<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import PublicLayout from '../Layouts/PublicLayout.vue';
import LazyImage from '../Components/Base/LazyImage.vue';
import ProductShowcase from '../Components/Home/ProductShowcase.vue';

const props = defineProps({ hero: Object, news: Array, promotions: Array, promotionFallbackEnabled: Boolean, partners: Array, featuredRides: Array, featuredRideFallbackEnabled: Boolean });
const heroFallback = {
  video_url: null,
  poster_url: '/assets/temporary/hero-waterpark-v2.png',
  eyebrow: 'Selamat Datang di',
  judul: 'Kampoeng Radja',
  tagline: 'Tempat Bermain, Belajar, dan Rekreasi untuk Semua',
  deskripsi: 'Nikmati beragam wahana seru, atraksi menarik, dan pengalaman berkesan bersama keluarga dan sahabat.',
  cta_primary_label: 'Jelajahi Wahana',
  cta_primary_url: '/wahana',
  cta_secondary_label: 'Tentang Kami',
  cta_secondary_url: '/tentang-kami',
};
const heroContent = computed(() => props.hero
  ? { ...props.hero, poster_url: props.hero.poster_url || heroFallback.poster_url }
  : heroFallback);
const heroVideoReady = ref(false);
const heroVideoFailed = ref(false);
const isInternalLink = (url) => typeof url === 'string' && url.startsWith('/') && !url.startsWith('//');
const onHeroVideoReady = () => { heroVideoReady.value = true; };
const onHeroVideoError = () => { heroVideoFailed.value = true; heroVideoReady.value = false; };
watch(() => props.hero?.video_url, () => { heroVideoReady.value = false; heroVideoFailed.value = false; });
const expandedNews = ref(null);
const selectedPromo = ref(null);
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
const fallbackPromoCards = [
  {
    poster_url: '/assets/promotions/promo-berempat.jpeg',
    title: 'Promo Main Ber-4',
    period: '18–31 Agustus 2026',
    summary: 'Promo Main Ber-4 cuma Rp100.000 untuk 4 orang. Sudah termasuk 4 wahana: Flying Pirate, Kiddy Land, Flying Fox, dan Outbound.',
    detail: 'Promo Main Ber-4 cuma Rp100.000 untuk 4 orang. Sudah termasuk Flying Pirate, Kiddy Land, Flying Fox, dan Outbound.',
    link_wa: null,
  },
  {
    poster_url: '/assets/promotions/promo-agus.jpeg',
    title: 'Promo Spesial Lahir Bulan Agustus',
    period: 'Agustus 2026',
    summary: 'Khusus kamu yang lahir di bulan Agustus, cukup Rp20.000 untuk menikmati Outbound dan Ombang Ambing.',
    detail: 'Khusus pengunjung yang lahir pada bulan Agustus. Tunjukkan identitas saat pembelian tiket dan ikuti media sosial Kampoeng Radja.',
    link_wa: null,
  },
  {
    poster_url: '/assets/promotions/promo-honda.jpeg',
    title: 'Promo Pengguna Motor Honda',
    period: 'Hingga 31 Agustus 2026',
    summary: 'Datang menggunakan motor Honda dan follow media sosial Kampoeng Radja untuk menikmati promo spesial bermain hingga 31 Agustus 2026.',
    detail: 'Datang menggunakan motor Honda dan ikuti media sosial Kampoeng Radja untuk menikmati promo spesial selama periode yang berlaku.',
    link_wa: null,
  },
];
const currentPromoIndex = ref(0);
const promoViewport = ref(null);
const promoTrack = ref(null);
const promoStep = ref(0);
const promoTranslateX = ref(0);
const promoDirection = ref(0);
const isPromoAnimating = ref(false);
const isPromoTransitioning = ref(false);
const isPromoReady = ref(false);
let promoResizeFrame = null;
const promoCards = computed(() => {
  const cmsPromoCards = (props.promotions ?? []).map((promo) => ({
    id: `cms-${promo.id}`,
    poster_url: promo.poster_url,
    title: promo.title,
    period: promo.period ?? '',
    summary: promo.description ?? '',
    detail: promo.detail ?? promo.description ?? '',
    link_wa: promo.link_wa,
  }));

  if (cmsPromoCards.length) return cmsPromoCards;

  return props.promotionFallbackEnabled ? fallbackPromoCards : [];
});
const visualPromoCards = computed(() => {
  const cards = promoCards.value;

  if (!cards.length) return [];
  if (cards.length === 1) return [{ ...cards[0], visualKey: 'promo-slot-0' }];

  return Array.from({ length: Math.min(3, cards.length) + 2 }, (_, slotIndex) => {
    const cardIndex = (currentPromoIndex.value + slotIndex - 1 + cards.length) % cards.length;

    return { ...cards[cardIndex], visualKey: `promo-slot-${slotIndex}` };
  });
});
const promoTrackStyle = computed(() => ({
  transform: `translate3d(${promoTranslateX.value}px, 0, 0)`,
}));
const measurePromoStep = async () => {
  await nextTick();

  const slides = promoTrack.value?.children;

  if (!slides?.length || promoCards.value.length <= 1) {
    promoStep.value = 0;
    promoTranslateX.value = 0;
    isPromoReady.value = true;
    return;
  }

  promoStep.value = slides.length > 1
    ? slides[1].offsetLeft - slides[0].offsetLeft
    : slides[0].getBoundingClientRect().width;
  promoTranslateX.value = -promoStep.value;
  isPromoReady.value = true;
};
const movePromo = async (direction) => {
  const total = promoCards.value.length;

  if (total <= 1 || isPromoAnimating.value) return;

  if (!promoStep.value) await measurePromoStep();

  promoDirection.value = direction;
  isPromoAnimating.value = true;
  isPromoTransitioning.value = true;

  await nextTick();

  window.requestAnimationFrame(() => {
    promoTranslateX.value = direction > 0 ? -promoStep.value * 2 : 0;
  });
};
const finishPromoTransition = async (event) => {
  if (!isPromoAnimating.value || event.propertyName !== 'transform' || event.target !== promoTrack.value) return;

  const total = promoCards.value.length;
  isPromoTransitioning.value = false;
  currentPromoIndex.value = (currentPromoIndex.value + promoDirection.value + total) % total;
  promoTranslateX.value = -promoStep.value;

  await nextTick();

  promoDirection.value = 0;
  isPromoAnimating.value = false;
};
const handlePromoResize = () => {
  if (isPromoAnimating.value) return;

  if (promoResizeFrame) window.cancelAnimationFrame(promoResizeFrame);

  promoResizeFrame = window.requestAnimationFrame(() => {
    measurePromoStep();
  });
};
const openPromoDetail = (promo) => {
  selectedPromo.value = promo;
};
const closePromoDetail = () => {
  selectedPromo.value = null;
};
const handlePromoModalKeydown = (event) => {
  if (event.key === 'Escape' && selectedPromo.value) closePromoDetail();
};

watch(selectedPromo, (promo) => {
  document.body.style.overflow = promo ? 'hidden' : '';
});

onMounted(() => {
  measurePromoStep();
  window.addEventListener('resize', handlePromoResize);
  window.addEventListener('keydown', handlePromoModalKeydown);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', handlePromoResize);
  window.removeEventListener('keydown', handlePromoModalKeydown);
  document.body.style.overflow = '';
  if (promoResizeFrame) window.cancelAnimationFrame(promoResizeFrame);
});
const featuredFallback = [
  { title: 'Waterpark', description: 'Kolam renang luas dengan berbagai jenis perosotan seru untuk anak-anak dan dewasa.', image: '/assets/temporary/hero-waterpark-v2.png', label: 'Keluarga', tone: 'bg-[#fce7f3] text-[#be185d]' },
  { title: 'Flying Fox', description: 'Rasakan sensasi meluncur dari ketinggian melintasi area taman yang hijau dan asri.', image: '/assets/figma/figma-news-1.png', label: 'Adrenalin', tone: 'bg-[#fee2e2] text-[#b91c1c]' },
  { title: 'Go Kart', description: 'Pacu kecepatanmu di sirkuit mini yang dirancang aman untuk pengalaman balapan yang seru.', image: '/assets/figma/figma-news-3.png', label: 'Populer', tone: 'bg-[#fef9c3] text-[#854d0e]' },
];
const featuredCards = computed(() => props.featuredRides?.length ? props.featuredRides.map((ride) => ({ title: ride.title || '[PERLU KONTEN RESMI: Wahana]', description: ride.description || '[PERLU KONTEN RESMI: Deskripsi wahana]', image: ride.cover_url, label: ride.labels?.[0]?.name || 'Wahana', tone: 'bg-[#e0f2fe] text-[#0369a1]' })) : (props.featuredRideFallbackEnabled ? featuredFallback : []));
</script>

<template>
  <Head title="Kampoeng Radja"><meta name="description" content="[PLACEHOLDER: Deskripsi resmi Kampoeng Radja]" /></Head>
  <PublicLayout>
    <section class="home-hero relative z-10 flex min-h-[620px] items-center overflow-visible bg-[#304755] sm:min-h-[680px] lg:h-[720px]">
      <div class="absolute inset-0 overflow-hidden bg-[#304755]">
        <img v-if="heroContent.poster_url" :src="heroContent.poster_url" alt="" aria-hidden="true" fetchpriority="high" class="h-full w-full object-cover" />
        <video
          v-if="heroContent.video_url && !heroVideoFailed"
          :key="heroContent.video_url"
          :src="heroContent.video_url"
          :poster="heroContent.poster_url || undefined"
          autoplay
          muted
          loop
          playsinline
          preload="metadata"
          class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500"
          :class="heroVideoReady ? 'opacity-100' : 'opacity-0'"
          @canplay="onHeroVideoReady"
          @playing="onHeroVideoReady"
          @error="onHeroVideoError"
        />
        <div class="home-hero-overlay absolute inset-0"></div>
      </div>

      <div class="relative mx-auto w-full max-w-[1120px] px-5 pb-16 pt-10 text-center text-white sm:pb-12 lg:px-0">
        <p v-if="heroContent.eyebrow" class="text-xs font-bold uppercase tracking-[0.22em] text-blue-100 sm:text-sm">{{ heroContent.eyebrow }}</p>
        <h1 class="mx-auto mt-3 max-w-4xl font-heading text-4xl font-extrabold leading-[1.05] drop-shadow-sm sm:text-6xl lg:text-[72px]">{{ heroContent.judul }}</h1>
        <p v-if="heroContent.tagline" class="mx-auto mt-4 max-w-3xl font-heading text-lg font-semibold leading-7 text-white sm:text-2xl sm:leading-8">{{ heroContent.tagline }}</p>
        <p v-if="heroContent.deskripsi" class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-blue-50/95 sm:text-base sm:leading-7">{{ heroContent.deskripsi }}</p>
        <div v-if="(heroContent.cta_primary_label && heroContent.cta_primary_url) || (heroContent.cta_secondary_label && heroContent.cta_secondary_url)" class="mt-7 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center">
          <Link v-if="heroContent.cta_primary_label && heroContent.cta_primary_url && isInternalLink(heroContent.cta_primary_url)" :href="heroContent.cta_primary_url" class="inline-flex h-12 items-center justify-center rounded-xl bg-[#f47a1f] px-6 text-sm font-bold text-white shadow-lg shadow-orange-950/20 transition hover:bg-[#dc650d] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-orange-200">{{ heroContent.cta_primary_label }}</Link>
          <a v-else-if="heroContent.cta_primary_label && heroContent.cta_primary_url" :href="heroContent.cta_primary_url" target="_blank" rel="noopener noreferrer" class="inline-flex h-12 items-center justify-center rounded-xl bg-[#f47a1f] px-6 text-sm font-bold text-white shadow-lg shadow-orange-950/20 transition hover:bg-[#dc650d] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-orange-200">{{ heroContent.cta_primary_label }}</a>
          <Link v-if="heroContent.cta_secondary_label && heroContent.cta_secondary_url && isInternalLink(heroContent.cta_secondary_url)" :href="heroContent.cta_secondary_url" class="inline-flex h-12 items-center justify-center rounded-xl border border-white/70 bg-white/10 px-6 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/30">{{ heroContent.cta_secondary_label }}</Link>
          <a v-else-if="heroContent.cta_secondary_label && heroContent.cta_secondary_url" :href="heroContent.cta_secondary_url" target="_blank" rel="noopener noreferrer" class="inline-flex h-12 items-center justify-center rounded-xl border border-white/70 bg-white/10 px-6 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white/30">{{ heroContent.cta_secondary_label }}</a>
        </div>
      </div>

      <div class="absolute -bottom-12 left-1/2 grid h-[90px] w-[min(90%,1152px)] -translate-x-1/2 grid-cols-2 items-center rounded-xl border border-[#c2c6d4]/30 bg-[#f8f9fa] p-5 shadow-[0_10px_15px_-3px_rgba(0,0,0,.1),0_4px_6px_-4px_rgba(0,0,0,.1)] lg:grid-cols-4"><div v-for="(item,index) in [{value:'14+',label:'Wahana Seru',icon:'⌖',tone:'bg-[#d7e2ff] text-[#003f87]'}, {value:'100K+',label:'Pengunjung/Tahun',icon:'♟',tone:'bg-[#ffdcc3] text-[#904d00]'}, {value:'20',label:'Tahun Pengalaman',icon:'✦',tone:'bg-[#ffd9df] text-[#86003a]'}, {value:'Keluarga',label:'Ramah & Edukatif',icon:'♟',tone:'bg-[#dcfce7] text-[#166534]'}]" :key="item.value" :class="index < 3 ? 'lg:border-r' : ''" class="flex items-center gap-4 border-[#c2c6d4]/50 px-3 first:pl-0 last:pr-0"><span :class="item.tone" class="grid h-10 w-10 place-items-center rounded-full text-sm font-bold" aria-hidden="true">{{ item.icon }}</span><span><strong class="block font-heading text-2xl leading-[30px] text-[#003f87]">{{ item.value }}</strong><small class="block text-xs leading-[18px] text-[#424752]">{{ item.label }}</small></span></div></div>
    </section>
    <section class="bg-[#f8f9fa] px-5 pb-20 pt-32 lg:px-0"><div class="mx-auto max-w-[1120px]"><h2 class="font-heading text-[28px] font-bold text-[#062a59]">Media & Berita</h2><p class="mt-2 text-sm text-[#4b5563]">Informasi terbaru seputar Kampoeng Radja.</p><div class="mt-5 grid items-start gap-5 md:grid-cols-3"><article v-for="(newsItem,index) in newsCards" :key="newsItem.title" class="overflow-hidden rounded-xl border border-[#e1e2eb] bg-white"><div class="relative"><LazyImage :src="newsItem.foto_url" :alt="newsItem.title" class-name="h-[198px] w-full object-cover" /><span :class="newsItem.categoryClass" class="absolute left-3 top-3 rounded-full px-3 py-1 text-[10px] text-white">{{ newsItem.category }}</span></div><div class="p-[18px]"><h3 class="font-heading text-xl font-bold leading-6 text-[#062a59]">{{ newsItem.title }}</h3><p class="mt-3 text-sm leading-5 text-[#4b5563]">{{ newsItem.excerpt }}</p><p v-if="expandedNews === index" class="mt-4 border-t border-[#e1e2eb] pt-4 text-sm leading-6 text-[#4b5563]">{{ newsItem.detail }}</p><button type="button" class="mt-4 h-9 w-full rounded-md border border-[#0063c7] text-xs font-medium text-[#0063c7]" :aria-expanded="expandedNews === index" @click="expandedNews = expandedNews === index ? null : index">{{ expandedNews === index ? 'Tutup Detail' : 'Lihat Detail' }}</button></div></article></div></div></section>
    <section class="home-promo-section relative overflow-hidden px-4 py-16 text-white sm:px-6 sm:py-20 lg:px-8 lg:py-24">
      <div class="mx-auto max-w-[1240px]">
        <header class="mx-auto max-w-[720px] text-center">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.14em] text-[#ffe3b7] backdrop-blur-sm">
            <span aria-hidden="true">🎁</span>
            Penawaran Spesial
          </span>
          <h2 class="mt-4 font-heading text-[42px] font-extrabold leading-none sm:text-[52px] lg:text-[60px]">Promo</h2>
          <p class="mx-auto mt-4 max-w-[650px] text-sm leading-6 text-[#dcecff] sm:text-base sm:leading-7">
            Nikmati berbagai penawaran menarik di Kampoeng Radja. Waktu terbatas, jangan sampai ketinggalan!
          </p>
        </header>

        <div
          class="promo-carousel-shell relative mt-9 rounded-[28px] border border-white/15 bg-white/[0.08] p-4 shadow-[0_24px_60px_rgba(0,30,73,.24)] backdrop-blur-[2px] sm:p-5 lg:mt-12 lg:p-6"
          :class="promoCards.length === 1 ? 'mx-auto max-w-[500px]' : promoCards.length === 2 ? 'lg:mx-auto lg:max-w-[900px]' : ''"
        >
          <div class="mb-4 flex items-center justify-between gap-4 sm:mb-5">
            <div>
              <span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-[#9bcfff]">Promo aktif</span>
              <strong class="mt-1 block text-sm font-semibold text-white">{{ promoCards.length }} penawaran pilihan</strong>
            </div>

            <div v-if="promoCards.length > 1" class="flex items-center gap-3">
              <span class="hidden text-xs font-semibold tabular-nums text-[#c9e3ff] sm:inline">{{ currentPromoIndex + 1 }} / {{ promoCards.length }}</span>
              <div class="inline-flex rounded-xl border border-white/15 bg-[#073e7c]/60 p-1">
                <button
                  type="button"
                  aria-label="Promo sebelumnya"
                  :disabled="isPromoAnimating"
                  class="promo-navigation grid h-10 w-10 place-items-center rounded-lg text-lg font-bold text-white focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#ffca58]/60 disabled:cursor-wait disabled:opacity-50"
                  @click="movePromo(-1)"
                >←</button>
                <button
                  type="button"
                  aria-label="Promo berikutnya"
                  :disabled="isPromoAnimating"
                  class="promo-navigation grid h-10 w-10 place-items-center rounded-lg text-lg font-bold text-white focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#ffca58]/60 disabled:cursor-wait disabled:opacity-50"
                  @click="movePromo(1)"
                >→</button>
              </div>
            </div>
          </div>

          <div v-if="!promoCards.length" class="rounded-2xl border border-dashed border-white/25 bg-white/[0.06] px-6 py-12 text-center">
            <h3 class="text-base font-bold text-white">Belum ada promo aktif saat ini.</h3>
            <p class="mt-2 text-sm text-[#c9e3ff]">Promo baru akan tampil otomatis sesuai periode yang dikelola melalui CMS.</p>
          </div>
          <div v-else ref="promoViewport" class="overflow-hidden" :class="isPromoReady ? 'visible' : 'invisible'">
            <div
              ref="promoTrack"
              class="promo-track flex gap-5"
              :class="[
                isPromoTransitioning ? 'promo-track--animating' : '',
                promoCards.length === 1 ? 'promo-track--single' : '',
                promoCards.length === 2 ? 'promo-track--double' : '',
              ]"
              :style="promoTrackStyle"
              @transitionend="finishPromoTransition"
            >
              <article
                v-for="promo in visualPromoCards"
                :key="promo.visualKey"
                class="promo-slide flex h-full shrink-0 flex-col overflow-hidden rounded-[24px] bg-white text-[#062a59]"
              >
                <img :src="promo.poster_url" :alt="`Poster ${promo.title}`" class="aspect-[4/5] w-full object-cover" loading="lazy" />
                <div class="flex flex-1 flex-col p-5 lg:p-6">
                  <div class="flex items-center justify-between gap-3">
                    <span class="w-fit rounded-full bg-[#fff0e4] px-3 py-1 text-[10px] font-extrabold tracking-[0.13em] text-[#c95700]">PROMO</span>
                    <span v-if="promo.period" class="text-right text-[11px] font-bold text-[#d65f00]">{{ promo.period }}</span>
                  </div>
                  <h3 class="mt-4 min-h-[52px] font-heading text-[21px] font-bold leading-[1.28] text-[#062a59] lg:text-[22px]">{{ promo.title }}</h3>
                  <p class="mt-3 line-clamp-2 min-h-[44px] flex-1 text-sm leading-[22px] text-[#596273]">{{ promo.summary }}</p>
                  <button type="button" :aria-label="`Lihat detail ${promo.title}`" class="mt-5 flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#f47a1f] text-center text-sm font-bold text-white" @click="openPromoDetail(promo)">Lihat Detail <span aria-hidden="true">→</span></button>
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div v-if="selectedPromo" class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/65 p-3 sm:p-6" role="dialog" aria-modal="true" :aria-label="`Detail ${selectedPromo.title}`" @click.self="closePromoDetail">
      <article class="relative grid max-h-[92vh] w-full max-w-[920px] overflow-y-auto rounded-[24px] bg-white shadow-2xl md:grid-cols-[minmax(280px,0.85fr)_minmax(0,1.15fr)]">
        <button type="button" class="absolute right-3 top-3 z-10 grid h-10 w-10 place-items-center rounded-full bg-white/95 text-2xl leading-none text-slate-600 shadow-md transition hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200" aria-label="Tutup detail Promo" @click="closePromoDetail">×</button>
        <div class="grid min-h-[300px] place-items-center bg-slate-100 p-4 sm:p-6 md:min-h-[560px]">
          <img :src="selectedPromo.poster_url" :alt="`Poster ${selectedPromo.title}`" class="max-h-[520px] h-auto w-full object-contain" />
        </div>
        <div class="flex flex-col p-5 sm:p-8 md:p-10">
          <span class="w-fit rounded-full bg-[#fff0e4] px-3 py-1 text-[10px] font-extrabold tracking-[0.13em] text-[#c95700]">PROMO</span>
          <h2 class="mt-4 pr-9 font-heading text-2xl font-extrabold leading-tight text-[#062a59] sm:text-3xl">{{ selectedPromo.title }}</h2>
          <p v-if="selectedPromo.period" class="mt-3 text-sm font-bold text-[#d65f00]">{{ selectedPromo.period }}</p>
          <div class="mt-6 whitespace-pre-line text-sm leading-7 text-[#4b5563]">{{ selectedPromo.detail || selectedPromo.summary }}</div>
          <a v-if="selectedPromo.link_wa" :href="selectedPromo.link_wa" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex h-12 w-full items-center justify-center rounded-xl bg-[#f47a1f] px-5 text-sm font-bold text-white transition hover:bg-[#d96008] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-orange-200 sm:w-auto">Tanya Lebih Lanjut</a>
        </div>
      </article>
    </div>
    <ProductShowcase />
    <section class="bg-[#f1f3f5] px-5 py-20 lg:px-0"><div class="mx-auto max-w-[1120px]"><div class="flex flex-wrap items-end justify-between gap-5"><div><h2 class="font-heading text-[28px] font-bold text-[#191c1e]">Wahana Unggulan</h2><p class="mt-2 text-sm text-[#434655]">Jelajahi berbagai wahana seru yang siap menguji adrenalin dan memberikan keceriaan.</p></div><Link :href="route('wahana')" class="text-sm font-bold text-[#005cc8]">Lihat Semua Wahana →</Link></div><div class="mt-8 grid gap-6 md:grid-cols-3"><article v-for="ride in featuredCards" :key="ride.title" class="overflow-hidden rounded-[24px] border border-[#e0e3e5] bg-white shadow-[0_1px_2px_rgba(0,0,0,.05)]"><div class="relative"><LazyImage :src="ride.image" :alt="ride.title" class-name="h-[137px] w-full object-cover" /><span :class="ride.tone" class="absolute left-3 top-3 rounded-full px-3 py-1 text-[10px] font-bold">{{ ride.label }}</span></div><div class="p-[18px]"><h3 class="font-heading text-lg font-bold text-[#191c1e]">{{ ride.title }}</h3><p class="mt-2 min-h-10 text-xs leading-4 text-[#434655]">{{ ride.description }}</p><Link :href="route('wahana')" class="mt-4 block w-full rounded-md border border-[#0063c7] py-2 text-center text-xs font-medium text-[#0063c7]">Detail Wahana</Link></div></article></div></div></section>
    <section class="overflow-hidden border-t-[3px] border-[#1685ed] bg-[#dedede] px-5 py-10 lg:px-0"><div class="mx-auto max-w-[1120px]"><h2 class="text-center font-heading text-xl font-bold text-[#191c1e]">Mitra</h2><div class="mt-8 overflow-hidden"><div class="flex w-max items-center gap-16 motion-reduce:animate-none animate-marquee"><div v-for="(item,index) in [...(partners?.length ? partners : [{name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}]), ...(partners?.length ? partners : [{name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}, {name:'[PERLU MITRA]'}])]" :key="`${item.id || item.name}-${index}`" class="grid h-12 w-28 shrink-0 place-items-center"><LazyImage v-if="item.logo" :src="item.logo" :alt="item.name" class-name="max-h-10 max-w-24 object-contain" /><span v-else class="text-center text-xs font-semibold text-[#5e6470]">{{ item.name }}</span></div></div></div></div></section>
    <section class="bg-[#f9f9ff] px-5 py-[120px] lg:px-0"><div class="mx-auto h-[450px] max-w-[1120px] overflow-hidden rounded-2xl border-2 border-[#e1e2eb] shadow-[0_1px_2px_rgba(0,0,0,.05)]"><iframe title="Lokasi Kampoeng Radja" class="h-[446px] w-full border-0" src="https://www.google.com/maps?q=kampoeng+radja&amp;z=14&amp;t=m&amp;hl=en&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div></section>
  </PublicLayout>
</template>

<style scoped>
/* Stakeholder refresh: fun blue-orange treatment without decorative assets. */
.home-hero-overlay {
  background: linear-gradient(110deg, rgba(2, 43, 91, .72) 0%, rgba(0, 64, 128, .46) 48%, rgba(21, 42, 60, .38) 100%);
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

.home-promo-section {
  background: linear-gradient(145deg, #052f65 0%, #0756a6 52%, #0878de 100%) !important;
  border-top: 6px solid #ff9d42;
}

.home-promo-section h2 {
  text-shadow: 0 5px 18px rgba(0, 31, 70, .28);
}

.home-promo-section .promo-carousel-shell {
  box-shadow:
    inset 0 1px 0 rgba(255, 255, 255, .12),
    0 24px 60px rgba(0, 25, 62, .24);
}

.home-promo-section article {
  border: 1px solid rgba(210, 225, 240, .95);
  box-shadow: 0 14px 32px rgba(0, 25, 62, .22);
  transition: transform .25s ease, box-shadow .25s ease;
}

.home-promo-section article a {
  background: #f47a1f;
  transition: background-color .2s ease, transform .2s ease;
}

.home-promo-section article:hover {
  box-shadow: 0 20px 42px rgba(0, 25, 62, .3);
  transform: translateY(-4px);
}

.home-promo-section article a:hover {
  background: #d96008;
  transform: translateY(-1px);
}

.home-promo-section .promo-track {
  will-change: transform;
}

.home-promo-section .promo-track--animating {
  transition: transform 380ms ease-in-out;
}

.home-promo-section .promo-slide {
  flex-basis: 100%;
}

.home-promo-section .promo-track--single .promo-slide {
  flex-basis: 100%;
}

.home-promo-section .promo-navigation {
  transition: color .2s ease, background-color .2s ease, border-color .2s ease, transform .2s ease;
}

.home-promo-section .promo-navigation:hover {
  color: #fff;
  background: #f47a1f;
  transform: translateY(-1px);
}

@media (min-width: 768px) {
  .home-promo-section .promo-slide {
    flex-basis: calc((100% - 1.25rem) / 2);
  }
}

@media (min-width: 1024px) {
  .home-promo-section .promo-slide {
    flex-basis: calc((100% - 2.5rem) / 3);
  }

  .home-promo-section .promo-track--double .promo-slide {
    flex-basis: calc((100% - 1.25rem) / 2);
  }
}

@media (prefers-reduced-motion: reduce) {
  .home-promo-section .promo-track--animating {
    transition-duration: 1ms;
  }
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

.home-promo-section {
  position: relative;
  z-index: 0;
}

.home-promo-section > div {
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
  .home-promo-section article:hover,
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
