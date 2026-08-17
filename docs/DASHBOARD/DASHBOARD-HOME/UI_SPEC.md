UI_SPEC.md

Dashboard Home — Spesifikasi UI & Interaksi

Status: Approved for Implementation — 16 Agustus 2026
Visual source of truth: references/dashboard-home.png
Behavior source of truth: PRD.md + PERMISSIONS.md
Tujuan dokumen: menjelaskan behavior UI, data dinamis, state, dan interaksi yang tidak dapat dipastikan hanya dari screenshot.

1. Layout Umum

Dashboard Home menggunakan layout internal Kampoeng Radja yang sama dengan halaman dashboard existing.

Struktur utama:

Sidebar kiri

Top navigation

Main content

Dashboard widgets

1.1 Sidebar

Pertahankan design language dashboard existing.

Menu yang terlihat pada screenshot:

Dashboard

Kelola Karyawan

Kelola Absensi

KPI (Soon)

Closing Event (Soon)

CMS (Soon)

Dashboard berada pada active state.

Kelola Karyawan dapat memiliki submenu sesuai implementasi modul Employee.

Status (Soon) hanya merupakan display state dan tidak boleh dianggap sebagai bukti bahwa modul telah diimplementasikan.

1.2 Top Navigation

Elemen yang terlihat:

Notification

Settings

User avatar

User identity / account menu

Nama/label account pada top navigation mengikuti data authenticated user dan tidak boleh di-hardcode sebagai Super Admin.

Role dapat digunakan pada account menu jika memang termasuk desain/permission final.

2. Hierarki Konten Dashboard Home

Urutan visual berdasarkan screenshot approved:

Welcome Banner

Summary Cards

Persentase Kehadiran Hari Ini

Kalender Kerja

Karyawan Terbaru

Layout desktop menggunakan area utama yang lebih lebar untuk ringkasan absensi, dengan widget sekunder di sisi kanan.

Jangan menambahkan widget lain yang tidak terdapat pada PRD/desain approved tanpa keputusan tim.

3. Welcome Banner

Welcome banner merupakan komponen utama pada bagian atas Dashboard Home.

3.1 Badge Role/View

Screenshot menggunakan badge:

SUPER ADMIN VIEW

Badge tersebut menggambarkan context/view dashboard.

Behavior final mengikuti PERMISSIONS.md.

Jangan menggunakan badge ini sebagai sumber authorization. Authorization tetap berasal dari backend.

3.2 Greeting

Teks pada screenshot:

Selamat datang, Admin!

merupakan dummy visual.

Pada implementasi, nama harus dinamis berdasarkan karyawan yang terhubung dengan authenticated user.

Sumber data:

Authenticated User
└── karyawan
    └── nama

Format:

Selamat datang, {nama_karyawan}!

Contoh:

Selamat datang, Budi Santoso!

Jangan gunakan username sebagai nama tampilan jika data nama karyawan tersedia.

3.3 Jabatan User

Di area greeting tampilkan jabatan user yang sedang login.

Sumber data:

Authenticated User
└── karyawan
    └── jabatan
        └── nama_jabatan

Contoh:

Manager Marketing

Role dan jabatan adalah dua data yang berbeda:

Role digunakan untuk authorization/view context.

Jabatan digunakan sebagai informasi posisi kerja user.

Jangan mengganti jabatan dengan Super Admin, Admin, atau User.

Jika jabatan kosong/null:

jangan tampilkan placeholder palsu;

layout greeting harus tetap rapi.

3.4 Subtitle

Gunakan copy:

Berikut ringkasan aktivitas Kampoeng Radja hari ini.

Copy dapat disesuaikan hanya jika ada perubahan requirement dari tim.

3.5 Tombol Review Dashboard

Screenshot menampilkan tombol:

Review Dashboard

Visual tombol mengikuti screenshot.

Destination/action tombol harus mengikuti PRD atau keputusan tim.

Jika destination belum ditentukan, agent tidak boleh membuat route/action baru berdasarkan asumsi.

4. Summary Cards

Screenshot approved menggunakan 4 summary cards.

Urutan:

Total Karyawan

Karyawan Aktif

Hadir Hari Ini

Izin / Alpha

Nilai pada screenshot seperti 142, 135, 120, dan 15 adalah contoh visual dan tidak boleh di-hardcode.

4.1 Total Karyawan

Label:

TOTAL KARYAWAN

Data berasal dari backend sesuai definisi pada PRD.md.

Agent tidak boleh menentukan sendiri apakah total menghitung seluruh record atau subset tertentu jika PRD belum mendefinisikannya.

4.2 Karyawan Aktif

Label:

KARYAWAN AKTIF

Data berasal dari status keaktifan master karyawan sesuai business rule.

4.3 Hadir Hari Ini

Label:

HADIR HARI INI

Data berasal dari Absensi hari berjalan dengan status:

H

Tanggal harus mengikuti tanggal aplikasi/server.

4.4 Izin / Alpha

Label:

IZIN / ALPHA

Widget menampilkan jumlah ketidakhadiran kategori Izin dan Alpha sesuai definisi PRD.

Jika angka merupakan gabungan:

jumlah I + jumlah A

maka perhitungan tersebut harus dilakukan di backend atau payload dashboard yang authoritative.

5. Persentase Kehadiran Hari Ini

Widget utama menampilkan:

Hadir (H)

Izin (I)

Alpha (A)

Setiap kategori memiliki:

label;

indikator warna;

persentase;

horizontal progress bar.

5.1 Warna Status

Gunakan makna visual yang konsisten dengan modul Absensi:

Hadir → hijau

Izin → kuning

Alpha → merah

Warna exact mengikuti screenshot/design system.

5.2 Data

Semua angka berasal dari data Absensi hari berjalan.

Contoh 85%, 10%, dan 5% pada screenshot adalah dummy visual.

Denominator adalah seluruh karyawan aktif dalam scope. Gunakan formula H/I/A pada `PRD.md`; denominator nol menghasilkan 0%.

5.3 Lihat Semua

Widget memiliki action:

Lihat Semua

Jika user memiliki akses ke halaman Data Absensi, action dapat mengarah ke halaman tersebut.

Jika user tidak memiliki page access:

action harus disembunyikan atau dibuat non-interaktif sesuai PERMISSIONS.md;

jangan hanya mengandalkan frontend, backend route tetap wajib dilindungi.

6. Kalender Kerja

Screenshot menampilkan widget:

Kalender Kerja

6.1 Tampilan

Pada desktop:

compact card;

menampilkan header hari/minggu;

tanggal hari berjalan memiliki highlight;

ukuran widget tidak boleh mendominasi Dashboard Home.

6.2 Behavior

Minimal menampilkan tanggal/periode berjalan sesuai requirement.

Jangan mengarang:

hari libur;

jadwal kerja;

event;

shift;

tanda khusus lain

jika tidak ada sumber data/business rule yang mendukung.

Jika requirement hanya membutuhkan kalender visual/ringkasan tanggal, jangan menambah interaksi click-to-detail.

6.3 Responsive

Pada viewport sempit, Kalender Kerja dapat turun ke bawah widget utama tanpa mengubah fungsi.

7. Karyawan Terbaru

Screenshot menampilkan card:

Karyawan Terbaru

7.1 Informasi Item

Setiap item menampilkan minimal:

avatar/fallback avatar;

nama karyawan;

jabatan.

Foto KTP tidak boleh digunakan sebagai avatar.

Jika project belum memiliki sumber foto profil khusus, gunakan fallback avatar/inisial sesuai design system.

7.2 Sorting

Definisi terbaru harus mengikuti PRD.md.

Urutkan berdasarkan `tanggal_masuk DESC, id DESC`.

7.3 Jumlah Item

Jumlah item mengikuti kapasitas desain/widget.

Jangan mengambil seluruh daftar karyawan pada Dashboard Home.

7.4 Menu Item

Screenshot memiliki icon menu tiga titik pada tiap item.

Action menu hanya boleh ditampilkan jika:

action tersebut didefinisikan pada PRD;

user memiliki permission pada PERMISSIONS.md.

Jika belum ada action final, jangan membuat fitur edit/detail/delete hanya karena icon tersedia pada screenshot.

7.5 Lihat Semua

Tombol:

Lihat Semua

mengarah ke Data Karyawan jika user memiliki page access.

Jika tidak memiliki permission, jangan tampilkan action tersebut.

8. Role-Aware Rendering

Dashboard Home menggunakan satu design system.

Komponen dapat berbeda berdasarkan:

role;

jabatan;

departemen;

data scope;

action permission.

Sumber aturan:

PERMISSIONS.md

Frontend boleh menyembunyikan widget/action untuk UX, tetapi backend wajib menerapkan authorization dan data scope.

Jangan membuat halaman Vue terpisah untuk setiap role kecuali requirement teknis kemudian menetapkan hal tersebut.

9. Data Loading

Dashboard tidak boleh menggunakan data statis production.

Data dinamis minimal meliputi:

nama authenticated user;

jabatan authenticated user;

summary karyawan;

summary Absensi;

persentase Absensi;

data Karyawan Terbaru;

data Kalender Kerja jika memiliki source data.

Loading State

Gunakan skeleton/loading state ringan yang mempertahankan struktur layout sehingga tidak terjadi layout shift besar.

Empty State

Jangan menampilkan angka atau nama dummy ketika database kosong.

Contoh:

statistik boleh menggunakan 0 jika secara bisnis memang valid;

daftar Karyawan Terbaru menggunakan empty message;

widget yang tidak memiliki data tidak boleh menampilkan sample employee.

Error State

Jika request data dashboard gagal:

tampilkan feedback yang jelas;

jangan mengganti kegagalan dengan dummy data;

pertahankan layout sejauh memungkinkan.

10. Navigation

Target requirement Dashboard Home:

setelah login, destination mengikuti requirement role pada PRD/PERMISSIONS;

sidebar Dashboard aktif ketika berada pada Dashboard Home;

setiap action hanya mengarah ke route yang benar-benar tersedia dan diizinkan.

Kondisi implementation berdasarkan LOG terbaru:

/dashboard masih redirect ke /coming-soon;

/admin masih berupa dashboard placeholder.

Implementasi Dashboard Home nantinya perlu menyelesaikan mismatch tersebut sesuai requirement yang telah disetujui, tanpa merusak flow authentication existing.

11. Responsive Behavior

Screenshot approved merupakan referensi desktop utama.

Responsive fallback:

Tablet

sidebar dapat mengikuti behavior collapse/drawer dashboard existing;

4 summary cards dapat menjadi 2 × 2;

area kehadiran dan widget kanan dapat turun menjadi layout vertikal jika ruang tidak cukup.

Mobile

content menjadi single column;

summary card tetap mudah dibaca;

progress bar tidak overflow;

Kalender Kerja dan Karyawan Terbaru turun di bawah widget utama;

action tetap dapat digunakan;

tidak mengubah permission atau business behavior.

Jangan mengklaim pixel-accurate untuk viewport yang tidak memiliki screenshot approved.

12. Visual Consistency

Dashboard Home harus konsisten dengan halaman internal existing, terutama Data Absensi.

Pertahankan:

sidebar;

top navigation;

primary blue;

white/light gray surface;

card radius;

border;

shadow minimal;

typography;

spacing;

icon language;

button language.

Screenshot Dashboard Home adalah visual source of truth khusus halaman ini.

Jika terdapat perbedaan kecil antara Dashboard Home dan Data Absensi, jangan melakukan redesign global pada task Dashboard Home kecuali diminta.

13. Security & Permission UI

Widget yang disembunyikan berdasarkan permission bukan pengganti backend authorization.

Untuk setiap clickable item:

frontend memeriksa visibility/action permission;

backend tetap memeriksa authorization;

query backend menerapkan data scope yang sesuai.

Jangan mengirim data yang tidak boleh dilihat user lalu hanya menyembunyikannya dengan Vue.

14. Visual Reference

File utama:

docs/DASHBOARD/DASHBOARD-HOME/references/dashboard-home.png

Screenshot menetapkan visual seperti:

komposisi layout;

4 summary cards;

welcome banner;

attendance percentage;

Kalender Kerja;

Karyawan Terbaru;

hierarchy dan proporsi card.

Screenshot tidak menetapkan data production.

Semua:

nama;

jabatan;

jumlah;

persentase;

employee list;

tanggal

harus berasal dari data dinamis sesuai PRD/source backend.

15. Related Documents

LOG.md

docs/README.md

docs/GLOBAL/BRAND_GUIDELINE.md

docs/GLOBAL/ACCESS_CONTROL.md

docs/GLOBAL/ACCESS_CONTROL_MATRIX.md

README.md

PRD.md

PERMISSIONS.md

references/dashboard-home.png

16. Implementation Notes

Sebelum coding:

baca LOG.md;

baca PRD.md;

baca PERMISSIONS.md;

buka screenshot reference;

audit layout/component dashboard existing;

audit route dan payload user authenticated;

jangan hardcode data screenshot.

Setelah coding:

verifikasi visual terhadap screenshot;

verifikasi permission;

verifikasi data dinamis;

jalankan test;

jalankan production build;

update LOG.md.
