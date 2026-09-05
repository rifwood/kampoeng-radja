<script setup>
import { computed, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import PublicLayout from "../Layouts/PublicLayout.vue";
import PageContainer from "../Components/Layout/PageContainer.vue";
import BaseCard from "../Components/Base/BaseCard.vue";

const props = defineProps({ events: { type: Array, default: () => [] } });
const order = ref("newest");
const featuredPhotoIds = ref({});
const thumbnailPages = ref({});

const sortedEvents = computed(() =>
    [...props.events].sort((a, b) => {
        const difference = new Date(b.event_date) - new Date(a.event_date);
        const stableIdDifference = Number(b.id) - Number(a.id);

        return order.value === "newest"
            ? difference || stableIdDifference
            : -difference || -stableIdDifference;
    }),
);

const dateLabel = (value) =>
    value
        ? new Intl.DateTimeFormat("id-ID", {
              day: "numeric",
              month: "long",
              year: "numeric",
          }).format(new Date(`${value}T00:00:00`))
        : "[PLACEHOLDER: Tanggal event]";

const eventParagraphs = (description) =>
    (description || "[PLACEHOLDER: Deskripsi event belum tersedia]")
        .split(/\r?\n+/)
        .map((paragraph) => paragraph.trim())
        .filter(Boolean);

const eventPhotos = (event) =>
    [...(event.photos || [])].sort((a, b) => {
        const firstOrder = a.urutan ?? Number.MAX_SAFE_INTEGER;
        const secondOrder = b.urutan ?? Number.MAX_SAFE_INTEGER;
        return firstOrder - secondOrder || a.id - b.id;
    });
const featuredPhoto = (event) => {
    const photos = eventPhotos(event);
    return (
        photos.find((photo) => photo.id === featuredPhotoIds.value[event.id]) ||
        photos[0] ||
        null
    );
};
const secondaryPhotos = (event) =>
    eventPhotos(event).filter((photo) => photo.id !== featuredPhoto(event)?.id);
const thumbnailPageCount = (event) =>
    Math.max(1, Math.ceil(secondaryPhotos(event).length / 6));
const visibleThumbnails = (event) => {
    const photos = secondaryPhotos(event);
    const page = Math.min(
        thumbnailPages.value[event.id] ?? 0,
        thumbnailPageCount(event) - 1,
    );

    return photos.slice(page * 6, page * 6 + 6);
};

const selectFeatured = (event, photo) => {
    featuredPhotoIds.value = {
        ...featuredPhotoIds.value,
        [event.id]: photo.id,
    };
};

const moveThumbnailPage = (event, direction) => {
    const pageCount = thumbnailPageCount(event);
    const currentPage = thumbnailPages.value[event.id] ?? 0;

    thumbnailPages.value = {
        ...thumbnailPages.value,
        [event.id]: (currentPage + direction + pageCount) % pageCount,
    };
};
</script>

<template>
    <Head title="Galeri Event"
        ><meta
            name="description"
            content="[PLACEHOLDER: Meta description Galeri Event]"
    /></Head>
    <PublicLayout>
        <section class="gallery-page pb-14 sm:pb-20">
            <header class="gallery-header">
                <PageContainer>
                    <h1
                        class="font-heading text-3xl font-bold text-[#0756a6] sm:text-4xl lg:text-[42px]"
                    >
                        Galeri Event Kampoeng Radja
                    </h1>
                    <p
                        class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-[#5b6472]"
                    >
                        Mendokumentasikan keceriaan dan momen tak terlupakan.
                        Temukan kembali kenangan manis Anda bersama keluarga dan
                        sahabat di setiap acara kami.
                    </p>
                </PageContainer>
            </header>

            <PageContainer>
                <div class="sort-toolbar">
                    <p
                        class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.1em] text-[#5b6472]"
                    >
                        <svg
                            aria-hidden="true"
                            viewBox="0 0 24 24"
                            class="h-4 w-4 text-[#0756a6]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                d="M4 7h16M7 12h10M10 17h4"
                                stroke-linecap="round"
                            />
                        </svg>
                        Urutan dokumentasi
                    </p>
                    <div
                        class="inline-flex rounded-lg border border-[#d8dee8] bg-[#f7f9fc] p-1"
                        aria-label="Urutan Galeri Event"
                    >
                        <button
                            type="button"
                            :aria-pressed="order === 'newest'"
                            :class="
                                order === 'newest'
                                    ? 'bg-white text-[#0756a6] shadow-sm ring-1 ring-[#bfd5ed]'
                                    : 'text-[#667085]'
                            "
                            class="h-8 rounded-md px-4 text-xs font-semibold transition"
                            @click="order = 'newest'"
                        >
                            Terbaru
                        </button>
                        <button
                            type="button"
                            :aria-pressed="order === 'oldest'"
                            :class="
                                order === 'oldest'
                                    ? 'bg-white text-[#0756a6] shadow-sm ring-1 ring-[#bfd5ed]'
                                    : 'text-[#667085]'
                            "
                            class="h-8 rounded-md px-4 text-xs font-semibold transition"
                            @click="order = 'oldest'"
                        >
                            Terlama
                        </button>
                    </div>
                </div>
                <div v-if="sortedEvents.length" class="event-card-list">
                    <article
                        v-for="(event, index) in sortedEvents"
                        :key="event.id"
                        :class="
                            index % 2 === 0
                                ? 'event-album--red'
                                : 'event-album--blue'
                        "
                        class="event-album"
                        :aria-labelledby="`event-title-${event.id}`"
                    >
                        <header
                            class="event-heading"
                            style="text-align: center"
                        >
                            <!-- //<span class="event-title-accent" aria-hidden="true"></span> -->
                            <h2
                                :id="`event-title-${event.id}`"
                                class="m-8 text-center font-heading text-lg font-bold text-[#0756a6] sm:text-3xl"
                            >
                                {{ event.title }}
                            </h2>
                        </header>

                        <div
                            v-if="eventPhotos(event).length"
                            class="gallery-grid"
                            :class="{
                                'gallery-grid--single':
                                    eventPhotos(event).length === 1,
                            }"
                        >
                            <div class="featured-photo">
                                <Transition name="featured-swap" mode="out-in">
                                    <img
                                        v-if="featuredPhoto(event)?.url"
                                        :key="featuredPhoto(event)?.id"
                                        :src="featuredPhoto(event)?.url"
                                        :alt="
                                            featuredPhoto(event)?.caption ||
                                            event.title
                                        "
                                        :loading="
                                            index === 0 ? 'eager' : 'lazy'
                                        "
                                        :fetchpriority="
                                            index === 0 ? 'high' : 'auto'
                                        "
                                        decoding="async"
                                        class="h-full w-full object-cover"
                                    />
                                </Transition>
                            </div>

                            <div
                                v-if="secondaryPhotos(event).length"
                                class="thumbnail-panel"
                            >
                                <div class="thumbnail-grid">
                                    <button
                                        v-for="photo in visibleThumbnails(
                                            event,
                                        )"
                                        :key="photo.id"
                                        type="button"
                                        class="thumbnail-photo"
                                        :aria-label="`Jadikan foto ini sebagai foto utama ${event.title}`"
                                        @click="selectFeatured(event, photo)"
                                    >
                                        <img
                                            :src="photo.url"
                                            :alt="photo.caption || event.title"
                                            loading="lazy"
                                            decoding="async"
                                            class="h-full w-full object-cover"
                                        />
                                    </button>
                                </div>

                                <div
                                    v-if="thumbnailPageCount(event) > 1"
                                    class="thumbnail-navigation"
                                    :aria-label="`Navigasi foto ${event.title}`"
                                >
                                    <button
                                        type="button"
                                        :aria-label="`Foto sebelumnya untuk ${event.title}`"
                                        @click="moveThumbnailPage(event, -1)"
                                    >
                                        ‹
                                    </button>
                                    <span
                                        >{{
                                            (thumbnailPages[event.id] ?? 0) + 1
                                        }}
                                        / {{ thumbnailPageCount(event) }}</span
                                    >
                                    <button
                                        type="button"
                                        :aria-label="`Foto berikutnya untuk ${event.title}`"
                                        @click="moveThumbnailPage(event, 1)"
                                    >
                                        ›
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="album-empty-photo">
                            Belum ada foto untuk event ini.
                        </div>

                        <div class="event-information">
                            <div class="min-w-0">
                                <p class="event-information-label">
                                    Informasi Event
                                </p>
                                <div
                                    class="event-description mt-2 text-sm leading-6 text-[#596273]"
                                >
                                    <p
                                        v-for="(
                                            paragraph, paragraphIndex
                                        ) in eventParagraphs(event.description)"
                                        :key="paragraphIndex"
                                    >
                                        {{ paragraph }}
                                    </p>
                                </div>
                                <p
                                    class="mt-3 flex items-center gap-2 text-xs font-semibold text-[#697386]"
                                >
                                    <svg
                                        aria-hidden="true"
                                        viewBox="0 0 24 24"
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="16"
                                            rx="2"
                                        />
                                        <path
                                            d="M16 3v4M8 3v4M3 10h18"
                                            stroke-linecap="round"
                                        />
                                    </svg>
                                    {{ dateLabel(event.event_date) }}
                                </p>
                            </div>
                        </div>
                    </article>
                </div>

                <BaseCard v-else class="mt-8 p-10 text-center"
                    ><p class="font-heading text-2xl text-[#002147]">
                        Dokumentasi event belum tersedia
                    </p>
                    <p class="mt-2 text-sm text-[#414753]">
                        Belum ada Galeri Event yang dapat ditampilkan.
                    </p></BaseCard
                >
            </PageContainer>
        </section>
    </PublicLayout>
</template>

<style scoped>
.gallery-page {
    min-height: 70vh;
    background: #f5f7fa;
}
.gallery-header {
    padding: 58px 0 42px;
    text-align: center;
    background: #f8fafc;
}
.sort-toolbar {
    display: flex;
    min-height: 58px;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 26px;
    border: 1px solid #e1e6ed;
    border-radius: 12px;
    padding: 10px 14px 10px 18px;
    background: rgba(255, 255, 255, 0.96);
    box-shadow: 0 4px 14px rgba(27, 55, 89, 0.04);
}
.event-card-list {
    display: grid;
    gap: 42px;
    margin-top: 30px;
}
.event-album {
    --event-accent: #d84646;
    --event-tint: #fff5f5;
    overflow: hidden;
    border: 1px solid #e1e6ed;
    border-radius: 18px;
    padding: 20px;
    background: #fff;
    box-shadow: 0 9px 24px rgba(34, 56, 82, 0.07);
}
.event-album--blue {
    --event-accent: #2379c9;
    --event-tint: #f2f8ff;
}
.event-heading {
    margin-bottom: 16px;
    border-bottom: 1px solid #e5eaf0;
}
.event-title-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}
.event-title-accent {
    width: 12px;
    height: 12px;
    flex: 0 0 auto;
    border: 3px solid color-mix(in srgb, var(--event-accent), white 74%);
    border-radius: 999px;
    background: var(--event-accent);
    box-shadow: 0 0 0 3px
        color-mix(in srgb, var(--event-accent), transparent 88%);
}
.gallery-grid {
    display: grid;
    min-height: 390px;
    grid-template-columns: minmax(0, 1.82fr) minmax(300px, 0.95fr);
    gap: 12px;
}
.gallery-grid--single {
    min-height: 0;
    grid-template-columns: 1fr;
}
.featured-photo,
.thumbnail-photo {
    overflow: hidden;
    border-radius: 12px;
    background: #e8edf3;
}
.featured-photo {
    position: relative;
    min-width: 0;
    min-height: 390px;
}
.gallery-grid--single .featured-photo {
    aspect-ratio: 16/9;
    min-height: 0;
}
.thumbnail-panel {
    position: relative;
    min-width: 0;
    min-height: 0;
}
.thumbnail-grid {
    display: grid;
    height: 100%;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-template-rows: repeat(3, minmax(0, 1fr));
    gap: 10px;
}
.thumbnail-photo {
    position: relative;
    width: 100%;
    min-width: 0;
    min-height: 0;
    cursor: pointer;
}
.thumbnail-photo::after {
    position: absolute;
    inset: 0;
    content: "";
    background: rgba(7, 86, 166, 0);
    transition: background-color 220ms ease;
}
.thumbnail-photo:hover::after,
.thumbnail-photo:focus-visible::after {
    background: rgba(7, 86, 166, 0.13);
}
.thumbnail-photo:focus-visible {
    outline: 3px solid rgba(35, 121, 201, 0.35);
    outline-offset: 2px;
}
.thumbnail-navigation {
    position: absolute;
    right: 8px;
    bottom: 8px;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border-radius: 999px;
    padding: 4px;
    color: #fff;
    background: rgba(22, 42, 66, 0.78);
    box-shadow: 0 5px 14px rgba(16, 35, 58, 0.2);
    backdrop-filter: blur(5px);
}
.thumbnail-navigation button {
    display: grid;
    width: 28px;
    height: 28px;
    place-items: center;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.14);
    font-size: 20px;
    line-height: 1;
}
.thumbnail-navigation span {
    min-width: 34px;
    font-size: 10px;
    font-weight: 700;
    text-align: center;
}
.event-information {
    margin-top: 18px;
    border: 1px solid color-mix(in srgb, var(--event-accent), white 79%);
    border-radius: 13px;
    padding: 17px 18px;
    background: var(--event-tint);
}
.event-information-label {
    color: var(--event-accent);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.13em;
    text-transform: uppercase;
}
.event-description {
    text-align: justify;
}
.event-description p {
    text-indent: 1.75rem;
}
.event-description p + p {
    margin-top: 0.75rem;
}
.album-empty-photo {
    display: grid;
    min-height: 250px;
    place-items: center;
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    color: #64748b;
    background: #f8fafc;
    font-size: 13px;
}
.featured-swap-enter-active,
.featured-swap-leave-active {
    transition: opacity 280ms ease;
}
.featured-swap-enter-from,
.featured-swap-leave-to {
    opacity: 0;
}
@media (max-width: 899px) {
    .gallery-grid {
        min-height: 330px;
        grid-template-columns: minmax(0, 1.55fr) minmax(250px, 1fr);
    }
    .featured-photo {
        min-height: 330px;
    }
}
@media (max-width: 767px) {
    .gallery-header {
        padding: 44px 0 34px;
    }
    .event-card-list {
        gap: 30px;
    }
    .event-album {
        padding: 14px;
    }
    .gallery-grid {
        min-height: 0;
        grid-template-columns: 1fr;
    }
    .featured-photo {
        aspect-ratio: 16/9;
        min-height: 0;
    }
    .thumbnail-panel {
        min-height: 0;
    }
    .thumbnail-grid {
        height: auto;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        grid-template-rows: repeat(2, auto);
        gap: 8px;
    }
    .thumbnail-photo {
        aspect-ratio: 4/3;
    }
}
@media (max-width: 639px) {
    .sort-toolbar {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px;
    }
    .sort-toolbar > div {
        width: 100%;
    }
    .sort-toolbar button {
        flex: 1;
    }
    .event-album {
        border-radius: 15px;
        padding: 11px;
    }
    .event-information {
        padding: 14px;
    }
    .event-description {
        text-align: left;
    }
}
@media (prefers-reduced-motion: reduce) {
    .featured-swap-enter-active,
    .featured-swap-leave-active {
        transition-duration: 1ms;
    }
}
</style>
