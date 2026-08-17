# EMPLOYEE — Kelola Karyawan

**Project:** Kampoeng Radja
**Area:** Dashboard Internal
**Module:** Kelola Karyawan
**Requirement Status:** READY FOR IMPLEMENTATION
**Implementation Status:** CRUD Employee/master, private KTP, account management, dan forced first-PIN change diimplementasikan
**Last Updated:** 2026-08-17

## Tujuan Folder

Folder ini adalah requirement package untuk membangun modul **Kelola Karyawan**.

Cakupan:

1. Data Karyawan
2. Tambah/Edit/Detail Karyawan
3. Jabatan & Departemen
4. Akun Sistem Karyawan
5. Ganti PIN Pertama

## Urutan Baca Agent

1. `README.md`
2. `PRD.md`
3. `PERMISSIONS.md`
4. `UI_SPEC.md`
5. `references/data_karyawan.png`
6. `references/departemen_jabatan.png`
7. `LOG.md`
8. `docs/GLOBAL/ACCESS_CONTROL.md`
9. source code terkait sebelum implementasi

## Source of Truth

- Business requirement → `PRD.md`
- Access control → `PERMISSIONS.md`
- UI behavior → `UI_SPEC.md`
- Visual list/master → screenshot di `references/`
- Desain Tambah/Edit/Detail yang belum tersedia → agent menurunkan dari design system dua screenshot reference yang sudah approved
- Progress implementation → root `LOG.md`

## Visual References

Tersedia:

- `references/data_karyawan.png`
- `references/departemen_jabatan.png`

Belum tersedia screenshot terpisah untuk:

- Tambah Karyawan
- Edit Karyawan
- Detail Karyawan

Untuk tiga state tersebut, agent boleh membuat desain berdasarkan visual language reference yang tersedia dengan syarat:

- tidak membuat design system baru;
- sidebar/top navigation harus konsisten;
- typography, radius, spacing, button, border, dan primary blue mengikuti reference;
- form dibuat desktop-first dan responsive fallback;
- tidak mengarang fitur bisnis di luar PRD.

## Kondisi Source Saat Ini

Sudah tersedia:

- tabel/model `karyawan`
- tabel/model `jabatan`
- tabel/model `departemen`
- relasi dasar
- authentication + role
- Dashboard Home
- Absensi yang menggunakan karyawan aktif
- route/controller/page CRUD Data Karyawan
- route/controller/page Jabatan & Departemen
- private/protected Foto KTP
- create/manage status Akun Sistem oleh Super Admin
- mapping Jabatan ke Role pada backend
- `must_change_pin` dan forced first-login PIN change

Keputusan yang belum final:

- soft delete vs hard delete Karyawan; implementation mempertahankan conditional hard delete yang menolak dependency historis;
- Jabatan di luar mapping PRD tidak dapat dibuatkan akun sampai stakeholder menentukan role;
- reset PIN lanjutan tetap mengikuti workflow `pin_reset_requests` dan bukan bagian delta ini.

## Build Guardrails

Jangan:

- membuat permission baru di luar `PERMISSIONS.md`;
- hardcode data screenshot;
- memakai Foto KTP sebagai avatar;
- membuat delete universal lintas model;
- mengubah modul KPI/Closing Event;
- merombak Dashboard Home/Absensi;
- membuat Vue Router/API SPA baru.

Setelah implementasi:

- jalankan test;
- jalankan production build;
- visual QA terhadap references;
- update `LOG.md`.
