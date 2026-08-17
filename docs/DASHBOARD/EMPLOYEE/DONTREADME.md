# PRD — Kelola Karyawan

**Project:** Kampoeng Radja
**Module:** Kelola Karyawan
**Scope:** Data Karyawan + Jabatan & Departemen
**Status:** READY FOR IMPLEMENTATION
**Last Updated:** 2026-08-17

---

# 1. Tujuan

Modul **Kelola Karyawan** menjadi pusat pengelolaan master data karyawan Kampoeng Radja dan sumber data bagi Dashboard Home, Absensi, KPI (future), serta modul internal lain.

Modul terdiri dari:

1. **Data Karyawan**
2. **Jabatan & Departemen**

---

# 2. Aktor & Data Scope

## Super Admin
- Akses seluruh data karyawan.
- Full management Data Karyawan.
- Full management Jabatan & Departemen.

## Admin
- Read-only.
- Hanya melihat karyawan pada **departemen yang sama** dengan karyawan yang terhubung ke akun Admin.
- Jika Admin tidak mempunyai `departemen_id`, scope aman = kosong; jangan fallback ke seluruh perusahaan.

## User
- Self-only.
- Hanya melihat data dirinya sendiri.
- Tidak melihat daftar seluruh karyawan/departemen.

---

# 3. Data Karyawan

## 3.1 Data Pribadi
- NIK
- Nama
- Tempat Lahir
- Tanggal Lahir
- Jenis Kelamin
- Agama
- Status Perkawinan
- Pendidikan
- Alamat

## 3.2 Data Pekerjaan
- Jabatan
- Departemen
- Status Keaktifan
- Status Kerja
- Tanggal Masuk
- Tanggal Keluar

## 3.3 Kontak & Dokumen
- Nomor HP
- Foto KTP

Ketentuan:
- NIK unique.
- Departemen nullable.
- Tanggal Keluar nullable.
- Foto KTP nullable.
- Jabatan/Departemen berasal dari master.
- Foto KTP disimpan sebagai path file dan bukan avatar.

---

# 4. Data Karyawan — List

## Super Admin
Tabel mengikuti screenshot `references/data_karyawan.png`, tetapi boleh disesuaikan agar tidak terlalu lebar.

Kolom inti yang wajib diprioritaskan:
- Nama
- NIK
- Jabatan
- Departemen
- Status Kerja
- Status Keaktifan
- Aksi

Atribut lengkap tersedia melalui Detail/Edit.

## Admin
Read-only department-scope.

Field yang boleh dikirim:
- nama
- jenis_kelamin
- agama
- tanggal_lahir
- tempat_lahir
- pendidikan
- jabatan
- departemen
- status_kerja
- status_keaktifan
- tanggal_masuk
- tanggal_keluar

## User
Self-only.

Field yang boleh dikirim sama dengan field non-sensitif Admin.

## Field sensitif
Hanya Super Admin boleh menerima:
- nik
- alamat
- status_perkawinan
- no_hp
- foto_ktp

Field sensitif tidak boleh dikirim ke frontend Admin/User.

---

# 5. Search, Filter, Sorting, Pagination

## Super Admin
Search:
- Nama
- NIK

Filter:
- Jabatan
- Departemen
- Status Keaktifan
- Status Kerja

Default sorting:
- Nama A–Z

Pagination:
- server-side
- 15 data per halaman

## Admin
Search:
- Nama

Filter:
- Jabatan
- Status Keaktifan
- Status Kerja

Departemen otomatis terkunci pada departemen Admin.

Pagination:
- server-side
- 15 data per halaman

## User
Tidak perlu search/filter karena self-only.

---

# 6. Tambah Karyawan

Hanya Super Admin.

Flow:
1. authorize;
2. load master Jabatan/Departemen;
3. isi data;
4. backend validation;
5. cek NIK unique;
6. proses Foto KTP jika ada;
7. simpan Karyawan;
8. feedback sukses/error;
9. redirect ke Detail Karyawan yang baru dibuat.

### Akun User
Tambah Karyawan **tidak otomatis membuat akun User**.

Master Karyawan dan akun login dipisahkan.

Pembuatan akun User dilakukan melalui workflow terpisah di masa berikutnya.

---

# 7. Edit Karyawan

Hanya Super Admin.

- Prefill data existing.
- Backend authorization wajib.
- NIK unique mengabaikan record sendiri.
- Foto KTP tidak wajib upload ulang.
- Jika Foto KTP diganti, file lama dibersihkan sesuai storage policy.
- Setelah sukses, redirect ke Detail Karyawan.

---

# 8. Detail Karyawan

Gunakan **halaman Detail Karyawan terpisah**.

Super Admin melihat:
- Data Pribadi
- Data Pekerjaan
- Kontak
- Dokumen/Foto KTP

Admin:
- Detail read-only untuk karyawan yang masih berada dalam department-scope.
- Field sensitif tidak dikirim.

User:
- Detail self-only.
- Field sensitif tidak dikirim.

---

# 9. Foto KTP

- Hanya Super Admin.
- Tidak ditampilkan di tabel list.
- Ditampilkan pada Detail.
- Edit menampilkan file existing + opsi ganti.
- Tidak boleh dipakai sebagai avatar.
- Disarankan menggunakan private/protected file delivery dengan authorization.
- Database tetap menyimpan path.

---

# 10. Delete vs Deactivate Karyawan

Gunakan **Conditional Hard Delete**.

### Hard Delete diperbolehkan hanya jika:
- record benar-benar salah input; dan
- belum mempunyai User; dan
- belum mempunyai Absensi; dan
- belum mempunyai histori/relasi lain yang harus dipertahankan.

Jika sudah mempunyai relasi historis:
- hard delete ditolak;
- gunakan **Nonaktifkan Karyawan**.

Tidak boleh cascade-delete histori.

---

# 11. Nonaktifkan Karyawan

Flow normal untuk karyawan yang tidak lagi aktif.

Ketika `status_keaktifan` menjadi nonaktif:
- karyawan tidak muncul pada input Absensi berikutnya;
- histori Absensi tetap dipertahankan;
- jika mempunyai akun User, `users.is_active` otomatis menjadi `false`.

### Reaktivasi
Mengaktifkan kembali status Karyawan **tidak otomatis** mengaktifkan kembali akun User.

Reaktivasi akun dilakukan eksplisit melalui workflow akun.

---

# 12. Tanggal Masuk & Tanggal Keluar

## Tanggal Masuk
- wajib;
- tidak boleh setelah Tanggal Keluar;
- boleh tanggal hari ini/lampau;
- tanggal masa depan tidak digunakan untuk karyawan yang sudah aktif.

## Tanggal Keluar
- nullable selama masih bekerja;
- wajib ketika proses **Karyawan Keluar** dilakukan;
- harus >= Tanggal Masuk;
- tidak boleh tanggal masa depan untuk proses keluar yang sudah efektif;
- proses Karyawan Keluar membuat status keaktifan nonaktif dan menonaktifkan akun User jika ada.

Status nonaktif administratif yang bukan “keluar dari perusahaan” tidak otomatis mengisi Tanggal Keluar.

---

# 13. Jabatan & Departemen

Visual mengikuti `references/departemen_jabatan.png`.

Hanya Super Admin.

## Jabatan
- list
- tambah
- edit
- hapus

## Departemen
- list
- tambah
- edit
- hapus

### Rule Delete Master
Jabatan/Departemen tidak boleh dihapus selama masih direferensikan karyawan mana pun, aktif maupun nonaktif.

Backend menolak dan UI menampilkan pesan jelas.

Tidak perlu menambah status aktif/nonaktif pada master Jabatan/Departemen pada fase ini.

---

# 14. Relationship dengan Absensi

- Absensi menggunakan karyawan aktif.
- Karyawan nonaktif tidak ikut input baru.
- Histori Absensi tetap ada.
- Karyawan baru yang aktif dapat muncul pada input Absensi hari berjalan setelah data tersimpan.
- `tanggal_masuk` harus <= tanggal hari berjalan agar masuk daftar Absensi hari itu.

Histori Jabatan/Departemen menggunakan relasi current-state pada fase ini; snapshot histori jabatan belum menjadi scope.

---

# 15. Authorization

Detail pada `PERMISSIONS.md`.

Prinsip:
- backend authorization wajib;
- query menerapkan data scope sebelum props Inertia dibuat;
- frontend hanya conditional UI;
- data di luar scope tidak boleh dikirim lalu disembunyikan.

---

# 16. Validation Minimum

- `nik`: required, unique, max mengikuti schema.
- `nama`: required.
- `jabatan_id`: required, valid.
- `departemen_id`: nullable, valid jika diisi.
- enum mengikuti migration aktual.
- `tanggal_masuk`: required.
- `tanggal_keluar`: nullable, >= tanggal_masuk.
- Foto KTP nullable.
- validation file Foto KTP: image/jpeg/png/webp, maksimal 5 MB.
- backend validation wajib.

---

# 17. Acceptance Criteria

- [ ] Super Admin company-wide.
- [ ] Admin department-only tanpa fallback.
- [ ] User self-only.
- [ ] Field sensitif tidak bocor ke Admin/User.
- [ ] List/search/filter/pagination berjalan.
- [ ] Tambah/Edit/Detail berjalan.
- [ ] NIK unique.
- [ ] Foto KTP protected dan bukan avatar.
- [ ] Conditional hard delete berjalan.
- [ ] Deactivate menjaga histori.
- [ ] Nonaktif menonaktifkan User jika ada.
- [ ] Jabatan/Departemen CRUD Super Admin berjalan.
- [ ] Master yang masih dipakai tidak dapat dihapus.
- [ ] Visual list/master mengikuti references.
- [ ] Tambah/Edit/Detail mengikuti design language references.
- [ ] Regression Dashboard Home dan Absensi lulus.
- [ ] Production build berhasil.
- [ ] `LOG.md` diperbarui.

---

# 18. Out of Scope

- workflow pembuatan akun User;
- reset PIN;
- KPI;
- Closing Event;
- redesign Dashboard Home;
- permission engine baru;
- riwayat perubahan Jabatan/Departemen;
- import/export massal.
