<script setup>
import { onBeforeUnmount, onMounted } from 'vue';

defineProps({
    open: Boolean,
    title: { type: String, default: 'Pratinjau' },
    panelClass: { type: String, default: '' },
});

const emit = defineEmits(['close']);
const onKeydown = (event) => {
    if (event.key === 'Escape') emit('close');
};

onMounted(() => window.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown));
</script>

<template>
    <Teleport to="body">
        <div
            v-if="open"
            class="fixed inset-0 z-[60] grid place-items-center bg-neutral-dark/70 p-4"
            role="dialog"
            aria-modal="true"
            :aria-label="title"
            @click.self="emit('close')"
        >
            <div
                :class="panelClass || 'max-w-3xl'"
                class="max-h-full w-full overflow-auto rounded-3xl bg-white p-5 shadow-2xl"
            >
                <div class="mb-4 flex items-center justify-between gap-4">
                    <h2 class="font-heading text-2xl font-bold">{{ title }}</h2>
                    <button
                        class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-neutral-dark text-xl text-white transition hover:bg-[#ff8a1f] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0878de]"
                        aria-label="Tutup"
                        @click="emit('close')"
                    >
                        ×
                    </button>
                </div>
                <slot />
            </div>
        </div>
    </Teleport>
</template>
