<script setup>
import { ref } from "vue";

const props = defineProps({
    title: { type: String, required: true },
    description: { type: String, required: true },
    icon: { type: String, required: true },
    defaultOpen: { type: Boolean, default: false },
});

const open = ref(props.defaultOpen);
</script>

<template>
    <section
        class="overflow-hidden rounded-xl border border-[#dbe2ea] bg-white shadow-[0_2px_8px_rgba(15,23,42,0.05)]"
    >
        <button
            type="button"
            class="flex w-full items-center gap-3 px-4 py-4 text-left transition hover:bg-slate-50 sm:px-5"
            :aria-expanded="open"
            @click="open = !open"
        >
            <span
                class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-sm font-extrabold text-[#0756d8]"
                aria-hidden="true"
                >{{ icon }}</span
            >
            <span class="min-w-0 flex-1">
                <strong class="block text-sm font-bold text-[#172554]">{{
                    title
                }}</strong>
                <small class="mt-0.5 block text-xs leading-5 text-slate-500">{{
                    description
                }}</small>
            </span>
            <svg
                :class="open ? 'rotate-180' : ''"
                class="h-5 w-5 shrink-0 text-slate-400 transition-transform"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="m7 10 5 5 5-5" />
            </svg>
        </button>

        <div v-show="open" class="border-t border-slate-100">
            <slot />
        </div>
    </section>
</template>
