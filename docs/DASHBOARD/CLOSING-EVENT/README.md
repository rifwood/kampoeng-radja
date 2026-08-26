# CLOSING EVENT — Kampoeng Radja

**Area:** Dashboard Internal  
**Module:** Closing Event  
**Requirement Status:** FINAL  
**Visual Status:** ROUGH REFERENCE AVAILABLE  
**Implementation Status:** IMPLEMENTED  
**Last Updated:** 2026-08-22

## Source of Truth

Gunakan urutan berikut jika ada perbedaan:

1. `PRD.md` → business requirement dan struktur data.
2. `PERMISSIONS.md` → access control.
3. `UI_SPEC.md` → behavior dan struktur UI.
4. `references/` → arah visual/layout kasar.
5. source code → implementasi aktual.
6. root `LOG.md` → progress.

Visual reference **bukan sumber requirement bisnis**.

## Struktur Modul Final

```text
Closing Event
├── Data Closing Event
│   ├── List
│   ├── Tambah
│   ├── Detail
│   └── Edit
└── Master Data Event
    ├── Master PIC
    ├── Master Jenis Event
    └── Master Lokasi
```

### Super Admin

```text
Closing Event
├── Data Closing Event
└── Master Data Event
```

### Pengguna Closing Event selain Super Admin

```text
Closing Event
└── Data Closing Event
```

### Pengguna tanpa permission

Menu Closing Event tidak tampil.

## Visual Reference

- `references/closing_event.png` → acuan kasar halaman Data Closing Event.
- `references/closing_event_master.png` → acuan kasar halaman Master Data Event.

Ambil dari desain:
- hierarchy halaman;
- card/table;
- filter area;
- CTA;
- badges/chips;
- compact row action;
- nested Closing Event menu;
- tiga master dalam satu halaman.

Jangan ambil mentah:
- badge `SUPER ADMIN VIEW`;
- search bar;
- `Rows per page 10`;
- menu dashboard dummy;
- kode/deskripsi/status master;
- dummy data/tanggal;
- field yang tidak ada dalam PRD.

## Periode Pelaksanaan

- `closing_event.tanggal` tetap menjadi Tanggal Mulai.
- `closing_event.tanggal_selesai` nullable; `NULL` berarti event satu hari.
- Event multi-hari tetap satu record, satu row list/export, dan satu Harga Total.
- Filter/export menggunakan overlap periode, sedangkan highlight berlangsung memakai rentang tanggal inclusive.

## Master Data Final

### PIC
```text
id
nama_pic
```

### Jenis Event
```text
id
jenis_event
```

### Lokasi
```text
id
nama_lokasi
```

## Departemen Final terkait Authorization

```text
Management
Marcom
Marketing
OPS 1
OPS 2
```

`Marcom` dan `Marketing` adalah dua departemen berbeda.

## Sebelum Implementasi

Audit:
- User/Karyawan/Jabatan/Departemen;
- role resolver existing;
- sidebar/layout internal;
- authorization convention;
- schema/migration existing;
- PRD/PERMISSIONS/UI_SPEC;
- references.

Jangan mengubah role global hanya untuk Closing Event.

## Setelah Implementasi

Wajib:
```text
php artisan test
npm.cmd run build
git diff --check
```

Lakukan browser QA untuk:
- Super Admin;
- Manajer;
- SPV Marcom;
- SPV Marketing;
- karyawan Marketing biasa;
- user tanpa akses.

Update `LOG.md`.
