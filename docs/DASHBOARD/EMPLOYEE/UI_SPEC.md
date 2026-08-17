# UI_SPEC — Kelola Karyawan

**Status:** READY FOR IMPLEMENTATION
**Last Updated:** 2026-08-17 — disinkronkan dengan PRD akun karyawan terbaru

Visual references:
- `references/data_karyawan.png`
- `references/departemen_jabatan.png`

Desain Tambah/Edit/Detail belum memiliki screenshot khusus. Agent harus menurunkannya dari design language dua reference di atas tanpa membuat style baru.

---

# 1. Shared Layout

Gunakan `InternalDashboardLayout`/layout internal existing.

Harus konsisten:
- sidebar;
- top navigation;
- primary blue;
- typography;
- card radius;
- border;
- spacing;
- button;
- icon style;
- responsive behavior.

Active navigation:
- Data Karyawan pada list/detail/tambah/edit.
- Jabatan & Departemen pada halaman master.

---

# 2. Data Karyawan — List

Visual utama mengikuti `data_karyawan.png`.

## Super Admin
Tampilkan:
- heading + deskripsi;
- tombol `Tambah Karyawan`;
- search/filter sesuai PRD;
- tabel;
- pagination;
- action Detail/Edit/Delete-or-Deactivate sesuai rule.

Prioritaskan kolom:
- Nama
- NIK
- Jabatan
- Departemen
- Status Kerja
- Status Keaktifan
- Aksi

Tabel tidak perlu memuat seluruh 17 field.

## Admin
- read-only;
- department-scope;
- tidak ada tombol mutation;
- NIK dan field sensitif tidak ada pada payload/tabel.

## User
- self-only;
- tidak perlu list multi-row/search/filter;
- halaman dapat menampilkan record dirinya dengan style Employee yang sama.

---

# 3. Search / Filter / Pagination

## Super Admin
Search:
- Nama
- NIK

Filter:
- Jabatan
- Departemen
- Status Keaktifan
- Status Kerja

## Admin
Search:
- Nama

Filter:
- Jabatan
- Status Keaktifan
- Status Kerja

Departemen locked ke scope user.

## Pagination
- server-side;
- 15 rows/page;
- preserve query/filter saat pindah page;
- default sort Nama A–Z.

---

# 4. Tambah Karyawan

Agent membuat halaman berdasarkan design language references.

Gunakan halaman penuh, bukan modal kecil, karena field banyak.

Kelompok form:

## Data Pribadi
- Nama
- NIK
- Jenis Kelamin
- Tempat Lahir
- Tanggal Lahir
- Agama
- Status Perkawinan
- Pendidikan
- Alamat
- No. HP

## Data Pekerjaan
- Jabatan
- Departemen
- Status Kerja
- Status Keaktifan
- Tanggal Masuk
- Tanggal Keluar

## Dokumen
- Foto KTP

Behavior:
- submit disabled saat processing;
- backend validation;
- field error inline;
- input tidak hilang saat invalid;
- dropdown master dari backend;
- departemen boleh kosong;
- tanggal keluar boleh kosong;
- setelah sukses → Detail Karyawan.

Jangan tambahkan username/PIN/role pada form.

---

# 5. Edit Karyawan

Visual diturunkan dari Tambah Karyawan.

Perbedaan:
- prefilled;
- Foto KTP existing ditampilkan hanya kepada Super Admin;
- upload baru opsional;
- Save Changes;
- Cancel kembali ke Detail;
- setelah sukses → Detail Karyawan.

---

# 6. Detail Karyawan

Agent membuat halaman berdasarkan reference.

Gunakan card/section:
1. Data Pribadi
2. Data Pekerjaan
3. Kontak
4. Dokumen
5. Akun Sistem

Super Admin:
- semua data;
- preview Foto KTP;
- tombol Edit;
- action Nonaktifkan/Karyawan Keluar;
- conditional Delete bila eligible.
- melihat section Akun Sistem;
- membuat akun jika belum tersedia;
- melihat username, role, status akun, dan status wajib ganti PIN;
- mengaktifkan/nonaktifkan akun sesuai status Karyawan.

Admin:
- field non-sensitif scoped;
- tanpa mutation.

User:
- field non-sensitif self-only;
- tanpa mutation.

Admin/User tidak menerima account-management payload atau action.

## 6.1 Akun Sistem — Belum Memiliki Akun

Tampilkan kepada Super Admin:

- status `Belum memiliki akun`;
- role hasil mapping Jabatan bila tersedia;
- pesan `Role untuk jabatan ini belum ditentukan` bila Jabatan belum memiliki mapping;
- tombol `Buat Akun` hanya bila role dapat ditentukan.

Create Account menggunakan modal/compact form pada Detail Karyawan dengan:

- identitas Nama dan Jabatan read-only;
- Role read-only dari backend;
- Username;
- PIN Awal 6 digit;
- Konfirmasi PIN;
- tombol Batal dan Buat Akun.

Browser tidak mengirim `role_id`. Error duplikasi username, akun kedua, PIN, dan Jabatan tanpa mapping ditampilkan inline/di area form tanpa menghilangkan input.

## 6.2 Akun Sistem — Sudah Memiliki Akun

Tampilkan kepada Super Admin:

- Username;
- Role;
- status Aktif/Nonaktif;
- status `Wajib ganti PIN` atau `PIN sudah diganti`.

Action minimum:

- Nonaktifkan Akun untuk akun aktif;
- Aktifkan Akun untuk akun nonaktif hanya bila Karyawan aktif.

Jika Karyawan nonaktif, tombol Aktivasi disabled dan UI menjelaskan bahwa master Karyawan harus aktif. Tidak ada action lihat PIN, lihat hash, atau delete akun.

---

# 6.3 Ganti PIN Pertama

Halaman auth sederhana yang konsisten dengan Login:

- heading `Ganti PIN`;
- informasi bahwa PIN sementara harus diganti;
- PIN Baru;
- Konfirmasi PIN;
- submit `Simpan PIN`;
- logout tetap tersedia.

Behavior:

1. login dengan akun `must_change_pin = true` diarahkan ke halaman ini;
2. akses URL internal lain dialihkan oleh middleware backend;
3. PIN tepat 6 digit dan konfirmasi sama;
4. tombol disabled selama request;
5. validasi inline tanpa menghilangkan kedua input kecuali setelah sukses;
6. setelah sukses, PIN disimpan hash, `must_change_pin = false`, lalu redirect `/dashboard`.

---

# 7. Foto KTP

- jangan tampilkan di list;
- Detail: preview hanya Super Admin;
- Edit: existing file + replace;
- jika null → placeholder `Belum ada dokumen`;
- bukan avatar;
- jangan expose URL/file kepada Admin/User.

---

# 8. Delete / Deactivate UX

## Hard Delete
Jika eligible:
- confirmation dialog;
- jelaskan permanen;
- backend cek dependency lagi;
- jangan mengandalkan eligibility frontend.

Jika tidak eligible:
- jangan tawarkan hard delete atau tampilkan disabled state yang jelas;
- arahkan ke Nonaktifkan.

## Nonaktifkan
- confirmation;
- jelaskan dampak terhadap Absensi/login;
- setelah sukses status badge berubah;
- histori tetap ada.

## Karyawan Keluar
- minta Tanggal Keluar;
- validasi;
- status menjadi nonaktif;
- User existing ikut inactive.

---

# 9. Jabatan & Departemen

Visual mengikuti `departemen_jabatan.png`.

Desktop:
- dua card berdampingan:
  - Data Jabatan
  - Data Departemen

Action:
- Tambah;
- Edit;
- Delete.

Tambah/Edit boleh menggunakan modal compact yang konsisten dengan reference karena field hanya satu nama.

Delete:
- confirmation;
- jika masih dipakai → backend menolak + tampilkan message.

Tablet/mobile:
- cards stack;
- action tetap accessible;
- tabel/card tidak overflow.

---

# 10. Loading / Empty / Error

Loading:
- skeleton/processing state ringan;
- jangan layout shift besar.

Empty:
- bedakan database kosong vs filter kosong;
- tombol reset filter jika relevan.

Unauthorized:
- backend 403.

Validation:
- inline.

Delete rejected:
- tampilkan alasan bisnis yang jelas.

---

# 11. Responsive

Desktop adalah reference utama.

Tablet:
- filters wrap;
- table horizontal scroll bila perlu;
- dua master cards stack jika ruang tidak cukup.

Mobile:
- form single-column;
- action stack/wrap;
- tabel dapat scroll horizontal;
- jangan mengubah permission/data scope.

---

# 12. Visual QA

Agent wajib membandingkan halaman aktual dengan:
- `data_karyawan.png`
- `departemen_jabatan.png`

Untuk Tambah/Edit/Detail:
- gunakan style yang sama dengan reference;
- jangan mengarang tema baru;
- dokumentasikan perbedaan visual yang sengaja dibuat.

---

# 13. Post-Implementation

Wajib:
- authorization tests;
- field-leakage tests Admin/User;
- CRUD tests;
- delete constraint tests;
- regression Dashboard Home;
- regression Absensi;
- production build;
- visual QA;
- update `LOG.md`.

---

# 14. Route Behavior Akun

- create/manage account hanya melalui route Employee yang dilindungi `auth + active + super_admin`;
- halaman dan submit Ganti PIN memerlukan `auth + active` tetapi dikecualikan dari forced-PIN redirect;
- logout selalu tersedia bagi authenticated user;
- route internal lain tidak boleh dapat dibypass selama `must_change_pin = true`.
