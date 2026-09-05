# Permissions — Dashboard Home

Status: **Approved — revisi 22 Agustus 2026**

Backend menentukan payload sebelum props Inertia dibentuk. Conditional Vue hanya untuk UX.

## Page Access

| Actor | `/dashboard` | `/admin` |
| --- | --- | --- |
| Guest | Redirect login | Redirect login |
| Akun aktif `super_admin`, `admin`, `user` | Allow | Redirect `/dashboard` |
| Akun/Karyawan nonaktif | Deny | Deny |

## Widget dan Scope

| Widget/Data | Super Admin | Admin | User |
| --- | --- | --- | --- |
| Greeting identity | Self | Self | Self |
| Badge `SUPER ADMIN VIEW` | Allow | Hidden | Hidden |
| Empat summary organisasi | Company-wide | Company-wide | Not sent |
| Ringkasan Absensi organisasi | Company-wide | Company-wide | Not sent |
| Absensi diri hari ini | Not needed | Not needed | Self only |
| Pendapatan Harian | Jika `canViewClosingEvent` | Jika `canViewClosingEvent` | Jika `canViewClosingEvent` |
| Ringkasan Closing Event | Jika `canViewClosingEvent` | Jika `canViewClosingEvent` | Jika `canViewClosingEvent` |

Capability Closing Event tetap berasal dari `ClosingEventAccess` (role + jabatan + departemen). Dashboard tidak membuat access matrix duplikat.

## Security

- Chart dan Ringkasan Closing Event tidak diserialisasi jika capability View ditolak.
- Aggregate organisasi tidak diserialisasi untuk User.
- Role/jabatan/departemen selalu dibaca dari relasi authenticated user.
