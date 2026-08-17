README.md

Dashboard Sistem Internal Kampoeng Radja

Status: Approved Documentation — Dashboard Home disetujui untuk implementasi pada 16 Agustus 2026.
Baseline progres: lihat LOG.md sebagai source of truth untuk status implementasi aktual.
Scope dokumen ini: Dashboard Home serta modul internal yang sudah aktif atau sudah memiliki requirement package.

1. Cakupan Folder Ini

Folder docs/DASHBOARD/ mendokumentasikan sistem internal Kampoeng Radja setelah login.

Modul

Folder

Status Requirement

Status Implementasi

Dashboard Home

DASHBOARD-HOME/

Approved for Implementation

PARTIAL — /admin masih dashboard placeholder dan /dashboard masih redirect ke Coming Soon

Employee (Kelola Karyawan)

EMPLOYEE/

Requirement gathering

PARTIAL — schema/model tersedia, CRUD/UI belum ada

Attendance (Kelola Absensi)

ATTENDANCE/

Aktif

PARTIAL — flow Super Admin sudah diimplementasikan dan diuji; Admin/User belum tersedia

CMS

(package dokumentasi menyusul sesuai kebutuhan)

Sebagian aktif

PARTIAL — Media Berita dan Event Promo sudah IMPLEMENTED; modul CMS lain belum lengkap

KPI

(belum dibuat)

Belum aktif

NOT STARTED

Closing Event

(belum dibuat)

Belum aktif

NOT STARTED

Untuk modul aktif, package dokumentasi menggunakan pola:

PRD.md — kebutuhan produk & bisnis

UI_SPEC.md — perilaku UI yang tidak cukup dijelaskan screenshot

PERMISSIONS.md — akses halaman, data scope, visible component, dan allowed action

references/ — screenshot/desain approved dari tim desain

2. Konteks Global (Wajib Dibaca Dulu)

Sebelum mengerjakan Dashboard, baca:

AGENTS.md

LOG.md

docs/README.md

docs/GLOBAL/PROJECT_CONTEXT.md

docs/GLOBAL/ARCHITECTURE.md

docs/GLOBAL/ACCESS_CONTROL.md

docs/GLOBAL/ACCESS_CONTROL_MATRIX.md

docs/GLOBAL/AGENT_RULES.md

LOG.md digunakan untuk mengetahui progres implementasi aktual. PRD/Permissions tetap menjadi sumber requirement bisnis dan hak akses.

3. Ringkasan Role & Autentikasi

Item

Detail

Role sistem

super_admin, admin, user

super_admin

Dirut, Direktur, Admin Sistem

admin

Manajer dan Supervisor sesuai data organisasi

user

Role pengguna umum sesuai kebutuhan operasional

Autentikasi

Username + PIN 6 digit; PIN disimpan dalam bentuk hash

Relasi akun

users.karyawan_id -> karyawan.id dan users.role_id -> role.id

Status akun

users.is_active = false tidak dapat login

4. Struktur Menu Utama Dashboard

Struktur menu target saat ini:

Dashboard Home

Kelola Karyawan

Data Karyawan

Jabatan & Departemen

Kelola Absensi / Data Absensi

KPI (Soon / belum aktif)

Closing Event (Soon / belum aktif)

CMS

Catatan implementasi aktual:

Sidebar internal belum sepenuhnya sinkron dengan fitur yang sudah tersedia.

CMS masih dapat tampil sebagai Soon pada layout tertentu meskipun CRUD Media Berita dan Event Promo sudah aktif.

Struktur menu final tetap harus mengikuti PERMISSIONS.md dan access control global.

5. Sinkronisasi Requirement dengan Implementasi Aktual

Dokumentasi Dashboard adalah sumber requirement bisnis untuk modul yang dicakup. Source code adalah sumber status implementasi aktual.

Mismatch yang sudah diketahui dari audit source:

Dashboard Home belum menjadi landing page aktual setelah login. Requirement mengarah ke Dashboard Home, tetapi /dashboard saat ini masih redirect ke /coming-soon.

Data Absensi Super Admin sudah diimplementasikan. Tanggal lampau bersifat read-only dan mutation hanya diizinkan untuk hari berjalan.

Employee belum memiliki CRUD/UI. Schema/model Karyawan, Jabatan, dan Departemen sudah ada, tetapi halaman internal belum dibuat.

CMS tidak sepenuhnya Soon. Media Berita dan Event Promo sudah memiliki CRUD aktif dan teruji; Wahana, Mitra, Galeri, dan Site Settings belum lengkap.

Permission Admin/User untuk Absensi dan Employee harus mengikuti dokumen permission terbaru. Jangan menyimpulkan dari kondisi 403 saat ini bahwa itu adalah kebijakan final bila requirement belum memutuskannya.

Jika requirement dan source tidak sama, catat mismatch di LOG.md/audit. Jangan mengubah requirement hanya agar sesuai dengan source yang belum selesai.

6. Requirement Lintas Dashboard

Authorization wajib diterapkan di backend dan frontend.

Access control mengikuti 3 tingkat: module/sidebar → page → feature/action.

Data scope dapat ditentukan berdasarkan kombinasi role, jabatan, dan departemen.

Sidebar dan komponen UI harus menyesuaikan permission.

Semua form wajib memiliki validasi server-side.

Role dibaca dari relasi database, bukan hardcode session.

Data sensitif (termasuk dokumen seperti foto KTP) tidak boleh diekspos tanpa authorization yang tepat.

Dashboard desktop mengikuti screenshot/desain approved; responsive diturunkan secara fungsional bila tidak ada frame khusus.

KPI dan Closing Event tidak boleh diimplementasikan sebelum requirement aktif.

7. Out of Scope Dokumen Dashboard Home

Detail business flow KPI

Detail business flow Closing Event

CRUD Employee secara teknis

Workflow reset PIN

Detail permission CMS per fitur

Perubahan schema Wahana kategori/label

Desain pixel-accurate sebelum screenshot approved tersedia

Detail modul lain harus berada pada package dokumentasinya sendiri.
