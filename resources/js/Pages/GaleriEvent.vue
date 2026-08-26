<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
import PageContainer from '../Components/Layout/PageContainer.vue';
import BaseCard from '../Components/Base/BaseCard.vue';
import LazyImage from '../Components/Base/LazyImage.vue';

const props = defineProps({ events: { type: Array, default: () => [] } });
const order = ref('newest');
const featuredPhotoIds = ref({});
const railElements = new Map();

const sortedEvents = computed(() => [...props.events].sort((a, b) => {
    const difference = new Date(b.event_date) - new Date(a.event_date);
    const stableIdDifference = Number(b.id) - Number(a.id);

    return order.value === 'newest'
        ? difference || stableIdDifference
        : -difference || -stableIdDifference;
}));

const dateLabel = (value) => value
    ? new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(`${value}T00:00:00`))
    : '[PLACEHOLDER: Tanggal event]';

const eventPhotos = (event) => [...(event.photos || [])].sort((a, b) => {
    const firstOrder = a.urutan ?? Number.MAX_SAFE_INTEGER;
    const secondOrder = b.urutan ?? Number.MAX_SAFE_INTEGER;
    return firstOrder - secondOrder || a.id - b.id;
});
const featuredPhoto = (event) => {
    const photos = eventPhotos(event);
    return photos.find((photo) => photo.id === featuredPhotoIds.value[event.id]) || photos[0] || null;
};
const secondaryPhotos = (event) => eventPhotos(event).filter((photo) => photo.id !== featuredPhoto(event)?.id);
const sidePhotos = (event) => secondaryPhotos(event).slice(0, 2);
const railPhotos = (event) => secondaryPhotos(event).slice(2);

const selectFeatured = (event, photo) => {
    featuredPhotoIds.value = { ...featuredPhotoIds.value, [event.id]: photo.id };
};
const setRailElement = (eventId, element) => {
    if (element) railElements.set(eventId, element);
    else railElements.delete(eventId);
};
const scrollRail = (eventId, direction) => {
    const rail = railElements.get(eventId);
    if (rail) rail.scrollBy({ left: rail.clientWidth * 0.82 * direction, behavior: 'smooth' });
};
</script>

<template>
    <Head title="Galeri Event"><meta name="description" content="[PLACEHOLDER: Meta description Galeri Event]" /></Head>
    <PublicLayout>
        <section class="gallery-page relative overflow-hidden pb-12 sm:pb-16">
            <span class="pointer-events-none absolute -bottom-24 -right-20 h-56 w-56 rounded-full bg-[#f6b400]/15 blur-xl" aria-hidden="true"></span>
            <div class="gallery-hero relative py-16 text-center lg:py-20">
                <span class="absolute -left-32 -top-32 h-64 w-64 rounded-full bg-[#0052c8]/10"></span>
                <PageContainer>
                    <p class="relative text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#d7670d]">Dokumentasi Kampoeng Radja</p>
                    <h1 class="relative mt-2 font-heading text-4xl font-bold lg:text-5xl">Galeri Event</h1>
                    <p class="relative mx-auto mt-4 max-w-2xl text-sm leading-6 text-[#414753]">Dokumentasi kegiatan dan momen berkesan yang berlangsung di Kampoeng Radja.</p>
                </PageContainer>
            </div>

            <div class="gallery-content">
            <PageContainer>
                <div class="sort-toolbar flex min-h-[70px] flex-wrap items-center justify-between gap-3 py-3">
                    <p class="text-xs font-semibold text-[#414753]">↕ Urutkan dokumentasi event</p>
                    <div class="inline-flex gap-2" aria-label="Urutan Galeri Event">
                        <button type="button" :aria-pressed="order === 'newest'" :class="order === 'newest' ? 'bg-[#004e9f] text-white' : 'border-[#cbd3df] bg-white text-[#414753]'" class="h-9 rounded-lg border-2 px-4 text-xs font-semibold" @click="order = 'newest'">Terbaru</button>
                        <button type="button" :aria-pressed="order === 'oldest'" :class="order === 'oldest' ? 'bg-[#004e9f] text-white' : 'border-[#cbd3df] bg-white text-[#414753]'" class="h-9 rounded-lg border-2 px-4 text-xs font-semibold" @click="order = 'oldest'">Terlama</button>
                    </div>
                </div>
            </PageContainer>

            <PageContainer>
                <div v-if="sortedEvents.length" class="event-card-list mt-6">
                    <article v-for="(event, index) in sortedEvents" :key="event.id" :class="index % 2 === 0 ? 'event-album--blue' : 'event-album--orange'" class="event-album" :aria-labelledby="`event-title-${event.id}`">
                        <div v-if="eventPhotos(event).length" class="album-gallery">
                            <div :class="{ 'album-stage--single': eventPhotos(event).length === 1 }" class="album-stage">
                                <div class="featured-photo">
                                    <Transition name="featured-swap" mode="out-in">
                                        <LazyImage :key="featuredPhoto(event)?.id" :src="featuredPhoto(event)?.url" :alt="featuredPhoto(event)?.caption || event.title" class-name="h-full w-full object-cover" />
                                    </Transition>
                                    <span class="featured-label">Foto Utama</span>
                                </div>
                                <div v-if="sidePhotos(event).length" class="side-photo-grid">
                                    <button v-for="photo in sidePhotos(event)" :key="photo.id" type="button" class="secondary-photo" :aria-label="`Jadikan foto ini sebagai foto utama ${event.title}`" @click="selectFeatured(event, photo)">
                                        <LazyImage :src="photo.url" :alt="photo.caption || event.title" class-name="h-full w-full object-cover" />
                                        <span v-if="photo.caption" class="photo-caption">{{ photo.caption }}</span>
                                    </button>
                                </div>
                            </div>

                            <div v-if="railPhotos(event).length" class="thumbnail-rail-shell">
                                <button v-if="railPhotos(event).length > 3" type="button" class="rail-navigation rail-navigation--left" :aria-label="`Geser foto ${event.title} ke kiri`" @click="scrollRail(event.id, -1)">‹</button>
                                <div :ref="(element) => setRailElement(event.id, element)" class="thumbnail-rail">
                                    <button v-for="photo in railPhotos(event)" :key="photo.id" type="button" class="secondary-photo thumbnail-rail-item" :aria-label="`Jadikan foto ini sebagai foto utama ${event.title}`" @click="selectFeatured(event, photo)">
                                        <LazyImage :src="photo.url" :alt="photo.caption || event.title" class-name="h-full w-full object-cover" />
                                        <span v-if="photo.caption" class="photo-caption">{{ photo.caption }}</span>
                                    </button>
                                </div>
                                <button v-if="railPhotos(event).length > 3" type="button" class="rail-navigation rail-navigation--right" :aria-label="`Geser foto ${event.title} ke kanan`" @click="scrollRail(event.id, 1)">›</button>
                            </div>
                        </div>
                        <div v-else class="album-empty-photo">Belum ada foto untuk event ini.</div>

                        <div class="event-detail-card">
                            <div class="event-date-panel"><span aria-hidden="true" class="text-xl">▣</span><span>{{ dateLabel(event.event_date) }}</span></div>
                            <div class="event-copy">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#d7670d]">Album Event</p>
                                <h2 :id="`event-title-${event.id}`" class="mt-2 font-heading text-2xl font-bold text-[#063b76] sm:text-3xl">{{ event.title }}</h2>
                                <div class="mt-3 h-1 w-16 rounded-full bg-gradient-to-r from-[#ff9d42] to-[#2da9ea]"></div>
                                <p class="mt-4 whitespace-pre-line text-sm leading-7 text-[#4f5967]">{{ event.description || '[PLACEHOLDER: Deskripsi event belum tersedia]' }}</p>
                            </div>
                        </div>
                    </article>
                </div>

                <BaseCard v-else class="mt-6 p-10 text-center"><p class="font-heading text-2xl text-[#002147]">Dokumentasi event belum tersedia</p><p class="mt-2 text-sm text-[#414753]">[PLACEHOLDER: Tim perlu menyediakan judul, tanggal, deskripsi, dan foto event.]</p></BaseCard>
            </PageContainer>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.gallery-page { background:linear-gradient(135deg,#e8f6ff 0%,#f8fbff 52%,#fff0df 100%); }
.gallery-hero h1 { color:#063b76; text-shadow:0 4px 14px rgba(8,120,222,.12); }
.gallery-hero h1::after { display:block; width:92px; height:6px; margin:16px auto 0; content:''; border-radius:999px; background:linear-gradient(90deg,#2da9ea 0 62%,#ff8a1f 62%); }
.gallery-content { position:relative; }
.sort-toolbar { border:1px solid rgba(203,213,225,.72); border-top:3px solid #f18a2a; border-radius:14px; padding-inline:20px; background:rgba(255,255,255,.94); box-shadow:0 10px 24px rgba(4,69,133,.08); }
.sort-toolbar button { transition:transform .18s ease,box-shadow .18s ease; }
.sort-toolbar button:hover { transform:translateY(-2px); box-shadow:0 7px 15px rgba(8,120,222,.14); }
.event-card-list { display:grid; gap:36px; }
.event-album { overflow:hidden; scroll-margin-top:96px; border:1px solid rgba(203,213,225,.88); border-top-width:4px; border-radius:20px; padding:20px; background:#fff; box-shadow:0 12px 30px rgba(4,69,133,.085); }
.event-album--blue { border-top-color:#2da9ea; }
.event-album--orange { border-top-color:#f18a2a; }
.album-gallery { padding:0; }
.album-stage { display:grid; grid-template-columns:minmax(0,2fr) minmax(230px,1fr); gap:12px; }
.album-stage--single { grid-template-columns:1fr; }
.featured-photo { position:relative; aspect-ratio:16/9; min-width:0; overflow:hidden; border-radius:17px; background:#e5edf5; }
.featured-photo :deep(img),.secondary-photo :deep(img) { display:block; }
.featured-label { position:absolute; bottom:14px; left:14px; border:1px solid rgba(255,255,255,.55); border-radius:999px; padding:7px 12px; color:#fff; background:rgba(3,59,118,.72); font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; backdrop-filter:blur(6px); }
.side-photo-grid { display:grid; min-height:0; grid-template-rows:repeat(2,minmax(0,1fr)); gap:12px; }
.secondary-photo { position:relative; aspect-ratio:16/9; width:100%; min-width:0; overflow:hidden; border-radius:14px; background:#e5edf5; box-shadow:inset 0 0 0 1px rgba(255,255,255,.6); }
.secondary-photo::after { position:absolute; inset:0; content:''; background:rgba(3,59,118,0); transition:background-color .22s ease; }
.secondary-photo:hover::after,.secondary-photo:focus-visible::after { background:rgba(3,59,118,.12); }
.photo-caption { position:absolute; z-index:1; right:8px; bottom:8px; left:8px; overflow:hidden; border-radius:7px; padding:5px 7px; color:#fff; background:rgba(15,23,42,.64); font-size:9px; line-height:1.3; text-align:left; text-overflow:ellipsis; white-space:nowrap; backdrop-filter:blur(4px); }
.thumbnail-rail-shell { position:relative; margin-top:12px; }
.thumbnail-rail { display:grid; grid-auto-flow:column; grid-auto-columns:calc((100% - 24px) / 3); gap:12px; overflow-x:auto; overscroll-behavior-inline:contain; scroll-behavior:smooth; scroll-snap-type:x mandatory; scrollbar-width:none; }
.thumbnail-rail::-webkit-scrollbar { display:none; }
.thumbnail-rail-item { scroll-snap-align:start; }
.rail-navigation { position:absolute; top:50%; z-index:3; display:grid; height:40px; width:40px; transform:translateY(-50%); place-items:center; border:2px solid #fff; border-radius:999px; color:#fff; background:rgba(6,59,118,.84); box-shadow:0 8px 20px rgba(3,59,118,.25); font-size:26px; line-height:1; transition:background-color .2s ease,transform .2s ease; }
.rail-navigation:hover { background:#f47a1f; transform:translateY(-50%) scale(1.04); }
.rail-navigation--left { left:10px; }
.rail-navigation--right { right:10px; }
.event-detail-card { display:grid; grid-template-columns:190px minmax(0,1fr); gap:26px; margin-top:24px; border-top:1px solid #d9e4ef; padding:24px 4px 2px; }
.event-date-panel { display:flex; min-height:72px; align-items:center; justify-content:center; gap:9px; align-self:start; border-radius:12px; padding:12px 14px; color:#0756a6; background:linear-gradient(135deg,#edf7ff,#f5f8ff); font-size:12px; font-weight:800; text-align:center; }
.event-copy { min-width:0; border-left:1px solid #dce5ef; padding-left:28px; }
.album-empty-photo { display:grid; min-height:240px; place-items:center; border:2px dashed #cbd5e1; border-radius:22px; color:#64748b; background:rgba(255,255,255,.72); font-size:13px; }
.featured-swap-enter-active,.featured-swap-leave-active { transition:opacity 300ms ease; }
.featured-swap-enter-from,.featured-swap-leave-to { opacity:0; }
@media (max-width:899px) { .event-card-list { gap:28px; } .event-album { padding:16px; } .album-stage { grid-template-columns:1fr; } .side-photo-grid { grid-template-columns:repeat(2,minmax(0,1fr)); grid-template-rows:none; } .thumbnail-rail { grid-auto-columns:calc((100% - 12px) / 2); } .event-detail-card { grid-template-columns:170px minmax(0,1fr); gap:20px; padding-right:2px; padding-left:2px; } .event-copy { padding-left:20px; } }
@media (max-width:639px) { .sort-toolbar { align-items:flex-start; padding:14px; } .event-card-list { gap:24px; } .event-album { border-radius:16px; padding:10px; } .album-stage,.side-photo-grid { gap:8px; } .featured-photo { border-radius:13px; } .secondary-photo { border-radius:10px; } .thumbnail-rail-shell { margin-top:8px; } .thumbnail-rail { grid-auto-columns:calc((100% - 8px) / 2); gap:8px; } .rail-navigation { height:34px; width:34px; font-size:22px; } .rail-navigation--left { left:6px; } .rail-navigation--right { right:6px; } .featured-label { bottom:9px; left:9px; padding:5px 8px; font-size:8px; } .event-detail-card { grid-template-columns:1fr; gap:16px; margin-top:18px; padding:18px 4px 4px; } .event-date-panel { min-height:auto; justify-content:flex-start; } .event-copy { border-top:1px solid #dce5ef; border-left:0; padding-top:16px; padding-left:0; } .sort-toolbar button:hover { transform:none; } }
@media (prefers-reduced-motion:reduce) { .featured-swap-enter-active,.featured-swap-leave-active { transition-duration:1ms; } .thumbnail-rail { scroll-behavior:auto; } }
</style>
