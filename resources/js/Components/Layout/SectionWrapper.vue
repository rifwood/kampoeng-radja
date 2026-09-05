<script setup>
defineProps({ tone: { type: String, default: "white" } });
</script>
<template>
    <section
        :class="
            tone === 'blue'
                ? 'bg-primary-blue/15'
                : tone === 'yellow'
                  ? 'bg-secondary-yellow/25'
                  : 'bg-white'
        "
        class="py-12 sm:py-16 lg:py-20"
    >
        <slot />
    </section>
</template>
