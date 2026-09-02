<script setup>
import {
    acceptConfirmation,
    cancelConfirmation,
    confirmationState,
} from '@/composables/useConfirmation';
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';

const confirmButton = ref(null);
let previousFocus = null;

const palette = computed(() => ({
    save: {
        icon: 'bg-blue-100 text-blue-600',
        button: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300',
    },
    edit: {
        icon: 'bg-emerald-100 text-emerald-600',
        button: 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-300',
    },
    delete: {
        icon: 'bg-red-100 text-red-600',
        button: 'bg-red-600 hover:bg-red-700 focus:ring-red-300',
    },
    warning: {
        icon: 'bg-amber-100 text-amber-600',
        button: 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-300',
    },
}[confirmationState.type] || {
    icon: 'bg-blue-100 text-blue-600',
    button: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300',
}));

watch(() => confirmationState.open, async (open) => {
    if (open) {
        previousFocus = document.activeElement;
        document.body.style.overflow = 'hidden';
        await nextTick();
        confirmButton.value?.focus();
        return;
    }

    document.body.style.overflow = '';
    previousFocus?.focus?.();
});

const close = () => cancelConfirmation();
const onKeydown = (event) => {
    if (event.key === 'Escape') close();
};

onBeforeUnmount(() => {
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="confirmationState.open" class="fixed inset-0 z-[120] flex items-center justify-center bg-slate-950/40 p-4" @click.self="close" @keydown="onKeydown">
                <section class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-5 shadow-2xl sm:p-6" role="dialog" aria-modal="true" :aria-labelledby="`global-confirmation-title`">
                    <div class="flex items-start gap-4">
                        <span :class="palette.icon" class="grid h-10 w-10 shrink-0 place-items-center rounded-full" aria-hidden="true">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path v-if="confirmationState.type === 'delete'" d="M12 8v5m0 3h.01"/><path v-else d="M12 8v5m0 3h.01"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <h2 id="global-confirmation-title" class="text-base font-bold text-slate-900">{{ confirmationState.title }}</h2>
                            <p class="mt-1.5 text-sm leading-6 text-slate-600">{{ confirmationState.message }}</p>
                            <p v-if="confirmationState.description" :class="confirmationState.type === 'delete' ? 'text-red-600' : confirmationState.type === 'warning' ? 'text-amber-700' : 'text-slate-500'" class="mt-1 text-xs leading-5">{{ confirmationState.description }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2.5">
                        <button type="button" class="h-9 rounded-lg border border-slate-300 bg-white px-4 text-xs font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200" @click="close">{{ confirmationState.cancelText }}</button>
                        <button ref="confirmButton" type="button" :class="palette.button" class="h-9 rounded-lg px-4 text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-1" @click="acceptConfirmation">{{ confirmationState.confirmText }}</button>
                    </div>
                </section>
            </div>
        </Transition>
    </Teleport>
</template>
