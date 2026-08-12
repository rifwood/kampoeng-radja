<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ href: { type: String, default: null }, variant: { type: String, default: 'primary' }, type: { type: String, default: 'button' } });
</script>
<template>
  <component :is="href ? Link : 'button'" :href="href || undefined" :type="href ? undefined : type" :class="['inline-flex min-h-9 items-center justify-center rounded-lg px-5 py-2 text-sm font-bold transition focus:outline-none focus:ring-4 focus:ring-[#004e9f]/25', variant === 'primary' ? 'bg-[#004e9f] text-white hover:bg-[#003d7d]' : variant === 'outline' ? 'border border-[#004e9f] bg-white text-[#004e9f] hover:bg-[#eef5ff]' : 'bg-[#004e9f] text-white hover:bg-[#003d7d]']"><slot /></component>
</template>
