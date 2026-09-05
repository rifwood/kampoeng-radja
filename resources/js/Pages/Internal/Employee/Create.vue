<script setup>
import InternalDashboardLayout from "@/Layouts/InternalDashboardLayout.vue";
import EmployeeForm from "./EmployeeForm.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    user: { type: Object, required: true },
    masterData: { type: Object, required: true },
});
</script>
<template>
    <Head title="Tambah Karyawan" />
    <InternalDashboardLayout
        :user="user"
        title="Tambah Karyawan"
        :can-manage-employee-masters="true"
    >
        <div class="mx-auto max-w-[1180px] px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-[#15356f]">
                    Tambah Karyawan
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Lengkapi data pribadi, pekerjaan, dan dokumen karyawan.
                </p>
            </div>
            <EmployeeForm :master-data="masterData" />
        </div>
    </InternalDashboardLayout>
</template>
