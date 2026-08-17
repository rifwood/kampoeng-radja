<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    employeeName: {
        type: String,
        required: true,
    },
    roleName: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard Admin
            </h1>
        </template>

        <section class="py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <div class="space-y-6 p-6 text-gray-900 sm:p-8">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-primary-blue">
                                Kampoeng Radja
                            </p>
                            <h2 class="mt-2 text-2xl font-bold">Dashboard Admin</h2>
                        </div>

                        <dl class="grid gap-4 rounded-lg bg-gray-50 p-5 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm text-gray-500">Login sebagai</dt>
                                <dd class="mt-1 font-semibold">{{ employeeName }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Role</dt>
                                <dd class="mt-1 font-semibold">{{ roleName }}</dd>
                            </div>
                        </dl>

                        <p class="text-gray-600">CMS Landing Page segera tersedia.</p>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
