# UI Spec — Dashboard Home

Status: **Approved — revisi 23 Agustus 2026**
Visual source: `references/dashboard-home.png`

## Komposisi Desktop

1. Welcome Banner full width, biru, ringkas, dengan dekorasi grafik yang subtle.
2. Empat summary card dalam satu baris.
3. Main grid: grafik Pendapatan Harian sebagai area utama; Ringkasan Absensi dan Ringkasan Closing Event bertumpuk di kanan.
4. Akses Cepat full width di bawah main grid.

Kalender Kerja dan Karyawan Terbaru dihapus. Tombol tanpa fungsi seperti `Review Dashboard` tidak dirender.

## Welcome Banner

- Greeting: `Selamat datang, {nama}!`.
- Jabatan berasal dari Karyawan.
- Subtitle: `Berikut ringkasan aktivitas Kampoeng Radja hari ini.`
- Badge `SUPER ADMIN VIEW` hanya untuk Super Admin.

## Summary Card

Urutan: Karyawan Aktif, Hadir Hari Ini, Terlambat Hari Ini, Izin/Alfa Hari Ini. Setiap card memiliki icon, angka utama, dan secondary text/persentase. Angka screenshot tidak boleh di-hardcode.

## Grafik Pendapatan Harian

- Line chart responsive dengan area tint tipis.
- Selector Bulan/Tahun melakukan partial Inertia reload tanpa page jump.
- X-axis memuat semua tanggal bulan; label dapat dipadatkan agar terbaca.
- Y-axis memakai format Rupiah compact; hover/focus point menampilkan tanggal Indonesia dan nilai Rupiah penuh.
- Empty period mempertahankan baseline nol serta pesan `Belum ada data Closing Event pada periode ini.`
- Footer grafik menampilkan tiga summary card compact.

## Panel Ringkasan

Ringkasan Absensi menampilkan lima row, dot warna, jumlah orang, progress bar, dan persentase. Ringkasan Closing Event menampilkan empat metric compact: Event Aktif Bulan Ini, Berlangsung Hari Ini, Dibatalkan, dan Total Pengunjung Aktif.

## Akses Cepat

Setiap shortcut berbentuk compact card-link dengan icon, label, dan chevron. Shortcut hanya dirender jika capability target tersedia.

## Responsive

- Desktop: summary 4 kolom; grafik kiri dan panel kanan.
- Tablet: summary 2×2; grafik full width; panel ringkasan 2 kolom di bawah.
- Mobile: seluruh card stack satu kolom; selector wrap; grafik tetap dapat dibaca tanpa page-level horizontal overflow; panel dan shortcut stack.

## State dan Accessibility

- Angka nol tampil sebagai `0`, bukan dummy atau `NaN`.
- Select mempunyai accessible label.
- Titik grafik dapat difokuskan keyboard dan mempunyai aria-label tanggal/nilai.
- Link mempunyai focus ring dan seluruh halaman tidak boleh mengalami page-level horizontal overflow.
