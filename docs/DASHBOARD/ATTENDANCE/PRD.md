# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Modul Attendance / Absensi — Sistem Internal Kampoeng Radja

**Status:** FINAL
**Tanggal finalisasi requirement:** 20 Agustus 2026

## 1. Gambaran Umum

Modul Absensi digunakan untuk mencatat kehadiran harian seluruh karyawan Kampoeng Radja dan menyediakan monitoring untuk Super Admin, Admin, dan User.

| Aspek | Ketentuan |
|---|---|
| Menu Super Admin | Kelola Absensi |
| Menu Admin/User | Data Absensi |
| Aktor | super_admin, admin, user |
| Status | H = Hadir, I = Izin, A = Alfa |
| Input/Edit | Hanya Super Admin |
| Monitoring | Semua role |
| Jam Masuk | Manual oleh Super Admin |
| Jam Keluar | Manual oleh Super Admin |
| Export Excel bulanan | Hanya Super Admin |
| Data historis | Tetap tersimpan |
| Window mutation | Hari berjalan dan satu hari kalender sebelumnya |
| Historis sebelum kemarin | Read-only |

## 2. Hak Akses

| Fitur | Super Admin | Admin | User |
|---|:---:|:---:|:---:|
| Lihat daftar absensi | ✅ | ✅ | ✅ |
| Lihat seluruh karyawan | ✅ | ✅ | ✅ |
| Input H/I/A | ✅ | ❌ | ❌ |
| Input Jam Masuk/Keluar | ✅ | ❌ | ❌ |
| Input Keterangan | ✅ | ❌ | ❌ |
| Simpan | ✅ | ❌ | ❌ |
| Input/edit hari berjalan | ✅ | ❌ | ❌ |
| Input/edit satu hari sebelumnya | ✅ | ❌ | ❌ |
| Input/edit sebelum kemarin | ❌ | ❌ | ❌ |
| Hapus | ❌ | ❌ | ❌ |
| Export Excel bulanan | ✅ | ❌ | ❌ |

Admin dan User tetap memiliki menu `Data Absensi`, tetapi seluruh halaman bersifat read-only. Direct mutation URL/API tetap harus ditolak backend.

## 3. Tabel Absensi

Kolom final:

- Tanggal
- Nama
- Jabatan
- Kehadiran
- Jam Masuk
- Jam Keluar
- Keterangan

Super Admin dapat mengubah Kehadiran, Jam Masuk, Jam Keluar, dan Keterangan untuk tanggal hari berjalan atau satu hari kalender sebelumnya.

## 4. Struktur Database

Tambahkan dua kolom ke tabel absensi existing:

```text
jam_masuk TIME NULL
jam_keluar TIME NULL
```

Struktur logis final:

```text
id
karyawan_id
tanggal
kehadiran ENUM('H','I','A')
jam_masuk TIME NULL
jam_keluar TIME NULL
keterangan VARCHAR(255) NULL
created_by
updated_by NULL
created_at
updated_at
```

Unique tetap pada kombinasi:

```text
karyawan_id + tanggal
```

Tidak membuat tabel Absensi baru.

## 5. Aturan Jam

- Jam masuk dan jam keluar diisi manual oleh Super Admin.
- Keduanya nullable.
- Jam keluar boleh kosong saat absensi pagi disimpan.
- Status I atau A harus memiliki jam masuk dan jam keluar NULL.
- Jika status berubah dari H ke I/A, backend wajib membersihkan kedua jam.
- Jika kedua jam terisi, jam keluar tidak boleh lebih awal dari jam masuk.
- Jam masuk maksimal `12:00`; nilai setelahnya ditolak dengan pesan validasi yang jelas.
- Hari Normal memakai target masuk `08:30` dan toleransi 10 menit: sampai `08:30` Tepat Waktu, `08:31`–`08:40` Dalam Toleransi, setelah `08:40` Terlambat.
- Tanggal tanpa konfigurasi khusus otomatis dianggap Hari Normal dan tidak membutuhkan record konfigurasi harian.
- Hari Event dapat memiliki beberapa jadwal Panitia. Setiap jadwal memakai jam masuk fleksibel dan toleransi tetap 5 menit.
- Hanya karyawan yang dipilih sebagai Panitia yang memakai jadwal Event; karyawan lain tetap memakai aturan Hari Normal.
- Satu karyawan hanya boleh berada pada satu jadwal Panitia dalam tanggal Event yang sama.
- Hadir dengan jam keluar sebelum `16:30` dihitung sebagai Pulang Lebih Awal. Nilai `16:30` sudah normal.
- Terlambat dan Pulang Lebih Awal dihitung dari nilai jam saat dibutuhkan dan tidak disimpan sebagai kolom database.
- Jam yang masih NULL tidak menghasilkan status Terlambat/Pulang Lebih Awal.

## 6. Aturan Bisnis

1. Satu karyawan hanya satu record per tanggal.
2. Nama dan jabatan diambil melalui relasi Karyawan.
3. Input/edit hanya Super Admin.
4. Admin/User read-only.
5. Input/edit diizinkan jika tanggal absensi adalah hari ini atau satu hari kalender sebelumnya.
6. Mutation untuk tanggal sebelum kemarin ditolak backend dan datanya tetap read-only.
7. Tanggal masa depan tidak boleh digunakan untuk input/edit.
8. Window mutation dihitung berdasarkan tanggal kalender zona waktu `Asia/Jakarta`, bukan periode 24 jam berjalan.
9. Data historis tetap tersimpan.
10. Halaman harian utama menampilkan hari berjalan dan menyediakan akses cepat ke kemarin.
11. Keterangan nullable.
12. created_by/updated_by mengarah ke users.id.

## 7. Flow Super Admin

1. Buka Kelola Absensi.
2. Sistem menampilkan karyawan eligible pada tanggal hari ini atau kemarin yang dipilih.
3. Pilih H/I/A.
4. Isi Jam Masuk/Jam Keluar manual jika Hadir.
5. Isi keterangan bila perlu.
6. Klik Simpan Absensi.
7. Backend validasi authorization, tanggal, uniqueness, jam, dan payload.
8. Simpan.
9. Data tetap editable sampai akhir satu hari kalender berikutnya.
10. Setelah melewati window kemarin, data menjadi read-only dan mutation ditolak.

## 8. Flow Admin/User

1. Buka Data Absensi.
2. Sistem menampilkan daftar absensi seluruh karyawan.
3. Semua data read-only.
4. Tidak ada Simpan/Edit/Hapus.
5. Koreksi dilakukan melalui Super Admin.
6. Direct mutation endpoint ditolak.

## 9. Export Excel Bulanan

Export Excel `.xlsx` masuk scope sekarang dan hanya untuk Super Admin.

Filter:

```text
Bulan
Tahun
```

Filter berdasarkan tanggal absensi, bukan created_at/updated_at.

Scope laporan: company-wide seluruh karyawan pada bulan yang dipilih. Satu bulan menghasilkan satu workbook. Sheet pertama adalah `Rekap Bulanan`, diikuti satu sheet untuk setiap tanggal kalender dalam bulan, termasuk tanggal yang belum mempunyai record Absensi.

Kolom `Rekap Bulanan`:

- No
- Nama Karyawan
- Jabatan
- Total Hadir
- Total Izin
- Total Alfa
- Total Terlambat
- Total Pulang Awal

Total Terlambat menghitung record H yang melewati target beserta toleransinya: aturan Normal `08:30 + 10 menit`, atau jadwal Panitia Event `+ 5 menit`. Total Pulang Awal menghitung record H dengan jam keluar sebelum `16:30`. Jam NULL serta status I/A tidak dihitung pada kedua total tersebut.

Isi setiap sheet harian:

- No
- Tanggal
- Nama
- Jabatan
- Kehadiran
- Jam Masuk
- Jam Keluar
- Keterangan

Urutan sheet dan row:

```text
Rekap Bulanan
sheet seluruh tanggal kalender ASC
row nama_karyawan ASC
```

Nama sheet memakai tanggal Indonesia yang singkat dan valid, misalnya:

```text
17 Agt 2026
18 Agt 2026
19 Agt 2026
```

Nilai jam NULL ditampilkan `-`. Sheet tanggal tanpa record tetap menampilkan header dan pesan `Belum ada data absensi pada tanggal ini.` Jumlah tanggal ditentukan dari kalender aktual sehingga Februari dan leap year ditangani otomatis.

Header dibuat bold dengan warna ringan, border tipis, alignment rapi, freeze row header, dan lebar kolom yang proporsional.

Contoh filename:

```text
absensi-karyawan-agustus-2026.xlsx
```

Admin/User yang mencoba endpoint export mendapat 403.

## 10. Capability

```text
canViewAttendance
canManageAttendance
canExportAttendance
```

Super Admin:

```text
true / true / true
```

Admin:

```text
true / false / false
```

User:

```text
true / false / false
```

## 11. Acceptance Criteria

- [ ] Super Admin dapat input H/I/A.
- [ ] Super Admin dapat input Jam Masuk manual.
- [ ] Super Admin dapat input Jam Keluar manual.
- [ ] Jam Keluar boleh kosong sementara.
- [ ] I/A membersihkan nilai jam.
- [ ] Admin/User dapat melihat daftar absensi company-wide read-only.
- [ ] Admin/User tidak memiliki Simpan/Edit/Hapus.
- [ ] Admin/User mutation ditolak backend.
- [ ] Super Admin dapat input/edit hari berjalan.
- [ ] Super Admin dapat input/edit satu hari kalender sebelumnya.
- [ ] Mutation sebelum kemarin ditolak dan data tetap read-only.
- [ ] Mutation tanggal masa depan ditolak.
- [ ] Satu karyawan tidak dapat memiliki dua record pada tanggal sama.
- [ ] Super Admin dapat export Excel bulanan.
- [ ] Excel memakai tanggal absensi, bukan created_at.
- [ ] Sheet pertama workbook adalah Rekap Bulanan dengan total H/I/A/Terlambat/Pulang Awal per karyawan.
- [ ] Satu workbook berisi satu sheet untuk setiap tanggal kalender dalam bulan, termasuk tanggal tanpa data.
- [ ] Urutan sheet berdasarkan tanggal dan urutan row berdasarkan nama karyawan.
- [ ] Admin/User tidak dapat export Excel.

## 12. Out of Scope

- Koreksi historis sebelum kemarin
- Fingerprint
- Check-in mandiri
- GPS attendance
- Approval izin
- Shift
- Overtime
- Payroll
- Export PDF
