# UI_SPEC — Absensi

**Status:** FINAL
**Last Updated:** 2026-08-20

## 1. Navigation

Super Admin menggunakan label `Kelola Absensi`. Admin/User menggunakan label
`Data Absensi` dan menu tetap terlihat.

## 2. Super Admin — Kelola Absensi

Kolom/input:

```text
Nama
Jabatan
Kehadiran
Jam Masuk
Jam Keluar
Keterangan
```

Kehadiran memakai H/I/A sesuai design system existing. Jam Masuk dan Jam
Keluar memakai input teks waktu `HH:MM` yang keyboard-first. Input angka empat
digit seperti `0930` otomatis menjadi `09:30`; nilai harus berada pada rentang
`00:00`–`23:59`. Jam masuk maksimal `12:00`. Control kecil di sisi input dapat
menambah/mengurangi satu menit tanpa membuka native time picker. Enter berpindah
dari Jam Masuk ke Jam Keluar lalu ke Jam Masuk karyawan Hadir berikutnya.
Arrow Up/Down berpindah antar-karyawan pada kolom waktu yang sama dan melewati
row I/A; perubahan menit hanya melalui tombol mouse. Jam Keluar boleh kosong.
Jika I/A dipilih, kedua input jam dinonaktifkan dan dikosongkan, sementara
backend tetap membersihkan nilainya. Keterangan opsional.

Jam masuk H setelah `08:30` menampilkan indikator kecil `Terlambat`. Jam keluar
H sebelum `16:30` menampilkan indikator kecil `Pulang Awal`. Tidak ada indikator
jika nilai jam terkait masih kosong.

Tombol `Simpan Absensi` harus bisa diklik ketika payload valid dan tidak boleh
stuck disabled karena state frontend.

Super Admin dapat memilih tanggal melalui date picker serta shortcut `Kemarin`
dan `Hari Ini`. Controls mutation tampil untuk kedua tanggal tersebut. Tanggal
sebelum kemarin tetap dapat dilihat, tetapi controls menjadi read-only. Tanggal
masa depan tidak dapat dipilih untuk mutation.

## 3. Admin/User — Data Absensi

Read-only table berisi Tanggal, Nama, Jabatan, Kehadiran, Jam Masuk, Jam Keluar,
dan Keterangan. Tidak ada input editable, Simpan, Edit, Hapus, atau Export
Excel.

## 4. Window Input/Edit

Super Admin dapat input/edit hari berjalan dan satu hari kalender sebelumnya.
Window memakai tanggal kalender zona `Asia/Jakarta`, sehingga tetap aman pada
pergantian bulan, tahun, dan leap year. Tanggal sebelum kemarin read-only untuk
semua role dan mutation ditolak backend. Admin/User tetap read-only pada hari
ini maupun kemarin.

## 5. Laporan Absensi — Super Admin

Area export:

```text
Laporan Absensi

[Bulan ▼] [Tahun ▼] [Export Excel]
```

Bulan/tahun berdasarkan `tanggal_absensi`. Admin/User tidak menerima tombol
Export Excel dan akses endpoint secara langsung tetap ditolak HTTP 403.

## 6. Excel Multi-Sheet

Satu bulan menghasilkan satu workbook `.xlsx`. Sheet pertama adalah `Rekap
Bulanan`; sheet berikutnya mencakup setiap tanggal kalender dalam bulan. Tanggal
tanpa data tetap memiliki sheet dengan header dan empty state.

Kolom Rekap Bulanan: NO, NAMA KARYAWAN, JABATAN, TOTAL HADIR, TOTAL IZIN,
TOTAL ALFA, TOTAL TERLAMBAT, dan TOTAL PULANG AWAL.

Nama sheet memakai tanggal Indonesia ringkas, misalnya `17 Agt 2026`. Sheet
diurutkan tanggal ascending setelah Rekap Bulanan. Row pada setiap sheet harian
diurutkan nama karyawan ascending dan nomor dimulai lagi dari 1. Jumlah daily
sheet mengikuti jumlah hari kalender aktual, termasuk leap year.

Kolom final:

```text
NO
TANGGAL
NAMA
JABATAN
KEHADIRAN
JAM MASUK
JAM KELUAR
KETERANGAN
```

Jam NULL tampil `-`. Header bold dengan warna ringan dan border tipis; lebar
kolom Nama, Jabatan, dan Keterangan dibuat cukup luas. Jika seluruh periode
kosong, workbook tetap berisi header Rekap Bulanan dan daily sheet dengan empty
state untuk setiap tanggal kalender.

## 7. Responsive

Desktop: table compact dan input jam tidak terlalu lebar.

Tablet/Mobile: horizontal scroll diperbolehkan dan controls tetap usable.

## 8. States

- loading;
- processing;
- validation error;
- success flash;
- Excel export error;
- read-only Admin/User;
- editable today/yesterday untuk Super Admin;
- read-only sebelum kemarin;
- empty monthly period.

## 9. QA Matrix

Super Admin: edit controls untuk hari ini/kemarin, jam inputs, dan Export Excel terlihat.

Admin/User: data read-only terlihat; edit controls dan Export Excel tidak ada.
