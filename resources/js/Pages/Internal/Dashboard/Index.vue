<script setup>
import InternalDashboardLayout from '@/Layouts/InternalDashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: { type: Object, required: true },
    permissions: { type: Object, required: true },
    employeeSummary: { type: Object, default: null },
    attendanceSummary: { type: Object, default: null },
    ownAttendance: { type: Object, default: null },
    latestEmployees: { type: Array, required: true },
    calendar: { type: Object, required: true },
});

const summaryCards = computed(() => props.employeeSummary ? [
    { label: 'Total Karyawan', value: props.employeeSummary.total, icon: 'users', toneClass: 'summary-card--blue' },
    { label: 'Karyawan Aktif', value: props.employeeSummary.active, icon: 'active', toneClass: 'summary-card--orange' },
    { label: 'Hadir Hari Ini', value: props.employeeSummary.presentToday, icon: 'present', toneClass: 'summary-card--pink' },
    { label: 'Izin / Alpha', value: props.employeeSummary.absentToday, icon: 'absent', toneClass: 'summary-card--gray' },
] : []);

const attendanceRows = computed(() => props.attendanceSummary ? [
    { key: 'hadir', label: 'Hadir', code: 'H', color: '#22c55e', track: '#dcfce7', ...props.attendanceSummary.hadir },
    { key: 'izin', label: 'Izin', code: 'I', color: '#eab308', track: '#fef9c3', ...props.attendanceSummary.izin },
    { key: 'alpha', label: 'Alpha', code: 'A', color: '#ef4444', track: '#fee2e2', ...props.attendanceSummary.alpha },
] : []);

const attendanceStatusClasses = computed(() => ({
    H: 'border-emerald-200 bg-emerald-50 text-emerald-700',
    I: 'border-amber-200 bg-amber-50 text-amber-700',
    A: 'border-rose-200 bg-rose-50 text-rose-700',
})[props.ownAttendance?.status] || 'border-slate-200 bg-slate-50 text-slate-600');

</script>

<template>
    <Head title="Dashboard" />

    <InternalDashboardLayout
        :user="user"
        title="Dashboard"
        :can-view-attendance="permissions.canViewAttendance"
        :can-manage-employee-masters="user.roleName === 'super_admin'"
    >
        <div class="px-4 py-4 sm:px-6 lg:px-7 lg:py-5">
            <section class="dashboard-shell mx-auto min-h-[calc(100vh-104px)] max-w-[1500px] overflow-hidden border border-[#e2e8f0] bg-white p-4 shadow-[0_8px_28px_rgba(15,23,42,0.035)] sm:p-5 lg:p-6">
                <div class="dashboard-content">
                    <div class="dashboard-banner relative overflow-hidden rounded-[14px] px-5 py-5 sm:px-7 sm:py-6 lg:px-8">
                        <span class="absolute -right-12 -top-24 h-56 w-56 rounded-full border-[42px] border-white/10" aria-hidden="true"></span>
                        <svg class="dashboard-banner__icon" aria-hidden="true" viewBox="0 0 96 96" fill="none" stroke="currentColor" stroke-width="8">
                            <rect x="12" y="12" width="72" height="72" rx="8"/>
                            <path d="M29 67V47h12v20M47 67V29h12v38M65 67V39h12v28"/>
                        </svg>
                        <div class="relative z-10 max-w-[72%] sm:max-w-[75%]">
                            <span class="mb-3 inline-flex rounded-full border border-white/25 bg-white/15 px-3 py-1 text-[10px] font-bold tracking-[0.14em]">
                                {{ user.viewBadge }}
                            </span>
                            <h2 class="dashboard-banner__title font-heading font-bold">
                                Selamat datang, {{ user.name }}!
                            </h2>
                            <p v-if="user.position" class="mt-1.5 text-sm font-semibold text-white/95">{{ user.position }}</p>
                            <p class="mt-1.5 text-sm text-blue-50">Berikut ringkasan aktivitas Kampoeng Radja hari ini.</p>
                            <span class="dashboard-review mt-4 inline-flex rounded-md bg-white px-5 py-2 text-xs font-semibold shadow-sm">
                                Review Dashboard
                            </span>
                        </div>
                    </div>

                    <div v-if="permissions.showsOrganizationWidgets" class="dashboard-summary-grid mt-4">
                    <article v-for="card in summaryCards" :key="card.label" :class="card.toneClass" class="summary-card">
                        <span class="summary-card__icon">
                            <svg v-if="card.icon === 'users'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3 19v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2M16 7a3 3 0 0 1 0 6M17 14a4 4 0 0 1 4 4v1"/></svg>
                            <svg v-else-if="card.icon === 'active'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg>
                            <svg v-else-if="card.icon === 'present'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20V7l8-4 8 4v13"/><path d="M9 20v-5h6v5M8 10h.01M12 10h.01M16 10h.01"/></svg>
                            <svg v-else class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v6l4 2"/></svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold uppercase leading-tight tracking-[0.08em] text-[#172554]">{{ card.label }}</p>
                            <strong class="mt-0.5 block font-heading text-[24px] leading-none text-[#172554]">{{ card.value }}</strong>
                        </div>
                    </article>
                    </div>

                <article v-else class="mt-5 rounded-[14px] border border-[#e2e8f0] bg-white p-5 shadow-[0_4px_14px_rgba(15,23,42,0.035)]">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-[#8a94a6]">Kehadiran Saya Hari Ini</p>
                            <h3 class="mt-2 text-lg font-bold text-[#172554]">{{ ownAttendance.label }}</h3>
                            <p v-if="ownAttendance.note" class="mt-1 text-sm text-slate-500">{{ ownAttendance.note }}</p>
                        </div>
                        <span :class="attendanceStatusClasses" class="inline-flex w-fit items-center rounded-full border px-4 py-2 text-sm font-bold">
                            {{ ownAttendance.status || '—' }}
                        </span>
                    </div>
                </article>

                    <div :class="{ 'dashboard-main-grid--single': !permissions.showsOrganizationWidgets }" class="dashboard-main-grid mt-4">
                    <article v-if="permissions.showsOrganizationWidgets" class="self-start rounded-[12px] border border-[#e2e8f0] bg-white p-5 shadow-[0_3px_10px_rgba(15,23,42,0.025)]">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="font-heading text-lg font-bold text-[#172554]">Persentase Kehadiran Hari Ini</h3>
                                <p class="mt-1 text-xs text-slate-500">Dari {{ attendanceSummary.activeEmployees }} karyawan aktif dalam scope Anda</p>
                            </div>
                            <Link v-if="permissions.canViewAttendance" :href="route('admin.absensi.index')" class="text-xs font-bold text-[#2867e8] hover:underline">
                                Lihat Semua
                            </Link>
                        </div>

                        <div class="mt-5 space-y-4">
                            <div v-for="item in attendanceRows" :key="item.key">
                                <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                    <span class="flex items-center gap-2 font-semibold text-[#334155]">
                                        <span class="h-2.5 w-2.5 rounded-full" :style="{ backgroundColor: item.color }"></span>
                                        {{ item.label }} ({{ item.code }})
                                        <small class="font-normal text-slate-400">{{ item.count }} orang</small>
                                    </span>
                                    <strong class="text-[#172554]">{{ item.percentage }}%</strong>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full" :style="{ backgroundColor: item.track }">
                                    <div class="h-full rounded-full transition-[width] duration-300" :style="{ width: `${item.percentage}%`, backgroundColor: item.color }"></div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <div :class="permissions.showsOrganizationWidgets ? '' : 'mx-auto w-full max-w-[520px]'" class="dashboard-side-column min-w-0">
                        <article class="calendar-card rounded-[12px] border border-[#e2e8f0] bg-white p-4 shadow-[0_3px_10px_rgba(15,23,42,0.025)]">
                            <div class="mb-3 flex items-center justify-between gap-2">
                                <h3 class="font-heading text-sm font-bold text-[#172554]">Kalender Kerja</h3>
                                <span class="text-[10px] font-semibold capitalize text-[#2867e8]">{{ calendar.monthLabel }}</span>
                            </div>
                            <div class="calendar-grid text-center text-[10px]">
                                <span v-for="(dayName, dayIndex) in ['M', 'S', 'S', 'R', 'K', 'J', 'S']" :key="dayIndex" class="calendar-weekday">{{ dayName }}</span>
                                <span
                                    v-for="day in calendar.days"
                                    :key="day.date"
                                    :class="[
                                        day.isToday ? 'bg-[#2867e8] font-bold text-white shadow-sm' : '',
                                        !day.isToday && day.isCurrentMonth ? 'text-[#334155]' : '',
                                        !day.isCurrentMonth ? 'text-slate-300' : '',
                                    ]"
                                    class="calendar-day"
                                >{{ day.day }}</span>
                            </div>
                        </article>

                        <article v-if="permissions.showsOrganizationWidgets" class="rounded-[12px] border border-[#e2e8f0] bg-white p-4 shadow-[0_3px_10px_rgba(15,23,42,0.025)]">
                            <h3 class="font-heading text-sm font-bold text-[#172554]">Karyawan Terbaru</h3>
                            <div v-if="latestEmployees.length" class="mt-3 space-y-3">
                                <div v-for="employee in latestEmployees" :key="employee.id" class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#eef1f5] text-[10px] font-bold text-[#2867e8]">{{ employee.initials }}</span>
                                    <div class="min-w-0">
                                        <strong class="block truncate text-sm text-[#172554]">{{ employee.name }}</strong>
                                        <small class="block truncate text-xs text-slate-500">{{ employee.position || 'Jabatan belum tersedia' }}</small>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="mt-4 rounded-lg bg-slate-50 px-4 py-5 text-center text-sm text-slate-500">Belum ada data karyawan.</p>
                        </article>
                    </div>
                    </div>
                </div>
            </section>
        </div>
    </InternalDashboardLayout>
</template>

<style scoped>
.dashboard-content {
    width: min(100%, 1040px);
    margin-inline: auto;
}

.dashboard-shell {
    border-radius: 18px;
}

.dashboard-banner {
    background: #0756ca;
    color: #ffffff;
    border-radius: 14px;
}

.dashboard-banner__icon {
    position: absolute;
    top: 50%;
    right: 32px;
    width: 96px;
    height: 96px;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.2);
}

.dashboard-banner__title {
    font-size: 30px;
    line-height: 1.15;
}

.dashboard-review {
    color: #0756ca;
}

.dashboard-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.summary-card {
    display: flex;
    min-width: 0;
    min-height: 76px;
    align-items: center;
    gap: 12px;
    border-radius: 10px;
    padding: 12px 14px;
}

.summary-card__icon {
    display: grid;
    width: 38px;
    height: 38px;
    flex: 0 0 auto;
    place-items: center;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.72);
    color: #0756ca;
}

.summary-card__icon :deep(svg) {
    width: 19px;
    height: 19px;
}

.summary-card--blue { background: #dce5ff; }
.summary-card--orange { background: #ffe0d0; }
.summary-card--pink { background: #ffd9e2; }
.summary-card--gray { background: #e8ebee; }

.dashboard-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.72fr) minmax(250px, 0.82fr);
    align-items: start;
    gap: 20px;
}

.dashboard-main-grid--single {
    grid-template-columns: minmax(0, 1fr);
}

.dashboard-side-column {
    display: grid;
    grid-auto-rows: max-content;
    align-content: start;
    align-self: start;
    gap: 16px;
}

.dashboard-main-grid > article,
.dashboard-side-column > article {
    border-radius: 12px;
}

.calendar-card {
    height: auto;
    min-height: 0;
    align-self: start;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    grid-auto-rows: 26px;
    align-items: center;
    width: 100%;
}

.calendar-weekday,
.calendar-day {
    display: grid;
    width: 24px;
    height: 24px;
    margin-inline: auto;
    place-items: center;
}

.calendar-weekday {
    color: #94a3b8;
    font-weight: 700;
}

.calendar-day {
    border-radius: 999px;
}

@media (max-width: 1023px) {
    .dashboard-main-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .dashboard-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 479px) {
    .dashboard-banner__title {
        font-size: 24px;
    }

    .dashboard-banner__icon {
        display: none;
    }

    .dashboard-summary-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .summary-card {
        min-height: 84px;
    }
}
</style>
