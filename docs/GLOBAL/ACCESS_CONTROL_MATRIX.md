# Access Control Matrix — Kampoeng Radja

Terakhir direbaseline: **19 Agustus 2026**

Matrix ini memisahkan keputusan yang sudah ada dari implementation truth. `TBD` tidak boleh diterjemahkan menjadi allow atau deny permanen.

| Module/Page | Role | Jabatan | Departemen | Data Scope | Actions | Requirement Status | Implementation Saat Ini |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Public Landing Page | Guest | N/A | N/A | Konten publik | View | Confirmed | Allowed tanpa login. |
| Login | Guest | N/A | N/A | Akun sendiri | Login | Confirmed | Username + PIN 6 digit; akun harus aktif. |
| Admin foundation `/admin` | `super_admin` | TBD | TBD | Identitas akun sendiri | View placeholder | Confirmed untuk role | Allowed. |
| Admin foundation `/admin` | `admin` | TBD | TBD | Identitas akun sendiri | View placeholder | Confirmed untuk role | Allowed. |
| Admin foundation `/admin` | `user` | TBD | TBD | TBD | TBD | User tidak otomatis menerima admin access | Denied (403). |
| CMS Media Berita | `super_admin`, `admin` | TBD | TBD | Seluruh record CMS | View/create/update/delete | Confirmed untuk Fase 1 | Allowed melalui `auth + admin`. |
| CMS Media Berita | `user` | TBD | TBD | None saat ini | None saat ini | Tidak otomatis mendapat CMS access | Denied. |
| CMS Event Promo | `super_admin`, `admin` | TBD | TBD | Seluruh record CMS | View/create/update/delete | Confirmed untuk Fase 1 | Allowed melalui `auth + admin`. |
| CMS Event Promo | `user` | TBD | TBD | None saat ini | None saat ini | Tidak otomatis mendapat CMS access | Denied. |
| Data Absensi | `super_admin` | Semua | Semua | Company-wide | View; create/update hanya hari berjalan; export Excel bulanan multi-sheet | Confirmed di Attendance PRD/PERMISSIONS | View melalui `auth + active`; mutation/export dijaga capability Super Admin. |
| Data Absensi | `admin` | Semua | Semua | Company-wide | Read-only | Confirmed di Attendance PRD/PERMISSIONS | View allowed; mutation/export 403. |
| Data Absensi | `user` | Semua | Semua | Company-wide | Read-only | Confirmed di Attendance PRD/PERMISSIONS | View allowed; mutation/export 403. |
| Data Karyawan | `super_admin` | Semua | Semua | Company-wide | View/create/update/deactivate/exit/conditional delete; create/manage account | Confirmed di Employee PRD/PERMISSIONS | Allowed melalui `auth + active`; mutation melalui `super_admin`; perubahan Jabatan menyinkronkan role akun secara transaksional. |
| Data Karyawan | `admin` | Semua | Semua | Company-wide | Read-only, 12 atribut umum | Confirmed di Employee PRD/PERMISSIONS | Allowed company-wide; mutation dan field sensitif denied. |
| Data Karyawan | `user` | Semua | Semua | Company-wide | Read-only, 12 atribut umum | Confirmed di Employee PRD/PERMISSIONS | Allowed company-wide; mutation dan field sensitif denied. |
| Master Organisasi (Jabatan, Departemen, Penempatan) | `super_admin` | Semua | Semua | Semua master | View/create/update/delete dengan reference guard | Confirmed | Allowed melalui `auth + active + super_admin`. |
| Master Organisasi (Jabatan, Departemen, Penempatan) | `admin`, `user` | Semua | Semua | None | None | Confirmed deny | Denied (403). |
| Ganti PIN Pertama | Semua authenticated role | Semua | Semua | Akun sendiri | Update PIN sementara | Confirmed | `must_change_pin=true` membatasi akses ke change PIN + logout sampai selesai. |
| KPI | Semua role | TBD | TBD | TBD | TBD | Planned, belum aktif | Tidak diimplementasikan. |
| Closing Event | `super_admin` | Semua | Semua | Company-wide | View/create/update/delete/master | Confirmed di Closing Event PRD/PERMISSIONS | Implemented dengan capability backend. |
| Closing Event | `admin` | Manajer/Manager | Semua | Company-wide | View/create/detail/update | Confirmed | Implemented. |
| Closing Event | `admin` | Supervisor | Marcom/Marketing | Company-wide | View/create/detail/update | Confirmed | Implemented. |
| Closing Event | `user`/kelompok lain | Selain rule lebih tinggi | Marketing | Company-wide | View/create/detail | Confirmed | Implemented; update/delete/master denied. |
| Closing Event | Semua role | Selain kondisi di atas | None | None | None | Confirmed deny | Backend 403 dan menu disembunyikan. |

## Cara Memperbarui

1. Keputusan stakeholder masuk ke kolom requirement terlebih dahulu.
2. Implementation diperiksa terpisah melalui route, middleware, policy/Form Request, controller, dan test.
3. Jika berbeda, jangan mengubah requirement; catat mismatch.
4. Jabatan/departemen tetap `TBD` sampai aturan scope data disetujui.
