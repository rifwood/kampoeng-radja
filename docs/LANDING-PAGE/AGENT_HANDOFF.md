# AGENT_HANDOFF.md
## Brief Kerja Agent — Fase 1 Landing Page Kampoeng Radja

Dokumen ini digunakan sebagai brief operasional saat pekerjaan Fase 1 diserahkan kepada coding agent.

> Dokumen ini **tidak menggantikan** dokumentasi global maupun dokumentasi Fase 1.
>
> Agent tetap wajib membaca seluruh dokumen yang relevan sebelum menulis kode.

---

# 1. Tujuan Fase 1

Fase 1 berfokus pada:

> **Landing Page / Company Profile publik Kampoeng Radja**

Tujuan utama:

- mengimplementasikan desain Landing Page berdasarkan Figma approved;
- menyajikan informasi publik Kampoeng Radja;
- membangun frontend yang responsive;
- menggunakan aset Figma sementara jika aset produksi resmi belum tersedia;
- menyiapkan data/content management hanya sejauh yang memang termasuk scope Fase 1.

Fase ini **tidak mencakup implementasi Sistem KPI Karyawan atau Sistem Closing Event Marketing**.

---

# 2. Source of Truth

Agent wajib memahami bahwa setiap jenis keputusan memiliki source of truth yang berbeda.

## Requirement dan Business Logic

Prioritas:

1. `GLOBAL/PROJECT_CONTEXT.md`
2. `GLOBAL/ARCHITECTURE.md`
3. `LANDING-PAGE/PRD.md`
4. dokumentasi Fase 1 lain yang relevan

## Visual

Prioritas:

1. **Figma approved**
2. `LANDING-PAGE/FIGMA.md`
3. `LANDING-PAGE/FIGMA_ACCURACY.md`
4. `LANDING-PAGE/UI_SPEC.md`
5. `GLOBAL/BRAND_GUIDELINE.md`

> Untuk keputusan visual, Figma approved adalah source of truth utama.

---

# 3. Protokol Figma-First — Wajib Sebelum Coding UI

Sebelum mulai mengimplementasikan section atau halaman yang memiliki desain Figma, agent wajib:

1. membuka `LANDING-PAGE/FIGMA.md`;
2. menemukan page/frame/node Figma yang sesuai;
3. memastikan frame yang digunakan merupakan versi approved/current;
4. menghubungkan section yang akan dikerjakan dengan node/frame Figma;
5. menginspeksi ukuran frame;
6. menginspeksi hierarchy layer;
7. menginspeksi typography;
8. menginspeksi spacing dan alignment;
9. menginspeksi warna;
10. menginspeksi radius, border, shadow, dan visual state;
11. menginspeksi image/icon/illustration/decorative element;
12. membaca `FIGMA_ACCURACY.md`;
13. baru mulai coding.

Agent **tidak boleh memulai UI hanya berdasarkan gambaran umum PRD** jika Figma dapat diakses.

---

# 4. Pemetaan Section ke Figma

Sebelum coding, setiap section yang akan dikerjakan harus memiliki referensi yang jelas di `FIGMA.md`.

Contoh konseptual:

```text
Page: Landing Page
Frame: Home / Desktop
Node: Hero Section
Viewport: 1440
```

atau:

```text
Page: Landing Page
Frame: Wahana / Mobile
Node: Filter Section
Viewport: 390
```

Nama aktual mengikuti file Figma.

Jika section belum tercatat di `FIGMA.md`, agent harus memperbarui mapping tersebut sebelum atau bersamaan dengan pengerjaan.

---

# 5. Aturan Jika Figma Tidak Bisa Diakses

Jika file/frame/node Figma tidak dapat diakses:

1. jangan mengklaim hasil sesuai Figma;
2. jangan mengklaim pixel-perfect;
3. gunakan fallback berdasarkan dokumentasi tertulis;
4. pertahankan perubahan seminimal mungkin;
5. tulis blocker di `TODO.md`.

Gunakan status seperti:

```text
[BLOCKED: FIGMA ACCESS]
```

atau:

```text
[PERLU KLARIFIKASI]
```

sesuai kasus.

Agent harus menjelaskan bagian mana yang belum dapat diverifikasi secara visual.

---

# 6. Aset Figma Sementara

Jika Figma menyediakan asset yang dibutuhkan dan asset produksi resmi belum tersedia:

> gunakan asset Figma sebagai asset sementara.

Tandai pada dokumentasi asset dengan status:

```text
[FIGMA SEMENTARA]
```

Contoh:

```text
hero-home.webp — [FIGMA SEMENTARA]
```

Asset Figma sementara dapat meliputi:

- logo;
- foto;
- illustration;
- icon;
- background;
- decorative element;
- hero image;
- wahana image;
- event/gallery image.

Agent tidak boleh mengganti asset Figma dengan stock image acak jika asset yang benar tersedia.

---

# 7. Scope yang Tidak Boleh Dilanggar

Agent hanya mengerjakan requirement Fase 1 yang telah disetujui.

Agent tidak boleh:

- mengerjakan KPI;
- mengerjakan Closing Event Marketing;
- membuat fitur internal spekulatif;
- membuat halaman baru hanya berdasarkan asumsi;
- membuat panel admin di luar kebutuhan PRD;
- menambah business rule sendiri.

Jumlah halaman publik, nama navigasi, section, dan route final mengikuti:

1. `PRD.md`
2. Figma approved
3. `USER_FLOW.md`

Dokumen handoff ini **tidak mengunci jumlah halaman secara global**.

---

# 8. Navbar dan Footer

Struktur navbar/footer harus mengikuti Figma dan PRD.

Agent tidak boleh mengunci aturan seperti:

```text
navbar selalu empat item
login selalu footer
```

hanya berdasarkan versi requirement lama.

Jika requirement terbaru memang menetapkan aturan tersebut, implementasikan.

Jika Figma dan PRD berbeda, gunakan hierarki source of truth dan catat konflik bila menyangkut fungsi.

---

# 9. Konten Belum Tersedia

Untuk teks atau data perusahaan yang belum tersedia:

> jangan mengarang.

Gunakan placeholder yang jelas.

Contoh:

```text
[PLACEHOLDER: Deskripsi Perusahaan]
```

atau data mock yang eksplisit bila hanya diperlukan untuk menguji layout.

Konten final mengikuti `CONTENT.md`.

---

# 10. Data Dinamis

Tidak semua elemen yang tampil di Figma harus dibuat dinamis.

Agent harus membaca PRD untuk menentukan data mana yang:

- dinamis;
- dikelola admin;
- static;
- presentational;
- sementara.

Hanya data yang memang termasuk requirement content management yang harus dihubungkan ke database.

Jangan membangun CMS berlebihan.

---

# 11. Filter Wahana

Jika fitur filter Wahana masih tercantum sebagai requirement aktif di PRD:

- implementasikan berdasarkan logic PRD;
- gunakan label/category yang benar;
- pastikan state visual mengikuti Figma;
- behavior reset/toggle mengikuti UI spec.

Logika seperti:

```text
AND
OR
grouped filter
```

tidak boleh ditentukan oleh dokumen handoff jika PRD sudah berubah.

PRD adalah source of truth behavior.

---

# 12. Galeri Event

Jika sorting/filtering Galeri Event termasuk requirement aktif:

implementasikan sesuai PRD dan UI spec.

Jangan menambahkan:

- sorting;
- filter;
- pagination;
- infinite scroll;

hanya karena dianggap umum untuk galeri.

---

# 13. Responsive

Agent tidak boleh menggunakan daftar viewport generik sebagai pengganti Figma.

Prioritas responsive:

1. frame Figma yang tersedia;
2. `RESPONSIVE.md`;
3. viewport QA tambahan yang relevan.

Jika desain memiliki frame:

- Desktop;
- Tablet;
- Mobile;

ketiganya harus diperiksa.

Viewport QA dapat menggunakan ukuran yang sama dengan frame Figma.

Contoh:

```text
Desktop Figma: 1440px
Tablet Figma: 768px
Mobile Figma: 390px
```

Gunakan ukuran aktual dari desain, bukan asumsi dari contoh ini.

---

# 14. Urutan Eksekusi Agent

Agent disarankan bekerja dalam urutan berikut:

## Tahap 1 — Pahami Scope

1. baca `docs/README.md` jika tersedia;
2. baca seluruh dokumentasi GLOBAL;
3. baca seluruh dokumentasi Fase 1 sesuai `AGENT_RULES.md`;
4. pahami task yang diberikan.

---

## Tahap 2 — Audit Project Existing

Sebelum membuat file baru:

- periksa route;
- periksa Pages;
- periksa Components;
- periksa Layouts;
- periksa controller;
- periksa model;
- periksa migration;
- periksa package;
- periksa asset existing;
- periksa konfigurasi Tailwind;
- periksa font;
- periksa auth existing.

Jangan menduplikasi fondasi yang sudah ada.

---

## Tahap 3 — Mapping Figma

Sebelum coding UI:

- buka Figma;
- pilih frame approved;
- mapping section/node;
- catat viewport;
- cek asset;
- cek responsive frame;
- baca `FIGMA_ACCURACY.md`.

---

## Tahap 4 — Implementasi Fondasi yang Benar-Benar Dibutuhkan

Fondasi dapat meliputi:

- layout;
- navbar;
- footer;
- shared components;
- font loading;
- brand token yang benar-benar reusable;
- asset structure.

Jangan otomatis membuat:

- `BaseButton`;
- `BaseCard`;
- modal global;
- design system besar;

sebelum ada kebutuhan nyata.

---

## Tahap 5 — Implementasi Halaman

Bangun halaman/section berdasarkan urutan task.

Untuk setiap section:

1. cek node Figma;
2. implementasikan;
3. cek responsive;
4. cek asset;
5. lakukan visual QA;
6. perbaiki selisih yang terlihat.

---

## Tahap 6 — Data / Content Management

Bangun backend/database hanya untuk requirement yang sudah jelas.

Jangan membuat tabel hanya karena suatu entity disebut secara konseptual di `ARCHITECTURE.md`.

---

## Tahap 7 — Verification

Verifikasi:

- build;
- runtime;
- route;
- interaction;
- data state;
- empty state;
- responsive;
- accessibility dasar;
- asset;
- visual accuracy.

---

## Tahap 8 — Dokumentasi Hasil

Perbarui jika tersedia:

- `TODO.md`;
- `DELIVERY_CHECKLIST.md`;
- `ASSETS.md`;
- `REFERENCE.md`.

Catat blocker/asumsi yang relevan.

---

# 15. Visual QA — Wajib

Setelah UI selesai, agent wajib membandingkan implementasi dengan Figma.

Periksa minimal:

- viewport;
- overall layout;
- container width;
- section height;
- alignment;
- gap;
- padding;
- typography;
- line wrapping;
- color;
- border;
- radius;
- shadow;
- image crop;
- icon sizing;
- illustration;
- decorative element;
- navbar;
- footer;
- responsive transformation.

Jika memungkinkan, gunakan screenshot hasil browser dan bandingkan dengan Figma pada viewport yang sama.

---

# 16. Klaim Pixel-Perfect

Agent **tidak boleh** menyebut hasil:

```text
pixel-perfect
100% identical
100% sama dengan Figma
```

kecuali visual QA telah dilakukan.

Jika visual QA belum dilakukan, gunakan deskripsi faktual seperti:

```text
Implementasi telah dibuat berdasarkan frame Figma X, tetapi belum diverifikasi dengan screenshot comparison.
```

---

# 17. Output yang Diharapkan dari Agent per Task

Setelah menyelesaikan task, laporan agent minimal berisi:

## Implemented

- halaman/section/fitur yang dikerjakan;
- file utama yang berubah.

## Figma Reference

- page;
- frame;
- node/section;
- viewport.

## Asset

- asset Figma yang digunakan;
- asset production yang digunakan;
- asset yang masih `[FIGMA SEMENTARA]`.

## Verification

- build/test yang dijalankan;
- viewport yang diperiksa;
- interaction yang diuji;
- visual QA status.

## Remaining

- TODO;
- blocker;
- perbedaan visual yang masih ada;
- content/asset resmi yang masih dibutuhkan.

---

# 18. Content Intake

Jika konten atau asset resmi belum tersedia, agent boleh menggunakan:

`CONTENT_INTAKE_TEMPLATE.md`

jika file tersebut tersedia.

Namun agent tidak harus menunggu seluruh asset produksi jika Figma sudah menyediakan asset sementara yang dapat digunakan.

---

# 19. Prioritas Konflik

Gunakan aturan berikut.

## Business / Architecture

```text
PROJECT_CONTEXT
      ↓
ARCHITECTURE
      ↓
PRD
```

## Visual

```text
FIGMA APPROVED
      ↓
FIGMA.md
      ↓
FIGMA_ACCURACY.md
      ↓
UI_SPEC.md
      ↓
BRAND_GUIDELINE.md
```

Jika konflik menyangkut business function, data, role, security, atau scope:

> jangan memutuskan sendiri jika dampaknya besar.

Catat pada `TODO.md`.

Jika konflik hanya berupa nilai visual dan Figma approved jelas:

> ikuti Figma.

---

# 20. Larangan Utama

Agent dilarang:

- coding UI tanpa memeriksa Figma jika Figma tersedia;
- redesign;
- mengganti asset Figma;
- membuat section baru;
- menghilangkan section;
- mengubah font;
- mengubah spacing secara generik;
- memaksakan component abstraction;
- menambah dependency tanpa kebutuhan;
- membuat database spekulatif;
- mengerjakan fase berikutnya;
- mengarang konten perusahaan;
- menyatakan pixel-perfect tanpa QA.

---

# 21. Definition of Ready untuk Task UI

Task UI dianggap siap dikerjakan jika minimal tersedia:

- PRD yang relevan;
- Figma atau fallback terdokumentasi;
- mapping frame/node yang dapat diidentifikasi;
- asset yang tersedia atau status asset jelas.

Jika salah satu belum ada, agent tetap boleh mengerjakan bagian yang tidak terblokir dan mencatat blocker secara eksplisit.

---

# 22. Definition of Done untuk Handoff Task

Task dianggap selesai jika:

- [ ] Requirement task terpenuhi
- [ ] Scope tidak melebar
- [ ] Figma reference tercatat
- [ ] Asset sesuai
- [ ] Responsive diuji
- [ ] Interaction utama diuji
- [ ] Build/runtime valid
- [ ] Visual QA dilakukan jika Figma dapat diakses
- [ ] Selisih visual signifikan diperbaiki
- [ ] TODO/blocker diperbarui
- [ ] Tidak ada klaim pixel-perfect tanpa bukti QA

---

# 23. Prinsip Akhir

Untuk Fase 1:

> **Agent bukan designer ulang.**

Tugas agent adalah menerjemahkan requirement dan Figma approved menjadi implementasi yang akurat, responsive, maintainable, dan dapat diverifikasi.

Jika desain tersedia:

> **inspect first, implement second, compare third.**
