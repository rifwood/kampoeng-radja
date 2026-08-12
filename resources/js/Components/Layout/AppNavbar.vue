<script setup>
import { Link } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const open = ref(false);
const scrolled = ref(false);
const items = [
    { label: 'Beranda', route: 'home' },
    { label: 'Tentang Kami', route: 'tentang-kami' },
    { label: 'Wahana', route: 'wahana' },
    { label: 'Galeri Event', route: 'galeri-event' },
];
const updateScroll = () => { scrolled.value = window.scrollY > 8; };

onMounted(() => {
    updateScroll();
    window.addEventListener('scroll', updateScroll, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('scroll', updateScroll));
</script>

<template>
    <header class="sticky top-0 z-50 px-4 pt-3 transition-all duration-300 lg:px-0 lg:pt-5">
        <nav
            :class="scrolled ? 'border-[#dce3ee]/80 bg-white/80 shadow-[0_8px_24px_rgba(0,45,99,.12)] backdrop-blur-md' : 'border-white/70 bg-white/72 backdrop-blur-sm'"
            class="mx-auto flex h-[68px] max-w-[1120px] items-center justify-between rounded-2xl border px-4 transition-[background-color,box-shadow,border-color] duration-300 lg:h-[76px] lg:px-7"
            aria-label="Navigasi utama"
        >
            <Link :href="route('home')" class="flex h-full items-center">
                <img
                    src="/assets/figma/logo-main-transparent.png"
                    alt="Taman Wisata Kampoeng Radja"
                    class="h-[58px] w-auto object-contain lg:h-[65px]"
                >
            </Link>
            <button
                class="grid h-9 w-9 place-items-center rounded-md border border-[#cbd5e1]/80 bg-white/50 text-[#424752] md:hidden"
                :aria-expanded="open"
                aria-label="Buka menu"
                @click="open = !open"
            >☰</button>
            <div class="hidden h-full items-center gap-8 md:flex">
                <Link
                    v-for="item in items"
                    :key="item.route"
                    :href="route(item.route)"
                    :class="route().current(item.route) ? 'text-[#904d00]' : 'text-[#424752] hover:text-[#004e9f]'"
                    class="flex h-full items-center text-[13px] font-bold transition-colors"
                >{{ item.label }}</Link>
            </div>
        </nav>
        <div v-if="open" class="mx-auto max-w-[1120px] rounded-b-2xl border border-t-0 border-[#e1e2eb] bg-white/95 px-5 py-3 shadow-lg backdrop-blur-md md:hidden">
            <Link
                v-for="item in items"
                :key="item.route"
                :href="route(item.route)"
                class="block rounded-lg px-4 py-3 font-semibold text-[#424752]"
                @click="open = false"
            >{{ item.label }}</Link>
        </div>
    </header>
</template>
