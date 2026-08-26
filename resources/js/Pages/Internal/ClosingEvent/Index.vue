<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    user: { type: Object, required: true },
    events: { type: Object, required: true },
    filters: { type: Object, required: true },
    years: { type: Array, required: true },
    permissions: { type: Object, required: true },
});

const page = usePage();
const filter = reactive({ bulan: props.filters.bulan, tahun: props.filters.tahun, status: props.filters.status ?? '' });
const deleteTarget = ref(null);
const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
const exportUrl = computed(() => route('dashboard.closing-event.export', {
    bulan: filter.bulan,
    tahun: filter.tahun,
}));
const months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];

const applyFilter = () => router.get(
    route('dashboard.closing-event.index'),
    filter,
    { preserveState: true, replace: true },
);
const number = (value) => new Intl.NumberFormat('id-ID').format(value);
const remove = () => router.delete(
    route('dashboard.closing-event.destroy', deleteTarget.value.id),
    { onSuccess: () => { deleteTarget.value = null; } },
);
</script>

<template>
    <Head title="Data Closing Event" />

    <InternalDashboardLayout :user="user" title="Data Closing Event">
        <div class="mx-auto max-w-[1540px] px-4 py-6 sm:px-6 lg:px-7">
            <div
                v-if="success"
                class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
            >
                {{ success }}
            </div>
            <div
                v-if="error"
                class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                {{ error }}
            </div>

            <header class="mb-5">
                <h2 class="text-2xl font-bold tracking-tight text-[#172554]">Data Closing Event</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola dan pantau data kegiatan event Kampoeng Radja.</p>
            </header>

            <section class="mb-5 rounded-xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm sm:px-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <label class="block text-xs font-semibold text-slate-600">
                            Bulan
                            <select
                                v-model="filter.bulan"
                                class="mt-1.5 block h-9 w-full rounded-lg border-slate-300 py-1.5 pr-9 text-sm text-slate-700 focus:border-[#1769e0] focus:ring-[#1769e0] sm:w-[180px]"
                                @change="applyFilter"
                            >
                                <option v-for="(month, index) in months" :key="month" :value="index + 1">
                                    {{ month }}
                                </option>
                            </select>
                        </label>

                        <label class="block text-xs font-semibold text-slate-600">
                            Tahun
                            <select
                                v-model="filter.tahun"
                                class="mt-1.5 block h-9 w-full rounded-lg border-slate-300 py-1.5 pr-9 text-sm text-slate-700 focus:border-[#1769e0] focus:ring-[#1769e0] sm:w-[130px]"
                                @change="applyFilter"
                            >
                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                            </select>
                        </label>

                        <label class="block text-xs font-semibold text-slate-600">
                            Status Event
                            <select
                                v-model="filter.status"
                                class="mt-1.5 block h-9 w-full rounded-lg border-slate-300 py-1.5 pr-9 text-sm text-slate-700 focus:border-[#1769e0] focus:ring-[#1769e0] sm:w-[160px]"
                                @change="applyFilter"
                            >
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                        <a
                            v-if="permissions.canExport"
                            :href="exportUrl"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M12 3v12m0 0 4-4m-4 4-4-4" />
                                <path d="M5 14v5h14v-5" />
                            </svg>
                            Export Excel
                        </a>
                        <Link
                            v-if="permissions.canCreate"
                            :href="route('dashboard.closing-event.create')"
                            class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-[#1769e0] px-4 text-xs font-semibold text-white shadow-sm transition hover:bg-[#0756ba]"
                        >
                            <span class="text-base leading-none">+</span>
                            Tambah Closing Event
                        </Link>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto 2xl:overflow-x-visible">
                    <table class="w-full min-w-[1220px] table-fixed text-left text-[11px] 2xl:min-w-full">
                        <colgroup>
                            <col class="w-[14%]">
                            <col class="w-[6%]">
                            <col class="w-[10%]">
                            <col class="w-[9%]">
                            <col class="w-[12%]">
                            <col class="w-[7%]">
                            <col class="w-[8%]">
                            <col class="w-[6%]">
                            <col class="w-[9%]">
                            <col class="w-[9%]">
                            <col class="w-[10%]">
                        </colgroup>
                        <thead class="border-b border-slate-200 bg-slate-50/90 text-[10px] font-semibold uppercase tracking-[0.04em] text-slate-500">
                            <tr>
                                <th class="px-3 py-3">Tanggal</th>
                                <th class="px-2 py-3">PIC</th>
                                <th class="px-3 py-3">Konsumen</th>
                                <th class="px-2 py-3">Jenis Event</th>
                                <th class="px-2 py-3">Lokasi</th>
                                <th class="px-2 py-3">Jam Kedatangan</th>
                                <th class="px-2 py-3 text-center">Jumlah Pengunjung</th>
                                <th class="px-2 py-3 text-center">Konsumsi</th>
                                <th class="px-2 py-3">Additional</th>
                                <th class="px-2 py-3">Panitia</th>
                                <th class="px-2 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="item in events.data"
                                :key="item.id"
                                :class="item.isOngoing ? 'closing-event-ongoing-row' : (item.isCancelled ? 'bg-slate-50/60 hover:bg-slate-100/70' : 'hover:bg-slate-50/70')"
                                class="transition-colors"
                            >
                                <td class="whitespace-nowrap px-3 py-3.5 font-semibold text-slate-700">
                                    <span class="block">{{ item.tanggalLabel }}</span>
                                    <span
                                        v-if="item.isOngoing"
                                        class="mt-1.5 inline-flex items-center gap-1 rounded-full border border-orange-200/70 bg-orange-50/90 px-1.5 py-0.5 text-[8px] font-semibold leading-4 text-orange-700"
                                    >
                                        <span class="ongoing-status-dot h-1.5 w-1.5 shrink-0 rounded-full bg-orange-500" aria-hidden="true" />
                                        Sedang Berlangsung
                                    </span>
                                    <span
                                        v-else-if="item.isCancelled"
                                        class="mt-1.5 inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[8px] font-semibold leading-4 text-rose-700"
                                    >
                                        Dibatalkan
                                    </span>
                                </td>
                                <td class="truncate px-2 py-3.5 font-semibold text-slate-700" :title="item.pic">
                                    {{ item.pic }}
                                </td>
                                <td class="px-3 py-3.5 align-middle">
                                    <Link
                                        :href="route('dashboard.closing-event.show', item.id)"
                                        class="block truncate font-semibold text-[#0756ba] hover:underline"
                                        :title="item.konsumen"
                                    >
                                        {{ item.konsumen }}
                                    </Link>
                                </td>
                                <td class="px-2 py-3.5">
                                    <span
                                        class="inline-flex max-w-full rounded-md bg-blue-50 px-2 py-1 text-[9px] font-semibold leading-4 text-[#0756ba]"
                                        :title="item.jenisEvent"
                                    >
                                        <span class="line-clamp-2">{{ item.jenisEvent }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-3.5">
                                    <div class="flex min-w-0 flex-wrap gap-1">
                                        <span
                                            v-for="place in item.lokasi.slice(0, 2)"
                                            :key="place.id"
                                            class="max-w-[88px] truncate rounded-md bg-slate-100 px-1.5 py-1 text-[9px] font-semibold text-slate-600"
                                            :title="place.name"
                                        >
                                            {{ place.name }}
                                        </span>
                                        <span
                                            v-if="item.lokasi.length > 2"
                                            class="rounded-md bg-slate-100 px-1.5 py-1 text-[9px] font-semibold text-slate-500"
                                            :title="item.lokasi.slice(2).map((place) => place.name).join(', ')"
                                        >
                                            +{{ item.lokasi.length - 2 }}
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-2 py-3.5 text-slate-600">{{ item.jamKedatangan }}</td>
                                <td class="px-2 py-3.5 text-center font-semibold tabular-nums text-slate-700">
                                    {{ number(item.jumlahPengunjung) }}
                                </td>
                                <td class="px-2 py-3.5 text-center">
                                    <span
                                        :class="item.konsumsi ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                        class="inline-flex rounded-full px-2 py-1 text-[9px] font-semibold"
                                    >
                                        {{ item.konsumsi ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3.5 align-middle text-slate-600">
                                    <span class="line-clamp-2 leading-4" :title="item.additional || '-'">
                                        {{ item.additional || '-' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3.5 align-middle text-slate-600">
                                    <span class="line-clamp-2 leading-4" :title="item.panitia || '-'">
                                        {{ item.panitia || '-' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3.5">
                                    <div class="flex items-center justify-center gap-1">
                                        <Link
                                            :href="route('dashboard.closing-event.show', item.id)"
                                            class="grid h-7 w-7 place-items-center rounded-md border border-blue-100 text-[#1769e0] transition hover:bg-blue-50"
                                            title="Lihat detail"
                                            aria-label="Lihat detail Closing Event"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </Link>
                                        <Link
                                            v-if="permissions.canUpdate"
                                            :href="route('dashboard.closing-event.edit', item.id)"
                                            class="grid h-7 w-7 place-items-center rounded-md border border-blue-100 text-[#1769e0] transition hover:bg-blue-50"
                                            title="Edit Closing Event"
                                            aria-label="Edit Closing Event"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="m4 16-.8 4 4-.8L18 8.4 15.6 6 4 16Z" />
                                                <path d="m14 7 3 3" />
                                            </svg>
                                        </Link>
                                        <button
                                            v-if="permissions.canDelete"
                                            type="button"
                                            class="grid h-7 w-7 place-items-center rounded-md border border-red-100 text-red-500 transition hover:bg-red-50"
                                            title="Hapus Closing Event"
                                            aria-label="Hapus Closing Event"
                                            @click="deleteTarget = item"
                                        >
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M4 7h16M9 7V4h6v3m-9 0 1 14h10l1-14M10 11v6m4-6v6" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="events.data.length === 0">
                                <td colspan="11" class="px-6 py-14 text-center">
                                    <p class="font-semibold text-slate-700">Belum ada Closing Event pada bulan ini.</p>
                                    <Link
                                        v-if="permissions.canCreate"
                                        :href="route('dashboard.closing-event.create')"
                                        class="mt-3 inline-flex text-sm font-semibold text-[#1769e0] hover:underline"
                                    >
                                        Tambah Closing Event
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <footer
                    v-if="events.data.length > 0"
                    class="flex flex-col gap-3 border-t border-slate-100 px-4 py-3 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between"
                >
                    <span>Menampilkan {{ events.from ?? 0 }}–{{ events.to ?? 0 }} dari {{ events.total }} data</span>
                    <div v-if="events.links.length > 3" class="flex flex-wrap gap-1">
                        <template v-for="link in events.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                preserve-scroll
                                :class="link.active ? 'bg-[#1769e0] text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="min-w-8 rounded-md px-2.5 py-1.5 text-center"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="min-w-8 rounded-md border border-slate-100 px-2.5 py-1.5 text-center text-slate-300"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </footer>
            </section>
        </div>

        <div
            v-if="deleteTarget"
            class="fixed inset-0 z-[80] grid place-items-center bg-slate-950/40 p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-closing-event-title"
            @click.self="deleteTarget = null"
        >
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                <h3 id="delete-closing-event-title" class="text-lg font-bold text-slate-800">Hapus Closing Event?</h3>
                <p class="mt-2 text-sm text-slate-500">
                    Data {{ deleteTarget.konsumen }} dan relasi lokasinya akan dihapus permanen.
                </p>
                <div class="mt-6 flex justify-end gap-2">
                    <button
                        type="button"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600"
                        @click="deleteTarget = null"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white"
                        @click="remove"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </InternalDashboardLayout>
</template>

<style scoped>
.closing-event-ongoing-row {
    background: linear-gradient(90deg, rgba(255, 247, 237, 0.95), rgba(239, 246, 255, 0.65), rgba(255, 247, 237, 0.95));
    background-size: 200% 100%;
    box-shadow: inset 3px 0 0 #f59e0b;
    animation: ongoing-row-shimmer 7s ease-in-out infinite;
}

.ongoing-status-dot {
    position: relative;
}

.ongoing-status-dot::after {
    position: absolute;
    inset: 0;
    content: '';
    border-radius: 9999px;
    background: rgba(249, 115, 22, 0.45);
    animation: ongoing-dot-ring 2s ease-out infinite;
}

@keyframes ongoing-row-shimmer {
    0%,
    100% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }
}

@keyframes ongoing-dot-ring {
    0% {
        opacity: 0.5;
        transform: scale(1);
    }

    80%,
    100% {
        opacity: 0;
        transform: scale(1.8);
    }
}

@media (prefers-reduced-motion: reduce) {
    .closing-event-ongoing-row {
        animation: none;
    }

    .ongoing-status-dot::after {
        animation: none;
        opacity: 0;
    }
}
</style>
