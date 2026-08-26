<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import Modal from '@/Components/Modal.vue';
import AttendanceTimeInput from '@/Components/Internal/AttendanceTimeInput.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    attendanceDate: { type: String, required: true },
    today: { type: String, required: true },
    yesterday: { type: String, required: true },
    isToday: { type: Boolean, required: true },
    canMutateDate: { type: Boolean, required: true },
    isSaved: { type: Boolean, required: true },
    employees: { type: Array, required: true },
    permissions: { type: Object, required: true },
    reportYears: { type: Array, required: true },
    user: { type: Object, required: true },
});

const page = usePage();
const isEditing = ref(props.permissions.canManage && !props.isSaved && props.canMutateDate);
const bulkConfirmationOpen = ref(false);
const searchQuery = ref('');
const reportPeriod = ref(props.today.slice(0, 7));
const months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];
const reportPeriodOptions = computed(() => props.reportYears.flatMap((year) => months.map((month, index) => ({
    value: `${year}-${String(index + 1).padStart(2, '0')}`,
    label: `${month} ${year}`,
}))));
const reportMonth = computed(() => Number(reportPeriod.value.slice(5, 7)));
const reportYear = computed(() => Number(reportPeriod.value.slice(0, 4)));

const recordsFromEmployees = (employees) => employees.map((employee) => ({
    karyawan_id: employee.id,
    status_kehadiran: employee.attendance?.status || null,
    jam_masuk: employee.attendance?.entryTime || '',
    jam_keluar: employee.attendance?.exitTime || '',
    keterangan: employee.attendance?.note || '',
}));

const form = useForm({
    tanggal_absensi: props.attendanceDate,
    records: recordsFromEmployees(props.employees),
});

const originalRecords = ref(JSON.parse(JSON.stringify(form.records)));
const recordByEmployee = computed(() => new Map(form.records.map((record) => [record.karyawan_id, record])));
const filteredEmployees = computed(() => {
    const query = searchQuery.value.trim().toLocaleLowerCase('id-ID');
    if (!query) return props.employees;
    return props.employees.filter((employee) => `${employee.name} ${employee.position}`.toLocaleLowerCase('id-ID').includes(query));
});
const allSelected = computed(() => form.records.length > 0 && form.records.every((record) => ['H', 'I', 'A'].includes(record.status_kehadiran)));
const timePattern = /^([01]\d|2[0-3]):[0-5]\d$/;
const hasValidTimes = computed(() => form.records.every((record) => {
    if (record.status_kehadiran !== 'H') {
        return !record.jam_masuk && !record.jam_keluar;
    }

    if (record.jam_masuk && !timePattern.test(record.jam_masuk)) return false;
    if (record.jam_masuk && record.jam_masuk > '12:00') return false;
    if (record.jam_keluar && !timePattern.test(record.jam_keluar)) return false;

    return !(record.jam_masuk && record.jam_keluar && record.jam_keluar < record.jam_masuk);
}));
const missingSelectionCount = computed(() => form.records.filter((record) => !['H', 'I', 'A'].includes(record.status_kehadiran)).length);
const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);
const pageTitle = computed(() => props.permissions.canManage ? 'Kelola Absensi' : 'Data Absensi');
const exportUrl = computed(() => route('admin.absensi.export', {
    bulan: reportMonth.value,
    tahun: reportYear.value,
}));
const formattedDate = computed(() => new Intl.DateTimeFormat('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta',
}).format(new Date(`${props.attendanceDate}T00:00:00+07:00`)));
const compactDate = computed(() => new Intl.DateTimeFormat('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric', timeZone: 'Asia/Jakarta',
}).format(new Date(`${props.attendanceDate}T00:00:00+07:00`)));

const attendanceOptions = [
    { value: 'H', label: 'Hadir', active: 'border-[#0756ba] bg-[#0756ba] text-white' },
    { value: 'I', label: 'Izin', active: 'border-[#facc15] bg-[#facc15] text-[#422006]' },
    { value: 'A', label: 'Alfa', active: 'border-[#f9a8b8] bg-[#f9a8b8] text-[#881337]' },
];
const statusClasses = {
    H: 'bg-blue-50 text-[#0756ba]',
    I: 'bg-amber-50 text-amber-700',
    A: 'bg-rose-50 text-rose-700',
};

const recordFor = (employeeId) => recordByEmployee.value.get(employeeId);
const recordIndex = (employeeId) => form.records.findIndex((record) => record.karyawan_id === employeeId);
const fieldError = (employeeId, field) => form.errors[`records.${recordIndex(employeeId)}.${field}`];
const clearFieldError = (employeeId, field) => form.clearErrors(`records.${recordIndex(employeeId)}.${field}`);
const timeInputRefs = new Map();
const timeInputKey = (employeeId, field) => `${employeeId}:${field}`;
const setTimeInputRef = (employeeId, field, component) => {
    const key = timeInputKey(employeeId, field);
    if (component) timeInputRefs.set(key, component);
    else timeInputRefs.delete(key);
};
const focusTimeInput = (employeeId, field) => timeInputRefs.get(timeInputKey(employeeId, field))?.focus();
const presentEmployeeIndex = (employeeId) => filteredEmployees.value.findIndex((employee) => employee.id === employeeId);
const nextPresentEmployee = (employeeId, direction) => {
    const currentIndex = presentEmployeeIndex(employeeId);
    for (let index = currentIndex + direction; index >= 0 && index < filteredEmployees.value.length; index += direction) {
        const employee = filteredEmployees.value[index];
        if (recordFor(employee.id)?.status_kehadiran === 'H') return employee;
    }
    return null;
};
const navigateNextTimeInput = (employeeId, field) => {
    if (field === 'jam_masuk') {
        focusTimeInput(employeeId, 'jam_keluar');
        return;
    }

    const nextEmployee = nextPresentEmployee(employeeId, 1);
    if (nextEmployee) focusTimeInput(nextEmployee.id, 'jam_masuk');
};
const navigateTimeRow = (employeeId, field, direction) => {
    const employee = nextPresentEmployee(employeeId, direction);
    if (employee) focusTimeInput(employee.id, field);
};
const isLate = (record) => record.status_kehadiran === 'H'
    && timePattern.test(record.jam_masuk)
    && record.jam_masuk > '08:30'
    && record.jam_masuk <= '12:00';
const isEarlyLeave = (record) => record.status_kehadiran === 'H'
    && timePattern.test(record.jam_keluar)
    && record.jam_keluar < '16:30';
const setAttendanceStatus = (record, status) => {
    record.status_kehadiran = status;
    if (status !== 'H') {
        record.jam_masuk = '';
        record.jam_keluar = '';
    }
};
const startEditing = () => {
    if (props.permissions.canManage && props.canMutateDate) isEditing.value = true;
};
const cancelEditing = () => {
    form.records = JSON.parse(JSON.stringify(originalRecords.value));
    form.clearErrors();
    isEditing.value = false;
};
const markUnselectedPresent = () => {
    form.records.forEach((record) => {
        if (!['H', 'I', 'A'].includes(record.status_kehadiran)) {
            setAttendanceStatus(record, 'H');
        }
    });
    bulkConfirmationOpen.value = false;
};
const submit = () => {
    if (!props.permissions.canManage || !allSelected.value || !hasValidTimes.value || form.processing) return;

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
const openQuickDate = (date) => {
    router.get(route('admin.absensi.index'), { tanggal: date });
};

watch(() => props.attendanceDate, () => {
    form.tanggal_absensi = props.attendanceDate;
    form.records = recordsFromEmployees(props.employees);
    originalRecords.value = JSON.parse(JSON.stringify(form.records));
    form.clearErrors();
    bulkConfirmationOpen.value = false;
    isEditing.value = props.permissions.canManage && !props.isSaved && props.canMutateDate;
});

watch(() => props.isSaved, (saved) => {
    if (saved) isEditing.value = false;
});
</script>

<template>
    <Head :title="pageTitle" />

    <InternalDashboardLayout
        :user="user"
        :title="pageTitle"
        :can-view-attendance="permissions.canView"
        :can-manage-employee-masters="user.roleName === 'super_admin'"
        :show-search="true"
        @search="searchQuery = $event"
    >
        <section class="mx-auto min-w-0 max-w-[1500px] px-4 py-5 sm:px-6 lg:px-7">
            <div
                v-if="successMessage"
                role="status"
                class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
            >
                {{ successMessage }}
            </div>

            <div
                v-if="errorMessage"
                role="alert"
                class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
            >
                {{ errorMessage }}
            </div>

            <div
                v-if="Object.keys(form.errors).length"
                role="alert"
                class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                <p class="font-semibold">Absensi belum dapat disimpan.</p>
                <p class="mt-1">{{ form.errors.records || form.errors.tanggal_absensi || Object.values(form.errors)[0] }}</p>
            </div>

            <div class="rounded-xl border border-[#dce3ed] bg-white px-5 py-4 shadow-[0_1px_2px_rgba(15,23,42,.03)] lg:px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="font-heading text-lg font-bold text-[#0f172a]">{{ formattedDate }}</h2>
                        <p
                            :class="canMutateDate && permissions.canManage ? 'text-emerald-700' : 'text-slate-500'"
                            class="mt-1.5 flex items-center gap-2 text-xs font-semibold"
                        >
                            <span
                                :class="canMutateDate && permissions.canManage ? 'bg-emerald-500' : 'bg-slate-400'"
                                class="h-2 w-2 rounded-full"
                            ></span>
                            <template v-if="canMutateDate && permissions.canManage">
                                {{ isToday ? 'Input dibuka — Hari ini' : 'Masa koreksi — Kemarin' }}
                            </template>
                            <template v-else>Data terkunci</template>
                        </p>
                    </div>

                    <p
                        v-if="canMutateDate && permissions.canManage"
                        :class="missingSelectionCount > 0 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700'"
                        class="w-fit rounded-lg px-3 py-2 text-xs font-semibold"
                    >
                        {{ missingSelectionCount > 0 ? `${missingSelectionCount} karyawan belum diisi` : 'Semua absensi sudah lengkap ✓' }}
                    </p>
                </div>

                <div
                    v-if="permissions.canManage"
                    class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="grid w-full grid-cols-2 rounded-lg border border-slate-200 bg-slate-100 p-1 sm:w-[230px]" aria-label="Pilih periode absensi">
                        <button
                            type="button"
                            :aria-pressed="attendanceDate === yesterday"
                            :class="attendanceDate === yesterday ? 'bg-white text-[#0756ba] shadow-sm' : 'text-slate-500 hover:text-[#0756ba]'"
                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md px-3 text-xs font-semibold transition"
                            @click="openQuickDate(yesterday)"
                        >
                            <span class="w-3 text-center" aria-hidden="true">{{ attendanceDate === yesterday ? '✓' : '' }}</span>
                            Kemarin
                        </button>
                        <button
                            type="button"
                            :aria-pressed="attendanceDate === today"
                            :class="attendanceDate === today ? 'bg-white text-[#0756ba] shadow-sm' : 'text-slate-500 hover:text-[#0756ba]'"
                            class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md px-3 text-xs font-semibold transition"
                            @click="openQuickDate(today)"
                        >
                            <span class="w-3 text-center" aria-hidden="true">{{ attendanceDate === today ? '✓' : '' }}</span>
                            Hari Ini
                        </button>
                    </div>

                    <div v-if="canMutateDate" class="grid w-full grid-cols-1 gap-2 sm:flex sm:w-auto sm:flex-wrap sm:justify-end">
                        <button
                            v-if="isSaved && !isEditing"
                            type="button"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-[#2867e8] px-4 text-sm font-semibold text-[#0756ba] transition hover:bg-blue-50"
                            @click="startEditing"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m4 20 4-1 11-11-3-3L5 16l-1 4Z" /><path d="m14 7 3 3" /></svg>
                            Edit Absensi
                        </button>
                        <button
                            v-if="isEditing && isSaved"
                            type="button"
                            class="h-10 rounded-lg border border-slate-300 px-4 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                            :disabled="form.processing"
                            @click="cancelEditing"
                        >
                            Batal
                        </button>
                        <button
                            v-if="isEditing && missingSelectionCount > 0"
                            type="button"
                            class="h-10 rounded-lg border border-[#2867e8] px-4 text-sm font-semibold text-[#0756ba] transition hover:bg-blue-50 disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="form.processing"
                            @click="bulkConfirmationOpen = true"
                        >
                            Isi Kosong → Hadir
                        </button>
                        <button
                            v-if="isEditing"
                            type="button"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#0756ba] px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#064ca3] disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
                            :disabled="form.processing || !allSelected || !hasValidTimes"
                            @click="submit"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h12l2 2v14H5V4Z" /><path d="M8 4v6h8V4M9 16h6" /></svg>
                            {{ form.processing ? 'Menyimpan...' : (isSaved ? 'Simpan Perubahan' : 'Simpan Absensi') }}
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="permissions.canExport"
                class="mt-4 flex flex-col gap-3 rounded-xl border border-[#dce3ed] bg-white px-5 py-3.5 shadow-[0_1px_2px_rgba(15,23,42,.03)] lg:flex-row lg:items-end lg:justify-between"
            >
                <div>
                    <h3 class="font-heading text-sm font-bold text-[#0f172a]">Laporan Absensi Bulanan</h3>
                    <p class="mt-1 text-xs text-slate-500">Export data absensi per tanggal dalam satu workbook.</p>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-[220px_auto] sm:items-end">
                    <label class="text-xs font-semibold text-slate-600">
                        Periode Export
                        <select v-model="reportPeriod" class="mt-1.5 h-10 w-full rounded-lg border-slate-300 text-sm focus:border-[#2867e8] focus:ring-[#2867e8]">
                            <option v-for="period in reportPeriodOptions" :key="period.value" :value="period.value">{{ period.label }}</option>
                        </select>
                    </label>
                    <a
                        :href="exportUrl"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#0756ba] px-5 text-sm font-semibold text-white transition hover:bg-[#064ca3]"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v12m0 0 4-4m-4 4-4-4" /><path d="M5 18v3h14v-3" /></svg>
                        Export Excel
                    </a>
                </div>
            </div>

            <div class="mt-4 overflow-hidden rounded-xl border border-[#dce3ed] bg-white shadow-[0_1px_2px_rgba(15,23,42,.03)]">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-4 py-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex flex-col gap-2.5 lg:flex-row lg:items-center lg:gap-5">
                        <div>
                            <h3 class="font-heading text-sm font-bold text-[#0f172a]">Data Kehadiran</h3>
                            <p class="mt-1 text-xs font-medium text-slate-500">{{ formattedDate }}</p>
                        </div>
                        <div class="flex flex-wrap gap-4 text-[11px] font-medium text-slate-600" aria-label="Legenda kehadiran">
                            <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-[#2867e8]"></i>H Hadir</span>
                            <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-[#facc15]"></i>I Izin</span>
                            <span class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-full bg-[#f9a8b8]"></i>A Alfa</span>
                        </div>
                    </div>
                    <label class="flex w-full flex-col gap-1.5 text-xs font-semibold text-slate-500 sm:w-auto">
                        <span>Tanggal Absensi</span>
                        <input
                            type="date"
                            :value="attendanceDate"
                            :max="today"
                            class="h-9 w-full rounded-lg border-slate-300 text-xs text-slate-600 focus:border-[#2867e8] focus:ring-[#2867e8] sm:w-auto"
                            @change="openDate"
                        >
                    </label>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1180px] table-fixed border-collapse text-left">
                        <colgroup>
                            <col class="w-[10%]">
                            <col class="w-[19%]">
                            <col class="w-[16%]">
                            <col class="w-[14%]">
                            <col class="w-[12%]">
                            <col class="w-[12%]">
                            <col class="w-[17%]">
                        </colgroup>
                        <thead class="bg-[#f1f4f8] text-[10px] font-bold uppercase tracking-wide text-[#334155]">
                            <tr>
                                <th class="px-3 py-3">Tanggal</th>
                                <th class="px-3 py-3">Nama Karyawan</th>
                                <th class="px-3 py-3">Jabatan</th>
                                <th class="px-3 py-3">Kehadiran</th>
                                <th class="px-3 py-3">Jam Masuk</th>
                                <th class="px-3 py-3">Jam Keluar</th>
                                <th class="px-3 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0] text-xs">
                            <tr v-for="employee in filteredEmployees" :key="employee.id" class="min-h-[62px] hover:bg-slate-50/60">
                                <td class="whitespace-nowrap px-3 py-3 align-middle font-medium text-slate-500">{{ compactDate }}</td>
                                <td class="px-3 py-3 align-middle">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#dbeafe] text-[10px] font-bold text-[#0756ba]">{{ employee.initials }}</span>
                                        <span class="truncate font-semibold text-[#0f172a]" :title="employee.name">{{ employee.name }}</span>
                                    </div>
                                </td>
                                <td class="truncate px-3 py-3 align-middle text-[#334155]" :title="employee.position">{{ employee.position }}</td>
                                <td class="px-3 py-3 align-middle">
                                    <fieldset v-if="isEditing" class="flex gap-2">
                                        <legend class="sr-only">Kehadiran {{ employee.name }}</legend>
                                        <label v-for="option in attendanceOptions" :key="option.value" class="cursor-pointer">
                                            <input
                                                type="radio"
                                                :name="`attendance-${employee.id}`"
                                                :value="option.value"
                                                :checked="recordFor(employee.id).status_kehadiran === option.value"
                                                class="sr-only"
                                                @change="setAttendanceStatus(recordFor(employee.id), option.value)"
                                            >
                                            <span
                                                :class="recordFor(employee.id).status_kehadiran === option.value ? option.active : 'border-[#cbd5e1] bg-white text-[#334155]'"
                                                class="grid h-8 w-8 place-items-center rounded-full border text-xs font-semibold transition-colors"
                                                :title="option.label"
                                            >
                                                {{ option.value }}
                                            </span>
                                        </label>
                                    </fieldset>
                                    <span
                                        v-else-if="recordFor(employee.id).status_kehadiran"
                                        :class="statusClasses[recordFor(employee.id).status_kehadiran]"
                                        class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold"
                                    >
                                        {{ recordFor(employee.id).status_kehadiran }}
                                    </span>
                                    <span v-else class="text-slate-400">-</span>
                                    <p v-if="fieldError(employee.id, 'status_kehadiran')" class="mt-1 text-[10px] text-red-600">Pilih H, I, atau A.</p>
                                </td>
                                <td class="px-3 py-3 align-top">
                                    <AttendanceTimeInput
                                        v-if="isEditing"
                                        :ref="(component) => setTimeInputRef(employee.id, 'jam_masuk', component)"
                                        v-model="recordFor(employee.id).jam_masuk"
                                        :disabled="recordFor(employee.id).status_kehadiran !== 'H'"
                                        :label="`Jam Masuk ${employee.name}`"
                                        :error="fieldError(employee.id, 'jam_masuk')"
                                        max-time="12:00"
                                        :indicator="isLate(recordFor(employee.id)) ? 'Terlambat' : ''"
                                        @update:model-value="clearFieldError(employee.id, 'jam_masuk')"
                                        @navigate-next="navigateNextTimeInput(employee.id, 'jam_masuk')"
                                        @navigate-row="navigateTimeRow(employee.id, 'jam_masuk', $event)"
                                    />
                                    <span v-else class="tabular-nums text-slate-600">{{ recordFor(employee.id).jam_masuk || '-' }}</span>
                                </td>
                                <td class="px-3 py-3 align-top">
                                    <AttendanceTimeInput
                                        v-if="isEditing"
                                        :ref="(component) => setTimeInputRef(employee.id, 'jam_keluar', component)"
                                        v-model="recordFor(employee.id).jam_keluar"
                                        :disabled="recordFor(employee.id).status_kehadiran !== 'H'"
                                        :label="`Jam Keluar ${employee.name}`"
                                        :error="fieldError(employee.id, 'jam_keluar')"
                                        :min-time="recordFor(employee.id).jam_masuk"
                                        :indicator="isEarlyLeave(recordFor(employee.id)) ? 'Pulang Awal' : ''"
                                        @update:model-value="clearFieldError(employee.id, 'jam_keluar')"
                                        @navigate-next="navigateNextTimeInput(employee.id, 'jam_keluar')"
                                        @navigate-row="navigateTimeRow(employee.id, 'jam_keluar', $event)"
                                    />
                                    <span v-else class="tabular-nums text-slate-600">{{ recordFor(employee.id).jam_keluar || '-' }}</span>
                                </td>
                                <td class="px-3 py-3 align-top">
                                    <input
                                        v-if="isEditing"
                                        v-model="recordFor(employee.id).keterangan"
                                        type="text"
                                        maxlength="255"
                                        placeholder="Tambah catatan..."
                                        class="h-9 w-full rounded-md border-[#cbd5e1] bg-white text-xs placeholder:text-slate-400 focus:border-[#2867e8] focus:ring-[#2867e8]"
                                    >
                                    <span v-else class="line-clamp-2 text-slate-600" :title="recordFor(employee.id).keterangan || '-'">
                                        {{ recordFor(employee.id).keterangan || '-' }}
                                    </span>
                                    <p v-if="fieldError(employee.id, 'keterangan')" class="mt-1 text-[10px] text-red-600">{{ fieldError(employee.id, 'keterangan') }}</p>
                                </td>
                            </tr>
                            <tr v-if="!filteredEmployees.length">
                                <td colspan="7" class="px-5 py-16 text-center text-sm text-slate-500">
                                    Tidak ada karyawan yang sesuai pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <Modal :show="bulkConfirmationOpen" max-width="sm" @close="bulkConfirmationOpen = false">
                <div class="p-5 sm:p-6">
                    <div class="flex items-start gap-3">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-50 text-[#0756ba]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18M3 12h18" /></svg>
                        </span>
                        <div>
                            <h3 class="font-heading text-base font-bold text-[#0f172a]">Isi absensi yang kosong?</h3>
                            <p class="mt-1.5 text-sm leading-5 text-slate-500">
                                Tandai {{ missingSelectionCount }} karyawan yang belum dipilih sebagai Hadir. Status yang sudah terisi tidak akan berubah.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            class="h-10 rounded-lg border border-slate-300 px-4 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                            @click="bulkConfirmationOpen = false"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            class="h-10 rounded-lg bg-[#0756ba] px-5 text-sm font-semibold text-white transition hover:bg-[#064ca3]"
                            @click="markUnselectedPresent"
                        >
                            Ya, Isi
                        </button>
                    </div>
                </div>
            </Modal>
        </section>
    </InternalDashboardLayout>
</template>
