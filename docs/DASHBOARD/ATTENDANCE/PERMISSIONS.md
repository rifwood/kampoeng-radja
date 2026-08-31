# PERMISSIONS — Absensi

**Status:** FINAL
**Last Updated:** 2026-08-20

## Capability

```text
canViewAttendance
canManageAttendance
canExportAttendance
```

## Matrix

| Fitur | Super Admin | Admin | User |
|---|:---:|:---:|:---:|
| View company-wide | ALLOW | ALLOW | ALLOW |
| Input H/I/A | ALLOW | DENY | DENY |
| Input Jam Masuk/Keluar | ALLOW | DENY | DENY |
| Input Keterangan | ALLOW | DENY | DENY |
| Simpan | ALLOW | DENY | DENY |
| Input/edit hari berjalan | ALLOW | DENY | DENY |
| Input/edit satu hari kalender sebelumnya | ALLOW | DENY | DENY |
| Input/edit sebelum kemarin | DENY | DENY | DENY |
| Delete | DENY | DENY | DENY |
| Export Excel bulanan multi-sheet | ALLOW | DENY | DENY |
| Atur Hari Event dan jadwal Panitia | ALLOW | DENY | DENY |

## Navigation

Super Admin: `Kelola Absensi`.

Admin/User: `Data Absensi`.

Admin/User menu Absensi harus tampil.

## Scope Data

Semua role melihat daftar absensi company-wide.

## Mutation

Hanya Super Admin. Backend wajib menolak Admin/User pada store/update dan
mutation lain. Super Admin hanya dapat mutation pada hari berjalan dan satu hari
kalender sebelumnya. Future dan tanggal sebelum kemarin ditolak. Window dihitung
dengan tanggal kalender `Asia/Jakarta`, bukan rolling 24 jam.

## Jam

- manual oleh Super Admin;
- nullable;
- Admin/User read-only;
- I/A membuat `jam_masuk` dan `jam_keluar` menjadi NULL;
- jika keduanya ada, `jam_keluar >= jam_masuk`.
- jam masuk maksimal `12:00`;
- Ketepatan Hari Normal: sampai `08:30` Tepat Waktu, `08:31`–`08:40` Dalam Toleransi, setelah `08:40` Terlambat;
- Ketepatan Panitia Hari Event memakai target jadwal masing-masing dengan toleransi tetap 5 menit;
- Admin/User tidak dapat membuat, mengubah, atau menonaktifkan konfigurasi Hari Event;
- Pulang Awal dihitung hanya untuk H dengan jam keluar `< 16:30`;
- jam NULL tidak menghasilkan status turunan.

## Excel Bulanan

Hanya Super Admin. Admin/User yang membuka URL export secara langsung menerima
HTTP 403.

Filter memakai `tanggal_absensi`, bukan `created_at` atau `updated_at`. Satu
bulan menghasilkan satu workbook `.xlsx`. Sheet pertama adalah `Rekap Bulanan`,
kemudian setiap tanggal kalender pada bulan tersebut menghasilkan satu sheet,
termasuk tanggal tanpa record. Isi export bersifat company-wide.

## Test Minimum

- Super Admin View/Manage/Export.
- Admin View, mutation/export forbidden.
- User View, mutation/export forbidden.
- I/A clears times.
- Hadir supports nullable `jam_keluar`.
- invalid time order rejected.
- yesterday create/update allowed.
- mutation before yesterday rejected.
- future mutation rejected.
