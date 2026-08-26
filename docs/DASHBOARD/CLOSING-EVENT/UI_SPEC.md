# UI_SPEC — Closing Event

**Status:** FINAL FOR IMPLEMENTATION  
**Visual Reference:** ROUGH  
**Last Updated:** 2026-08-23

## 1. Dasar UI

Gunakan design system dashboard internal Kampoeng Radja existing.

Visual:
```text
references/closing_event.png
references/closing_event_master.png
```

Kedua gambar hanya **desain kasar**.

Urutan kebenaran:
```text
PRD > PERMISSIONS > UI_SPEC > reference image
```

Jangan menambah requirement karena terlihat pada mockup.

## 2. Sidebar

### Super Admin
```text
Closing Event
├── Data Closing Event
└── Master Data Event
```

### Manajer / SPV Marcom / SPV Marketing / Karyawan Marketing
```text
Closing Event
└── Data Closing Event
```

Unauthorized: menu tidak tampil.

Jangan menyalin menu lain dari screenshot.

# DATA CLOSING EVENT

## 3. Struktur List

```text
Page Header
Filter / Action Bar
Data Table
Pagination
```

Gunakan card putih, border tipis, radius ringan, spacing sesuai dashboard existing.

Hapus badge:
```text
SUPER ADMIN VIEW
```

## 4. Header

Judul:
```text
Data Closing Event
```

Deskripsi:
```text
Kelola dan pantau data kegiatan event Kampoeng Radja.
```

CTA:
```text
+ Tambah Closing Event
```

hanya jika boleh Create.

Action toolbar juga memuat `Export Excel` hanya jika `canExportClosingEvent = true`.

## 5. Filter Final

Gunakan:
```text
Bulan
Tahun
Status Event
```

Berdasarkan overlap rentang pelaksanaan:
```text
tanggal <= akhir bulan
AND
(tanggal_selesai ?? tanggal) >= awal bulan
```

Bukan `created_at` atau `updated_at`.

Default:
```text
Bulan = bulan berjalan
Tahun = tahun berjalan
Status Event = Semua Status
```

Search bar pada mockup **tidak dibuat pada fase ini**.

## 6. Sorting

```text
tanggal ASC
id ASC
```

## 7. Table Final

Kolom:
```text
Tanggal
PIC
Konsumen
Jenis Event
Lokasi
Jam Kedatangan
Jumlah Pengunjung
Konsumsi
Additional
Panitia
Aksi
```

`Kontak` dan `Harga Total` tidak tampil pada list utama. Additional dan Panitia menggunakan truncation/line clamp agar tinggi row tetap compact; nilai lengkap tersedia di Detail.

### Lokasi
- compact badges/chips;
- jika banyak, boleh tampilkan beberapa + `+N`;
- semua lokasi terlihat di Detail.

### Jenis Event
Tampilkan sebagai text/badge compact, jangan pecah nama berdasarkan spasi seperti dummy mockup.

### Konsumsi
Badge:
```text
Ya
Tidak
```

### Event sedang berlangsung

Jika `isOngoing = true` berdasarkan `status_event = aktif` dan inclusive date range `tanggal` sampai `tanggal_selesai ?? tanggal`, row menggunakan highlight biru-oranye lembut, aksen sisi kiri, dan label compact:

```text
Sedang berlangsung
```

Semua event yang rentangnya mencakup hari berjalan mendapat treatment yang sama. Animasi harus halus, tidak mengubah ukuran row, dan dinonaktifkan ketika perangkat menggunakan `prefers-reduced-motion: reduce`.

Event `dibatalkan` tidak pernah `isOngoing`; tampilkan badge rose/red subtle **Dibatalkan** di bawah tanggal tanpa membuat seluruh row merah terang.

## 8. Row Actions

### Super Admin
```text
Detail
Edit
Delete
```

### Manajer / SPV Marcom / SPV Marketing
```text
Detail
Edit
```

### Karyawan Marketing biasa
```text
Detail
```

Gunakan icon compact + accessibility label/tooltip.

## 9. Pagination

Final:
```text
15 rows/page
```

Tidak perlu selector rows-per-page jika convention project fixed.

Query bulan/tahun harus tetap saat pindah page.

## 10.1 Export Excel

Toolbar desktop:

```text
[Bulan] [Tahun]        [Export Excel] [+ Tambah Closing Event]
```

- Tombol export hanya terlihat bagi actor yang memiliki `canExportClosingEvent`.
- Export memakai overlap periode filter saat ini berdasarkan `tanggal` sampai `tanggal_selesai ?? tanggal`.
- Periode kosong menampilkan flash error `Tidak ada data Closing Event pada periode yang dipilih.`
- File `.xlsx` berisi seluruh data bisnis company-wide untuk event aktif dan dibatalkan, termasuk Tanggal Mulai, Tanggal Selesai, Kontak, Harga Total, Status Event, alasan, actor, dan waktu pembatalan. Event multi-hari tetap satu row dan Harga Total tidak diduplikasi.

## 10. Empty State

```text
Belum ada Closing Event pada bulan ini.
```

CTA Tambah hanya jika allowed.

# TAMBAH / EDIT

## 11. Form Structure

### Informasi Konsumen
```text
Konsumen *
Kontak *
Jumlah Pengunjung *
```

### Informasi Event
```text
PIC *
Tanggal Mulai *
Tanggal Selesai (Opsional)
Jam Kedatangan *
Jenis Event *
Lokasi *
```

Pada Edit saja tambahkan:
```text
Status Event *
Alasan Pembatalan * (hanya ketika memilih Dibatalkan)
```

Create tidak menampilkan pilihan status dan selalu menyimpan `aktif`.

Lokasi multi-select/checklist.

### Kebutuhan Event
```text
Konsumsi *
Panitia
Additional
```

Panitia dan Additional opsional.

### Nilai
```text
Harga Total *
```

Footer create:
```text
[Batal] [Simpan Closing Event]
```

Footer edit:
```text
[Batal] [Simpan Perubahan]
```

## 12. Form Behavior

- inline validation;
- processing state;
- no double submit;
- PIC/Jenis Event/Lokasi dari backend;
- jangan hardcode master;
- lokasi minimal satu dan distinct;
- edit memuat existing locations;
- harga dikirim numeric sesuai backend.
- Tanggal Selesai nullable dan tidak boleh sebelum Tanggal Mulai;
- kosongkan Tanggal Selesai untuk event satu hari.
- perubahan aktif ke dibatalkan tidak dapat disimpan tanpa alasan;
- event dibatalkan tetap dapat diedit dan status dapat dikembalikan ke Aktif;
- metadata pembatalan terakhir dipertahankan setelah reaktivasi.

# DETAIL

## 13. Detail Closing Event

Gunakan card/section.

### Informasi Event
```text
Tanggal Mulai
Tanggal Selesai (`—` jika event satu hari)
Status Event
PIC
Jenis Event
Lokasi
Jam Kedatangan
```

Jika metadata pembatalan pernah tercatat, tampilkan section **Informasi Pembatalan** berisi alasan, actor, dan waktu. Untuk event yang sudah diaktifkan kembali, section tetap tampil sebagai histori pembatalan terakhir.

### Informasi Konsumen
```text
Konsumen
Kontak
Jumlah Pengunjung
```

### Operasional
```text
Konsumsi
Panitia
Additional
```

### Nilai
```text
Harga Total
```

### Informasi Sistem
```text
Dibuat Oleh
Tanggal Dibuat
Terakhir Diubah Oleh
Tanggal Terakhir Diubah
```

Jika `updated_by = null`:
```text
Belum pernah diubah
```

Header action:
```text
Kembali
Edit   [jika allowed]
Hapus  [Super Admin only]
```

# MASTER DATA EVENT

## 14. Struktur Halaman

Satu halaman, tiga card/table bertumpuk seperti rough reference:

```text
Master PIC
Master Jenis Event
Master Lokasi
```

Judul:
```text
Master Data Event
```

Deskripsi:
```text
Kelola data master untuk modul Closing Event.
```

Hapus badge `SUPER ADMIN VIEW`.

Super Admin only.

## 15. Master PIC

```text
Master PIC                       [+ Tambah PIC]

No.
Nama PIC
Aksi
```

Jangan membuat:
```text
Kode PIC
Kontak
Deskripsi
Status
```

Tambah/Edit via modal compact:
```text
Nama PIC *
```

## 16. Master Jenis Event

```text
Master Jenis Event      [+ Tambah Jenis Event]

No.
Nama Jenis Event
Aksi
```

Field database:
```text
jenis_event
```

Jangan membuat:
```text
Kode Jenis
Deskripsi
Status
```

## 17. Master Lokasi

```text
Master Lokasi                 [+ Tambah Lokasi]

No.
Nama Lokasi
Aksi
```

Jangan membuat:
```text
Kode Lokasi
Deskripsi
Status
```

## 18. Master CRUD

Tambah/Edit gunakan modal compact.

Delete gunakan confirmation modal.

Jika masih dipakai:
```text
Data tidak dapat dihapus karena masih digunakan pada Closing Event.
```

## 19. Master Pagination

Gunakan pattern project existing.

Final untuk setiap tabel master:
```text
5 rows/page
```

Gunakan paginator independen `pic_page`, `event_page`, dan `lokasi_page`. Jangan tampilkan selector rows-per-page.

# RESPONSIVE

## 20. Desktop
- filter horizontal;
- compact table;
- master cards stacked;
- action ringkas.

## 21. Tablet
- filter wrap;
- table horizontal scroll;
- form 1–2 kolom.

## 22. Mobile
- form single column;
- filter stack;
- table horizontal scroll;
- modal responsive.

## 23. State Wajib

- loading/processing;
- empty;
- validation error;
- success flash;
- delete confirmation;
- delete master rejected;
- 403 unauthorized.

## 24. Visual Spirit yang Dipertahankan

- white card;
- compact table;
- primary blue CTA;
- subtle gray border;
- light background;
- small icon actions;
- location chips;
- consumption badge;
- nested Closing Event sidebar.

Final styling harus mengikuti dashboard existing, bukan screenshot secara terisolasi.

## 25. QA Checklist

### Data Closing Event
- [ ] tanpa SUPER ADMIN VIEW
- [ ] Bulan/Tahun berdasarkan tanggal pelaksanaan
- [ ] default bulan berjalan
- [ ] final table columns benar
- [ ] Kontak dan Harga Total tidak tampil di list
- [ ] Additional dan Panitia tampil compact
- [ ] Export Excel hanya tampil untuk actor ber-capability Export
- [ ] tanggal list tampil compact sebagai satu tanggal atau range
- [ ] seluruh event yang rentangnya mencakup hari berjalan terlihat jelas tanpa mengganggu keterbacaan tabel
- [ ] multiple location terbaca
- [ ] action sesuai permission
- [ ] 15 rows/page

### Master Data Event
- [ ] Super Admin only
- [ ] tiga tabel satu halaman
- [ ] PIC hanya Nama PIC
- [ ] Jenis Event hanya Nama Jenis Event
- [ ] Lokasi hanya Nama Lokasi
- [ ] tidak ada kode/deskripsi/status
- [ ] add/edit/delete compact
- [ ] dependency delete handled

### Permission
- [ ] Manager semua departemen allowed
- [ ] Supervisor Marcom allowed
- [ ] Supervisor Marketing allowed
- [ ] Supervisor OPS 1/OPS 2 denied
- [ ] karyawan Marketing biasa View/Create
- [ ] karyawan Marcom biasa denied
- [ ] seluruh kelompok berakses dapat Export; pengguna lain ditolak backend
