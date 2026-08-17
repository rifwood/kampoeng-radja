# PERMISSIONS — Kelola Karyawan

**Status:** READY FOR IMPLEMENTATION
**Last Updated:** 2026-08-17 — disinkronkan dengan PRD akun karyawan terbaru

Legenda:
- ✅ ALLOW
- ❌ DENY
- 🔒 SCOPE/CONDITION

---

# 1. Page Access

| Halaman | Super Admin | Admin | User |
|---|---|---|---|
| Data Karyawan | ✅ | ✅ | ✅ |
| Tambah Karyawan | ✅ | ❌ | ❌ |
| Edit Karyawan | ✅ | ❌ | ❌ |
| Detail Karyawan | ✅ | ✅ | ✅ |
| Jabatan & Departemen | ✅ | ❌ | ❌ |
| Ganti PIN Pertama | 🔒 akun sendiri dengan `must_change_pin = true` | 🔒 akun sendiri dengan `must_change_pin = true` | 🔒 akun sendiri dengan `must_change_pin = true` |

---

# 2. Data Scope

| Role | Scope |
|---|---|
| Super Admin | Semua karyawan |
| Admin | Karyawan dengan `departemen_id` yang sama dengan Admin |
| User | Hanya karyawan yang terhubung ke akun dirinya |

Jika Admin tidak memiliki `departemen_id`, result set Employee = kosong. Tidak ada fallback company-wide.

---

# 3. Field Visibility

## Sensitif
Hanya Super Admin:
- NIK
- Alamat
- Status Perkawinan
- No. HP
- Foto KTP/path

| Field | Super Admin | Admin | User |
|---|---|---|---|
| NIK | ✅ | ❌ | ❌ |
| Alamat | ✅ | ❌ | ❌ |
| Status Perkawinan | ✅ | ❌ | ❌ |
| No. HP | ✅ | ❌ | ❌ |
| Foto KTP | ✅ | ❌ | ❌ |

## Umum

| Field | Super Admin | Admin | User |
|---|---|---|---|
| Nama | ✅ | ✅ | ✅ |
| Jenis Kelamin | ✅ | ✅ | ✅ |
| Agama | ✅ | ✅ | ✅ |
| Tanggal Lahir | ✅ | ✅ | ✅ |
| Tempat Lahir | ✅ | ✅ | ✅ |
| Pendidikan | ✅ | ✅ | ✅ |
| Jabatan | ✅ | ✅ | ✅ |
| Departemen | ✅ | ✅ | ✅ |
| Status Kerja | ✅ | ✅ | ✅ |
| Status Keaktifan | ✅ | ✅ | ✅ |
| Tanggal Masuk | ✅ | ✅ | ✅ |
| Tanggal Keluar | ✅ | ✅ | ✅ |

---

# 4. Actions

| Action | Super Admin | Admin | User |
|---|---|---|---|
| View list | ✅ | ✅ scoped | ✅ self-only |
| Search/filter | ✅ | ✅ scoped | ❌ |
| View detail | ✅ | ✅ scoped | ✅ self-only |
| Create | ✅ | ❌ | ❌ |
| Edit | ✅ | ❌ | ❌ |
| View Foto KTP | ✅ | ❌ | ❌ |
| Replace Foto KTP | ✅ | ❌ | ❌ |
| Change Status Kerja | ✅ | ❌ | ❌ |
| Change Status Keaktifan | ✅ | ❌ | ❌ |
| Process Karyawan Keluar | ✅ | ❌ | ❌ |
| Conditional Hard Delete | ✅ dengan rule | ❌ | ❌ |
| Create Account | ✅ | ❌ | ❌ |
| View Account Status | ✅ | ❌ | ❌ |
| Activate/Deactivate Account | ✅ dengan rule status Karyawan | ❌ | ❌ |

---

# 5. Delete / Deactivate Rule

## Hard Delete
Super Admin boleh hard delete hanya bila Karyawan belum memiliki:
- User;
- Absensi;
- histori/relasi lain yang harus dipertahankan.

Jika sudah memiliki dependency, backend menolak.

## Deactivate
Super Admin boleh menonaktifkan Karyawan.

Jika mempunyai User:
- `users.is_active = false`.

Mengaktifkan kembali Karyawan tidak otomatis mengaktifkan User.

---

# 6. Jabatan & Departemen

| Action | Super Admin | Admin | User |
|---|---|---|---|
| View | ✅ | ❌ | ❌ |
| Create | ✅ | ❌ | ❌ |
| Edit | ✅ | ❌ | ❌ |
| Delete | ✅ dengan constraint | ❌ | ❌ |

Delete ditolak jika masih direferensikan karyawan aktif maupun nonaktif.

---

# 7. User Account

Pengelolaan akun merupakan bagian modul Employee berdasarkan PRD terbaru, tetapi akun **tidak dibuat otomatis** ketika data Karyawan dibuat.

| Action | Super Admin | Admin | User |
|---|---|---|---|
| View account section | ✅ | ❌ | ❌ |
| Create account for Employee without account | ✅ | ❌ | ❌ |
| Choose arbitrary `role_id` | ❌ | ❌ | ❌ |
| Activate/deactivate account | ✅ dengan guard | ❌ | ❌ |
| View PIN/hash | ❌ | ❌ | ❌ |
| Delete account | ❌ | ❌ | ❌ |

Aturan authoritative:

1. satu Karyawan maksimal satu akun (`users.karyawan_id` UNIQUE);
2. role ditentukan backend dari Jabatan, bukan input browser;
3. Jabatan tanpa mapping role ditolak dengan pesan aman, tanpa default role;
4. PIN awal tepat 6 digit, dikonfirmasi, lalu disimpan sebagai hash;
5. akun baru memiliki `must_change_pin = true`;
6. akun Karyawan nonaktif tidak boleh diaktifkan;
7. aktivasi kembali master Karyawan tidak otomatis mengaktifkan akun;
8. ketika Karyawan dinonaktifkan atau diproses keluar, akun existing ikut dinonaktifkan.

## 7.1 Role Mapping

| Kategori Jabatan | Role |
|---|---|
| Dirut, Direktur, Admin Sistem | `super_admin` |
| Manajer/Manager, Supervisor | `admin` |
| Mitra, Operasional/OPS, Facility/FLT | `user` |

Pencocokan tidak case-sensitive dan menggunakan token/kategori Jabatan yang dinormalisasi. Jabatan lain tidak memperoleh fallback.

## 7.2 Forced First PIN Change

User terautentikasi dengan `must_change_pin = true` hanya boleh mengakses:

- halaman Ganti PIN Pertama;
- submit Ganti PIN Pertama;
- logout.

Route Dashboard, Employee, CMS, Absensi, dan route internal lain wajib diblokir backend sampai PIN baru valid tersimpan dan `must_change_pin` menjadi `false`.

---

# 8. Backend Security

Wajib:
1. authorization backend untuk mutation;
2. query scope diterapkan sebelum data dikirim;
3. field sensitif tidak diserialisasi kepada Admin/User;
4. request URL manual unauthorized → 403;
5. role dari database;
6. tidak percaya `role`, `departemen_id`, atau ownership dari client payload;
7. protected delivery untuk Foto KTP bila diterapkan.
8. forced PIN change ditegakkan middleware backend, bukan redirect frontend saja;
9. login memeriksa `users.is_active` dan `karyawan.status_keaktifan`;
10. status akun tidak boleh diaktifkan jika master Karyawan nonaktif.
