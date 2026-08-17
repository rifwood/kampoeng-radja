<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    employees: { type: Object, required: true }, filters: { type: Object, required: true },
    masterData: { type: Object, required: true }, permissions: { type: Object, required: true }, user: { type: Object, required: true },
});
const page = usePage();
const query = reactive({ ...props.filters });
const success = computed(() => page.props.flash?.success);
const error = computed(() => page.props.flash?.error);
const applyFilters = () => router.get(route('dashboard.karyawan.index'), query, { preserveState: true, replace: true });
const resetFilters = () => router.get(route('dashboard.karyawan.index'));
const statusClass = (status) => status === 'aktif' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-slate-200';
</script>

<template>
    <Head title="Data Karyawan" />
    <InternalDashboardLayout :user="user" title="Data Karyawan" :can-manage-employee-masters="permissions.canManageMasters">
        <div class="mx-auto max-w-[1500px] px-4 py-5 sm:px-6 lg:px-7">
            <div v-if="success" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ success }}</div>
            <div v-if="error" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ error }}</div>
            <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
                <div><h2 class="text-[20px] font-bold leading-tight text-[#172554]">Data Karyawan</h2><p class="mt-1 text-xs text-slate-500">{{ permissions.roleName === 'admin' ? 'Daftar karyawan pada departemen Anda.' : permissions.roleName === 'user' ? 'Informasi pekerjaan Anda.' : 'Kelola informasi dan status karyawan Kampoeng Radja.' }}</p></div>
                <Link v-if="permissions.canManage" :href="route('dashboard.karyawan.create')" class="inline-flex h-9 items-center gap-2 rounded-full bg-[#0756d8] px-5 text-xs font-bold text-white shadow-sm hover:bg-[#0647b0] focus:outline-none focus:ring-2 focus:ring-[#0756d8]/30"><span class="text-base leading-none">+</span> Tambah Karyawan</Link>
            </div>

            <section class="overflow-hidden rounded-[10px] border border-[#dbe2ea] bg-white shadow-[0_2px_8px_rgba(15,23,42,0.04)]">
                <form v-if="permissions.canSearch" class="border-b border-slate-200 bg-white p-3" @submit.prevent="applyFilters">
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-6 xl:grid-cols-12">
                        <label class="relative sm:col-span-2 lg:col-span-2 xl:col-span-3"><span class="sr-only">Cari karyawan</span><svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input v-model="query.search" class="h-9 w-full rounded-md border-slate-300 pl-9 text-xs focus:border-[#1769e0] focus:ring-[#1769e0]" type="search" :placeholder="permissions.roleName === 'super_admin' ? 'Cari nama atau NIK...' : 'Cari nama...'" /></label>
                        <select v-model="query.jabatan_id" class="h-9 rounded-md border-slate-300 py-0 text-xs lg:col-span-1 xl:col-span-2"><option value="">Semua Jabatan</option><option v-for="item in masterData.jabatan" :key="item.id" :value="item.id">{{ item.nama_jabatan }}</option></select>
                        <select v-if="permissions.roleName === 'super_admin'" v-model="query.departemen_id" class="h-9 rounded-md border-slate-300 py-0 text-xs lg:col-span-1 xl:col-span-2"><option value="">Semua Departemen</option><option v-for="item in masterData.departemen" :key="item.id" :value="item.id">{{ item.nama_departemen }}</option></select>
                        <select v-model="query.status_kerja" class="h-9 rounded-md border-slate-300 py-0 text-xs lg:col-span-1 xl:col-span-2"><option value="">Semua Status Kerja</option><option v-for="item in ['kontrak','magang','buruh','freelance']" :key="item" :value="item">{{ item }}</option></select>
                        <select v-model="query.status_keaktifan" class="h-9 rounded-md border-slate-300 py-0 text-xs lg:col-span-1 xl:col-span-2"><option value="">Semua Keaktifan</option><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select>
                        <div class="flex gap-2 sm:col-span-2 lg:col-span-2 xl:col-span-1"><button type="submit" class="h-9 flex-1 rounded-md bg-[#0756d8] px-3 text-xs font-semibold text-white hover:bg-[#0647b0]">Cari</button><button type="button" class="h-9 rounded-md border border-slate-300 px-3 text-xs font-semibold text-slate-600 hover:bg-slate-50 xl:hidden" @click="resetFilters">Reset</button></div>
                    </div>
                    <div class="mt-2 hidden justify-end xl:flex"><button type="button" class="text-[11px] font-semibold text-slate-500 hover:text-[#0756d8]" @click="resetFilters">Reset filter</button></div>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] text-left text-xs">
                        <thead class="bg-[#f1f3f6] text-[10px] uppercase tracking-[0.04em] text-slate-500"><tr><th class="px-4 py-3">Nama</th><th v-if="permissions.roleName === 'super_admin'" class="px-4 py-3">NIK</th><th class="px-4 py-3">Jabatan</th><th class="px-4 py-3">Departemen</th><th class="px-4 py-3">Status Kerja</th><th class="px-4 py-3">Keaktifan</th><th class="px-4 py-3 text-center">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="employee in employees.data" :key="employee.id" class="hover:bg-slate-50/80">
                                <td class="px-4 py-3"><div class="flex items-center gap-2.5"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-blue-100 text-[10px] font-bold text-[#0756ba]">{{ employee.name.split(' ').slice(0,2).map(n => n[0]).join('').toUpperCase() }}</span><strong class="font-semibold text-[#172554]">{{ employee.name }}</strong></div></td>
                                <td v-if="permissions.roleName === 'super_admin'" class="px-4 py-3 font-mono text-[11px] text-slate-600">{{ employee.nik }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ employee.position || '—' }}</td><td class="px-4 py-3 text-slate-600">{{ employee.department || 'Tanpa departemen' }}</td><td class="px-4 py-3 capitalize text-slate-600">{{ employee.employmentStatus }}</td>
                                <td class="px-4 py-3"><span :class="statusClass(employee.activeStatus)" class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold capitalize ring-1 ring-inset">{{ employee.activeStatus }}</span></td>
                                <td class="px-4 py-3"><div class="flex items-center justify-center gap-1.5"><Link :href="route('dashboard.karyawan.show', employee.id)" class="grid h-8 w-8 place-items-center rounded-md border border-blue-200 text-[#0756d8] hover:bg-blue-50" title="Lihat detail" aria-label="Lihat detail"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg></Link><Link v-if="permissions.canManage" :href="route('dashboard.karyawan.edit', employee.id)" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-[#0756d8]" title="Edit karyawan" aria-label="Edit karyawan"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m4 16-.8 4 4-.8L18 8.4 15.6 6 4 16Z"/><path d="m14 7 3 3"/></svg></Link></div></td>
                            </tr>
                            <tr v-if="employees.data.length === 0"><td :colspan="permissions.roleName === 'super_admin' ? 7 : 6" class="px-6 py-16 text-center"><p class="font-semibold text-slate-600">Data karyawan tidak ditemukan.</p><p class="mt-1 text-xs text-slate-400">Ubah pencarian/filter atau tambahkan data baru.</p></td></tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="employees.links.length > 3" class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-4 py-3 text-[11px] text-slate-500"><span>Menampilkan {{ employees.from ?? 0 }}–{{ employees.to ?? 0 }} dari {{ employees.total }} data</span><div class="flex gap-1"><template v-for="link in employees.links" :key="link.label"><Link v-if="link.url" :href="link.url" v-html="link.label" :class="link.active ? 'bg-[#0756d8] text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'" class="min-w-8 rounded px-2.5 py-1.5 text-center" :preserve-scroll="true" /><span v-else v-html="link.label" class="min-w-8 cursor-not-allowed rounded border border-slate-100 px-2.5 py-1.5 text-center text-slate-300"></span></template></div></div>
            </section>
        </div>
    </InternalDashboardLayout>
</template>
