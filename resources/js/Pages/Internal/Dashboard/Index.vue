<script setup>
import InternalDashboardLayout from "@/Layouts/InternalDashboardLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    user: { type: Object, required: true },
    permissions: { type: Object, required: true },
    employeeSummary: { type: Object, default: null },
    attendanceSummary: { type: Object, default: null },
    ownAttendance: { type: Object, default: null },
    revenueChart: { type: Object, default: null },
    closingEventSummary: { type: Object, default: null },
});

const hoveredPoint = ref(null);
const chartLoading = ref(false);

const monthOptions = [
    { value: 1, label: "Januari" },
    { value: 2, label: "Februari" },
    { value: 3, label: "Maret" },
    { value: 4, label: "April" },
    { value: 5, label: "Mei" },
    { value: 6, label: "Juni" },
    { value: 7, label: "Juli" },
    { value: 8, label: "Agustus" },
    { value: 9, label: "September" },
    { value: 10, label: "Oktober" },
    { value: 11, label: "November" },
    { value: 12, label: "Desember" },
];

const formatNumber = (value) =>
    new Intl.NumberFormat("id-ID").format(Number(value || 0));
const formatCurrency = (value) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(Number(value || 0));

const formatAxisCurrency = (value) => {
    const numericValue = Number(value || 0);
    if (numericValue === 0) return "Rp 0";
    if (numericValue >= 1_000_000_000)
        return `Rp ${(numericValue / 1_000_000_000).toLocaleString("id-ID", { maximumFractionDigits: 1 })} M`;
    if (numericValue >= 1_000_000)
        return `Rp ${(numericValue / 1_000_000).toLocaleString("id-ID", { maximumFractionDigits: 1 })} jt`;
    if (numericValue >= 1_000)
        return `Rp ${(numericValue / 1_000).toLocaleString("id-ID", { maximumFractionDigits: 0 })} rb`;
    return `Rp ${numericValue.toLocaleString("id-ID")}`;
};

const summaryCards = computed(() =>
    props.employeeSummary
        ? [
              {
                  label: "Karyawan Aktif",
                  value: props.employeeSummary.active,
                  secondary: `${formatNumber(props.employeeSummary.active)} dari ${formatNumber(props.employeeSummary.total)} total karyawan`,
                  icon: "employees",
                  tone: "blue",
              },
              {
                  label: "Hadir Hari Ini",
                  value: props.employeeSummary.presentToday,
                  secondary: `${props.employeeSummary.presentPercentage}% dari karyawan aktif`,
                  icon: "present",
                  tone: "green",
              },
              {
                  label: "Terlambat Hari Ini",
                  value: props.employeeSummary.lateToday,
                  secondary: `${props.employeeSummary.latePercentage}% dari karyawan aktif`,
                  icon: "late",
                  tone: "amber",
              },
              {
                  label: "Izin / Alfa Hari Ini",
                  value: props.employeeSummary.absentToday,
                  secondary: `${props.employeeSummary.absentPercentage}% dari karyawan aktif`,
                  icon: "absent",
                  tone: "rose",
              },
          ]
        : [],
);

const attendanceRows = computed(() =>
    props.attendanceSummary
        ? [
              {
                  key: "hadir",
                  label: "Hadir",
                  code: "H",
                  color: "#16b868",
                  track: "#e8f8ef",
                  ...props.attendanceSummary.hadir,
              },
              {
                  key: "izin",
                  label: "Izin",
                  code: "I",
                  color: "#f2b414",
                  track: "#fff7db",
                  ...props.attendanceSummary.izin,
              },
              {
                  key: "alpha",
                  label: "Alfa",
                  code: "A",
                  color: "#ef4444",
                  track: "#feecec",
                  ...props.attendanceSummary.alpha,
              },
              {
                  key: "terlambat",
                  label: "Terlambat",
                  code: null,
                  color: "#713be8",
                  track: "#f0ebff",
                  ...props.attendanceSummary.terlambat,
              },
              {
                  key: "pulangAwal",
                  label: "Pulang Awal",
                  code: null,
                  color: "#1368e8",
                  track: "#e7f0ff",
                  ...props.attendanceSummary.pulangAwal,
              },
          ]
        : [],
);

const attendanceStatusClasses = computed(
    () =>
        ({
            H: "border-emerald-200 bg-emerald-50 text-emerald-700",
            I: "border-amber-200 bg-amber-50 text-amber-700",
            A: "border-rose-200 bg-rose-50 text-rose-700",
        })[props.ownAttendance?.status] ||
        "border-slate-200 bg-slate-50 text-slate-600",
);

const chartDimensions = {
    width: 760,
    height: 250,
    left: 72,
    right: 18,
    top: 14,
    bottom: 36,
};
const chartInnerWidth =
    chartDimensions.width - chartDimensions.left - chartDimensions.right;
const chartInnerHeight =
    chartDimensions.height - chartDimensions.top - chartDimensions.bottom;

const chartMaximum = computed(() => {
    const maximum = Math.max(
        ...(props.revenueChart?.series || []).map((item) => Number(item.value)),
        0,
    );
    if (maximum === 0) return 1_000_000;

    const magnitude = 10 ** Math.floor(Math.log10(maximum));
    return Math.ceil(maximum / magnitude) * magnitude;
});

const chartPoints = computed(() => {
    const series = props.revenueChart?.series || [];
    const divisor = Math.max(series.length - 1, 1);

    return series.map((item, index) => ({
        ...item,
        x: chartDimensions.left + (index / divisor) * chartInnerWidth,
        y:
            chartDimensions.top +
            chartInnerHeight -
            (Number(item.value) / chartMaximum.value) * chartInnerHeight,
    }));
});

const chartLinePath = computed(() =>
    chartPoints.value
        .map(
            (point, index) =>
                `${index === 0 ? "M" : "L"} ${point.x.toFixed(2)} ${point.y.toFixed(2)}`,
        )
        .join(" "),
);

const chartAreaPath = computed(() => {
    if (!chartPoints.value.length) return "";
    const first = chartPoints.value[0];
    const last = chartPoints.value[chartPoints.value.length - 1];
    const baseline = chartDimensions.top + chartInnerHeight;

    return `${chartLinePath.value} L ${last.x.toFixed(2)} ${baseline} L ${first.x.toFixed(2)} ${baseline} Z`;
});

const chartTicks = computed(() =>
    Array.from({ length: 5 }, (_, index) => {
        const value = (chartMaximum.value / 4) * index;
        return {
            value,
            y:
                chartDimensions.top +
                chartInnerHeight -
                (value / chartMaximum.value) * chartInnerHeight,
        };
    }).reverse(),
);

const showDayLabel = (index, total) =>
    index === 0 || index === total - 1 || (index + 1) % 2 === 1;

const updateChartPeriod = (key, value) => {
    const query = {
        month:
            key === "month" ? Number(value) : props.revenueChart.selectedMonth,
        year: key === "year" ? Number(value) : props.revenueChart.selectedYear,
    };

    hoveredPoint.value = null;
    chartLoading.value = true;
    router.get(route("dashboard"), query, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        only: ["revenueChart"],
        onFinish: () => {
            chartLoading.value = false;
        },
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <InternalDashboardLayout
        :user="user"
        title="Dashboard"
        :can-view-attendance="permissions.canViewAttendance"
        :can-manage-employee-masters="permissions.canManageEmployeeMasters"
    >
        <div class="dashboard-page px-4 py-5 sm:px-6 lg:px-7 lg:py-6">
            <div class="mx-auto max-w-[1280px]">
                <section
                    class="dashboard-banner relative overflow-hidden px-6 py-6 sm:px-8 lg:px-9"
                >
                    <span
                        class="dashboard-banner__orb"
                        aria-hidden="true"
                    ></span>
                    <svg
                        class="dashboard-banner__icon"
                        aria-hidden="true"
                        viewBox="0 0 96 96"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="7"
                    >
                        <rect x="12" y="12" width="72" height="72" rx="8" />
                        <path
                            d="M28 68V48h13v20M47 68V28h13v40M66 68V39h12v29"
                        />
                    </svg>
                    <div class="relative z-10 max-w-[78%]">
                        <span
                            v-if="user.viewBadge"
                            class="mb-4 inline-flex rounded-full bg-white/12 px-3 py-1.5 text-[10px] font-bold tracking-[0.1em] text-white ring-1 ring-white/15"
                        >
                            {{ user.viewBadge }}
                        </span>
                        <h2
                            class="font-heading text-[24px] font-bold leading-tight text-white sm:text-[29px]"
                        >
                            Selamat datang, {{ user.name }}!
                        </h2>
                        <p
                            v-if="user.position"
                            class="mt-2 text-sm font-semibold text-white/95"
                        >
                            {{ user.position }}
                        </p>
                        <p class="mt-1.5 text-sm text-blue-50">
                            Berikut ringkasan aktivitas Kampoeng Radja hari ini.
                        </p>
                    </div>
                </section>

                <section
                    v-if="permissions.showsOrganizationWidgets"
                    class="summary-grid mt-4"
                    aria-label="Ringkasan hari ini"
                >
                    <article
                        v-for="card in summaryCards"
                        :key="card.label"
                        :class="`summary-card--${card.tone}`"
                        class="summary-card"
                    >
                        <span
                            :class="`summary-icon--${card.tone}`"
                            class="summary-card__icon"
                            aria-hidden="true"
                        >
                            <svg
                                v-if="card.icon === 'employees'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="9" cy="8" r="3" />
                                <path
                                    d="M3 19v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2M16 7a3 3 0 0 1 0 6M17 14a4 4 0 0 1 4 4v1"
                                />
                            </svg>
                            <svg
                                v-else-if="card.icon === 'present'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="9" />
                                <path d="m8 12 2.6 2.6L16.5 9" />
                            </svg>
                            <svg
                                v-else-if="card.icon === 'late'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3 2" />
                            </svg>
                            <svg
                                v-else
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="8" r="3" />
                                <path d="M5 20a7 7 0 0 1 14 0" />
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p
                                class="text-[10px] font-bold uppercase tracking-[0.08em] text-[#172554]"
                            >
                                {{ card.label }}
                            </p>
                            <strong
                                class="mt-1 block font-heading text-[27px] leading-none text-[#172554]"
                                >{{ formatNumber(card.value) }}</strong
                            >
                            <p class="mt-2 truncate text-[11px] text-slate-500">
                                {{ card.secondary }}
                            </p>
                        </div>
                    </article>
                </section>

                <article
                    v-else
                    class="mt-4 rounded-[14px] border border-slate-200 bg-white p-5 shadow-[0_3px_12px_rgba(15,23,42,0.035)]"
                >
                    <div
                        class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center"
                    >
                        <div>
                            <p
                                class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400"
                            >
                                Kehadiran Saya Hari Ini
                            </p>
                            <h3 class="mt-2 text-xl font-bold text-[#172554]">
                                {{ ownAttendance.label }}
                            </h3>
                            <p
                                v-if="
                                    ownAttendance.clockIn ||
                                    ownAttendance.clockOut
                                "
                                class="mt-1 text-xs text-slate-500"
                            >
                                Jam masuk {{ ownAttendance.clockIn || "—" }} ·
                                Jam keluar {{ ownAttendance.clockOut || "—" }}
                            </p>
                            <p
                                v-if="ownAttendance.note"
                                class="mt-1 text-sm text-slate-500"
                            >
                                {{ ownAttendance.note }}
                            </p>
                        </div>
                        <span
                            :class="attendanceStatusClasses"
                            class="inline-flex w-fit items-center rounded-full border px-4 py-2 text-sm font-bold"
                        >
                            {{ ownAttendance.status || "—" }}
                        </span>
                    </div>
                </article>

                <section
                    v-if="
                        revenueChart || attendanceSummary || closingEventSummary
                    "
                    :class="{ 'main-grid--without-chart': !revenueChart }"
                    class="main-grid mt-4"
                >
                    <article
                        v-if="revenueChart"
                        class="dashboard-card revenue-card"
                    >
                        <header
                            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
                        >
                            <div>
                                <h3
                                    class="font-heading text-lg font-bold text-[#172554]"
                                >
                                    Pendapatan Harian
                                </h3>
                                <p class="mt-1 text-xs text-slate-500">
                                    Akumulasi nilai closing event berdasarkan
                                    tanggal mulai event.
                                </p>
                            </div>
                            <div class="flex w-full gap-2 sm:w-auto">
                                <label
                                    class="period-select flex-1 sm:flex-none"
                                >
                                    <span class="sr-only">Bulan grafik</span>
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="16"
                                            rx="2"
                                        />
                                        <path d="M8 3v4m8-4v4M3 10h18" />
                                    </svg>
                                    <select
                                        :value="revenueChart.selectedMonth"
                                        :disabled="chartLoading"
                                        @change="
                                            updateChartPeriod(
                                                'month',
                                                $event.target.value,
                                            )
                                        "
                                    >
                                        <option
                                            v-for="month in monthOptions"
                                            :key="month.value"
                                            :value="month.value"
                                        >
                                            {{ month.label }}
                                        </option>
                                    </select>
                                </label>
                                <label
                                    class="period-select flex-1 sm:flex-none"
                                >
                                    <span class="sr-only">Tahun grafik</span>
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 7v5l3 2" />
                                    </svg>
                                    <select
                                        :value="revenueChart.selectedYear"
                                        :disabled="chartLoading"
                                        @change="
                                            updateChartPeriod(
                                                'year',
                                                $event.target.value,
                                            )
                                        "
                                    >
                                        <option
                                            v-for="year in revenueChart.yearOptions"
                                            :key="year"
                                            :value="year"
                                        >
                                            {{ year }}
                                        </option>
                                    </select>
                                </label>
                            </div>
                        </header>

                        <div
                            :class="{ 'opacity-55': chartLoading }"
                            class="chart-wrap mt-4 transition-opacity"
                            @mouseleave="hoveredPoint = null"
                        >
                            <svg
                                class="block h-auto w-full"
                                :viewBox="`0 0 ${chartDimensions.width} ${chartDimensions.height}`"
                                role="img"
                                :aria-label="`Grafik pendapatan harian ${revenueChart.monthLabel}`"
                            >
                                <defs>
                                    <linearGradient
                                        id="dashboard-chart-area"
                                        x1="0"
                                        y1="0"
                                        x2="0"
                                        y2="1"
                                    >
                                        <stop
                                            offset="0%"
                                            stop-color="#1570ef"
                                            stop-opacity="0.28"
                                        />
                                        <stop
                                            offset="100%"
                                            stop-color="#1570ef"
                                            stop-opacity="0.015"
                                        />
                                    </linearGradient>
                                </defs>
                                <g v-for="tick in chartTicks" :key="tick.y">
                                    <line
                                        :x1="chartDimensions.left"
                                        :x2="
                                            chartDimensions.width -
                                            chartDimensions.right
                                        "
                                        :y1="tick.y"
                                        :y2="tick.y"
                                        stroke="#e8edf4"
                                        stroke-width="1"
                                    />
                                    <text
                                        :x="chartDimensions.left - 12"
                                        :y="tick.y + 4"
                                        text-anchor="end"
                                        fill="#64748b"
                                        font-size="11"
                                    >
                                        {{ formatAxisCurrency(tick.value) }}
                                    </text>
                                </g>
                                <path
                                    v-if="chartAreaPath"
                                    :d="chartAreaPath"
                                    fill="url(#dashboard-chart-area)"
                                />
                                <path
                                    v-if="chartLinePath"
                                    :d="chartLinePath"
                                    fill="none"
                                    stroke="#1264d9"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                />
                                <g
                                    v-for="(point, index) in chartPoints"
                                    :key="point.date"
                                >
                                    <text
                                        v-if="
                                            showDayLabel(
                                                index,
                                                chartPoints.length,
                                            )
                                        "
                                        :x="point.x"
                                        :y="chartDimensions.height - 13"
                                        text-anchor="middle"
                                        fill="#64748b"
                                        font-size="10"
                                    >
                                        {{ point.day }}
                                    </text>
                                    <circle
                                        :cx="point.x"
                                        :cy="point.y"
                                        r="9"
                                        fill="transparent"
                                        tabindex="0"
                                        :aria-label="`${point.dateLabel}, ${formatCurrency(point.value)}`"
                                        @mouseenter="hoveredPoint = point"
                                        @focus="hoveredPoint = point"
                                        @blur="hoveredPoint = null"
                                    />
                                    <circle
                                        :cx="point.x"
                                        :cy="point.y"
                                        r="3.1"
                                        fill="#1264d9"
                                        stroke="#ffffff"
                                        stroke-width="1.5"
                                        pointer-events="none"
                                    />
                                </g>
                            </svg>
                            <div
                                v-if="hoveredPoint"
                                class="chart-tooltip"
                                :style="{
                                    left: `${(hoveredPoint.x / chartDimensions.width) * 100}%`,
                                    top: `${(hoveredPoint.y / chartDimensions.height) * 100}%`,
                                }"
                            >
                                <strong>{{ hoveredPoint.dateLabel }}</strong>
                                <span>{{
                                    formatCurrency(hoveredPoint.value)
                                }}</span>
                            </div>
                        </div>
                        <p
                            class="-mt-1 text-center text-xs font-medium capitalize text-slate-500"
                        >
                            {{ revenueChart.monthLabel }}
                        </p>
                        <p
                            v-if="revenueChart.summary.total === 0"
                            class="mt-3 rounded-lg bg-slate-50 px-4 py-3 text-center text-xs text-slate-500"
                        >
                            Belum ada data Closing Event pada periode ini.
                        </p>

                        <div class="chart-summary-grid mt-4">
                            <div
                                class="chart-summary-item chart-summary-item--blue"
                            >
                                <span
                                    class="chart-summary-icon"
                                    aria-hidden="true"
                                    >↗</span
                                >
                                <div>
                                    <small>Total Bulan Ini</small
                                    ><strong>{{
                                        formatCurrency(
                                            revenueChart.summary.total,
                                        )
                                    }}</strong>
                                </div>
                            </div>
                            <div
                                class="chart-summary-item chart-summary-item--green"
                            >
                                <span
                                    class="chart-summary-icon"
                                    aria-hidden="true"
                                    >↑</span
                                >
                                <div>
                                    <small>Hari Tertinggi</small>
                                    <span
                                        class="block text-[11px] text-slate-500"
                                        >{{
                                            revenueChart.summary.highestDay
                                                ?.dateLabel ||
                                            "Belum ada transaksi"
                                        }}</span
                                    >
                                    <strong>{{
                                        revenueChart.summary.highestDay
                                            ? formatCurrency(
                                                  revenueChart.summary
                                                      .highestDay.value,
                                              )
                                            : "—"
                                    }}</strong>
                                </div>
                            </div>
                            <div
                                class="chart-summary-item chart-summary-item--gray"
                            >
                                <span
                                    class="chart-summary-icon"
                                    aria-hidden="true"
                                    >—</span
                                >
                                <div>
                                    <small>Hari Tanpa Transaksi</small
                                    ><strong
                                        >{{
                                            revenueChart.summary
                                                .daysWithoutTransactions
                                        }}
                                        Hari</strong
                                    >
                                </div>
                            </div>
                        </div>
                    </article>

                    <div class="summary-side-column">
                        <article
                            v-if="attendanceSummary"
                            class="dashboard-card compact-summary-card"
                        >
                            <h3
                                class="font-heading text-base font-bold text-[#172554]"
                            >
                                Ringkasan Absensi Hari Ini
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div
                                    v-for="item in attendanceRows"
                                    :key="item.key"
                                    class="attendance-row"
                                >
                                    <div
                                        class="flex min-w-0 items-center gap-2"
                                    >
                                        <span
                                            class="h-2.5 w-2.5 shrink-0 rounded-full"
                                            :style="{
                                                backgroundColor: item.color,
                                            }"
                                        ></span>
                                        <span
                                            class="truncate text-xs font-semibold text-[#334155]"
                                            >{{ item.label
                                            }}<template v-if="item.code">
                                                ({{ item.code }})</template
                                            ></span
                                        >
                                        <span
                                            class="ml-auto whitespace-nowrap text-[11px] text-slate-500"
                                            >{{ item.count }} orang</span
                                        >
                                    </div>
                                    <div
                                        class="h-1.5 overflow-hidden rounded-full"
                                        :style="{ backgroundColor: item.track }"
                                    >
                                        <div
                                            class="h-full rounded-full"
                                            :style="{
                                                width: `${item.percentage}%`,
                                                backgroundColor: item.color,
                                            }"
                                        ></div>
                                    </div>
                                    <strong
                                        class="text-right text-xs text-[#172554]"
                                        >{{ item.percentage }}%</strong
                                    >
                                </div>
                            </div>
                        </article>

                        <article
                            v-if="closingEventSummary"
                            class="dashboard-card compact-summary-card"
                        >
                            <h3
                                class="font-heading text-base font-bold text-[#172554]"
                            >
                                Ringkasan Closing Event
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div class="event-metric-row">
                                    <span
                                        class="event-metric-icon text-blue-600"
                                        aria-hidden="true"
                                        ><svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <rect
                                                x="3"
                                                y="5"
                                                width="18"
                                                height="16"
                                                rx="2"
                                            />
                                            <path
                                                d="M8 3v4m8-4v4M3 10h18"
                                            /></svg
                                    ></span>
                                    <span>Event Bulan Ini</span
                                    ><strong
                                        >{{
                                            formatNumber(
                                                closingEventSummary.eventsThisMonth,
                                            )
                                        }}
                                        <small>Event</small></strong
                                    >
                                </div>
                                <div class="event-metric-row">
                                    <span
                                        class="event-metric-icon text-emerald-600"
                                        aria-hidden="true"
                                        ><svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <circle cx="12" cy="12" r="3" />
                                            <path
                                                d="M5.6 5.6a9 9 0 0 0 0 12.8M18.4 5.6a9 9 0 0 1 0 12.8M8.5 8.5a5 5 0 0 0 0 7M15.5 8.5a5 5 0 0 1 0 7"
                                            /></svg
                                    ></span>
                                    <span>Event Berlangsung Hari Ini</span
                                    ><strong
                                        >{{
                                            formatNumber(
                                                closingEventSummary.ongoingToday,
                                            )
                                        }}
                                        <small>Event</small></strong
                                    >
                                </div>
                                <div class="event-metric-row">
                                    <span
                                        class="event-metric-icon text-rose-500"
                                        aria-hidden="true"
                                        ><svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="m9 9 6 6m0-6-6 6" /></svg
                                    ></span>
                                    <span>Event Dibatalkan</span
                                    ><strong
                                        >{{
                                            formatNumber(
                                                closingEventSummary.cancelledThisMonth,
                                            )
                                        }}
                                        <small>Event</small></strong
                                    >
                                </div>
                                <div class="event-metric-row">
                                    <span
                                        class="event-metric-icon text-violet-600"
                                        aria-hidden="true"
                                        ><svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <circle cx="9" cy="8" r="3" />
                                            <path
                                                d="M3 19v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2M16 7a3 3 0 0 1 0 6M17 14a4 4 0 0 1 4 4v1"
                                            /></svg
                                    ></span>
                                    <span>Total Pengunjung bulanan</span
                                    ><strong
                                        >{{
                                            formatNumber(
                                                closingEventSummary.visitorsThisMonth,
                                            )
                                        }}
                                        <small>Orang</small></strong
                                    >
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </InternalDashboardLayout>
</template>

<style scoped>
.dashboard-page {
    min-height: calc(100vh - 64px);
    background: #f7f9fc;
}
.dashboard-banner {
    min-height: 164px;
    display: flex;
    align-items: center;
    border-radius: 15px;
    color: #fff;
    background: linear-gradient(105deg, #0756ca 0%, #0560e4 100%);
    box-shadow: 0 8px 24px rgba(5, 86, 202, 0.12);
}
.dashboard-banner__orb {
    position: absolute;
    right: -34px;
    top: -88px;
    width: 220px;
    height: 220px;
    border-radius: 999px;
    border: 42px solid rgba(255, 255, 255, 0.075);
}
.dashboard-banner__icon {
    position: absolute;
    right: 44px;
    top: 50%;
    width: 88px;
    height: 88px;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.16);
}
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}
.summary-card {
    display: flex;
    min-width: 0;
    min-height: 112px;
    align-items: center;
    gap: 14px;
    border: 1px solid rgba(203, 213, 225, 0.65);
    border-radius: 13px;
    padding: 16px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, 0.025);
}
.summary-card--blue {
    background: linear-gradient(135deg, #f3f7ff, #edf4ff);
}
.summary-card--green {
    background: linear-gradient(135deg, #f2fcf6, #ebfaf1);
}
.summary-card--amber {
    background: linear-gradient(135deg, #fffaf0, #fff7e4);
}
.summary-card--rose {
    background: linear-gradient(135deg, #fff5f5, #fff0f1);
}
.summary-card__icon {
    display: grid;
    width: 52px;
    height: 52px;
    flex: 0 0 auto;
    place-items: center;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.82);
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.045);
}
.summary-card__icon svg {
    width: 27px;
    height: 27px;
}
.summary-icon--blue {
    color: #1264d9;
}
.summary-icon--green {
    color: #16b868;
}
.summary-icon--amber {
    color: #f59e0b;
}
.summary-icon--rose {
    color: #ef4444;
}
.dashboard-card {
    min-width: 0;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 3px 12px rgba(15, 23, 42, 0.03);
}
.main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.85fr) minmax(290px, 0.92fr);
    align-items: start;
    gap: 16px;
}
.main-grid--without-chart {
    grid-template-columns: minmax(0, 1fr);
}
.main-grid--without-chart .summary-side-column {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}
.revenue-card {
    padding: 18px;
}
.summary-side-column {
    display: grid;
    grid-auto-rows: max-content;
    gap: 14px;
}
.compact-summary-card {
    padding: 17px;
}
.period-select {
    position: relative;
    display: flex;
    min-width: 132px;
    align-items: center;
}
.period-select svg {
    position: absolute;
    left: 12px;
    width: 17px;
    height: 17px;
    color: #475569;
    pointer-events: none;
}
.period-select select {
    width: 100%;
    height: 39px;
    appearance: none;
    border: 1px solid #d7dee8;
    border-radius: 9px;
    background: #fff;
    padding: 0 30px 0 36px;
    color: #334155;
    font-size: 12px;
    font-weight: 600;
}
.period-select::after {
    position: absolute;
    right: 12px;
    content: "⌄";
    color: #64748b;
    pointer-events: none;
}
.period-select select:focus {
    border-color: #2867e8;
    box-shadow: 0 0 0 2px rgba(40, 103, 232, 0.12);
    outline: none;
}
.chart-wrap {
    position: relative;
    min-height: 225px;
}
.chart-tooltip {
    position: absolute;
    z-index: 10;
    display: grid;
    gap: 2px;
    min-width: 145px;
    transform: translate(-50%, calc(-100% - 12px));
    border: 1px solid #dbe3ef;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.97);
    padding: 8px 10px;
    color: #172554;
    font-size: 11px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
    pointer-events: none;
}
.chart-tooltip span {
    color: #1264d9;
    font-weight: 700;
}
.chart-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}
.chart-summary-item {
    display: flex;
    min-width: 0;
    min-height: 76px;
    align-items: center;
    gap: 11px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 11px 12px;
}
.chart-summary-item small {
    display: block;
    margin-bottom: 3px;
    color: #475569;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
.chart-summary-item strong {
    display: block;
    color: #172554;
    font-size: 15px;
    line-height: 1.25;
}
.chart-summary-icon {
    display: grid;
    width: 37px;
    height: 37px;
    flex: 0 0 auto;
    place-items: center;
    border-radius: 999px;
    font-size: 18px;
    font-weight: 700;
}
.chart-summary-item--blue .chart-summary-icon {
    background: #e9f1ff;
    color: #1264d9;
}
.chart-summary-item--green .chart-summary-icon {
    background: #e8f8ef;
    color: #16b868;
}
.chart-summary-item--gray .chart-summary-icon {
    background: #f1f5f9;
    color: #475569;
}
.attendance-row {
    display: grid;
    grid-template-columns: minmax(125px, 1.2fr) minmax(72px, 0.9fr) 38px;
    align-items: center;
    gap: 10px;
}
.event-metric-row {
    display: grid;
    grid-template-columns: 26px minmax(0, 1fr) auto;
    align-items: center;
    gap: 10px;
    color: #334155;
    font-size: 12px;
}
.event-metric-row strong {
    color: #172554;
    font-size: 12px;
    white-space: nowrap;
}
.event-metric-row small {
    color: #64748b;
    font-weight: 500;
}
.event-metric-icon {
    display: grid;
    width: 25px;
    height: 25px;
    place-items: center;
}
.event-metric-icon svg {
    width: 19px;
    height: 19px;
}

@media (max-width: 1180px) {
    .main-grid {
        grid-template-columns: minmax(0, 1.55fr) minmax(270px, 0.85fr);
    }
    .chart-summary-grid {
        grid-template-columns: minmax(0, 1fr);
    }
}
@media (max-width: 1023px) {
    .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .main-grid {
        grid-template-columns: minmax(0, 1fr);
    }
    .summary-side-column,
    .main-grid--without-chart .summary-side-column {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .chart-summary-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}
@media (max-width: 700px) {
    .dashboard-banner {
        min-height: 150px;
    }
    .dashboard-banner__icon,
    .dashboard-banner__orb {
        opacity: 0.65;
    }
    .summary-side-column,
    .main-grid--without-chart .summary-side-column {
        grid-template-columns: minmax(0, 1fr);
    }
    .chart-summary-grid {
        grid-template-columns: minmax(0, 1fr);
    }
    .chart-wrap {
        min-height: 220px;
        overflow-x: auto;
    }
    .chart-wrap svg {
        min-width: 650px;
    }
}
@media (max-width: 479px) {
    .summary-grid {
        grid-template-columns: minmax(0, 1fr);
    }
    .summary-card {
        min-height: 100px;
    }
    .dashboard-banner__icon {
        display: none;
    }
    .dashboard-banner > div {
        max-width: 100%;
    }
    .revenue-card,
    .compact-summary-card {
        padding: 15px;
    }
}
</style>
