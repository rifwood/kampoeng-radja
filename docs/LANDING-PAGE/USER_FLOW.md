# USER_FLOW.md
## Alur Pengguna — Fase 1 Landing Page Kampoeng Radja

Dokumen ini mengatur **alur pengguna, perpindahan halaman, trigger, hasil aksi, dan behavior utama**.

> **Prinsip utama:**  
> User Flow mengunci **apa yang dilakukan pengguna dan apa hasilnya**.
>
> Presentasi visual setiap langkah mengikuti:
>
> 1. Figma approved
> 2. `UI_SPEC.md`
> 3. `RESPONSIVE.md` untuk tablet/mobile
>
> Dokumen ini tidak menentukan ukuran, bentuk, warna, posisi, atau layout control jika Figma sudah menentukannya.

---

# 1. Tipe Pengguna Fase 1

## 1.1 Guest / Pengunjung Publik

Guest adalah pengunjung website tanpa autentikasi.

Akses:

- Beranda
- Tentang Kami
- Wahana
- Galeri Event
- interaksi publik yang termasuk scope

Guest tidak dapat mengelola konten.

---

## 1.2 Karyawan Internal

Karyawan menggunakan autentikasi untuk masuk ke area internal.

Role dasar global:

- Super Admin
- Admin
- User

Detail pemetaan jabatan mengikuti:

`GLOBAL/PROJECT_CONTEXT.md`

Pada Fase 1, kebutuhan internal hanya sejauh:

- autentikasi yang diperlukan;
- pengelolaan konten publik oleh role yang berhak.

Sistem KPI dan Closing Event Marketing belum termasuk scope Fase 1.

---

# 2. Entry Point Guest

Pengguna dapat masuk melalui:

- homepage;
- search engine;
- direct URL;
- shared link;
- campaign/link eksternal;
- halaman publik tertentu.

Tidak ada requirement bahwa semua user harus masuk melalui Beranda terlebih dahulu.

Setiap halaman publik harus tetap memiliki navigasi yang memungkinkan user berpindah halaman.

---

# 3. Alur Navigasi Utama Guest

```text
[Masuk ke salah satu halaman publik]
              │
              ▼
      [Navigasi Publik]
              │
    ┌─────────┼─────────┬──────────────┐
    ▼         ▼         ▼              ▼
 Beranda   Tentang    Wahana      Galeri Event
              │
              └──── kembali/pindah melalui navigasi
```

Navigasi visual mengikuti Figma approved.

---

# 4. Alur Beranda

```text
[Buka Beranda]
      │
      ▼
[Lihat konten Beranda sesuai urutan Figma/PRD]
      │
      ├──► Interaksi dengan CTA jika tersedia
      │
      ├──► Buka konten Media/Berita jika behavior detail tersedia
      │
      ├──► Buka CTA Promo/Event jika tersedia
      │
      ├──► Buka halaman Wahana dari CTA jika tersedia
      │
      ├──► Gunakan link/interaction Mitra jika tersedia
      │
      ├──► Buka Petunjuk Arah dari section Lokasi
      │
      └──► Akses link Footer / Login
```

Section visual dan urutan final mengikuti Figma approved serta PRD.

---

# 5. Alur Tentang Kami

```text
[Buka Tentang Kami]
      │
      ▼
[Lihat profil/sejarah/visi-misi/struktur sesuai scope]
      │
      └──► Pindah halaman melalui navigasi/footer
```

Urutan kronologis sejarah harus tetap dipertahankan.

Presentasi visual timeline, card, tree, atau layout lain mengikuti Figma.

---

# 6. Alur Wahana — Default

```text
[Buka halaman Wahana]
      │
      ▼
[Load category/label + data Wahana]
      │
      ▼
[Tampilkan default result]
```

Default result:

> menampilkan seluruh item/foto Wahana yang tersedia untuk guest, tanpa filter aktif.

Jika data kosong:

> tampilkan empty state yang aman sesuai `UI_SPEC.md`.

---

# 7. Alur Wahana — Pemilihan Filter

```text
[Default / Current Result]
      │
      ▼
[User pilih label]
      │
      ├──► Label belum aktif → aktifkan
      │
      └──► Label sudah aktif → nonaktifkan
      │
      ▼
[Selection state berubah]
```

User dapat memilih lebih dari satu label.

Selection belum harus mengubah hasil sampai trigger `Cari` dijalankan, sesuai PRD aktif saat ini.

---

# 8. Alur Wahana — Apply Filter

```text
[User memiliki 1+ label aktif]
      │
      ▼
[Klik "Cari"]
      │
      ▼
[Apply filter]
      │
      ▼
[Ambil item yang memiliki SEMUA label aktif]
      │
   ┌──┴────────────┐
   │               │
Ada hasil?       Tidak ada
   │               │
   ▼               ▼
[Tampilkan]   [Empty State]
```

Logika:

> **AND antar seluruh label aktif.**

---

# 9. Alur Wahana — Filter Tanpa Selection

Jika user menekan `Cari` tanpa label aktif:

behavior default:

> tampilkan seluruh item.

Ini ekuivalen dengan kondisi tanpa filter.

---

# 10. Alur Wahana — Reset

```text
[Filter aktif / hasil terfilter]
      │
      ▼
[Klik "Reset"]
      │
      ▼
[Hapus seluruh selected label]
      │
      ▼
[Tampilkan seluruh item]
```

Reset harus menyinkronkan:

- state control;
- state data;
- active indicator.

---

# 11. Alur Wahana — Empty State

Jika filter menghasilkan 0 item:

```text
[Empty State]
      │
      ├──► User ubah pilihan label
      │
      ├──► User klik Reset
      │
      └──► User apply ulang
```

User tidak boleh terjebak pada empty state.

---

# 12. Alur Wahana — Detail / Lightbox

Jika desain final menggunakan preview/detail:

```text
[Klik/tap item Wahana]
      │
      ▼
[Open detail / lightbox]
      │
      ├──► Lihat media/info
      │
      └──► Close
              │
              ▼
       [Kembali ke grid]
```

Setelah modal ditutup, state filter dan posisi halaman sebaiknya tetap dipertahankan.

Jika detail/lightbox tidak termasuk desain final:

> flow ini tidak wajib.

---

# 13. Alur Galeri Event — Default

```text
[Buka Galeri Event]
      │
      ▼
[Load event]
      │
      ▼
[Apply default sorting]
      │
      ▼
[Tampilkan daftar]
```

Default sorting:

`[PERLU DITETAPKAN]`

Rekomendasi sementara:

> `Terbaru`

sampai stakeholder menetapkan keputusan final.

---

# 14. Alur Galeri Event — Sorting

```text
[Daftar Event]
      │
      ├──► Pilih "Terbaru"
      │       ↓
      │   tanggal DESC
      │
      └──► Pilih "Terlama"
              ↓
          tanggal ASC
```

Sorting hanya mengubah urutan, bukan isi data.

---

# 15. Alur Galeri Event — Empty State

Jika tidak ada event:

- halaman tidak error;
- tampilkan empty state minimal;
- navigation tetap dapat digunakan.

Jangan membuat event fiktif untuk mengisi state kosong.

---

# 16. Alur Galeri Event — Detail / Lightbox

Jika desain final menggunakan modal/detail:

```text
[Klik event/foto]
      │
      ▼
[Open detail/lightbox]
      │
      └──► Close
             │
             ▼
       [Kembali ke daftar]
```

Sorting aktif sebaiknya tetap dipertahankan setelah modal ditutup.

---

# 17. Alur Media & Berita

## Kondisi Saat Ini

Section list/card termasuk scope Beranda.

Halaman detail:

`[PERLU KEPUTUSAN]`

### Jika Detail Masuk Scope

```text
[Klik item berita]
      │
      ▼
[Halaman Detail Media & Berita]
```

Gunakan node Figma `1:650`.

### Jika Detail Tidak Masuk Scope

- jangan mengarahkan user ke halaman kosong;
- card dapat non-clickable atau mengikuti behavior lain yang disetujui.

---

# 18. Alur Footer

Dari seluruh halaman publik:

```text
[Footer]
   │
   ├──► Social link → platform resmi
   │
   ├──► Contact action jika tersedia
   │
   ├──► Maps/location jika tersedia
   │
   └──► Login → autentikasi internal
```

External link harus menggunakan URL resmi.

---

# 19. Alur Login

```text
[Klik Login]
      │
      ▼
[Halaman Login]
      │
      ▼
[Input credential]
      │
      ▼
[Submit]
      │
   ┌──┴─────────┐
   │            │
Valid         Invalid
   │            │
   ▼            ▼
[Authenticated] [Error]
```

Jika invalid:

- tetap di login;
- tampilkan error yang sesuai;
- jangan mengungkap informasi sensitif berlebihan.

---

# 20. Destination Setelah Login

Sistem KPI dan Closing Event Marketing belum dibangun pada Fase 1.

Karena itu destination setelah login **tidak boleh diasumsikan sebagai dashboard KPI/Closing Event**.

Status:

`[PERLU KEPUTUSAN IMPLEMENTASI]`

Pilihan yang dapat digunakan jika diperlukan:

- area admin content untuk Admin/Super Admin;
- halaman internal netral;
- halaman informasi bahwa modul tertentu belum tersedia.

Destination final harus mengikuti scope role dan implementasi auth aktual.

> Jangan membuat placeholder dashboard KPI seolah merupakan requirement Fase 1.

---

# 21. Alur Admin / Super Admin

Untuk role yang memiliki hak content management:

```text
[Login berhasil]
      │
      ▼
[Area Content Management]
      │
      ├──► Wahana
      │       ├── Category
      │       ├── Label
      │       ├── Item/Foto
      │       └── Assignment Label
      │
      ├──► Galeri Event
      │
      ├──► Media & Berita jika aktif
      │
      ├──► Promo jika aktif
      │
      └──► Mitra jika aktif
```

Scope aktual harus sinkron dengan PRD.

---

# 22. Alur User Role Biasa

Role `user` tidak otomatis memiliki akses content management.

Jika user biasa login pada Fase 1:

destination/fitur yang tersedia:

`[PERLU KEPUTUSAN]`

Jangan memberikan akses admin secara default.

---

# 23. Authorization

Minimal:

```text
Guest
→ public only

Admin
→ content management sesuai scope

Super Admin
→ content management sesuai scope

User
→ tidak otomatis content management
```

Permission granular Admin vs Super Admin dapat disederhanakan pada Fase 1 jika belum dibutuhkan, tetapi access control minimum tetap harus ada.

---

# 24. Alur CRUD Admin — Pola Umum

```text
[List]
  │
  ├──► Create
  │      ↓
  │    Validate
  │      ↓
  │    Save
  │
  ├──► Edit
  │      ↓
  │    Validate
  │      ↓
  │    Save
  │
  └──► Delete
         ↓
      Confirmation
         ↓
       Delete
```

Error validation tidak boleh menghilangkan input user tanpa alasan.

---

# 25. Alur Upload Media

```text
[Choose file]
      │
      ▼
[Validate type/size]
      │
   ┌──┴───────┐
   │          │
Valid       Invalid
   │          │
   ▼          ▼
[Upload]   [Show error]
   │
   ▼
[Save reference]
```

Jika assignment label diperlukan:

```text
Upload Wahana
      ↓
Choose label(s)
      ↓
Save
```

---

# 26. Alur Delete Category / Label

Karena category/label dapat memiliki relasi:

sebelum delete:

- cek dependency;
- gunakan confirmation;
- jangan menyebabkan orphan data atau error.

Behavior final delete relation mengikuti architecture/database rules.

Jika aturan cascade belum diputuskan:

> catat di `TODO.md`.

---

# 27. State Loading

Untuk page/data async:

```text
Request
  │
  ├── Loading
  │
  ├── Success
  │
  └── Error
```

Visual state mengikuti Figma jika tersedia.

Jika tidak:

> gunakan fallback minimal.

---

# 28. Error Recovery

Jika request gagal:

user harus tetap dapat:

- retry jika relevan;
- kembali ke navigation;
- mempertahankan data form bila memungkinkan.

Jangan membuat blank page/crash.

---

# 29. Responsive User Flow

Tablet/mobile tidak memiliki Figma frame.

Flow bisnis tetap sama.

Yang boleh berubah hanya:

- placement control;
- grouping;
- navigation pattern;
- modal sizing;
- layout.

Tidak boleh berubah:

- fungsi;
- available action;
- filter logic;
- sorting logic;
- access rule.

---

# 30. Navigation State

Saat user berada pada halaman publik tertentu:

active state dapat menunjukkan halaman saat ini jika desain menggunakannya.

Pada responsive menu:

state active tetap harus dapat dikenali.

---

# 31. Browser Back / Forward

Navigasi Inertia/browser sebaiknya mempertahankan behavior web normal.

Khusus filter/sort:

persistensi state setelah browser Back bergantung implementasi.

Jika state penting untuk UX, boleh dipertahankan melalui:

- query string;
- history state;
- Inertia remembered state.

Tidak otomatis wajib.

---

# 32. URL Filter / Sort

Filter Wahana dan sorting Event tidak diwajibkan masuk query string pada Fase 1.

Jika dibutuhkan untuk shareable URL atau history consistency:

> keputusan tersebut harus dicatat sebelum implementasi.

---

# 33. Logout

Untuk user authenticated:

```text
[Klik Logout]
      │
      ▼
[Session selesai]
      │
      ▼
[Redirect ke halaman publik/login]
```

Destination mengikuti implementasi auth yang disetujui.

---

# 34. Session Expired

Jika session expired di area internal:

- redirect ke login;
- jangan kehilangan security boundary;
- tampilkan feedback yang sesuai bila memungkinkan.

---

# 35. Flow yang Tidak Termasuk Fase 1

Tidak termasuk:

- penilaian KPI;
- daily report KPI;
- approval KPI;
- Closing Event Marketing;
- marketing workflow;
- employee operational workflow.

Jangan menambahkan flow tersebut ke Fase 1.

---

# 36. Keputusan Terbuka

## 36.1 Detail Media & Berita

`[PERLU KEPUTUSAN]`

- masuk scope;
- atau tidak.

---

## 36.2 Default Sort Galeri Event

`[PERLU KEPUTUSAN]`

Rekomendasi sementara:

`Terbaru`.

---

## 36.3 Struktur Organisasi

`[PERLU KEPUTUSAN]`

- statis;
- atau dinamis melalui admin.

---

## 36.4 Destination User Setelah Login

`[PERLU KEPUTUSAN]`

Terutama untuk role `user` karena sistem KPI/Closing belum tersedia.

---

# 37. Acceptance Criteria — Guest Flow

- [ ] Semua halaman publik dapat diakses tanpa login
- [ ] Navigation antar halaman bekerja
- [ ] Wahana default menampilkan data
- [ ] Multi-label selection bekerja
- [ ] AND filter bekerja
- [ ] Cari bekerja
- [ ] Reset bekerja
- [ ] Empty state aman
- [ ] Sorting Event bekerja
- [ ] Footer links valid
- [ ] Login dapat diakses sesuai requirement

---

# 38. Acceptance Criteria — Admin Flow

- [ ] Login berhasil untuk akun valid
- [ ] Akun tidak valid ditolak
- [ ] Admin/Super Admin dapat mengakses content management sesuai scope
- [ ] User biasa tidak otomatis memiliki akses admin
- [ ] CRUD utama bekerja
- [ ] Validation bekerja
- [ ] Delete memiliki confirmation
- [ ] Upload media bekerja
- [ ] Unauthorized access ditolak

---

# 39. Prinsip Akhir

> **USER_FLOW menentukan perjalanan dan hasil aksi pengguna.**

> **Figma menentukan bagaimana langkah tersebut terlihat.**

> **UI_SPEC menentukan behavior detail yang tidak terlihat.**

Agent tidak boleh mengubah flow bisnis hanya demi menyesuaikan layout, dan tidak boleh menggunakan user flow sebagai alasan untuk membuat visual yang tidak ada di Figma.
