# Permissions — Dashboard Home

Status: **Approved — 16 Agustus 2026**

Authorization wajib diterapkan sebelum data dikirim sebagai Inertia props. Frontend conditional rendering hanya untuk UX.

## Page Access

| Actor | `/dashboard` | `/admin` | Requirement |
| --- | --- | --- | --- |
| Guest | Redirect login | Redirect login | Tidak boleh melihat Dashboard Home |
| Active `super_admin` | Allow | Redirect `/dashboard` | Canonical Dashboard Home |
| Active `admin` | Allow | Redirect `/dashboard` | Canonical Dashboard Home |
| Active `user` | Allow | Redirect `/dashboard` | Canonical Dashboard Home; tidak memberi admin management access |
| Inactive account | Tidak dapat login | Tidak dapat login | Existing auth rule |

`/admin/*` management route tidak berubah dan tetap memakai middleware existing.

## Widget dan Data Scope

| Widget/Data | Super Admin | Admin | User |
| --- | --- | --- | --- |
| Greeting, nama, jabatan | Self identity | Self identity | Self identity |
| View badge | `SUPER ADMIN VIEW` | `ADMIN VIEW` | `USER VIEW` |
| Total karyawan | Company-wide | Department-only | Not sent |
| Karyawan aktif | Company-wide | Department-only | Not sent |
| H/I/A hari ini | Active employees company-wide | Active employees department-only | Own attendance only |
| Persentase H/I/A | Company-wide denominator | Department denominator | Not sent |
| Kalender bulan berjalan | Allow | Allow | Allow |
| Karyawan terbaru | Company-wide | Department-only | Not sent |

Jika Admin tidak mempunyai `departemen_id`, summary tetap terbatas pada empty scope: count `0`, percentage `0`, dan latest employees kosong. Jangan fallback company-wide.

## Actions

| Action | Super Admin | Admin | User |
| --- | --- | --- | --- |
| Lihat Semua Absensi | Allow; menuju `admin.absensi.index` | Hidden | Hidden |
| Lihat Semua Karyawan | Hidden sampai route/permission Employee tersedia | Hidden | Hidden |
| Employee item action | Hidden | Hidden | Hidden |
| KPI / Closing shortcut | Deny/Soon | Deny/Soon | Deny/Soon |

## Security Rules

- Role dibaca melalui relasi `users.role_id`.
- Identitas dan departemen dibaca melalui relasi `users.karyawan_id`.
- Scope query Admin wajib menggunakan `karyawan.departemen_id` authenticated Admin.
- User tidak boleh menerima aggregate atau employee list organisasi/departemen.
- `foto_ktp` tidak boleh dipilih/dikirim.
- Management route mempertahankan authorization backend existing.
