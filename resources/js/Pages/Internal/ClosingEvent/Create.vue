<script setup>
import ClosingEventForm from "./ClosingEventForm.vue";
import InternalDashboardLayout from "@/Layouts/InternalDashboardLayout.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    user: { type: Object, required: true },
    masterData: { type: Object, required: true },
    permissions: { type: Object, required: true },
});
</script>
<template>
    <Head title="Tambah Closing Event" /><InternalDashboardLayout
        :user="user"
        title="Tambah Closing Event"
        ><div class="mx-auto max-w-[1240px] px-4 py-6 sm:px-6 lg:px-7">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-[#172554]">
                    Tambah Closing Event
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Lengkapi informasi kegiatan dan kebutuhan konsumen.
                </p>
            </div>
            <ClosingEventForm :master-data="masterData" /></div
    ></InternalDashboardLayout>
</template>
