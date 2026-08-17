# LOG.md — Kampoeng Radja Project Tracker

Terakhir diperbarui: **17 Agustus 2026**
Dasar status: audit source code pada working tree aktif, bukan klaim dokumentasi
Fokus terbaru: **PRD Employee baru disinkronkan: account management dan forced first-PIN change sudah diimplementasikan; browser QA menunggu runtime tersedia**

Dokumen ini adalah pintu masuk tunggal untuk melihat posisi proyek. Untuk detail requirement tetap buka dokumen sumber yang dirujuk; `LOG.md` tidak menggantikan PRD, aturan agent, atau Figma.

## Status Legend

| Status | Arti |
| --- | --- |
| `IMPLEMENTED` | Source code dan fungsi utama tersedia, serta memiliki bukti test/build yang relevan. |
| `PARTIAL` | Sebagian fungsi/fondasi tersedia, tetapi requirement modul belum seluruhnya terpenuhi. |
| `DESIGN / REQUIREMENT ONLY` | Desain, PRD, atau requirement tersedia, tetapi implementasi belum ditemukan. |
| `NOT STARTED` | Tidak ditemukan implementasi maupun pekerjaan source code yang signifikan. |
| `NEEDS VERIFICATION` | Source code ditemukan, tetapi bukti yang tersedia belum cukup untuk memastikan fungsinya. |

## Project Snapshot

- Produk: website publik, CMS Landing Page, dan fondasi sistem internal Kampoeng Radja.
- Stack aktual: PHP 8.2+, Laravel 12, Inertia.js 2, Vue 3 Composition API, Tailwind CSS, Vite, MySQL; test menggunakan SQLite in-memory.
- Autentikasi: session Laravel dengan `username` + PIN 6 digit.
- Frontend navigation: Laravel/Inertia; tidak menggunakan Vue Router untuk route utama.
- Media upload CMS: disk Laravel `public`, dengan URL publik `/storage/...`.
- Dokumentasi utama: `AGENTS.md`, `docs/README.md`, `docs/GLOBAL/`, dan `docs/LANDING-PAGE/`.
- Kondisi repository saat audit: working tree memiliki banyak perubahan dan file untracked dari pekerjaan sebelumnya. Audit ini mencerminkan working tree tersebut, bukan hanya commit terakhir.

## Current State

1. Landing Page publik dan integrasi data CMS sudah tersedia, tetapi belum memenuhi Definition of Done Fase 1 karena CMS minimum belum lengkap, masih ada placeholder/aset sementara, dan QA visual Figma belum tercatat selesai.
2. Login username/PIN, role dasar, middleware, seeder development, dan Dashboard Home role-aware sudah tersedia. `/dashboard` menjadi entry point canonical; `/admin` mengarah ke `/dashboard`.
3. CRUD internal yang tersedia mencakup Kelola Karyawan (role-scoped), Jabatan & Departemen (Super Admin), Media & Berita, serta Event & Promotion.
4. Data Karyawan menyediakan list/search/filter/pagination, create/detail/edit, protected Foto KTP, deactivate, proses keluar, conditional hard delete, serta create/manage Akun Sistem oleh Super Admin. Admin read-only department-scope; User read-only self-scope.
5. Data Absensi Super Admin sudah memiliki migration, model, route, backend validation, UI, penyimpanan/edit hari berjalan, serta feature test. Akses/tampilan untuk Admin dan User belum tersedia.
6. KPI dan Closing Event belum memiliki implementasi; label `(Soon)` pada sidebar bukan fitur yang selesai.
7. Verifikasi 17 Agustus 2026: **79 test lulus (740 assertions)** dan **Vite production build berhasil (812 modules transformed)**.
8. Dokumentasi kini memiliki map global, area `DASHBOARD/`, baseline access control, serta dokumen authoritative Data Absensi. Permission Admin/User yang belum diputuskan tetap `TBD` dan tidak diselaraskan dengan 403 implementation.

## Implemented Pages

### Public / Landing Page

| Route | Inertia Page | Status | Catatan aktual |
| --- | --- | --- | --- |
| `/` | `Home.vue` | `PARTIAL` | Section utama tersedia; Media Berita, Promo Event, Wahana unggulan, dan Mitra membaca database dengan fallback. Masih ada konten/aset Figma sementara dan belum ada bukti visual QA final. |
| `/tentang-kami` | `TentangKami.vue` | `PARTIAL` | Halaman dan section utama tersedia, tetapi konten perusahaan masih mengandung placeholder/sementara dan status visual final belum diverifikasi. |
| `/wahana` | `Wahana.vue` | `PARTIAL` | Data database, toggle, tombol Cari/Reset, empty state, modal, dan filter multi-label AND tersedia. Model label masih berupa string dipisah koma dan CRUD admin wahana/kategori/label belum ada. |
| `/galeri-event` | `GaleriEvent.vue` | `PARTIAL` | Data event/foto, urutan Terbaru/Terlama, empty state, dan modal tersedia. CRUD admin Galeri belum ada dan QA visual final belum terbukti. |
| `/media-berita` | `Berita.vue` | `PARTIAL` | Data CMS diurutkan terbaru, item pertama menjadi featured, dan pencarian client-side bekerja. Fallback terpisah tersedia saat database kosong; halaman detail belum masuk scope final. |

Catatan: navbar publik utama tetap mengarah ke empat halaman scope PRD; `/media-berita` adalah halaman publik tambahan yang sudah memiliki route aktual.

### Authentication

| Route | Inertia Page | Status | Catatan aktual |
| --- | --- | --- | --- |
| `/login` | `Auth/Login.vue` | `IMPLEMENTED` | Login username + PIN 6 digit, rate limit, pemeriksaan hash, dan blokir akun inactive telah diuji. |
| `/ganti-pin` | `Auth/ChangePin.vue` | `IMPLEMENTED` | Akun temporary-PIN wajib mengganti PIN sebelum route internal lain; PIN baru di-hash dan flag dibersihkan. |
| `/coming-soon` | `ComingSoon.vue` | `IMPLEMENTED` | Placeholder authenticated tersedia; bukan dashboard bisnis. |
| `/dashboard` | `Internal/Dashboard/Index.vue` | `IMPLEMENTED` | Dashboard Home dinamis untuk Super Admin, Admin department-scope, dan User self-scope; widget, kalender, serta karyawan terbaru mengikuti permission final. |

### Dashboard Internal

| Route/Menu | Inertia Page | Status | Catatan aktual |
| --- | --- | --- | --- |
| `/admin` | redirect `/dashboard` | `IMPLEMENTED` | Entry lama dipertahankan untuk backward compatibility; route management `/admin/*` tetap dilindungi middleware existing. |
| `/dashboard/karyawan` | `Internal/Employee/Index.vue` | `IMPLEMENTED` | Scope Super Admin/Admin/User, payload sensitif per role, search/filter, sorting nama, dan pagination 15 baris telah diuji. |
| `/dashboard/karyawan/create` | `Internal/Employee/Create.vue` | `IMPLEMENTED` | Form penuh Super Admin, Form Request, Action create, nullable departemen, dan upload KTP private. |
| `/dashboard/karyawan/{id}` | `Internal/Employee/Show.vue` | `IMPLEMENTED` | Detail role-scoped; mutation, protected KTP, deactivate, keluar, conditional delete, serta section create/manage Akun Sistem hanya Super Admin. |
| `/dashboard/karyawan/{id}/edit` | `Internal/Employee/Edit.vue` | `IMPLEMENTED` | Prefill, unique NIK ignore-current, replace/cleanup KTP, dan user account tetap inactive saat employee direaktivasi. |
| `/dashboard/jabatan-departemen` | `Internal/Employee/Masters.vue` | `IMPLEMENTED` | CRUD dua master untuk Super Admin; delete ditolak ketika masih direferensikan karyawan. |
| `/admin/absensi` | `Internal/Absensi/Index.vue` | `PARTIAL` | Alur Super Admin hari ini dan riwayat read-only sudah diimplementasikan dan diuji; Admin/User belum memiliki view. |
| KPI | — | `NOT STARTED` | Hanya label `Soon`; tidak ada migration/model/controller/page/test KPI. |
| Closing Event | — | `NOT STARTED` | Hanya label `Soon`; tidak ada migration/model/controller/page/test Closing Event. |

### Admin CMS

| Route/Page | Status | Catatan aktual |
| --- | --- | --- |
| `/admin/media-berita` + create/edit | `IMPLEMENTED` | List, create, upload, edit/replace foto, delete dengan konfirmasi, audit user, validation, dan cleanup file telah diuji. |
| `/admin/event-promo` + create/edit | `IMPLEMENTED` | List, create, upload poster, edit/replace, link WhatsApp opsional, delete, audit user, validation, dan cleanup file telah diuji. |
| Wahana / kategori / label | `NOT STARTED` | Tabel/model dan public read tersedia; tidak ada CRUD admin. |
| Mitra | `NOT STARTED` | Tabel/model dan public read tersedia; tidak ada CRUD admin. |
| Galeri Event / foto | `NOT STARTED` | Tabel/model dan public read tersedia; tidak ada CRUD admin. |
| Site Settings | `NOT STARTED` | Tabel tersedia; belum ditemukan model, controller, route, atau UI. |
| Detail Media & Berita | `DESIGN / REQUIREMENT ONLY` | Node Figma `1:650` tersedia, tetapi PRD/TODO masih meminta keputusan scope. |

Total page component yang terhubung ke route Inertia: **21** setelah penambahan halaman Ganti PIN. `Dashboard.vue` root dan `Welcome.vue` masih ada di source tetapi tidak ditemukan terhubung ke route aktif.

## Module Status

| Module | Status | Notes |
| --- | --- | --- |
| Landing Page | `PARTIAL` | Lima route publik aktif dan fungsi utama tersedia, tetapi visual/content/asset QA serta CMS minimum Fase 1 belum lengkap. |
| Authentication | `IMPLEMENTED` | Username/PIN hash, `is_active`, status Karyawan, `must_change_pin`, forced first-PIN flow, session, logout, rate limiting, factory, seeder, dan test tersedia. |
| Admin Foundation | `PARTIAL` | Dashboard Home, middleware, role scope, dan route management tersedia; navigasi internal CMS masih belum sinkron dengan CRUD aktual. |
| Dashboard Home | `IMPLEMENTED` | Summary database, persentase H/I/A, kalender compact, latest employees, role/data scope, responsive fallback, test, dan visual QA tersedia. |
| Kelola Karyawan/Jabatan/Departemen | `IMPLEMENTED` | CRUD/read scope, private KTP, account lifecycle berbasis mapping Jabatan, master constraints, integration test, dan responsive UI tersedia. Visual browser QA UI account terbaru belum dapat dijalankan karena runtime browser lokal gagal tersambung. |
| Data Absensi | `PARTIAL` | Fitur Super Admin terimplementasi dan diuji; cakupan Admin/User serta workflow operasional lebih luas belum tersedia. |
| CMS Landing Page | `PARTIAL` | Media Berita dan Event Promo selesai secara fungsional; Wahana, Mitra, Galeri, kategori/label, dan Site Settings belum ada CRUD. |
| Detail Media & Berita | `DESIGN / REQUIREMENT ONLY` | Desain ada, keputusan scope belum final. |
| KPI | `NOT STARTED` | Tidak ada implementasi aktual. |
| Closing Event | `NOT STARTED` | Tidak ada implementasi aktual. |

## Database Snapshot

Migration aktual dapat membangun tabel berikut dari database kosong.

### CORE dan autentikasi

- `departemen`: `nama_departemen` unique.
- `jabatan`: `nama_jabatan` unique.
- `role`: `nama_role` unique.
- `karyawan`: NIK unique; FK ke `jabatan` dan nullable `departemen` menggunakan restrict delete; data personal/status sesuai enum schema final. Migration koreksi 17 Agustus menyelaraskan nullability departemen dengan PRD.
- `users`: satu akun per karyawan (`karyawan_id` unique); FK `role_id`; `username` unique; hash PIN; `is_active`; `must_change_pin` default false; timestamps.
- `pin_reset_requests`: FK pemohon dan pemroses ke `users`; workflow implementasinya belum tersedia.
- `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`: tabel infrastruktur Laravel.

### CMS Landing Page

- `media_berita`
- `event_promo`
- `wahana`
- `mitra`
- `galeri_event`
- `galeri_event_foto`
- `site_settings`

Seluruh tabel CMS memakai FK audit `created_by`/`updated_by` sesuai schema final, kecuali `site_settings` hanya memiliki `updated_by`. Child `galeri_event_foto` cascade ketika event induk dihapus; FK audit memakai restrict/null-on-delete sesuai nullable field.

### Absensi

- `absensi`: FK `karyawan_id` dengan restrict delete, `tanggal_absensi`, enum `status_kehadiran` (`H`, `I`, `A`), `keterangan` nullable, timestamps.
- Constraint unik `karyawan_id + tanggal_absensi` mencegah duplikasi kehadiran per hari.
- Index pada `tanggal_absensi` tersedia.
- Relasi model: `Karyawan hasMany Absensi`; `Absensi belongsTo Karyawan`.

Tidak ditemukan migration KPI atau Closing Event.

## Authentication & Access Control

- Login memakai `username` dan PIN tepat 6 digit; tidak ada `email/password` pada schema autentikasi final.
- `User.pin` menggunakan cast `hashed`; login memverifikasi dengan `Hash::check`. PIN tidak disimpan plaintext.
- Query login mensyaratkan `users.is_active = true` serta `karyawan.status_keaktifan = aktif`, dan dibatasi 5 percobaan per kombinasi username/IP.
- Akun baru Employee memiliki `must_change_pin = true`; middleware global membatasi akses ke Ganti PIN dan logout sampai PIN baru tersimpan.
- Middleware alias `admin` mengizinkan user aktif dengan role `admin` atau `super_admin`.
- Middleware alias `super_admin` mengizinkan hanya user aktif dengan role `super_admin`.
- Seluruh CRUD CMS berada di backend route group `auth + admin`.
- Data Absensi berada di backend route group `auth + admin + super_admin`; Form Request juga mengulang authorization Super Admin.
- Role `user` tidak dapat mengakses `/admin` atau CRUD CMS; Admin tidak dapat mengakses Absensi.
- Role dibaca melalui relasi database, bukan disimpan manual di session.
- Route read Data Karyawan memakai `auth + active` dan membentuk query/payload sesuai role; seluruh mutation Employee serta Jabatan/Departemen memakai backend middleware `super_admin` ditambah authorization Form Request.
- Create/manage Akun Sistem memakai route `super_admin`; role ditentukan backend dari Jabatan dan aktivasi akun ditolak ketika Karyawan nonaktif.
- Foto KTP disimpan pada disk private `local` dan hanya dikirim melalui endpoint stream Super Admin; Admin/User tidak menerima path maupun URL dokumen.
- Seeder development idempotent membuat role `super_admin`, `admin`, `user`, master IT/Admin Sistem, karyawan `ADMIN001`, dan akun lokal `admin` (PIN input development `123456`, tersimpan sebagai hash). Seeder hanya dipanggil otomatis pada environment local/testing.

## Data Absensi Audit

| Requirement | Kondisi source aktual |
| --- | --- |
| Migration/model/relasi | `IMPLEMENTED` |
| Hanya karyawan aktif | `IMPLEMENTED` di query dan validation backend |
| Input tunggal H/I/A | `IMPLEMENTED` sebagai radio-style button per baris |
| Keterangan opsional | `IMPLEMENTED`, nullable string maksimal 255 karakter |
| Simpan seluruh karyawan | `IMPLEMENTED`; request harus berisi setiap karyawan aktif tepat satu kali |
| Maksimal satu record/karyawan/hari | `IMPLEMENTED` melalui unique constraint dan `updateOrCreate` |
| Edit hari berjalan | `IMPLEMENTED` |
| Tanggal lewat read-only | `IMPLEMENTED` di UI dan ditolak backend untuk mutation |
| Tanggal masa depan | `IMPLEMENTED`; route index menolak tanggal future |
| Authorization Super Admin | `IMPLEMENTED` di middleware dan Form Request |
| Feedback/disable saat request | `IMPLEMENTED` melalui state Inertia form |
| Responsive/sidebar drawer | `IMPLEMENTED` pada layout internal; tabel tetap dapat scroll horizontal |
| Foto profil karyawan | `PARTIAL`; UI memakai fallback inisial karena schema hanya memiliki `foto_ktp`, bukan foto profil |
| Tampilan Admin/User | `NOT STARTED`; akses saat ini ditolak, belum ada read-only view berbasis kewenangan |

## Verification Snapshot — 17 Agustus 2026

- `php artisan route:list --except-vendor`: berhasil; **46 route aplikasi** terdaftar.
- `php artisan test`: berhasil; **79 passed, 740 assertions**.
- `npm.cmd run build`: berhasil; **812 modules transformed**.
- Migration `must_change_pin`: `migrate → rollback --step=1 → migrate` berhasil pada database development.
- `vendor/bin/pint`: berhasil dan merapikan file PHP terkait.
- `git diff --check`: bersih; hanya warning line-ending existing pada `AGENTS.md`.
- Catatan environment Windows: `npm run build` melalui `npm.ps1` ditolak PowerShell Execution Policy; penggunaan executable `npm.cmd` berhasil. Ini bukan kegagalan source aplikasi.
- Visual browser QA UI Akun Sistem/Ganti PIN belum dapat dilakukan: koneksi browser bawaan gagal menyiapkan runtime lokal. Build lulus, tetapi status visual desktop/tablet/mobile tidak diklaim verified.

## Current Focus

Fokus kode terakhir adalah **sinkronisasi PRD Employee terbaru**. Employee kini mengelola Akun Sistem secara terpisah dari form Karyawan, menentukan role dari Jabatan pada backend, dan memaksa penggantian PIN sementara sebelum akses internal. Functional regression lulus; visual browser QA UI baru masih perlu diulang ketika runtime browser tersedia.

## Recent Work Log

### 17 Agustus 2026 — Employee PRD Rebaseline & Account Management

- Menyinkronkan `PERMISSIONS.md`, `UI_SPEC.md`, documentation map, Dashboard map, Employee README, dan access-control matrix dengan PRD Employee terbaru.
- Menambahkan migration `users.must_change_pin` default false; migrate, rollback satu step, dan migrate ulang berhasil.
- Menambahkan create/manage Akun Sistem khusus Super Admin dengan one-account guard, hash PIN, status akun, dan role authoritative dari mapping Jabatan yang dinormalisasi.
- Menolak Jabatan tanpa mapping; tidak ada fallback role dan browser tidak dapat mengirim `role_id` authoritative.
- Menambahkan forced first-PIN flow: login temporary PIN diarahkan ke `/ganti-pin`, middleware memblokir route lain, lalu PIN baru di-hash dan flag dibersihkan.
- Memperkuat login/middleware agar Karyawan nonaktif tidak dapat mengakses sistem, serta mempertahankan reaktivasi Karyawan tanpa auto-enable akun.
- Verifikasi: 79 test lulus (740 assertions), 46 route, build Vite 812 modules, dan Pint file terkait berhasil.
- Visual browser QA: belum terverifikasi karena browser runtime gagal menyiapkan koneksi lokal sebelum halaman dapat dibuka.

### 17 Agustus 2026 — Employee Visual UI & Sidebar Regression Fix

- Mengubah sidebar flat menjadi parent accordion `Kelola Karyawan` dengan child Data Karyawan dan Jabatan & Departemen sesuai permission.
- Menambahkan auto-expand berdasarkan route Employee/master serta active state child yang terpisah.
- Memperbaiki root cause menu Absensi disabled: halaman Employee sebelumnya tidak meneruskan `canViewAttendance`; layout kini memakai role context server-side untuk membentuk link top-level Super Admin tanpa memindahkan route/modul Absensi.
- Memadatkan Data Karyawan menjadi header ringkas, toolbar filter terintegrasi, row/table/pagination compact, status badge stabil, serta action icon.
- Mengubah Jabatan & Departemen menjadi grid dua kolom desktop dengan header berikon, tombol Tambah jelas, tabel tiga kolom compact, action icon, dan modal yang lebih ringkas.
- Verifikasi fungsional: 70 test lulus (643 assertions), build Vite berhasil (811 modules), route list tetap 42, Pint dan diff check berhasil.
- Visual/manual navigation QA: **belum terverifikasi** karena runtime browser lokal gagal tersambung sebelum halaman dapat dibuka.

### 17 Agustus 2026 — Kelola Karyawan

- Mengimplementasikan list/search/filter/pagination Data Karyawan dengan company scope Super Admin, department scope Admin tanpa fallback, dan self-only User.
- Menjamin field sensitif tidak masuk props Admin/User; Foto KTP disimpan private dan dilayani oleh endpoint Super Admin.
- Menambahkan halaman Create, Detail, Edit, reusable form, Actions create/update/deactivate/exit/delete, serta conditional hard-delete yang menjaga User dan histori Absensi.
- Menambahkan CRUD Jabatan & Departemen dengan delete protection jika master masih digunakan.
- Menyelaraskan `departemen_id` menjadi nullable melalui migration korektif minimal dan memperbaiki eligibility Absensi berdasarkan `tanggal_masuk` tanpa menyembunyikan histori existing.
- Menormalisasi nama visual reference menjadi `references/departemen_jabatan.png`.
- Verifikasi: 70 test lulus (643 assertions), Vite build berhasil (811 modules), route list 42 route, Pint berhasil, dan diff check bersih.
- Visual QA browser: **belum terverifikasi** karena runtime browser lokal gagal tersambung.

### 16 Agustus 2026 — Dashboard Home + Visual QA Fix

- Menetapkan `/dashboard` sebagai Dashboard Home canonical dan `/admin` sebagai redirect backward-compatible tanpa mengubah proteksi `/admin/*`.
- Mengimplementasikan scope global Super Admin, scope departemen Admin tanpa fallback, dan self-only User di backend sebelum props Inertia dibentuk.
- Menambahkan summary database, formula persentase berdasarkan karyawan aktif dalam scope, kalender bulan berjalan, serta latest employees berdasarkan `tanggal_masuk DESC, id DESC`.
- Memperbaiki visual terhadap screenshot approved: banner, empat summary card, proporsi main/right column, kalender grid 7 kolom, serta posisi langsung Karyawan Terbaru di bawah kalender.
- Mengunci calendar/main/sidebar-widget layout menggunakan scoped CSS statis agar tidak bergantung pada runtime/dynamic Tailwind class.
- Visual QA browser: desktop 1280px, tablet 768px, mobile 375px; tanpa overflow atau console error.
- Verifikasi: 57 test lulus (512 assertions), Vite build berhasil (805 modules), dan `git diff --check` bersih selain warning line-ending existing.

### 15 Agustus 2026 — Documentation Audit & Rebaseline

- Mengklasifikasikan seluruh 20 dokumen existing: 12 `CURRENT`, 3 `PARTIAL`, 4 `OUTDATED`, dan 1 `REDUNDANT`/archive candidate.
- Memperbarui documentation map, konteks workstream, aturan agent, architecture cross-reference, dan backlog Landing Page berdasarkan bukti source tanpa mengubah business requirement agar cocok dengan implementation.
- Membuat baseline `docs/DASHBOARD/` untuk Attendance dan status Employee; tidak membuat folder KPI/Closing karena belum aktif.
- Membuat `GLOBAL/ACCESS_CONTROL.md` dan matrix yang memisahkan permission truth dari implementation truth.
- Mencatat delapan mismatch utama dan keputusan tim yang masih `TBD` di `docs/DOCUMENTATION_AUDIT.md`.
- Tidak mengubah application source code.

### 15 Agustus 2026 — Audit source dan sinkronisasi tracker

- Menginventarisasi route, controller, request, middleware, model, migration, seeder, Vue pages/layout/components, serta test aktual.
- Mengoreksi status Absensi dari “baru desain” menjadi implementasi Super Admin yang sudah diuji, dengan status modul keseluruhan tetap `PARTIAL`.
- Mengonfirmasi CRUD Media Berita dan Event Promo tersedia; modul CMS lain belum memiliki CRUD.
- Mengonfirmasi KPI dan Closing Event belum diimplementasikan meskipun tampil sebagai menu `(Soon)`.
- Menjalankan test dan production build dengan hasil berhasil.

### 15 Agustus 2026 — Desain Data Absensi disepakati

- Referensi desktop Data Absensi diterima.
- Requirement menetapkan H/I/A, catatan opsional, unique karyawan/tanggal, edit hanya hari berjalan, dan akses Super Admin.
- Catatan historis: entry ini mendahului implementasi yang sekarang sudah tersedia di working tree.

### 15 Agustus 2026 — `LOG.md` dibuat

- Tracker awal dibuat sebagai entry point progres proyek.
- Catatan historis awal telah digantikan oleh hasil audit source aktual pada entry terbaru di atas.

## Known Issues / Technical Debt

1. **Working tree belum bersih.** Banyak perubahan/untracked file berasal dari pekerjaan sebelum audit; commit/baseline perlu ditetapkan agar status yang dilacak dapat direproduksi.
2. **Visual QA Kelola Karyawan tertunda.** Runtime browser bawaan kembali gagal tersambung pada task UI/navigation; klik Dashboard/accordion/Absensi serta desktop/tablet/mobile perlu diperiksa ulang pada halaman aktual sebelum klaim visual/navigation final.
3. **Navigasi internal CMS tidak sinkron.** Sidebar menandai `CMS (Soon)` padahal Media Berita dan Event Promo sudah memiliki CRUD; keduanya juga belum ditautkan dari layout dashboard internal.
4. **Cakupan Absensi belum lengkap lintas role.** Hanya Super Admin yang dapat melihat halaman; kebutuhan tampilan read-only atau cakupan per departemen/jabatan untuk Admin/User belum diputuskan/diimplementasikan.
5. **Absensi mengirim seluruh karyawan aktif dalam satu request.** Validasi ini sesuai requirement saat ini tetapi perlu ditinjau jika jumlah karyawan besar.
6. **Foto profil belum memiliki sumber khusus.** `foto_ktp` tidak semestinya otomatis dipakai sebagai avatar publik/internal; UI memakai inisial.
7. **Model label Wahana belum sesuai model relasional PRD.** Label disimpan sebagai string dipisah koma pada `wahana.label`; filter AND bekerja di client, tetapi kategori/label/assignment dinamis dan CRUD-nya belum tersedia.
8. **Site Settings belum memiliki layer aplikasi.** Tabel ada, tetapi model/controller/UI belum ditemukan.
9. **PIN reset belum memiliki workflow.** Tabel ada, tetapi route/controller/UI belum ditemukan.
10. **Mapping role Jabatan bersifat tertutup.** Jabatan di luar kategori PRD (Dirut/Direktur/Admin Sistem, Manajer/Manager/Supervisor, Mitra/Operasional/OPS/Facility/FLT) ditolak sampai stakeholder menentukan mapping.
11. **Kebijakan delete Karyawan belum final di PRD.** Implementasi sementara tetap conditional hard delete dan menolak User/Absensi/dependency historis; deactivate adalah flow normal.
12. **Landing Page belum release-ready.** Masih terdapat placeholder/aset Figma sementara, keputusan Detail Media Berita terbuka, dan checklist visual/responsive belum memiliki bukti final.
13. **Aset publik belum sepenuhnya konsisten.** Media Berita dan Event Promo membentuk URL storage di backend; beberapa payload Wahana/Mitra/Galeri masih meneruskan path mentah dan perlu diverifikasi terhadap data production/storage aktual.
14. **File page bawaan tidak terpakai.** `resources/js/Pages/Dashboard.vue` dan `Welcome.vue` ada tetapi tidak terhubung ke route aktif; jangan hapus tanpa audit khusus.

## Next Step

**Satu task berikutnya yang direkomendasikan:** lakukan visual QA aktual untuk **Detail Karyawan → Akun Sistem**, modal Buat Akun, dan halaman Ganti PIN pada desktop/tablet/mobile ketika runtime browser tersedia.

Backlog berikutnya: sinkronisasi navigasi CMS dan sisa CMS minimum Landing Page. KPI serta Closing Event tetap tidak aktif.

## Handoff Quick Start

Agent berikutnya cukup memulai dengan urutan ini:

1. Baca bagian `Current State`, `Module Status`, `Known Issues`, dan `Next Step` di file ini.
2. Jalankan `git status --short` karena working tree saat audit belum bersih.
3. Buka `AGENTS.md` dan dokumen khusus modul yang akan dikerjakan.
4. Untuk Dashboard, mulai dari `docs/DASHBOARD/README.md`, access-control global, lalu dokumen modul.
5. Verifikasi source terkait; jangan menganggap label menu `(Soon)` sebagai implementasi atau 403 sebagai permission final.
6. Untuk Landing Page, ikuti urutan baca di `docs/README.md` dan protokol Figma-first.
7. Perbarui entry terbaru, hasil test/build, technical debt, mismatch, dan next step di file ini setelah pekerjaan selesai.
