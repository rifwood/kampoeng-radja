# Permissions — Data Absensi

Terakhir direbaseline: **15 Agustus 2026**

| Role | Page Access | Data Scope | View | Create/Update Hari Ini | Tanggal Lampau | Requirement | Implementation |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `super_admin` | Allowed | Seluruh karyawan aktif | Allowed | Allowed | Read-only | Confirmed untuk tahap ini | Implemented dan diuji |
| `admin` | `TBD` | `TBD — menunggu keputusan tim` | `TBD` | `TBD` | `TBD` | Belum diputuskan | Denied (403) |
| `user` | `TBD` | `TBD — menunggu keputusan tim` | `TBD` | `TBD` | `TBD` | Belum diputuskan | Denied (403) |
| Guest | Denied | None | Denied | Denied | Denied | Confirmed | Redirect login |

## Special Conditions

- Akun wajib aktif.
- Mutation hanya hari berjalan.
- Authorization backend wajib tetap ada.
- Role/jabatan/departemen tidak boleh dipakai sebagai scope tambahan sebelum matrix disetujui.

## Implementation Evidence

- Route: `routes/web.php`
- Middleware: `app/Http/Middleware/EnsureUserIsSuperAdmin.php`
- Form Request: `app/Http/Requests/Admin/SaveAbsensiRequest.php`
- Tests: `tests/Feature/Admin/AbsensiTest.php`

## Team Decisions Required

1. Apakah Admin boleh melihat Absensi.
2. Jika boleh, apakah scope seluruh data, departemen sendiri, bawahan, atau lainnya.
3. Apakah Admin boleh create/update atau read-only.
4. Apakah User boleh melihat data diri sendiri.
5. Apakah jabatan tertentu mengubah hak akses.

Sampai keputusan dibuat, 403 untuk Admin/User dipertahankan sebagai safe implementation, tetapi bukan permission truth permanen.
