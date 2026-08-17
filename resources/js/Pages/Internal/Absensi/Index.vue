<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    attendanceDate: { type: String, required: true },
    today: { type: String, required: true },
    isToday: { type: Boolean, required: true },
    isSaved: { type: Boolean, required: true },
    employees: { type: Array, required: true },
    user: { type: Object, required: true },
});

const page = usePage();
const isEditing = ref(!props.isSaved && props.isToday);
const searchQuery = ref('');

const form = useForm({
    tanggal_absensi: props.attendanceDate,
    records: props.employees.map((employee) => ({
        karyawan_id: employee.id,
        status_kehadiran: employee.attendance?.status || null,
        keterangan: employee.attendance?.note || '',
    })),
});

const originalRecords = ref(JSON.parse(JSON.stringify(form.records)));
const recordByEmployee = computed(() => new Map(form.records.map((record) => [record.karyawan_id, record])));
const filteredEmployees = computed(() => {
    const query = searchQuery.value.trim().toLocaleLowerCase('id-ID');
    if (!query) return props.employees;
    return props.employees.filter((employee) => `${employee.name} ${employee.position}`.toLocaleLowerCase('id-ID').includes(query));
});
const allSelected = computed(() => form.records.length > 0 && form.records.every((record) => ['H', 'I', 'A'].includes(record.status_kehadiran)));
const successMessage = computed(() => page.props.flash?.success);

const formattedDate = computed(() => new Intl.DateTimeFormat('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta',
}).format(new Date(`${props.attendanceDate}T00:00:00+07:00`)));

const attendanceOptions = [
    { value: 'H', label: 'Hadir', active: 'border-[#0756ba] bg-[#0756ba] text-white' },
    { value: 'I', label: 'Izin', active: 'border-[#facc15] bg-[#facc15] text-[#422006]' },
    { value: 'A', label: 'Alpha', active: 'border-[#f9a8b8] bg-[#f9a8b8] text-[#881337]' },
];

const startEditing = () => {
    if (props.isToday) isEditing.value = true;
};

const cancelEditing = () => {
    form.records = JSON.parse(JSON.stringify(originalRecords.value));
    form.clearErrors();
    isEditing.value = false;
};

const submit = () => {
    form.put(route('admin.absensi.store'), {
        preserveScroll: true,
        onSuccess: () => {
            originalRecords.value = JSON.parse(JSON.stringify(form.records));
            isEditing.value = false;
        },
    });
};

const openDate = (event) => {
    const date = event.target.value;
    if (date) router.get(route('admin.absensi.index'), { tanggal: date });
};

watch(() => props.isSaved, (saved) => {
    if (saved) isEditing.value = false;
});
</script>

<template>
    <Head title="Data Absensi" />

    <InternalDashboardLayout :user="user" title="Data Absensi" :can-view-attendance="true" :can-manage-employee-masters="true" :show-search="true" @search="searchQuery = $event">
        <section class="min-w-0 px-4 py-5 sm:px-6 lg:px-7">
            <div v-if="successMessage" role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ successMessage }}
            </div>

            <div v-if="Object.keys(form.errors).length" role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Absensi belum dapat disimpan.</p>
                <p class="mt-1">{{ form.errors.records || form.errors.tanggal_absensi || 'Periksa pilihan kehadiran pada setiap karyawan.' }}</p>
            </div>

            <div class="flex min-h-[102px] flex-col justify-between gap-5 rounded-xl border border-[#dce3ed] bg-white px-5 py-5 shadow-[0_1px_2px_rgba(15,23,42,.03)] md:flex-row md:items-center lg:px-6">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="font-heading text-lg font-bold text-[#0f172a]">{{ formattedDate }}</h2>
                        <span class="rounded bg-[#2867e8] px-2 py-1 text-[9px] font-bold tracking-wide text-white">SUPER ADMIN VIEW</span>
                    </div>
                    <p v-if="isToday" class="mt-2 flex items-center gap-2 text-xs font-medium text-emerald-600"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Status: Input Terbuka (Hanya Hari Ini)</p>
                    <p v-else class="mt-2 flex items-center gap-2 text-xs font-medium text-slate-500"><span class="h-2 w-2 rounded-full bg-slate-400"></span>Status: Arsip (Hanya Baca)</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <label class="relative">
                        <span class="sr-only">Pilih tanggal absensi</span>
                        <input type="date" :value="attendanceDate" :max="today" class="h-11 rounded-full border-[#cbd5e1] text-sm text-slate-600 focus:border-[#2867e8] focus:ring-[#2867e8]" @change="openDate" />
                    </label>
                    <template v-if="isToday">
                        <button v-if="isSaved && !isEditing" type="button" class="flex h-11 items-center gap-2 rounded-full border border-[#2867e8] px-5 text-sm font-semibold text-[#0756ba] hover:bg-blue-50" @click="startEditing">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m4 20 4-1 11-11-3-3L5 16l-1 4Z"/><path d="m14 7 3 3"/></svg>Edit Absensi
                        </button>
                        <button v-if="isEditing && isSaved" type="button" class="h-11 rounded-full border border-slate-300 px-5 text-sm font-semibold text-slate-600 hover:bg-slate-50" :disabled="form.processing" @click="cancelEditing">Batal</button>
                        <button v-if="isEditing" type="button" class="flex h-11 items-center gap-2 rounded-full bg-[#0756ba] px-6 text-sm font-semibold text-white shadow-sm hover:bg-[#064ca3] disabled:cursor-not-allowed disabled:opacity-50" :disabled="form.processing || !allSelected" @click="submit">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h12l2 2v14H5V4Z"/><path d="M8 4v6h8V4M9 16h6"/></svg>{{ form.processing ? 'Menyimpan...' : (isSaved ? 'Simpan Perubahan' : 'Simpan Absensi') }}
                        </button>
                    </template>
                </div>
            </div>

            <div class="mt-5 inline-flex flex-wrap gap-5 rounded-lg border border-[#e2e8f0] bg-white px-5 py-3 text-xs text-[#334155]">
                <span class="flex items-center gap-1.5"><i class="h-3 w-3 rounded-sm bg-[#2867e8]"></i>H = Hadir</span>
                <span class="flex items-center gap-1.5"><i class="h-3 w-3 rounded-sm bg-[#facc15]"></i>I = Izin</span>
                <span class="flex items-center gap-1.5"><i class="h-3 w-3 rounded-sm bg-[#f9a8b8]"></i>A = Alpha</span>
            </div>

            <div class="mt-5 min-h-[500px] overflow-hidden rounded-xl border border-[#dce3ed] bg-white shadow-[0_1px_2px_rgba(15,23,42,.03)]">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] border-collapse">
                        <thead class="bg-[#f1f4f8] text-[10px] font-bold uppercase tracking-wide text-[#334155]">
                            <tr>
                                <th class="w-16 px-5 py-4 text-left">No</th>
                                <th class="w-[30%] px-5 py-4 text-left">Nama Karyawan</th>
                                <th class="w-[27%] px-5 py-4 text-left">Jabatan</th>
                                <th class="w-[18%] px-5 py-4 text-left">Kehadiran</th>
                                <th class="px-5 py-4 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0] text-sm">
                            <tr v-for="(employee, index) in filteredEmployees" :key="employee.id" class="h-[66px]">
                                <td class="px-5 text-[#334155]">{{ index + 1 }}</td>
                                <td class="px-5">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#dbeafe] text-[10px] font-bold text-[#0756ba]">{{ employee.initials }}</span>
                                        <span class="font-semibold text-[#0f172a]">{{ employee.name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 text-[#334155]">{{ employee.position }}</td>
                                <td class="px-5">
                                    <fieldset class="flex gap-3" :disabled="!isEditing">
                                        <legend class="sr-only">Kehadiran {{ employee.name }}</legend>
                                        <label v-for="option in attendanceOptions" :key="option.value" class="cursor-pointer">
                                            <input v-model="recordByEmployee.get(employee.id).status_kehadiran" type="radio" :name="`attendance-${employee.id}`" :value="option.value" class="sr-only" />
                                            <span :class="recordByEmployee.get(employee.id).status_kehadiran === option.value ? option.active : 'border-[#cbd5e1] bg-white text-[#334155]'" class="grid h-8 w-8 place-items-center rounded-full border text-xs font-semibold transition-colors" :title="option.label">{{ option.value }}</span>
                                        </label>
                                    </fieldset>
                                    <p v-if="form.errors[`records.${form.records.findIndex((item) => item.karyawan_id === employee.id)}.status_kehadiran`]" class="mt-1 text-[10px] text-red-600">Pilih H, I, atau A.</p>
                                </td>
                                <td class="px-5">
                                    <input v-model="recordByEmployee.get(employee.id).keterangan" type="text" maxlength="255" placeholder="Tambah catatan..." :disabled="!isEditing" class="h-9 w-full rounded-md border-[#cbd5e1] bg-white text-xs placeholder:text-slate-400 focus:border-[#2867e8] focus:ring-[#2867e8] disabled:bg-[#f8fafc] disabled:text-slate-600" />
                                </td>
                            </tr>
                            <tr v-if="!filteredEmployees.length"><td colspan="5" class="px-5 py-16 text-center text-sm text-slate-500">Tidak ada karyawan aktif yang sesuai pencarian.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </InternalDashboardLayout>
</template>
