# PRD — Dashboard Home

Status: **Approved — revisi 23 Agustus 2026**

## Tujuan dan Entry Point

Dashboard Home merangkum kondisi operasional/manajerial Kampoeng Radja dari data Karyawan, Absensi, dan Closing Event.

- `/dashboard` adalah entry point canonical seluruh akun aktif.
- `/admin` redirect ke `/dashboard`.
- Nama dan jabatan greeting berasal dari relasi Karyawan authenticated user; role hanya untuk authorization.

## Ringkasan Organisasi

Ringkasan organisasi tersedia bagi Super Admin dan Admin menggunakan scope company-wide:

1. **Karyawan Aktif**: `status_keaktifan = aktif`.
2. **Hadir Hari Ini**: Absensi hari ini berstatus `H`.
3. **Terlambat Hari Ini**: `H`, `jam_masuk` terisi, dan lebih dari `08:30`.
4. **Izin / Alfa Hari Ini**: jumlah `I + A`.

Persentase memakai seluruh Karyawan aktif sebagai denominator. Denominator nol menghasilkan `0%`.

Ringkasan Absensi memakai lima metric: Hadir, Izin, Alfa, Terlambat, dan Pulang Awal. Pulang Awal adalah `H` dengan `jam_keluar` terisi dan lebih awal dari `16:30`.

User tidak menerima aggregate organisasi. User hanya menerima status Absensi dirinya sendiri hari ini.

## Pendapatan Harian

Grafik hanya tersedia bagi actor yang memiliki capability View Closing Event.

- Periode dipilih menggunakan bulan dan tahun; default periode berjalan.
- Sumber nilai: `closing_event.harga_total`.
- Pengelompokan: `closing_event.tanggal` (Tanggal Mulai), bukan `created_at`.
- Setiap tanggal kalender bulan terpilih selalu ada; hari tanpa event bernilai `0`.
- Beberapa event pada tanggal mulai yang sama dijumlahkan.
- Event multi-hari tetap dihitung sekali pada Tanggal Mulai. Harga tidak dibagi atau diduplikasi sepanjang durasi.

Ringkasan grafik:

- Total Bulan Ini
- Hari Tertinggi beserta nilai
- Jumlah Hari Tanpa Transaksi

## Ringkasan Closing Event

Panel ini hanya dikirim kepada actor yang memiliki capability View Closing Event.

- Event Aktif Bulan Ini: `status_event = aktif` dan rentang pelaksanaannya overlap bulan berjalan, satu event dihitung sekali.
- Berlangsung Hari Ini: `status_event = aktif` dan `tanggal <= today <= tanggal_selesai ?? tanggal`.
- Dibatalkan: `status_event = dibatalkan` dan rentang pelaksanaannya overlap bulan berjalan; periode memakai tanggal event, bukan `cancelled_at`.
- Total Pengunjung Aktif: jumlah pengunjung event aktif yang overlap bulan berjalan, satu event dihitung sekali.

Status `dibatalkan` hanya muncul sebagai jumlah pada panel ini dan tetap dikecualikan dari chart nilai serta total pengunjung aktif.

## Akses Cepat

Shortcut dibentuk dari capability backend dan tidak boleh mengarah ke route 403:

- Data Karyawan: actor dengan page access Karyawan.
- Kelola Absensi/Data Absensi: label mengikuti capability manage/view.
- Closing Event: hanya actor dengan View Closing Event.
- Master Data Event: hanya actor dengan Manage Master Closing Event.

## Data dan Performance

- Semua business date memakai `Asia/Jakarta`.
- Gunakan aggregate query; jangan query per hari.
- Jangan mengirim metric yang tidak boleh dilihat actor.
- Empty period menghasilkan baseline nol, total Rp0, dan pesan kosong; bukan dummy data.
- KPI dan statistik CMS tidak termasuk Dashboard Home.
