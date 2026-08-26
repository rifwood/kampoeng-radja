# PERMISSIONS — Closing Event

**Status:** FINAL  
**Last Updated:** 2026-08-23

## Prinsip

Authorization menggunakan:

```text
role + jabatan + departemen
```

Role global akun **tidak diubah** oleh Closing Event.

Frontend hanya mengatur visibility UI. Backend tetap sumber authorization utama.

## Departemen Final

```text
Management
Marcom
Marketing
OPS 1
OPS 2
```

## Capability

```text
canViewClosingEvent
canCreateClosingEvent
canUpdateClosingEvent
canDeleteClosingEvent
canExportClosingEvent
canManageClosingEventMaster
```

Detail mengikuti `canViewClosingEvent`.

## Matrix Akses

| Kelompok | Kondisi | View | Create | Detail | Edit | Delete | Export | Master |
|---|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| Super Admin | role `super_admin` | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Manajer | Manajer/Manager, semua departemen | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| SPV Marcom | Supervisor + Marcom | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| SPV Marketing | Supervisor + Marketing | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| Karyawan Marketing biasa | Marketing, bukan rule di atas | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ |
| Pengguna lain | selain kondisi di atas | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

## Prioritas Evaluasi

```text
1. Super Admin
2. Manajer / Manager
3. Supervisor + Marcom
4. Supervisor + Marketing
5. Karyawan biasa + Marketing
6. Deny
```

Contoh:
```text
Manajer + OPS 1
→ View/Create/Edit

Supervisor + Marcom
→ View/Create/Edit

Supervisor + Marketing
→ View/Create/Edit

Supervisor + OPS 1
→ DENY

Supervisor + OPS 2
→ DENY

Mitra + Marketing
→ View/Create

Mitra + Marcom
→ DENY

Operasional + OPS 1
→ DENY
```

## Menu

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

### Selain itu
Tidak tampil. Direct URL tetap ditolak backend.

## Scope Data

Semua actor dengan View melihat data Closing Event **company-wide**.

Tidak ada scope berdasarkan:
- creator;
- departemen pembuat;
- record milik sendiri.

## Create

Boleh:
- Super Admin;
- seluruh Manajer;
- SPV Marcom;
- SPV Marketing;
- karyawan biasa Marketing.

Backend:
```text
created_by = auth()->id()
updated_by = NULL
```

## Edit

Boleh:
- Super Admin;
- seluruh Manajer;
- SPV Marcom;
- SPV Marketing.

Tidak ada batas waktu edit.

Karyawan Marketing biasa tidak dapat edit, termasuk record yang dibuat sendiri.

Capability Update yang sama juga mengizinkan perubahan status `aktif ↔ dibatalkan`. Perubahan ke `dibatalkan` wajib disertai alasan; `cancelled_by` dan `cancelled_at` ditentukan backend. Event dibatalkan tidak menjadi read-only dan reaktivasi tidak membutuhkan capability baru.

## Export Excel

Capability `canExportClosingEvent` diberikan kepada seluruh kelompok yang memiliki akses Closing Event:

- Super Admin;
- seluruh Manajer/Manager;
- Supervisor + Marcom;
- Supervisor + Marketing;
- seluruh karyawan departemen Marketing.

Export selalu company-wide berdasarkan event yang rentang pelaksanaannya beririsan dengan bulan/tahun terpilih (`tanggal` sampai `tanggal_selesai ?? tanggal`). Event aktif dan dibatalkan ikut sebagai histori; event multi-hari tetap satu row dan satu Harga Total. Actor lain tidak melihat tombol export dan direct URL ditolak dengan `403 Forbidden`.

## Delete

Hanya Super Admin.

Unauthorized delete:
```text
403 Forbidden
```

## Detail

Semua actor dengan View dapat membuka Detail.

- Edit jika punya Update.
- Delete jika Super Admin.

## Master Data Event

Hanya Super Admin dapat:
- lihat;
- tambah;
- edit;
- hapus PIC/Jenis Event/Lokasi.

Master yang masih digunakan tidak boleh dihapus secara destruktif.

## Backend Security

Wajib:
1. authorization setiap route/action;
2. unauthorized user tidak menerima payload Closing Event;
3. create/update/delete backend-protected;
4. master endpoint Super Admin only;
5. `created_by`/`updated_by` dari authenticated user;
6. location IDs `exists` + `distinct`;
7. harga numeric dan nonnegative.
8. endpoint export memeriksa `canExportClosingEvent` dan tidak bergantung pada visibility frontend.
9. `cancelled_by` dan `cancelled_at` selalu ditetapkan backend, bukan dipercaya dari request frontend.

## Test Matrix Minimum

```text
test_superadmin
Admin Sistem + Management
→ full

test_manager
Manajer + Marketing
→ View/Create/Edit

test_spv_marcom
Supervisor + Marcom
→ View/Create/Edit

test_spv_marketing
Supervisor + Marketing
→ View/Create/Edit

test_spv_ops
Supervisor + OPS 1
→ DENY

test_marketing
Mitra + Marketing
→ View/Create

test_ops
Operasional + OPS 1
→ DENY
```

Direct route unauthorized wajib dites.
