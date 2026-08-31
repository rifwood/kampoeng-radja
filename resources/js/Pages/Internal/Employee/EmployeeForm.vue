<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    employee: { type: Object, default: null },
    masterData: { type: Object, required: true },
});

const editing = computed(() => Boolean(props.employee));
const form = useForm({
    _method: editing.value ? 'put' : undefined,
    nik: props.employee?.nik ?? '',
    nama: props.employee?.name ?? '',
    jenis_kelamin: props.employee?.gender ?? 'L',
    tempat_lahir: props.employee?.birthPlace ?? '',
    tanggal_lahir: props.employee?.birthDate ?? '',
    agama: props.employee?.religion ?? 'islam',
    status_perkawinan: props.employee?.maritalStatus ?? 'belum kawin',
    pendidikan: props.employee?.education ?? 'SMA',
    alamat: props.employee?.address ?? '',
    no_hp: props.employee?.phone ?? '',
    jabatan_id: props.employee?.positionId ?? '',
    departemen_id: props.employee?.departmentId ?? '',
    penempatan_id: props.employee?.placementId ?? '',
    atasan_langsung_id: props.employee?.supervisorId ?? '',
    status_kerja: props.employee?.employmentStatus ?? 'kontrak',
    status_keaktifan: props.employee?.activeStatus ?? 'aktif',
    tanggal_masuk: props.employee?.joinedAt ?? '',
    tanggal_keluar: props.employee?.leftAt ?? '',
    foto_ktp: null,
    foto_tanda_tangan: null,
});

const supervisorSearch = ref('');
const signaturePreviewUrl = ref(props.employee?.signaturePhotoUrl ?? null);
let signatureObjectUrl = null;
const supervisorOptions = computed(() => {
    const term = supervisorSearch.value.trim().toLocaleLowerCase('id');
    if (!term) return props.masterData.karyawan;
    return props.masterData.karyawan.filter((item) => `${item.nama} ${item.nik}`.toLocaleLowerCase('id').includes(term));
});
const selectSignature = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.foto_tanda_tangan = file;
    if (signatureObjectUrl) URL.revokeObjectURL(signatureObjectUrl);
    signatureObjectUrl = file ? URL.createObjectURL(file) : null;
    signaturePreviewUrl.value = signatureObjectUrl ?? props.employee?.signaturePhotoUrl ?? null;
};
onBeforeUnmount(() => {
    if (signatureObjectUrl) URL.revokeObjectURL(signatureObjectUrl);
});

const fields = {
    input: 'mt-1.5 block w-full rounded-lg border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-[#2867e8] focus:ring-[#2867e8]',
    label: 'block text-xs font-semibold text-slate-700',
};

const submit = () => {
    const options = { forceFormData: true, preserveScroll: true };
    if (editing.value) {
        form.post(route('dashboard.karyawan.update', props.employee.id), options);
    } else {
        form.post(route('dashboard.karyawan.store'), options);
    }
};
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 border-b border-slate-100 pb-4">
                <h2 class="text-base font-bold text-[#15356f]">Data Pribadi</h2>
                <p class="mt-1 text-xs text-slate-500">Identitas utama karyawan sesuai dokumen resmi.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label :class="fields.label">Nama <span class="text-red-500">*</span><input v-model="form.nama" :class="fields.input" type="text" autocomplete="name" /><span v-if="form.errors.nama" class="mt-1 block text-xs text-red-600">{{ form.errors.nama }}</span></label>
                <label :class="fields.label">NIK <span class="text-red-500">*</span><input v-model="form.nik" :class="fields.input" type="text" maxlength="20" /><span v-if="form.errors.nik" class="mt-1 block text-xs text-red-600">{{ form.errors.nik }}</span></label>
                <label :class="fields.label">Jenis Kelamin <select v-model="form.jenis_kelamin" :class="fields.input"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select><span v-if="form.errors.jenis_kelamin" class="mt-1 block text-xs text-red-600">{{ form.errors.jenis_kelamin }}</span></label>
                <label :class="fields.label">Tempat Lahir <input v-model="form.tempat_lahir" :class="fields.input" type="text" /><span v-if="form.errors.tempat_lahir" class="mt-1 block text-xs text-red-600">{{ form.errors.tempat_lahir }}</span></label>
                <label :class="fields.label">Tanggal Lahir <input v-model="form.tanggal_lahir" :class="fields.input" type="date" /><span v-if="form.errors.tanggal_lahir" class="mt-1 block text-xs text-red-600">{{ form.errors.tanggal_lahir }}</span></label>
                <label :class="fields.label">Agama <select v-model="form.agama" :class="fields.input"><option v-for="item in ['islam','kristen','katolik','hindu','buddha','konghucu']" :key="item" :value="item">{{ item }}</option></select><span v-if="form.errors.agama" class="mt-1 block text-xs text-red-600">{{ form.errors.agama }}</span></label>
                <label :class="fields.label">Status Perkawinan <select v-model="form.status_perkawinan" :class="fields.input"><option v-for="item in ['belum kawin','kawin','cerai hidup','cerai mati']" :key="item" :value="item">{{ item }}</option></select><span v-if="form.errors.status_perkawinan" class="mt-1 block text-xs text-red-600">{{ form.errors.status_perkawinan }}</span></label>
                <label :class="fields.label">Pendidikan <select v-model="form.pendidikan" :class="fields.input"><option v-for="item in ['SD','SMP','SMA','MAN','SMK','D3','D4','S1','S2','S3']" :key="item" :value="item">{{ item }}</option></select><span v-if="form.errors.pendidikan" class="mt-1 block text-xs text-red-600">{{ form.errors.pendidikan }}</span></label>
                <label :class="fields.label">No. HP <input v-model="form.no_hp" :class="fields.input" type="tel" maxlength="20" /><span v-if="form.errors.no_hp" class="mt-1 block text-xs text-red-600">{{ form.errors.no_hp }}</span></label>
                <label :class="[fields.label, 'md:col-span-2']">Alamat <textarea v-model="form.alamat" :class="fields.input" rows="3"></textarea><span v-if="form.errors.alamat" class="mt-1 block text-xs text-red-600">{{ form.errors.alamat }}</span></label>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 border-b border-slate-100 pb-4"><h2 class="text-base font-bold text-[#15356f]">Data Pekerjaan</h2><p class="mt-1 text-xs text-slate-500">Penempatan, status kerja, dan periode bekerja.</p></div>
            <div class="grid gap-4 md:grid-cols-2">
                <label :class="fields.label">Jabatan <span class="text-red-500">*</span><select v-model="form.jabatan_id" :class="fields.input"><option value="">Pilih jabatan</option><option v-for="item in masterData.jabatan" :key="item.id" :value="item.id">{{ item.nama_jabatan }}</option></select><span v-if="form.errors.jabatan_id" class="mt-1 block text-xs text-red-600">{{ form.errors.jabatan_id }}</span></label>
                <label :class="fields.label">Departemen <select v-model="form.departemen_id" :class="fields.input"><option value="">Tanpa departemen</option><option v-for="item in masterData.departemen" :key="item.id" :value="item.id">{{ item.nama_departemen }}</option></select><span v-if="form.errors.departemen_id" class="mt-1 block text-xs text-red-600">{{ form.errors.departemen_id }}</span></label>
                <label :class="fields.label">Penempatan <select v-model="form.penempatan_id" :class="fields.input"><option value="">Tanpa penempatan</option><option v-for="item in masterData.penempatan" :key="item.id" :value="item.id">{{ item.nama_penempatan }}</option></select><span v-if="form.errors.penempatan_id" class="mt-1 block text-xs text-red-600">{{ form.errors.penempatan_id }}</span></label>
                <div>
                    <label :class="fields.label">Atasan Langsung <span class="font-normal text-slate-400">(opsional)</span><input v-model="supervisorSearch" :class="fields.input" type="search" placeholder="Cari nama / NIK..." /></label>
                    <select v-model="form.atasan_langsung_id" :class="fields.input" class="mt-2">
                        <option value="">Tanpa atasan langsung</option>
                        <option v-for="item in supervisorOptions" :key="item.id" :value="item.id">{{ item.nama }} — {{ item.nik }}</option>
                    </select>
                    <span v-if="form.errors.atasan_langsung_id" class="mt-1 block text-xs text-red-600">{{ form.errors.atasan_langsung_id }}</span>
                </div>
                <label :class="fields.label">Status Kerja <select v-model="form.status_kerja" :class="fields.input"><option v-for="item in ['kontrak','magang','buruh','freelance']" :key="item" :value="item">{{ item }}</option></select><span v-if="form.errors.status_kerja" class="mt-1 block text-xs text-red-600">{{ form.errors.status_kerja }}</span></label>
                <label :class="fields.label">Status Keaktifan <select v-model="form.status_keaktifan" :class="fields.input"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select><span v-if="form.errors.status_keaktifan" class="mt-1 block text-xs text-red-600">{{ form.errors.status_keaktifan }}</span></label>
                <label :class="fields.label">Tanggal Masuk <input v-model="form.tanggal_masuk" :class="fields.input" type="date" /><span v-if="form.errors.tanggal_masuk" class="mt-1 block text-xs text-red-600">{{ form.errors.tanggal_masuk }}</span></label>
                <label :class="fields.label">Tanggal Keluar <input v-model="form.tanggal_keluar" :class="fields.input" type="date" /><span v-if="form.errors.tanggal_keluar" class="mt-1 block text-xs text-red-600">{{ form.errors.tanggal_keluar }}</span></label>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 border-b border-slate-100 pb-4"><h2 class="text-base font-bold text-[#15356f]">Dokumen</h2><p class="mt-1 text-xs text-slate-500">File disimpan privat dan hanya dapat dilihat Super Admin.</p></div>
            <label :class="fields.label">Foto KTP <span class="font-normal text-slate-400">(JPG, PNG, WEBP — maks. 5 MB)</span><input :class="fields.input" type="file" accept="image/jpeg,image/png,image/webp" @change="form.foto_ktp = $event.target.files[0]" /><span v-if="editing && employee.hasKtpPhoto" class="mt-2 block text-xs text-emerald-600">Dokumen lama tersedia. Pilih file hanya jika ingin mengganti.</span><span v-if="form.errors.foto_ktp" class="mt-1 block text-xs text-red-600">{{ form.errors.foto_ktp }}</span></label>
            <div class="mt-5 border-t border-slate-100 pt-5">
                <label :class="fields.label">Foto Tanda Tangan <span class="font-normal text-slate-400">(JPG atau PNG — maks. 5 MB)</span><input :class="fields.input" type="file" accept="image/jpeg,image/png" @change="selectSignature" /><span v-if="form.errors.foto_tanda_tangan" class="mt-1 block text-xs text-red-600">{{ form.errors.foto_tanda_tangan }}</span></label>
                <div v-if="signaturePreviewUrl" class="mt-3 inline-flex min-h-28 min-w-52 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3">
                    <img :src="signaturePreviewUrl" alt="Preview tanda tangan" class="max-h-28 max-w-64 object-contain" />
                </div>
                <p v-if="editing && employee.hasSignaturePhoto" class="mt-2 text-xs text-emerald-600">Tanda tangan lama dipertahankan jika tidak memilih file baru.</p>
            </div>
        </section>

        <div class="flex flex-wrap justify-end gap-3">
            <Link :href="editing ? route('dashboard.karyawan.show', employee.id) : route('dashboard.karyawan.index')" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</Link>
            <button :disabled="form.processing" class="rounded-lg bg-[#1769e0] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#0756ba] disabled:cursor-not-allowed disabled:opacity-60">{{ form.processing ? 'Menyimpan...' : (editing ? 'Simpan Perubahan' : 'Simpan Karyawan') }}</button>
        </div>
    </form>
</template>
