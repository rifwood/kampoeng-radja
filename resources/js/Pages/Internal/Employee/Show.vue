<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    employee: { type: Object, required: true },
    permissions: { type: Object, required: true },
    user: { type: Object, required: true },
});

const page = usePage();
const exitOpen = ref(false);
const accountOpen = ref(false);
const exitForm = useForm({ tanggal_keluar: '' });
const accountForm = useForm({ username: '', pin: '', pin_confirmation: '' });
const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
const isSuperAdmin = computed(() => props.permissions.roleName === 'super_admin');

const rows = (items) => items.filter((item) => item.value !== undefined);

const destroy = () => {
    if (confirm('Hapus data karyawan ini secara permanen?')) {
        router.delete(route('dashboard.karyawan.destroy', props.employee.id));
    }
};

const deactivate = () => {
    if (confirm('Nonaktifkan karyawan dan akun login terkait?')) {
        router.patch(route('dashboard.karyawan.deactivate', props.employee.id));
    }
};

const submitExit = () => {
    exitForm.patch(route('dashboard.karyawan.exit', props.employee.id), {
        preserveScroll: true,
        onSuccess: () => {
            exitOpen.value = false;
            exitForm.reset();
        },
    });
};

const openAccount = () => {
    accountForm.clearErrors();
    accountOpen.value = true;
};

const submitAccount = () => {
    accountForm.post(route('dashboard.karyawan.account.store', props.employee.id), {
        preserveScroll: true,
        onSuccess: () => {
            accountOpen.value = false;
            accountForm.reset();
        },
    });
};

const updateAccountStatus = (isActive) => {
    const action = isActive ? 'aktifkan' : 'nonaktifkan';
    if (!confirm(`Yakin ingin ${action} akun ini?`)) return;

    router.patch(
        route('dashboard.karyawan.account.status', props.employee.id),
        { is_active: isActive },
        { preserveScroll: true },
    );
};
</script>

<template>
    <Head :title="`Detail ${employee.name}`" />
    <InternalDashboardLayout
        :user="user"
        title="Detail Karyawan"
        :can-manage-employee-masters="permissions.canManageMasters"
    >
        <div class="mx-auto max-w-[1180px] px-4 py-6 sm:px-6 lg:px-8">
            <div v-if="success" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ success }}
            </div>
            <div v-if="error || page.props.errors?.account" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ error || page.props.errors.account }}
            </div>

            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <Link :href="route('dashboard.karyawan.index')" class="text-xs font-semibold text-[#1769e0]">← Data Karyawan</Link>
                    <h2 class="mt-2 text-xl font-bold text-[#15356f]">{{ employee.name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ employee.position || 'Tanpa jabatan' }} · {{ employee.department || 'Tanpa departemen' }}</p>
                </div>
                <div v-if="permissions.canManage" class="flex flex-wrap gap-2">
                    <Link :href="route('dashboard.karyawan.edit', employee.id)" class="rounded-lg border border-[#1769e0] px-4 py-2 text-sm font-semibold text-[#1769e0]">Edit</Link>
                    <button v-if="employee.activeStatus === 'aktif'" class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700" @click="deactivate">Nonaktifkan</button>
                    <button class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700" @click="exitOpen = true">Karyawan Keluar</button>
                    <button v-if="permissions.canDelete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white" @click="destroy">Hapus</button>
                </div>
            </div>

            <div class="grid gap-5 lg:grid-cols-2">
                <section
                    v-for="section in [
                        { title: 'Data Pribadi', items: rows([{ label: 'NIK', value: employee.nik }, { label: 'Nama', value: employee.name }, { label: 'Tempat, Tanggal Lahir', value: `${employee.birthPlace}, ${employee.birthDate}` }, { label: 'Jenis Kelamin', value: employee.gender === 'L' ? 'Laki-laki' : 'Perempuan' }, { label: 'Agama', value: employee.religion }, { label: 'Status Perkawinan', value: employee.maritalStatus }, { label: 'Pendidikan', value: employee.education }, { label: 'Alamat', value: employee.address }]) },
                        { title: 'Data Pekerjaan', items: rows([{ label: 'Jabatan', value: employee.position }, { label: 'Departemen', value: employee.department || 'Tanpa departemen' }, { label: 'Status Kerja', value: employee.employmentStatus }, { label: 'Status Keaktifan', value: employee.activeStatus }, { label: 'Tanggal Masuk', value: employee.joinedAt }, { label: 'Tanggal Keluar', value: employee.leftAt || '—' }]) },
                    ]"
                    :key="section.title"
                    class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <h3 class="border-b border-slate-100 pb-3 text-base font-bold text-[#15356f]">{{ section.title }}</h3>
                    <dl class="mt-2 divide-y divide-slate-100">
                        <div v-for="item in section.items" :key="item.label" class="grid gap-1 py-3 sm:grid-cols-[150px_1fr]">
                            <dt class="text-xs font-medium text-slate-500">{{ item.label }}</dt>
                            <dd class="text-sm font-semibold capitalize text-slate-700">{{ item.value || '—' }}</dd>
                        </div>
                    </dl>
                </section>

                <section v-if="permissions.roleName === 'super_admin'" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="border-b border-slate-100 pb-3 text-base font-bold text-[#15356f]">Kontak</h3>
                    <dl class="mt-2"><div class="grid gap-1 py-3 sm:grid-cols-[150px_1fr]"><dt class="text-xs font-medium text-slate-500">No. HP</dt><dd class="text-sm font-semibold text-slate-700">{{ employee.phone }}</dd></div></dl>
                </section>

                <section v-if="permissions.roleName === 'super_admin'" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="border-b border-slate-100 pb-3 text-base font-bold text-[#15356f]">Dokumen</h3>
                    <div class="mt-4">
                        <img v-if="employee.ktpPhotoUrl" :src="employee.ktpPhotoUrl" alt="Foto KTP karyawan" class="max-h-72 w-full rounded-lg border border-slate-200 bg-slate-50 object-contain" />
                        <p v-else class="rounded-lg bg-slate-50 px-4 py-8 text-center text-sm text-slate-400">Belum ada dokumen</p>
                    </div>
                </section>

                <section v-if="isSuperAdmin" class="rounded-xl border border-blue-100 bg-white p-5 shadow-sm lg:col-span-2" data-testid="employee-account-section">
                    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div><h3 class="text-base font-bold text-[#15356f]">Akun Sistem</h3><p class="mt-1 text-xs text-slate-500">Akses login internal yang terhubung ke Karyawan ini.</p></div>
                        <button v-if="!employee.account && employee.accountRole" type="button" class="rounded-lg bg-[#1769e0] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0756ba]" @click="openAccount">+ Buat Akun</button>
                    </div>

                    <div v-if="employee.account" class="mt-4 grid gap-4 md:grid-cols-4">
                        <div class="rounded-lg bg-slate-50 p-4"><p class="text-xs text-slate-500">Username</p><p class="mt-1 font-semibold text-slate-800">{{ employee.account.username }}</p></div>
                        <div class="rounded-lg bg-slate-50 p-4"><p class="text-xs text-slate-500">Role</p><p class="mt-1 font-semibold text-slate-800">{{ employee.account.roleLabel }}</p></div>
                        <div class="rounded-lg bg-slate-50 p-4"><p class="text-xs text-slate-500">Status Akun</p><span :class="employee.account.isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600'" class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-bold">{{ employee.account.isActive ? 'Aktif' : 'Nonaktif' }}</span></div>
                        <div class="rounded-lg bg-slate-50 p-4"><p class="text-xs text-slate-500">Keamanan PIN</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ employee.account.mustChangePin ? 'Wajib ganti PIN' : 'PIN sudah diganti' }}</p></div>
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4 md:col-span-4">
                            <p v-if="employee.activeStatus !== 'aktif'" class="text-xs font-medium text-amber-700">Aktifkan kembali master Karyawan sebelum mengaktifkan akun.</p><span v-else></span>
                            <button v-if="employee.account.isActive" type="button" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700" @click="updateAccountStatus(false)">Nonaktifkan Akun</button>
                            <button v-else type="button" :disabled="employee.activeStatus !== 'aktif'" class="rounded-lg bg-[#1769e0] px-4 py-2 text-sm font-semibold text-white disabled:cursor-not-allowed disabled:bg-slate-300" @click="updateAccountStatus(true)">Aktifkan Akun</button>
                        </div>
                    </div>

                    <div v-else class="mt-4 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-5 py-6">
                        <p class="font-semibold text-slate-700">Belum memiliki akun</p>
                        <p v-if="employee.accountRole" class="mt-1 text-sm text-slate-500">Role akun: <strong>{{ employee.accountRoleLabel }}</strong></p>
                        <p v-else class="mt-1 text-sm font-medium text-amber-700">Role untuk jabatan ini belum ditentukan.</p>
                    </div>
                </section>
            </div>
        </div>

        <div v-if="exitOpen" class="fixed inset-0 z-[70] grid place-items-center bg-slate-950/40 p-4" @click.self="exitOpen = false">
            <form class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" @submit.prevent="submitExit">
                <h3 class="text-lg font-bold text-[#15356f]">Proses Karyawan Keluar</h3><p class="mt-1 text-sm text-slate-500">Status karyawan dan akun login akan dinonaktifkan.</p>
                <label class="mt-5 block text-xs font-semibold text-slate-700">Tanggal Keluar<input v-model="exitForm.tanggal_keluar" type="date" class="mt-2 w-full rounded-lg border-slate-300" /></label>
                <p v-if="exitForm.errors.tanggal_keluar" class="mt-1 text-xs text-red-600">{{ exitForm.errors.tanggal_keluar }}</p>
                <div class="mt-5 flex justify-end gap-2"><button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm" @click="exitOpen = false">Batal</button><button :disabled="exitForm.processing" class="rounded-lg bg-[#1769e0] px-4 py-2 text-sm font-semibold text-white disabled:opacity-50">Simpan</button></div>
            </form>
        </div>

        <div v-if="accountOpen" class="fixed inset-0 z-[70] grid place-items-center bg-slate-950/40 p-4" @click.self="accountOpen = false">
            <form class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl" @submit.prevent="submitAccount">
                <h3 class="text-lg font-bold text-[#15356f]">Buat Akun Karyawan</h3><p class="mt-1 text-sm text-slate-500">PIN awal wajib diganti oleh pengguna saat login pertama.</p>
                <div class="mt-5 grid gap-3 rounded-lg bg-blue-50 p-4 sm:grid-cols-3">
                    <div><p class="text-[11px] font-medium text-slate-500">Nama</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ employee.name }}</p></div>
                    <div><p class="text-[11px] font-medium text-slate-500">Jabatan</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ employee.position }}</p></div>
                    <div><p class="text-[11px] font-medium text-slate-500">Role</p><p class="mt-1 text-sm font-semibold text-[#1769e0]">{{ employee.accountRoleLabel }}</p></div>
                </div>
                <p v-if="accountForm.errors.account" class="mt-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">{{ accountForm.errors.account }}</p>
                <label class="mt-5 block text-xs font-semibold text-slate-700">Username<input v-model="accountForm.username" type="text" maxlength="100" autocomplete="off" class="mt-2 w-full rounded-lg border-slate-300 focus:border-[#1769e0] focus:ring-[#1769e0]" /></label>
                <p v-if="accountForm.errors.username" class="mt-1 text-xs text-red-600">{{ accountForm.errors.username }}</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="block text-xs font-semibold text-slate-700">PIN Awal<input v-model="accountForm.pin" type="password" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" autocomplete="new-password" class="mt-2 w-full rounded-lg border-slate-300 focus:border-[#1769e0] focus:ring-[#1769e0]" /><span v-if="accountForm.errors.pin" class="mt-1 block text-xs text-red-600">{{ accountForm.errors.pin }}</span></label>
                    <label class="block text-xs font-semibold text-slate-700">Konfirmasi PIN<input v-model="accountForm.pin_confirmation" type="password" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" autocomplete="new-password" class="mt-2 w-full rounded-lg border-slate-300 focus:border-[#1769e0] focus:ring-[#1769e0]" /></label>
                </div>
                <div class="mt-6 flex justify-end gap-2"><button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm" @click="accountOpen = false">Batal</button><button :disabled="accountForm.processing" class="rounded-lg bg-[#1769e0] px-4 py-2 text-sm font-semibold text-white disabled:opacity-50">{{ accountForm.processing ? 'Membuat...' : 'Buat Akun' }}</button></div>
            </form>
        </div>
    </InternalDashboardLayout>
</template>
