<script setup>
import { onBeforeUnmount, onMounted } from 'vue';
const props = defineProps({ open: Boolean, title: { type: String, default: 'Pratinjau' } });
const emit = defineEmits(['close']);
const onKeydown = (event) => { if (event.key === 'Escape') emit('close'); };
onMounted(() => window.addEventListener('keydown', onKeydown)); onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown));
</script>
<template><Teleport to="body"><div v-if="open" class="fixed inset-0 z-[60] grid place-items-center bg-neutral-dark/70 p-4" role="dialog" aria-modal="true" :aria-label="title" @click.self="emit('close')"><div class="max-h-full w-full max-w-3xl overflow-auto rounded-3xl bg-white p-5 shadow-2xl"><div class="mb-4 flex items-center justify-between gap-4"><h2 class="font-heading text-2xl font-bold">{{ title }}</h2><button class="grid h-11 w-11 place-items-center rounded-full bg-neutral-dark text-white" aria-label="Tutup" @click="emit('close')">×</button></div><slot /></div></div></Teleport></template>
