<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useConfirmation } from '@/composables/useConfirmation';

const props = defineProps({
    user: { type: Object, required: true },
    event: { type: Object, required: true },
    permissions: { type: Object, required: true },
});

const { confirm } = useConfirmation();
const currency = (value) => new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
}).format(value);
const locationCount = computed(() => props.event.lokasi.length);
const remove = async () => {
    const confirmed = await confirm({ type: 'delete', title: 'Hapus Closing Event', message: `Apakah Anda yakin ingin menghapus data ${props.event.konsumen}?`, description: 'Tindakan ini tidak dapat dibatalkan.', confirmText: 'Ya, Hapus' });
    if (confirmed) router.delete(route('dashboard.closing-event.destroy', props.event.id));
};
</script>

<template>
    <Head title="Detail Closing Event" />

    <InternalDashboardLayout :user="user" title="Detail Closing Event">
        <div class="min-h-full bg-[#f5f7fb]">
        <div class="mx-auto max-w-[1240px] px-4 py-6 sm:px-6 lg:px-7">

            <header class="mb-5 overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-[0_3px_12px_rgba(15,23,42,.05)]">
                <div class="h-1 bg-[#1769e0]"></div>
                <div class="flex flex-col gap-5 p-5 sm:p-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#1769e0]">Detail Closing Event</p>
                        <h2 class="mt-1.5 truncate text-2xl font-bold text-[#102a56]">{{ event.konsumen }}</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">{{ event.jenisEvent }} <span class="mx-1 text-slate-300">·</span> {{ event.tanggalLabel }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-[#0756ba] ring-1 ring-inset ring-blue-200"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>PIC: {{ event.pic }}</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">{{ locationCount }} Lokasi</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">{{ event.jumlahPengunjung }} Pengunjung</span>
                            <span
                                :class="event.isCancelled ? 'bg-rose-50 text-rose-700 ring-rose-200' : 'bg-emerald-50 text-emerald-700 ring-emerald-200'"
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset"
                            >
                                {{ event.statusLabel }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 lg:justify-end">
                    <Link
                        :href="route('dashboard.closing-event.index')"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                    >
                        Kembali
                    </Link>
                    <Link
                        v-if="permissions.canUpdate"
                        :href="route('dashboard.closing-event.edit', event.id)"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#1769e0] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0756ba]"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m4 20 4-1 11-11-3-3L5 16l-1 4Z" /><path d="m14 7 3 3" /></svg>
                        Edit
                    </Link>
                    <button
                        v-if="permissions.canDelete"
                        class="rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100"
                        @click="remove"
                    >
                        Hapus
                    </button>
                    </div>
                </div>
            </header>

            <div class="grid items-stretch gap-5 lg:grid-cols-2">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_8px_rgba(15,23,42,.04)]">
                    <div class="flex items-center gap-2.5 border-b border-blue-100 bg-blue-50/70 px-5 py-3.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-[#1769e0] ring-1 ring-blue-100"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2" /><path d="M16 3v4M8 3v4M3 10h18" /></svg></span><h3 class="font-semibold text-[#15356f]">Informasi Event</h3></div>
                    <dl class="space-y-3 p-5">
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Tanggal Mulai</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.tanggalMulaiLabel }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Tanggal Selesai</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd :class="event.tanggalSelesaiLabel ? 'text-slate-700' : 'text-slate-400'" class="text-sm font-semibold">{{ event.tanggalSelesaiLabel || '—' }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">PIC</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.pic }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Jenis Event</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.jenisEvent }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500 sm:py-1">Lokasi</dt><span class="hidden py-1 text-sm text-slate-300 sm:block">:</span><dd class="flex flex-wrap gap-1.5"><span v-for="place in event.lokasi" :key="place.id" class="inline-flex rounded-md bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-[#0756ba] ring-1 ring-inset ring-blue-100">{{ place.name }}</span><span v-if="!event.lokasi.length" class="text-sm text-slate-400">—</span></dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Jam Kedatangan</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold tabular-nums text-slate-700">{{ event.jamKedatangan }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Status Event</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd><span :class="event.isCancelled ? 'bg-rose-50 text-rose-700 ring-rose-200' : 'bg-emerald-50 text-emerald-700 ring-emerald-200'" class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset">{{ event.statusLabel }}</span></dd></div>
                    </dl>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_8px_rgba(15,23,42,.04)]">
                    <div class="flex items-center gap-2.5 border-b border-blue-100 bg-blue-50/70 px-5 py-3.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-[#1769e0] ring-1 ring-blue-100"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></svg></span><h3 class="font-semibold text-[#15356f]">Informasi Konsumen</h3></div>
                    <dl class="space-y-3 p-5">
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Konsumen</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-bold text-[#102a56]">{{ event.konsumen }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Kontak</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.kontak }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Jumlah Pengunjung</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.jumlahPengunjung }} orang</dd></div>
                    </dl>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_8px_rgba(15,23,42,.04)]">
                    <div class="flex items-center gap-2.5 border-b border-blue-100 bg-blue-50/70 px-5 py-3.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-[#1769e0] ring-1 ring-blue-100"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21H9.6v-.09A1.7 1.7 0 0 0 8.5 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3V9.6h.09A1.7 1.7 0 0 0 4.6 8.5a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3h4v.09A1.7 1.7 0 0 0 15.5 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 9c.37.3.58.75.6 1.2v3.6c-.02.45-.23.9-.6 1.2Z" /></svg></span><h3 class="font-semibold text-[#15356f]">Operasional</h3></div>
                    <dl class="space-y-3 p-5">
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Konsumsi</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd><span :class="event.konsumsi ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-slate-200'" class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset">{{ event.konsumsi ? 'Ya' : 'Tidak' }}</span></dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Additional</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="whitespace-pre-wrap text-sm font-medium leading-6 text-slate-700">{{ event.additional || '—' }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Panitia</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="whitespace-pre-wrap text-sm font-medium leading-6 text-slate-700">{{ event.panitia || '—' }}</dd></div>
                    </dl>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_2px_8px_rgba(15,23,42,.04)]">
                    <div class="flex items-center gap-2.5 border-b border-blue-100 bg-blue-50/70 px-5 py-3.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-[#1769e0] ring-1 ring-blue-100"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M16 8h-6a2 2 0 0 0 0 4h4a2 2 0 0 1 0 4H8M12 6v12" /></svg></span><h3 class="font-semibold text-[#15356f]">Nilai</h3></div>
                    <dl class="p-5"><div class="grid gap-1 sm:grid-cols-[145px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500 sm:py-1">Harga Total</dt><span class="hidden py-1 text-sm text-slate-300 sm:block">:</span><dd class="text-lg font-bold text-[#102a56]">{{ currency(event.hargaTotal) }}</dd></div></dl>
                </section>

                <section v-if="event.hasCancellationHistory" class="overflow-hidden rounded-2xl border border-rose-100 bg-white shadow-[0_2px_8px_rgba(15,23,42,.04)] lg:col-span-2">
                    <div class="flex items-center gap-2.5 border-b border-rose-100 bg-rose-50/70 px-5 py-3.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-rose-600 ring-1 ring-rose-100"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="m9 9 6 6m0-6-6 6" /></svg></span><div><h3 class="font-semibold text-rose-800">Informasi Pembatalan</h3><p v-if="!event.isCancelled" class="text-[11px] text-rose-500">Event ini pernah dibatalkan dan telah diaktifkan kembali.</p></div></div>
                    <dl class="grid gap-x-8 gap-y-3 p-5 lg:grid-cols-2">
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2 lg:col-span-2"><dt class="text-xs font-medium text-slate-500">Alasan Pembatalan</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="whitespace-pre-wrap text-sm font-medium leading-6 text-slate-700">{{ event.alasanPembatalan || '—' }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Dibatalkan Oleh</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.cancelledBy || '—' }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Dibatalkan Pada</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.cancelledAt || '—' }}</dd></div>
                    </dl>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-[#eef2f7] shadow-[0_2px_8px_rgba(15,23,42,.03)] lg:col-span-2">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 px-5 py-3.5"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white/80 text-slate-500 ring-1 ring-slate-200"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg></span><h3 class="font-semibold text-slate-700">Informasi Sistem</h3></div>
                    <dl class="grid gap-x-8 gap-y-3 p-5 lg:grid-cols-2">
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Dibuat Oleh</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.createdBy }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Tanggal Dibuat</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd class="text-sm font-semibold text-slate-700">{{ event.createdAt }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Terakhir Diubah Oleh</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd :class="event.updatedBy ? 'text-slate-700' : 'text-slate-400'" class="text-sm font-semibold">{{ event.updatedBy || 'Belum pernah diubah' }}</dd></div>
                        <div class="grid gap-1 sm:grid-cols-[165px_12px_minmax(0,1fr)] sm:gap-2"><dt class="text-xs font-medium text-slate-500">Tanggal Terakhir Diubah</dt><span class="hidden text-sm text-slate-300 sm:block">:</span><dd :class="event.updatedAt ? 'text-slate-700' : 'text-slate-400'" class="text-sm font-semibold">{{ event.updatedAt || '—' }}</dd></div>
                    </dl>
                </section>
            </div>
        </div>
        </div>

    </InternalDashboardLayout>
</template>
