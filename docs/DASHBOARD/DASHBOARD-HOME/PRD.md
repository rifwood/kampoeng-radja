# PRD — Dashboard Home

Status: **Approved for Implementation — 16 Agustus 2026**
Progress source: `LOG.md`

## Tujuan

Dashboard Home adalah halaman pertama setelah login bagi seluruh akun aktif. Satu halaman digunakan bersama, tetapi data dan widget mengikuti role/data scope.

## Entry Point

- Login berhasil mengarah ke `/dashboard`.
- `/dashboard` adalah canonical Dashboard Home untuk `super_admin`, `admin`, dan `user` yang aktif.
- `/admin` mengarah ke `/dashboard` untuk backward compatibility.
- Route management `/admin/*` tetap dipertahankan dengan middleware existing.

## Identitas Pengguna

- Nama berasal dari `user.karyawan.nama`.
- Jabatan berasal dari `user.karyawan.jabatan.nama_jabatan`.
- Role berasal dari `user.role.nama_role` dan hanya digunakan untuk authorization/view context.
- Role tidak boleh digunakan sebagai pengganti jabatan.

## Scope per Role

### Super Admin

- Scope statistik: seluruh karyawan.
- Denominator kehadiran: seluruh karyawan aktif.
- Dapat melihat empat summary card, persentase H/I/A, kalender, dan karyawan terbaru.
- Dapat melihat shortcut Absensi karena route tersebut diizinkan.

### Admin

- Scope statistik: karyawan dengan `departemen_id` sama dengan karyawan authenticated Admin.
- Denominator kehadiran: karyawan aktif pada departemen tersebut.
- Tidak boleh fallback ke scope seluruh perusahaan bila Admin tidak memiliki departemen.
- Dapat melihat summary/persentase/karyawan terbaru hanya dalam scope departemen.
- Shortcut Absensi tidak ditampilkan selama route Absensi masih menolak Admin.

### User

- Scope hanya karyawan authenticated user.
- Tidak menerima statistik organisasi atau departemen.
- Tidak menerima daftar karyawan terbaru.
- Dapat menerima identitas, jabatan, status kehadiran sendiri hari ini, dan kalender umum.
- Tidak menerima shortcut management.

## Summary Data

Untuk Super Admin/Admin:

- Total Karyawan: seluruh record karyawan dalam scope.
- Karyawan Aktif: record dalam scope dengan `status_keaktifan = aktif`.
- Hadir Hari Ini: record Absensi hari berjalan berstatus `H` untuk karyawan aktif dalam scope.
- Izin / Alpha: jumlah status `I` + `A` hari berjalan untuk karyawan aktif dalam scope.

Untuk User, tampilkan status absensi dirinya sendiri dan jangan kirim summary organisasi/departemen.

## Persentase Kehadiran

Denominator adalah seluruh karyawan aktif dalam scope pengguna.

```text
H% = H / active employees in scope × 100
I% = I / active employees in scope × 100
A% = A / active employees in scope × 100
```

Jika denominator `0`, ketiga persentase bernilai `0`.

Karyawan aktif tanpa record Absensi hari ini tetap masuk denominator tetapi tidak masuk H/I/A.

## Karyawan Terbaru

- Hanya untuk Super Admin dan Admin.
- Query dibatasi sesuai kapasitas widget.
- Urutan: `tanggal_masuk DESC, id DESC`.
- Admin tetap memakai scope departemen.
- Tampilkan nama, jabatan, dan fallback inisial.
- `foto_ktp` tidak boleh digunakan sebagai avatar.
- Tidak ada menu Edit/Delete/Detail karena Employee CRUD belum tersedia.

## Kalender Kerja

- Hanya menampilkan kalender bulan berjalan.
- Tanggal hari ini diberi highlight.
- Tidak menampilkan shift, hari libur, event, marker, atau interaksi detail.

## Action

- `Lihat Semua` Absensi hanya tampil bila user memiliki access ke route Absensi; saat ini hanya Super Admin.
- `Lihat Semua` Karyawan tidak ditampilkan sampai route Data Karyawan tersedia dan diizinkan.
- Tombol `Review Dashboard` dan menu tiga titik tidak memiliki action, sehingga tidak dibuat sebagai control interaktif.
- KPI dan Closing Event tetap disabled/Soon.

## Data dan Query

- Gunakan aggregate query, bukan load seluruh collection untuk count.
- Query Absensi memakai business date `Asia/Jakarta`.
- Payload tidak boleh berisi data di luar scope role.
- Data kosong menghasilkan nilai `0` atau empty state, bukan dummy production data.

## Acceptance Criteria

- `/dashboard` tersedia untuk seluruh authenticated active role.
- Guest diarahkan ke login.
- `/admin` mengarah ke `/dashboard`; management routes tetap terlindungi.
- Nama dan jabatan dinamis dari relasi karyawan/jabatan.
- Scope Super Admin/Admin/User diterapkan di backend sebelum props dibentuk.
- Formula persentase mengikuti denominator karyawan aktif dalam scope.
- Latest employees mengikuti `tanggal_masuk DESC, id DESC`.
- Foto KTP tidak diekspos.
- UI mengikuti screenshot approved dan layout internal existing.
- KPI, Closing Event, dan Employee CRUD tidak ditambahkan.
- Test, production build, route verification, dan `LOG.md` diperbarui.
