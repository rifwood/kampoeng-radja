<script setup>
defineProps({
    open: { type: Boolean, required: true },
    title: { type: String, required: true },
    message: { type: String, required: true },
    confirmLabel: { type: String, default: "Hapus" },
    processing: { type: Boolean, default: false },
});

defineEmits(["cancel", "confirm"]);
</script>

<template>
    <div
        v-if="open"
        class="fixed inset-0 z-[90] grid place-items-center bg-slate-950/50 p-4"
        role="dialog"
        aria-modal="true"
        :aria-label="title"
        @click.self="$emit('cancel')"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-[#172554]">{{ title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ message }}</p>
            <div class="mt-6 flex justify-end gap-2">
                <button
                    type="button"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50"
                    @click="$emit('cancel')"
                >
                    Batal
                </button>
                <button
                    type="button"
                    :disabled="processing"
                    class="rounded-lg bg-red-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-red-700 disabled:opacity-60"
                    @click="$emit('confirm')"
                >
                    {{ processing ? "Menghapus..." : confirmLabel }}
                </button>
            </div>
        </div>
    </div>
</template>
