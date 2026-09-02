<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import ConfirmationDialog from '@/Components/Internal/Feedback/ConfirmationDialog.vue';
import NotificationToast from '@/Components/Internal/Feedback/NotificationToast.vue';
import { useNotification } from '@/composables/useNotification';

const props = defineProps({
    user: { type: Object, required: true },
    title: { type: String, required: true },
    canViewAttendance: { type: Boolean, default: false },
    canManageEmployeeMasters: { type: Boolean, default: false },
    showSearch: { type: Boolean, default: false },
});

defineEmits(['search']);

const page = usePage();
const notification = useNotification();
const sidebarOpen = ref(false);
const employeeRouteActive = computed(() => page.url.startsWith('/dashboard/karyawan') || page.url.startsWith('/dashboard/jabatan-departemen'));
const employeeExpanded = ref(employeeRouteActive.value);
const closingEventRouteActive = computed(() => page.url.startsWith('/dashboard/closing-event'));
const closingEventExpanded = ref(closingEventRouteActive.value);
const cmsRouteActive = computed(() => page.url.startsWith('/dashboard/cms'));
const cmsExpanded = ref(cmsRouteActive.value);
const closingEventPermissions = computed(() => page.props.auth?.closingEvent ?? {});
const attendancePermissions = computed(() => page.props.auth?.attendance ?? {});
const cmsPermissions = computed(() => page.props.auth?.cms ?? {});
const canAccessAttendance = computed(() => attendancePermissions.value.canView ?? props.canViewAttendance);
const attendanceMenuLabel = computed(() => attendancePermissions.value.canManage ? 'Kelola Absensi' : 'Data Absensi');
const menuItems = [
    { label: 'KPI (Soon)', soon: true, icon: 'chart' },
];

watch(employeeRouteActive, (active) => {
    if (active) employeeExpanded.value = true;
});
watch(closingEventRouteActive, (active) => {
    if (active) closingEventExpanded.value = true;
});
watch(cmsRouteActive, (active) => {
    if (active) cmsExpanded.value = true;
});
watch(
    () => [page.url, page.props.flash?.success, page.props.flash?.error, page.props.flash?.warning],
    ([, success, error, warning]) => {
        if (success) notification.success(success, { title: 'Berhasil' });
        if (error) notification.error(error, { title: 'Gagal' });
        if (warning) notification.warning(warning);
    },
    { immediate: true },
);
</script>

<template>
    <div class="min-h-screen overflow-x-hidden bg-[#f7f9fc] text-[#172554]">
        <ConfirmationDialog />
        <NotificationToast />
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-slate-950/30 lg:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 flex w-[252px] flex-col border-r border-[#dce3ed] bg-white transition-transform duration-200 lg:translate-x-0">
            <Link :href="route('dashboard')" class="flex h-[94px] items-center gap-3 border-b border-[#dce3ed] px-7">
                <img src="/assets/figma/logo-main-transparent.png" alt="Kampoeng Radja" class="h-9 w-9 object-contain" />
                <span>
                    <strong class="block text-[17px] font-bold leading-5 text-[#0756ba]">Kampoeng Radja</strong>
                    <small class="block font-mono text-[11px] tracking-wide text-[#334155]">Internal Dashboard</small>
                </span>
            </Link>

            <nav class="flex-1 space-y-1.5 overflow-y-auto px-3 py-6" aria-label="Navigasi internal">
                <Link :href="route('dashboard')" :class="route().current('dashboard') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#4b5563] hover:bg-slate-100'" class="flex h-11 items-center gap-3 rounded-lg px-4 text-sm font-semibold">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </Link>

                <div>
                    <button type="button" :aria-expanded="employeeExpanded" aria-controls="employee-navigation" :class="employeeRouteActive ? 'bg-blue-50 text-[#0756ba]' : 'text-[#4b5563] hover:bg-slate-100'" class="flex h-11 w-full items-center gap-3 rounded-lg px-4 text-left text-sm font-semibold" @click="employeeExpanded = !employeeExpanded">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3 19v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2M16 7a3 3 0 0 1 0 6M17 14a4 4 0 0 1 4 4v1"/></svg>
                        <span>Kelola Karyawan</span>
                        <svg :class="employeeExpanded ? 'rotate-180' : ''" class="ml-auto h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m7 10 5 5 5-5"/></svg>
                    </button>
                    <div v-show="employeeExpanded" id="employee-navigation" class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-3">
                        <Link :href="route('dashboard.karyawan.index')" :class="route().current('dashboard.karyawan.*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Data Karyawan</Link>
                        <Link v-if="canManageEmployeeMasters" :href="route('dashboard.employee-masters.index')" :class="route().current('dashboard.employee-masters.*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Master Organisasi</Link>
                    </div>
                </div>

                <Link v-if="canAccessAttendance" :href="route('admin.absensi.index')" :class="route().current('admin.absensi.*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#4b5563] hover:bg-slate-100'" class="flex h-11 items-center gap-3 rounded-lg px-4 text-sm font-semibold">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 6V4h6v2M5 7h14a1 1 0 0 1 1 1v11H4V8a1 1 0 0 1 1-1Z"/><path d="M4 12h16M10 10v4h4v-4"/></svg>
                    {{ attendanceMenuLabel }}
                </Link>
                <div v-if="closingEventPermissions.canView">
                    <button type="button" :aria-expanded="closingEventExpanded" aria-controls="closing-event-navigation" :class="closingEventRouteActive ? 'bg-blue-50 text-[#0756ba]' : 'text-[#4b5563] hover:bg-slate-100'" class="flex h-11 w-full items-center gap-3 rounded-lg px-4 text-left text-sm font-semibold" @click="closingEventExpanded = !closingEventExpanded">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4m8-4v4M3 10h18m-13 4h3m-3 3h6"/></svg>
                        <span>Closing Event</span>
                        <svg :class="closingEventExpanded ? 'rotate-180' : ''" class="ml-auto h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m7 10 5 5 5-5"/></svg>
                    </button>
                    <div v-show="closingEventExpanded" id="closing-event-navigation" class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-3">
                        <Link :href="route('dashboard.closing-event.index')" :class="route().current('dashboard.closing-event.index') || route().current('dashboard.closing-event.create') || route().current('dashboard.closing-event.show') || route().current('dashboard.closing-event.edit') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Data Closing Event</Link>
                        <Link v-if="closingEventPermissions.canManageMaster" :href="route('dashboard.closing-event.master.index')" :class="route().current('dashboard.closing-event.master.*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Master Data Event</Link>
                    </div>
                </div>
                <div v-if="cmsPermissions.canManage">
                    <button type="button" :aria-expanded="cmsExpanded" aria-controls="cms-navigation" :class="cmsRouteActive ? 'bg-blue-50 text-[#0756ba]' : 'text-[#4b5563] hover:bg-slate-100'" class="flex h-11 w-full items-center gap-3 rounded-lg px-4 text-left text-sm font-semibold" @click="cmsExpanded = !cmsExpanded">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h10M4 11h10M4 16h7"/><path d="m16 15 4-4m-3-1 3 3m-5 4 2-1 3-3-2-2-3 3-1 3Z"/></svg>
                        <span>CMS</span>
                        <svg :class="cmsExpanded ? 'rotate-180' : ''" class="ml-auto h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m7 10 5 5 5-5"/></svg>
                    </button>
                    <div v-show="cmsExpanded" id="cms-navigation" class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-3">
                        <Link :href="route('dashboard.cms.home')" :class="route().current('dashboard.cms.home*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Beranda</Link>
                        <Link :href="route('dashboard.cms.wahana.index')" :class="route().current('dashboard.cms.wahana.*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Wahana</Link>
                        <Link :href="route('dashboard.cms.gallery.index')" :class="route().current('dashboard.cms.gallery.*') ? 'bg-[#2867e8] text-white shadow-sm' : 'text-[#64748b] hover:bg-slate-100 hover:text-[#0756ba]'" class="flex min-h-9 items-center rounded-md px-3 py-2 text-xs font-semibold">Galeri Event</Link>
                    </div>
                </div>
                <span v-for="item in menuItems" :key="item.label" class="flex h-11 cursor-not-allowed items-center gap-3 rounded-lg px-4 text-sm font-medium text-[#64748b]" aria-disabled="true">
                    <svg v-if="item.icon === 'chart'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h16V4H4v16Z"/><path d="M8 16v-4m4 4V8m4 8v-6"/></svg>
                    <svg v-else-if="item.icon === 'calendar'" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4m8-4v4M3 10h18m-13 4h3"/></svg>
                    <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h10M4 11h10M4 16h7"/><path d="m16 15 4-4m-3-1 3 3m-5 4 2-1 3-3-2-2-3 3-1 3Z"/></svg>
                    {{ item.label }}
                </span>
            </nav>

            <Link :href="route('logout')" method="post" as="button" class="flex h-16 w-full items-center gap-3 border-t border-[#dce3ed] px-8 text-left text-sm font-semibold text-red-600 hover:bg-red-50">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 5H5v14h5M14 8l4 4-4 4m4-4H9"/></svg>
                Logout
            </Link>
        </aside>

        <div class="min-h-screen min-w-0 lg:pl-[252px]">
            <header class="sticky top-0 z-30 flex h-[64px] items-center border-b border-[#e2e8f0] bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-7">
                <button type="button" class="mr-3 rounded-lg border border-slate-200 p-2 lg:hidden" aria-label="Buka menu" @click="sidebarOpen = true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-heading text-xl font-bold text-[#0756ba] sm:text-[24px]">{{ title }}</h1>
                <div class="ml-auto flex items-center gap-3 sm:gap-5">
                    <label v-if="showSearch" class="relative hidden sm:block">
                        <span class="sr-only">Cari karyawan</span>
                        <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <input type="search" placeholder="Cari..." class="h-10 w-[210px] rounded-full border-[#d7dee8] bg-[#f8fafc] pl-11 pr-4 text-sm focus:border-[#2867e8] focus:ring-[#2867e8] xl:w-[255px]" @input="$emit('search', $event.target.value)" />
                    </label>
                    <button type="button" class="relative text-slate-500" aria-label="Notifikasi"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span class="absolute -right-0.5 -top-0.5 h-2 w-2 rounded-full bg-red-500"></span></button>
                    <button type="button" class="text-slate-500" aria-label="Pengaturan"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.03 1.56V21h-4v-.08A1.7 1.7 0 0 0 9 19.37a1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.63 15 1.7 1.7 0 0 0 3.08 14H3v-4h.08A1.7 1.7 0 0 0 4.63 9a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.63a1.7 1.7 0 0 0 1-1.55V3h4v.08A1.7 1.7 0 0 0 15 4.63a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.37 9a1.7 1.7 0 0 0 1.55 1H21v4h-.08A1.7 1.7 0 0 0 19.4 15Z"/></svg></button>
                    <span class="h-8 w-px bg-slate-200"></span>
                    <span v-if="user.roleLabel" class="hidden text-right xl:block">
                        <strong class="block max-w-[150px] truncate text-xs font-semibold text-slate-700">{{ user.name }}</strong>
                        <small class="block text-[10px] text-slate-500">{{ user.roleLabel }}</small>
                    </span>
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-[#dbeafe] text-xs font-bold text-[#0756ba]" :title="user.name">{{ user.initials }}</span>
                </div>
            </header>
            <main class="min-w-0"><slot /></main>
        </div>
    </div>
</template>
