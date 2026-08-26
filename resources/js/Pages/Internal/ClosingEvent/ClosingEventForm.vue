<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    event: { type: Object, default: null },
    masterData: { type: Object, required: true },
});

const editing = computed(() => Boolean(props.event));
const form = useForm({
    _method: editing.value ? 'put' : undefined,
    pic_id: props.event?.pic_id ?? '',
    event_id: props.event?.event_id ?? '',
    tanggal: props.event?.tanggal ?? '',
    tanggal_selesai: props.event?.tanggal_selesai ?? '',
    status_event: props.event?.status_event ?? 'aktif',
    alasan_pembatalan: props.event?.alasan_pembatalan ?? '',
    konsumen: props.event?.konsumen ?? '',
    kontak: props.event?.kontak ?? '',
    jam_kedatangan: props.event?.jam_kedatangan ?? '',
    lokasi_ids: props.event?.lokasi_ids ?? [],
    additional: props.event?.additional ?? '',
    konsumsi: props.event?.konsumsi ?? false,
    jumlah_pengunjung: props.event?.jumlah_pengunjung ?? 1,
    harga_total: props.event?.harga_total ?? '',
    panitia: props.event?.panitia ?? '',
});

const inputClass = 'mt-1.5 block w-full rounded-lg border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-[#2867e8] focus:ring-[#2867e8]';
const labelClass = 'block text-xs font-semibold text-slate-700';

const submit = () => {
    if (editing.value) {
        form.post(route('dashboard.closing-event.update', props.event.id), { preserveScroll: true });
        return;
    }
    form.post(route('dashboard.closing-event.store'), { preserveScroll: true });
};
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <header class="mb-5 border-b border-slate-100 pb-4"><h2 class="text-base font-bold text-[#15356f]">Informasi Konsumen</h2><p class="mt-1 text-xs text-slate-500">Data konsumen dan jumlah peserta kegiatan.</p></header>
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <label :class="labelClass">Konsumen <span class="text-red-500">*</span><input v-model="form.konsumen" :class="inputClass" type="text" maxlength="150" /><span v-if="form.errors.konsumen" class="mt-1 block text-xs text-red-600">{{ form.errors.konsumen }}</span></label>
                <label :class="labelClass">Kontak <span class="text-red-500">*</span><input v-model="form.kontak" :class="inputClass" type="text" maxlength="20" /><span v-if="form.errors.kontak" class="mt-1 block text-xs text-red-600">{{ form.errors.kontak }}</span></label>
                <label :class="labelClass">Jumlah Pengunjung <span class="text-red-500">*</span><input v-model.number="form.jumlah_pengunjung" :class="inputClass" type="number" min="1" /><span v-if="form.errors.jumlah_pengunjung" class="mt-1 block text-xs text-red-600">{{ form.errors.jumlah_pengunjung }}</span></label>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <header class="mb-5 border-b border-slate-100 pb-4"><h2 class="text-base font-bold text-[#15356f]">Informasi Event</h2><p class="mt-1 text-xs text-slate-500">Jadwal, PIC, jenis kegiatan, dan lokasi pelaksanaan.</p></header>
            <div class="grid gap-4 md:grid-cols-2">
                <label :class="labelClass">PIC <span class="text-red-500">*</span><select v-model="form.pic_id" :class="inputClass"><option value="">Pilih PIC</option><option v-for="item in masterData.pic" :key="item.id" :value="item.id">{{ item.nama_pic }}</option></select><span v-if="form.errors.pic_id" class="mt-1 block text-xs text-red-600">{{ form.errors.pic_id }}</span></label>
                <label :class="labelClass">Jenis Event <span class="text-red-500">*</span><select v-model="form.event_id" :class="inputClass"><option value="">Pilih jenis event</option><option v-for="item in masterData.events" :key="item.id" :value="item.id">{{ item.jenis_event }}</option></select><span v-if="form.errors.event_id" class="mt-1 block text-xs text-red-600">{{ form.errors.event_id }}</span></label>
                <label :class="labelClass">Tanggal Mulai <span class="text-red-500">*</span><input v-model="form.tanggal" :class="inputClass" type="date" /><span v-if="form.errors.tanggal" class="mt-1 block text-xs text-red-600">{{ form.errors.tanggal }}</span></label>
                <label :class="labelClass">Tanggal Selesai <span class="font-normal text-slate-400">(Opsional)</span><input v-model="form.tanggal_selesai" :min="form.tanggal || undefined" :class="inputClass" type="date" /><span class="mt-1 block text-[11px] font-normal text-slate-400">Kosongkan untuk event satu hari.</span><span v-if="form.errors.tanggal_selesai" class="mt-1 block text-xs text-red-600">{{ form.errors.tanggal_selesai }}</span></label>
                <label :class="labelClass">Jam Kedatangan <span class="text-red-500">*</span><input v-model="form.jam_kedatangan" :class="inputClass" type="time" /><span v-if="form.errors.jam_kedatangan" class="mt-1 block text-xs text-red-600">{{ form.errors.jam_kedatangan }}</span></label>
                <label v-if="editing" :class="labelClass">
                    Status Event <span class="text-red-500">*</span>
                    <select v-model="form.status_event" :class="inputClass">
                        <option value="aktif">Aktif</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    <span v-if="form.errors.status_event" class="mt-1 block text-xs text-red-600">{{ form.errors.status_event }}</span>
                </label>
                <label v-if="editing && form.status_event === 'dibatalkan'" :class="[labelClass, 'md:col-span-2']">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                    <textarea v-model="form.alasan_pembatalan" :class="inputClass" rows="3" placeholder="Jelaskan alasan event dibatalkan"></textarea>
                    <span v-if="form.errors.alasan_pembatalan" class="mt-1 block text-xs text-red-600">{{ form.errors.alasan_pembatalan }}</span>
                </label>
                <fieldset class="md:col-span-2"><legend :class="labelClass">Lokasi <span class="text-red-500">*</span></legend><div class="mt-2 grid gap-2 rounded-lg border border-slate-200 bg-slate-50 p-3 sm:grid-cols-2 lg:grid-cols-3"><label v-for="item in masterData.lokasi" :key="item.id" class="flex cursor-pointer items-center gap-2 rounded-md bg-white px-3 py-2 text-xs font-medium text-slate-700 shadow-sm"><input v-model="form.lokasi_ids" :value="item.id" type="checkbox" class="rounded border-slate-300 text-[#1769e0] focus:ring-[#1769e0]" />{{ item.nama_lokasi }}</label></div><span v-if="form.errors.lokasi_ids" class="mt-1 block text-xs text-red-600">{{ form.errors.lokasi_ids }}</span><span v-if="form.errors['lokasi_ids.0']" class="mt-1 block text-xs text-red-600">{{ form.errors['lokasi_ids.0'] }}</span></fieldset>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <header class="mb-5 border-b border-slate-100 pb-4"><h2 class="text-base font-bold text-[#15356f]">Kebutuhan Event</h2><p class="mt-1 text-xs text-slate-500">Catatan operasional yang dibutuhkan untuk kegiatan.</p></header>
            <div class="grid gap-4 md:grid-cols-2">
                <fieldset><legend :class="labelClass">Konsumsi <span class="text-red-500">*</span></legend><div class="mt-2 flex gap-3"><label v-for="item in [{value:true,label:'Ya'},{value:false,label:'Tidak'}]" :key="String(item.value)" :class="form.konsumsi === item.value ? 'border-[#1769e0] bg-blue-50 text-[#0756ba]' : 'border-slate-200 bg-white text-slate-600'" class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2 text-sm font-semibold"><input v-model="form.konsumsi" :value="item.value" type="radio" class="text-[#1769e0] focus:ring-[#1769e0]" />{{ item.label }}</label></div><span v-if="form.errors.konsumsi" class="mt-1 block text-xs text-red-600">{{ form.errors.konsumsi }}</span></fieldset>
                <label :class="labelClass">Panitia<textarea v-model="form.panitia" :class="inputClass" rows="3" placeholder="Nama/tim panitia (opsional)"></textarea><span v-if="form.errors.panitia" class="mt-1 block text-xs text-red-600">{{ form.errors.panitia }}</span></label>
                <label :class="[labelClass, 'md:col-span-2']">Additional<textarea v-model="form.additional" :class="inputClass" rows="3" placeholder="Kebutuhan atau catatan tambahan (opsional)"></textarea><span v-if="form.errors.additional" class="mt-1 block text-xs text-red-600">{{ form.errors.additional }}</span></label>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <header class="mb-5 border-b border-slate-100 pb-4"><h2 class="text-base font-bold text-[#15356f]">Nilai</h2><p class="mt-1 text-xs text-slate-500">Nilai total Closing Event.</p></header>
            <label :class="labelClass" class="max-w-md">Harga Total <span class="text-red-500">*</span><div class="relative"><span class="absolute left-3 top-1/2 mt-0.5 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span><input v-model="form.harga_total" :class="[inputClass, 'pl-10']" type="number" min="0" step="0.01" /></div><span v-if="form.errors.harga_total" class="mt-1 block text-xs text-red-600">{{ form.errors.harga_total }}</span></label>
        </section>

        <div class="flex flex-wrap justify-end gap-3">
            <Link :href="editing ? route('dashboard.closing-event.show', event.id) : route('dashboard.closing-event.index')" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</Link>
            <button :disabled="form.processing" class="rounded-lg bg-[#1769e0] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#0756ba] disabled:cursor-not-allowed disabled:opacity-60">{{ form.processing ? 'Menyimpan...' : (editing ? 'Simpan Perubahan' : 'Simpan Closing Event') }}</button>
        </div>
    </form>
</template>
