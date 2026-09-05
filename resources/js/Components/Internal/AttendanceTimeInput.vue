<script setup>
import { computed, ref, watch } from "vue";

const props = defineProps({
    modelValue: { type: String, default: "" },
    disabled: { type: Boolean, default: false },
    label: { type: String, required: true },
    error: { type: String, default: "" },
    minTime: { type: String, default: "" },
    maxTime: { type: String, default: "" },
    indicator: { type: String, default: "" },
});

const emit = defineEmits([
    "update:modelValue",
    "navigate-next",
    "navigate-row",
]);
const hasBlurred = ref(false);
const input = ref(null);
const timePattern = /^([01]\d|2[0-3]):[0-5]\d$/;

const localError = computed(() => {
    if (props.disabled || !props.modelValue) return "";

    if (!timePattern.test(props.modelValue)) {
        return hasBlurred.value || props.modelValue.length === 5
            ? "Gunakan format HH:MM (00:00–23:59)."
            : "";
    }

    if (
        props.maxTime &&
        timePattern.test(props.maxTime) &&
        props.modelValue > props.maxTime
    ) {
        return `Jam masuk maksimal pukul ${props.maxTime}.`;
    }

    if (
        props.minTime &&
        timePattern.test(props.minTime) &&
        props.modelValue < props.minTime
    ) {
        return "Jam keluar tidak boleh lebih awal dari jam masuk.";
    }

    return "";
});

const visibleError = computed(() => localError.value || props.error);

const handleInput = (event) => {
    emit("update:modelValue", event.target.value);
};

defineExpose({
    focus: () => input.value?.focus(),
});

watch(
    () => props.disabled,
    (disabled) => {
        if (disabled) hasBlurred.value = false;
    },
);
</script>

<template>
    <div class="w-full min-w-[104px]">
        <input
            ref="input"
            :value="modelValue"
            type="time"
            step="60"
            :min="minTime || undefined"
            :max="maxTime || undefined"
            :aria-label="label"
            :aria-invalid="visibleError ? 'true' : 'false'"
            :disabled="disabled"
            class="h-9 w-full rounded-md border px-2.5 text-xs tabular-nums outline-none transition focus:border-[#2867e8] focus:ring-2 focus:ring-[#2867e8]/15 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-[#f1f5f9] disabled:text-slate-400"
            :class="visibleError ? 'border-red-400' : 'border-[#cbd5e1]'"
            @input="handleInput"
            @blur="hasBlurred = true"
            @keydown.enter.prevent="emit('navigate-next')"
            @keydown.up.prevent="emit('navigate-row', -1)"
            @keydown.down.prevent="emit('navigate-row', 1)"
        />
        <p v-if="visibleError" class="mt-1 text-[10px] leading-4 text-red-600">
            {{ visibleError }}
        </p>
        <p
            v-else-if="indicator"
            class="mt-1 text-[10px] font-semibold leading-4 text-amber-600"
        >
            {{ indicator }}
        </p>
    </div>
</template>
