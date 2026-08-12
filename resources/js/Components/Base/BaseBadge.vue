<script setup>defineProps({ active: Boolean });</script>
<template><span :class="active ? 'bg-[#1d63dc] text-white ring-[#1d63dc]' : 'bg-white text-[#414753] ring-[#cbd3df] hover:ring-[#1d63dc]'" class="inline-flex min-h-8 items-center rounded-full px-4 text-xs font-semibold ring-1 transition"><slot /></span></template>
