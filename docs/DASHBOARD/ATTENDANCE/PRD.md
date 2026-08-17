# PRD — Data Absensi

Status requirement: **aktif untuk flow Super Admin**
Status implementation: lihat `LOG.md`

## Tujuan

Data Absensi digunakan untuk mengelola status kehadiran harian karyawan aktif Kampoeng Radja.

## Scope Tahap Saat Ini

Super Admin dapat:

- melihat seluruh karyawan aktif;
- melihat jabatan dari master karyawan;
- memilih tepat satu status `H`, `I`, atau `A` per karyawan;
- menambahkan keterangan opsional;
- menyimpan absensi tanggal hari berjalan;
- mengedit absensi yang sudah tersimpan selama tanggal tersebut masih hari berjalan;
- melihat tanggal lampau sebagai read-only.

Permission Admin/User belum diputuskan dan tidak boleh diinferensikan dari 403 implementation saat ini.

## Business Rules

1. Halaman default menampilkan tanggal hari ini berdasarkan waktu aplikasi/server untuk zona operasional Jambi.
2. Daftar berasal dari master `karyawan` dengan `status_keaktifan = aktif`.
3. Jabatan dibaca dari relasi karyawan; nama jabatan tidak diduplikasi ke record absensi.
4. Status valid hanya `H` (Hadir), `I` (Izin), atau `A` (Alpha).
5. Setiap karyawan hanya memiliki satu status pada satu tanggal.
6. Keterangan bersifat opsional.
7. Satu karyawan maksimal memiliki satu record per tanggal.
8. Create/update hanya diizinkan untuk hari berjalan.
9. Tanggal lampau dapat dilihat tetapi tidak dapat diubah.
10. Tanggal masa depan tidak termasuk scope input.
11. Authorization harus diterapkan backend; hide/show UI tidak cukup.
12. Penyimpanan tidak boleh menghasilkan record duplikat.

## Workflow

### Belum Disimpan

- Input H/I/A dan keterangan aktif.
- Action utama: `Simpan Absensi`.
- Submit mencakup seluruh karyawan aktif.

### Sudah Disimpan — Hari Ini

- Data awalnya read-only.
- Action: `Edit Absensi`.
- Setelah edit aktif: `Batal` dan `Simpan Perubahan`.

### Tanggal Lampau

- Data read-only.
- Tidak tersedia action edit/save.
- Backend tetap harus menolak mutation meskipun request dibuat manual.

## Data Integrity

Konsep unique:

```text
karyawan_id + tanggal_absensi
```

Foreign key tetap menunjuk master karyawan dan data master yang masih digunakan tidak boleh terhapus sembarangan.

## Validation

- `tanggal_absensi`: wajib, format tanggal valid, harus hari berjalan untuk mutation.
- `records`: wajib dan mencakup seluruh karyawan aktif tepat satu kali.
- `karyawan_id`: wajib, distinct, harus merupakan karyawan aktif.
- `status_kehadiran`: wajib, salah satu `H`, `I`, `A`.
- `keterangan`: nullable, string, batas panjang wajar sesuai schema.

## UX Requirements

- H/I/A bersifat single selection, bukan checkbox terpisah.
- Perubahan pilihan langsung terlihat tanpa reload.
- Tombol submit disabled saat request berlangsung dan double submit dicegah.
- Validation error tidak menghilangkan input user.
- Feedback sukses/error ditampilkan.
- Tabel dapat horizontal scroll pada viewport sempit bila dibutuhkan.

## Out of Scope / TBD

- Permission dan data scope Admin.
- Permission dan data scope User.
- Rekap bulanan, export, approval, shift, jam masuk/keluar, dan integrasi mesin absensi.
- Penggunaan `foto_ktp` sebagai avatar; hal ini tidak disetujui.
- KPI dan Closing Event.
