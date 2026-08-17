# Documentation Audit & Rebaseline

Tanggal audit: **15 Agustus 2026**
Scope: seluruh dokumentasi existing, struktur dokumentasi, dan pembanding source pada working tree aktif
Prinsip: source menentukan status implementasi; PRD/keputusan stakeholder menentukan requirement

## Ringkasan Klasifikasi Dokumen Existing

Klasifikasi berikut menggambarkan kondisi dokumen **sebelum tindakan rebaseline pada task ini**.

| File | Status | Masalah / catatan | Source pembanding | Tindakan |
| --- | --- | --- | --- | --- |
| `docs/README.md` | `OUTDATED` | Hanya memetakan Landing Page; Dashboard aktif tidak memiliki jalur baca. | Working tree, `LOG.md` | `UPDATE REQUIRED` — diperbarui menjadi documentation map. |
| `docs/GLOBAL/PROJECT_CONTEXT.md` | `OUTDATED` | Fokus aktif ditulis eksklusif Landing Page; fondasi Dashboard/Absensi belum tercermin. | Route, migration, page Absensi | `UPDATE REQUIRED`. |
| `docs/GLOBAL/TECH_STACK.md` | `CURRENT` | Stack dan policy masih sesuai; contoh nama entity bersifat ilustratif. | `composer.json`, `package.json`, source | `KEEP`. |
| `docs/GLOBAL/BRAND_GUIDELINE.md` | `CURRENT` | Masih valid sebagai fallback global dan tidak mengklaim sebagai visual truth Dashboard. | Figma/reference policy | `KEEP`. |
| `docs/GLOBAL/ARCHITECTURE.md` | `PARTIAL` | Arsitektur inti sesuai, tetapi belum menunjuk access-control docs dan schema konseptual dapat disalahartikan sebagai schema aktual. | Migration, middleware | `UPDATE CANDIDATE` — diberi penegasan/cross-reference. |
| `docs/GLOBAL/AGENT_RULES.md` | `OUTDATED` | Urutan baca dan pembatasan fase hanya mengakomodasi Landing Page. | Dashboard aktif, task stakeholder | `UPDATE REQUIRED`. |
| `docs/LANDING-PAGE/PRD.md` | `CURRENT` | Requirement bisnis Fase 1 masih authoritative; gap implementasi tidak mengubah PRD. | Source Landing Page/CMS | `KEEP`. |
| `docs/LANDING-PAGE/FIGMA.md` | `CURRENT` | Mapping frame approved dan status Detail Media masih relevan. | Figma decision log | `KEEP`. |
| `docs/LANDING-PAGE/FIGMA_ACCURACY.md` | `CURRENT` | Protokol QA visual masih relevan. | Delivery policy | `KEEP`. |
| `docs/LANDING-PAGE/UI_SPEC.md` | `CURRENT` | Behavior publik masih relevan; keputusan terbuka ditandai. | Vue pages, PRD | `KEEP`. |
| `docs/LANDING-PAGE/USER_FLOW.md` | `CURRENT` | Flow Fase 1 dan open decision tetap valid; implementation mismatch dicatat terpisah. | Routes/auth/CMS | `KEEP`. |
| `docs/LANDING-PAGE/COMPONENTS.md` | `CURRENT` | Strategi komponen masih sesuai dan tidak memaksa abstraction. | Vue components/layouts | `KEEP`. |
| `docs/LANDING-PAGE/RESPONSIVE.md` | `CURRENT` | Kontrak responsive fallback masih berlaku. | Keputusan tanpa frame mobile | `KEEP`. |
| `docs/LANDING-PAGE/CONTENT.md` | `PARTIAL` | Policy benar, tetapi inventaris status konten belum merefleksikan seluruh data CMS/aset sementara aktual. | Pages, seeder/data source, assets | `UPDATE CANDIDATE`. |
| `docs/LANDING-PAGE/ASSETS.md` | `PARTIAL` | Policy benar, tetapi inventaris hanya mencakup sebagian kecil aset yang sekarang ada. | `public/assets/` | `UPDATE CANDIDATE`. |
| `docs/LANDING-PAGE/REFERENCE.md` | `CURRENT` | Decision log dan batas penggunaan reference masih relevan. | PRD/Figma docs | `KEEP`. |
| `docs/LANDING-PAGE/TODO.md` | `OUTDATED` | Sejumlah checkbox audit/fitur tidak mengikuti source; prioritas lama masih berorientasi UI ulang. | Route, CRUD, Absensi-independent source audit | `UPDATE REQUIRED`. |
| `docs/LANDING-PAGE/AGENT_HANDOFF.md` | `REDUNDANT` | Banyak isi berulang dengan `AGENTS.md`, `docs/README.md`, dan dokumen Figma. Masih berguna sebagai handoff khusus Fase 1. | Documentation map | `MERGE / ARCHIVE CANDIDATE`; tidak dihapus. |
| `docs/LANDING-PAGE/CONTENT_INTAKE_TEMPLATE.md` | `CURRENT` | Template pengumpulan konten masih relevan. | Content policy | `KEEP`. |
| `docs/LANDING-PAGE/DELIVERY_CHECKLIST.md` | `CURRENT` | Checklist release masih valid; belum terisi bukan berarti outdated. | QA policy | `KEEP`. |

### Jumlah Dokumen Existing

| Status | Jumlah |
| --- | ---: |
| `CURRENT` | 12 |
| `PARTIAL` | 3 |
| `OUTDATED` | 4 |
| `PLANNED` | 0 |
| `REDUNDANT` | 1 |
| `UNCERTAIN` | 0 |

Dokumen KPI/Closing tidak dihitung sebagai `PLANNED` karena file modulnya memang belum ada. Requirement global masa depan tetap dipertahankan sebagai konteks, bukan sebagai dokumen modul aktif.

## Missing Documentation — Kondisi Awal Audit

| Module | Missing document | Mengapa diperlukan | Priority | Hasil task |
| --- | --- | --- | --- | --- |
| Global | `ACCESS_CONTROL.md` | Memisahkan konsep permission dari implementasi middleware saat ini. | P0 | Dibuat. |
| Global | `ACCESS_CONTROL_MATRIX.md` | Menampilkan access aktual dan `TBD` tanpa menebak role/jabatan/departemen. | P0 | Dibuat. |
| Dashboard | `DASHBOARD/README.md` | Entry point untuk sistem internal yang sudah memiliki route/page. | P0 | Dibuat. |
| Attendance | `PRD.md` | Menyimpan business rule Absensi yang sebelumnya hanya ada dalam task/chat. | P0 | Dibuat. |
| Attendance | `UI_SPEC.md` | Menyimpan behavior edit/save/read-only yang tidak cukup dijelaskan screenshot. | P0 | Dibuat. |
| Attendance | `PERMISSIONS.md` | Menegaskan Super Admin dan menandai Admin/User sebagai TBD. | P0 | Dibuat. |
| Attendance | `references/README.md` | Menentukan tempat screenshot handoff dan status reference yang belum dipersist. | P1 | Dibuat. |
| Employee | `DASHBOARD/EMPLOYEE/README.md` | Menyediakan status/gap untuk modul dependency Absensi tanpa mengarang PRD. | P0 | Dibuat. |

Dokumen berikut sengaja **belum dibuat**: PRD/UI spec/permissions Employee yang lengkap, serta folder KPI dan Closing Event. Requirement dan keputusan tim belum cukup.

# Documentation ↔ Implementation Mismatch

## MISMATCH-001

**Area:** Fokus proyek dan struktur dokumentasi

**Requirement/dokumentasi:** Dokumentasi global lama menyatakan fokus eksklusif Fase 1 Landing Page.

**Implementation:** Working tree sudah memiliki Dashboard Admin, autentikasi internal, dan Data Absensi Super Admin.

**Evidence:**

- `routes/web.php`
- `app/Http/Controllers/Admin/AbsensiController.php`
- `resources/js/Pages/Internal/Absensi/Index.vue`
- `tests/Feature/Admin/AbsensiTest.php`

**Impact:** Agent dapat mengabaikan modul aktif atau salah menganggap source Absensi sebagai pekerjaan yang tidak sah.

**Recommended Action:** Dokumentasi map dan aturan agent mengakui dua workstream aktif tanpa mengaktifkan KPI/Closing.

**Decision Required:** NO — task rebaseline ini secara eksplisit mengaktifkan dokumentasi Dashboard.

## MISMATCH-002

**Area:** Wahana category/label

**Requirement:** PRD mengharuskan kategori/label dinamis, multiple label assignment, dan pengelolaan admin.

**Implementation:** Tabel final hanya memiliki `wahana.label` berupa string nullable; controller memecah nilai comma-separated dan frontend menjalankan AND filter. Tidak ada tabel kategori/label/pivot atau CRUD admin.

**Evidence:**

- `docs/LANDING-PAGE/PRD.md` bagian 7–12 dan acceptance criteria
- `database/migrations/0001_01_01_000004_create_landing_page_cms_tables.php`
- `app/Http/Controllers/PublicPageController.php`
- `resources/js/Pages/Wahana.vue`

**Impact:** Filter guest bekerja, tetapi model data dan admin management belum memenuhi requirement bisnis.

**Recommended Action:** Konfirmasi apakah schema final boleh dikembangkan; jangan mengubah PRD atau migration tanpa keputusan.

**Decision Required:** YES.

## MISMATCH-003

**Area:** CMS minimum Fase 1

**Requirement:** PRD mencakup pengelolaan minimum Wahana, kategori/label, Galeri Event, Media Berita, Promo, dan Mitra sesuai scope aktif.

**Implementation:** CRUD admin hanya tersedia untuk Media Berita dan Event Promo. Wahana, Mitra, Galeri, serta Site Settings baru memiliki sebagian fondasi data/public read.

**Evidence:**

- `routes/web.php`
- `app/Http/Controllers/Admin/MediaBeritaController.php`
- `app/Http/Controllers/Admin/EventPromoController.php`
- `resources/js/Pages/Admin/`

**Impact:** Fase 1 belum memenuhi acceptance criteria panel admin minimum.

**Recommended Action:** Lanjutkan modul CMS yang sudah dikonfirmasi setelah prioritas Dashboard/master data diputuskan.

**Decision Required:** NO untuk gap; YES untuk urutan prioritas.

## MISMATCH-004

**Area:** Navigasi Dashboard

**Requirement/progress:** CMS Media Berita dan Event Promo sudah merupakan modul aktif.

**Implementation:** Sidebar `InternalDashboardLayout.vue` masih menampilkan `CMS (Soon)` tanpa link, sementara route CRUD CMS dapat dibuka langsung.

**Evidence:**

- `resources/js/Layouts/InternalDashboardLayout.vue`
- `routes/web.php`

**Impact:** Fitur yang tersedia sulit ditemukan dan status UI menyesatkan.

**Recommended Action:** Sinkronkan navigasi setelah information architecture Dashboard disetujui.

**Decision Required:** YES — perlu keputusan struktur menu Dashboard/CMS.

## MISMATCH-005

**Area:** Destination setelah login

**Requirement:** `LANDING-PAGE/USER_FLOW.md` menandai destination user setelah login sebagai keputusan terbuka; Admin/Super Admin dapat diarahkan ke area content sesuai akses.

**Implementation:** Route `/dashboard` selalu redirect ke `/coming-soon`; halaman `/admin` harus dibuka secara eksplisit.

**Evidence:**

- `routes/web.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**Impact:** Admin/Super Admin tidak otomatis masuk ke area kerja yang sudah tersedia.

**Recommended Action:** Tentukan destination per role sebelum mengubah redirect.

**Decision Required:** YES.

## MISMATCH-006

**Area:** Permission Absensi di luar Super Admin

**Requirement:** Flow Super Admin sudah ditentukan. Scope tampilan/akses Admin dan User disebut sebagai pengembangan berikutnya, tetapi belum memiliki matrix yang disetujui.

**Implementation:** Middleware dan Form Request menolak semua role selain `super_admin`.

**Evidence:**

- `app/Http/Middleware/EnsureUserIsSuperAdmin.php`
- `app/Http/Requests/Admin/SaveAbsensiRequest.php`
- `tests/Feature/Admin/AbsensiTest.php`

**Impact:** Implementasi Super Admin konsisten dengan scope saat ini, tetapi tidak boleh dianggap sebagai keputusan permission final untuk Admin/User.

**Recommended Action:** Pertahankan proteksi saat ini sampai matrix role/jabatan/departemen disetujui.

**Decision Required:** YES.

## MISMATCH-007

**Area:** Master data Karyawan

**Requirement/operasional:** Absensi harus mengambil karyawan aktif dan jabatan dari master data; Kelola Karyawan menjadi dependency operasional berikutnya.

**Implementation:** Schema, model, relasi, factory/seeder dasar tersedia, tetapi tidak ada route/controller/page CRUD Karyawan, Jabatan, atau Departemen.

**Evidence:**

- `database/migrations/0000_12_31_235959_create_employee_master_tables.php`
- `app/Models/Karyawan.php`
- `routes/web.php`
- `resources/js/Layouts/InternalDashboardLayout.vue`

**Impact:** Data Absensi bergantung pada data yang belum dapat dikelola melalui aplikasi.

**Recommended Action:** Jadikan Data Karyawan sebagai requirement task berikutnya setelah PRD/permissions/reference disediakan.

**Decision Required:** YES untuk permission dan workflow CRUD.

## MISMATCH-008

**Area:** Status TODO Landing Page

**Requirement:** `TODO.md` harus mencatat pekerjaan yang benar-benar tersisa.

**Implementation:** Beberapa audit route/auth/CRUD dan fungsi guest sudah tersedia serta diuji, tetapi checklist masih kosong; prioritas lama masih menyebut audit existing sebagai pekerjaan awal.

**Evidence:**

- `docs/LANDING-PAGE/TODO.md`
- `php artisan test`: 50 passed, 405 assertions pada audit source terakhir

**Impact:** Agent dapat mengulang pekerjaan atau salah membaca status.

**Recommended Action:** Tambahkan snapshot rebaseline dan sinkronkan checkbox yang memiliki bukti tanpa menandai visual QA selesai.

**Decision Required:** NO.

## Archive Candidates

| File | Alasan | Keputusan saat ini |
| --- | --- | --- |
| `docs/LANDING-PAGE/AGENT_HANDOFF.md` | Sebagian besar mengulang `AGENTS.md`, documentation map, dan protokol Figma. | Pertahankan sampai tim memutuskan merge/archive. |

Tidak ada file yang dihapus pada audit ini.

## Team Decisions Required

1. Scope baca/aksi Absensi untuk role `admin` dan `user`, termasuk batas departemen/jabatan/data scope.
2. Permission Kelola Karyawan, Jabatan, dan Departemen.
3. Destination setelah login untuk setiap role.
4. Information architecture menu Dashboard dan posisi CMS.
5. Apakah schema Wahana boleh diubah agar memenuhi category/label dinamis.
6. Apakah Detail Media & Berita (`1:650`) masuk scope Fase 1.
7. Urutan prioritas antara master data Dashboard dan sisa CMS minimum Fase 1.
8. Hosting, storage media production, dan deployment strategy tetap terbuka.

## Validation

- Tidak ada application source yang sengaja diubah pada task rebaseline.
- Working tree sudah dirty sebelum audit; daftar perubahan dokumentasi task harus dibedakan dari perubahan aplikasi existing.
- Status implementasi rinci tetap berada di `LOG.md`.
