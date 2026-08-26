# AGENT_RULES.md

# Aturan Kerja Agent Coding — Global

**Berlaku untuk seluruh produk dalam proyek Kampoeng Radja.**

Dokumen ini mengatur bagaimana AI coding agent atau developer bekerja di dalam proyek.

> **PENTING:** Agent tidak boleh mulai menulis atau mengubah kode sebelum membaca seluruh dokumentasi wajib yang relevan dengan scope pekerjaan.

---

# 1. Prinsip Utama

Seluruh pekerjaan agent harus mengikuti prinsip berikut:

1. **Requirement first**
2. **Figma first untuk visual**
3. **Jangan membuat requirement baru**
4. **Jangan melakukan redesign tanpa instruksi**
5. **Jangan mengganti aset yang sudah tersedia di Figma**
6. **Jangan menyebut implementasi pixel-perfect tanpa visual QA**
7. **Jangan mengerjakan scope fase lain**
8. **Semua keputusan harus dapat ditelusuri ke dokumentasi atau desain**

Jika suatu keputusan tidak memiliki dasar yang jelas, agent harus menganggapnya sebagai hal yang belum diputuskan.

---

# 2. Urutan Membaca Dokumentasi

## 2.1 Dokumentasi Global

Sebelum mengerjakan produk apa pun, agent wajib membaca:

1. `AGENTS.md`
2. `LOG.md`
3. `docs/README.md`
4. `docs/GLOBAL/PROJECT_CONTEXT.md`
5. `docs/GLOBAL/TECH_STACK.md`
6. `docs/GLOBAL/BRAND_GUIDELINE.md`
7. `docs/GLOBAL/ARCHITECTURE.md`
8. `docs/GLOBAL/ACCESS_CONTROL.md` jika access/auth relevan
9. `docs/GLOBAL/ACCESS_CONTROL_MATRIX.md` jika access/auth relevan
10. `docs/GLOBAL/AGENT_RULES.md`

Dokumen global memberikan konteks proyek, stack, brand, arsitektur, dan aturan kerja.

---

## 2.2 Dokumentasi Produk

Setelah membaca dokumentasi global, agent harus membaca dokumentasi produk yang sedang dikerjakan.

Untuk **Fase 1 — Landing Page**, urutan baca wajib adalah:

1. `docs/LANDING-PAGE/PRD.md`
2. `docs/LANDING-PAGE/FIGMA.md`
3. `docs/LANDING-PAGE/FIGMA_ACCURACY.md` jika tersedia
4. `docs/LANDING-PAGE/UI_SPEC.md`
5. `docs/LANDING-PAGE/CONTENT.md`
6. `docs/LANDING-PAGE/ASSETS.md`
7. `docs/LANDING-PAGE/USER_FLOW.md`
8. `docs/LANDING-PAGE/COMPONENTS.md`
9. `docs/LANDING-PAGE/RESPONSIVE.md`
10. `docs/LANDING-PAGE/REFERENCE.md`
11. `docs/LANDING-PAGE/TODO.md`

Agent tidak boleh melewati `FIGMA.md` ketika pekerjaan menyangkut frontend atau visual.

Untuk Dashboard Internal, baca `docs/DASHBOARD/README.md`, lalu PRD, permissions, UI spec, dan screenshot/reference modul yang tersedia. Dashboard tidak otomatis menggunakan Figma-first jika handoff visualnya berupa screenshot.

---

# 3. Hierarki Source of Truth

Karena setiap dokumen memiliki fungsi berbeda, konflik harus diselesaikan berdasarkan jenis keputusan.

## 3.1 Requirement Bisnis

Untuk keputusan mengenai:

- fitur;
- alur bisnis;
- hak akses;
- scope produk;
- data;
- aturan operasional;

gunakan urutan prioritas:

1. `PROJECT_CONTEXT.md`
2. `ARCHITECTURE.md` untuk keputusan fondasi arsitektur
3. `PRD.md` produk terkait
4. dokumentasi produk lain yang relevan
5. keputusan terbaru yang sudah terdokumentasi

---

## 3.2 Visual dan Layout

Untuk keputusan visual frontend:

> **Figma approved adalah source of truth utama.**

Figma mengendalikan:

- struktur visual halaman;
- urutan section;
- posisi elemen;
- width dan height;
- grid;
- alignment;
- spacing;
- padding;
- margin;
- typography;
- font size;
- font weight;
- line-height;
- warna;
- border;
- radius;
- shadow;
- icon;
- illustration;
- image;
- background;
- decorative element;
- responsive composition;
- state visual yang memang tersedia dalam desain.

Dokumen berikut membantu menerjemahkan Figma:

- `FIGMA.md`
- `FIGMA_ACCURACY.md`
- `UI_SPEC.md`
- `COMPONENTS.md`
- `RESPONSIVE.md`
- `BRAND_GUIDELINE.md`

Dokumentasi tersebut **tidak boleh digunakan sebagai alasan untuk mengubah visual Figma secara sepihak**.

Jika terdapat konflik antara nilai visual tertulis dan desain Figma approved terbaru, **gunakan desain Figma approved terbaru**, lalu catat ketidaksesuaian dokumentasinya untuk diperbarui.

---

# 4. Aturan Figma Wajib

Untuk setiap pekerjaan frontend yang memiliki desain Figma, agent wajib:

1. membuka file Figma yang tercantum di `FIGMA.md`;
2. menggunakan frame/page/node yang ditentukan;
3. memeriksa struktur layer dan hierarchy;
4. memeriksa ukuran frame;
5. memeriksa spacing dan alignment;
6. memeriksa typography;
7. memeriksa warna;
8. memeriksa radius, border, shadow, dan decorative elements;
9. memeriksa image dan asset yang digunakan;
10. memeriksa desain desktop, tablet, dan mobile yang tersedia;
11. mengimplementasikan berdasarkan desain tersebut;
12. melakukan visual QA setelah implementasi.

Agent **tidak boleh membuat UI hanya berdasarkan screenshot kecil, tebakan, deskripsi umum, atau ingatan visual jika file Figma dapat diakses**.

---

# 5. Larangan Redesign dan Improvisasi Visual

Agent tidak diperbolehkan:

- mengganti layout dengan layout lain yang dianggap lebih modern;
- mengubah posisi section;
- mengubah ukuran komponen karena dianggap lebih proporsional;
- mengganti warna dengan warna lain tanpa dasar;
- mengganti font tanpa instruksi;
- menambah gradient yang tidak ada;
- menambah shadow yang tidak ada;
- menghapus decorative element;
- menyederhanakan desain hanya agar lebih mudah dikodekan;
- mengganti bentuk card;
- mengganti bentuk button;
- menambah section baru;
- menghilangkan section;
- mengganti icon;
- mengubah image crop secara sembarangan;
- mengganti aset Figma dengan placeholder acak;
- mengambil gambar dari internet jika Figma sudah menyediakan aset yang sesuai.

Agent boleh menentukan detail teknis implementasi selama hasil akhirnya tidak mengubah visual approved.

---

# 6. Target Akurasi Implementasi

Target frontend Fase 1 adalah:

> **Visual implementation semirip mungkin dengan Figma approved pada viewport yang setara.**

Agent harus mengejar kesamaan dalam:

- overall composition;
- section height;
- container width;
- spacing;
- alignment;
- typography;
- image sizing;
- image crop;
- icon sizing;
- border radius;
- visual weight;
- relative proportion;
- responsive composition.

Istilah seperti:

- `pixel-perfect`;
- `100% sama`;
- `identik dengan Figma`;

**tidak boleh digunakan sebelum visual QA dilakukan pada frame dan viewport yang sama.**

Jika masih terdapat perbedaan visual yang terlihat, status pekerjaan harus dianggap **belum selesai secara visual**.

---

# 7. Visual QA Wajib

Setelah sebuah halaman selesai diimplementasikan, agent wajib membandingkan hasil implementasi dengan Figma.

Visual QA minimal memeriksa:

- viewport sama dengan frame Figma;
- posisi section;
- ukuran container;
- alignment;
- padding dan gap;
- typography;
- line wrapping;
- image crop;
- ukuran gambar;
- warna;
- radius;
- shadow;
- icon;
- decorative elements;
- header;
- footer;
- responsive behavior.

Jika memungkinkan, lakukan perbandingan screenshot implementasi dengan frame Figma pada ukuran viewport yang sama.

Agent harus memperbaiki perbedaan visual yang signifikan sebelum menyatakan halaman selesai.

---

# 8. Aturan Responsive

Agent **tidak boleh secara otomatis menganggap mobile-first sebagai aturan visual utama** jika desain Figma menentukan pendekatan berbeda.

Implementasi responsive harus mengikuti:

1. frame responsive yang tersedia di Figma;
2. `RESPONSIVE.md`;
3. perilaku layout yang dapat diinferensikan secara aman dari desain.

Jika tersedia desain:

- Desktop;
- Tablet;
- Mobile;

ketiganya harus digunakan sebagai acuan.

Jika hanya sebagian breakpoint tersedia, agent harus mempertahankan intent visual desain dan mencatat asumsi responsive yang diperlukan.

Agent tidak boleh membuat breakpoint atau perubahan layout ekstrem tanpa dasar.

---

# 9. Aturan Aset

Sebelum menggunakan placeholder, agent wajib membaca:

`docs/LANDING-PAGE/ASSETS.md`

Jika suatu aset tersedia di Figma, maka:

> **aset dari Figma harus diprioritaskan sebagai aset implementasi sementara sampai aset produksi resmi tersedia.**

Aset dapat berupa:

- logo;
- foto;
- hero image;
- background;
- illustration;
- icon;
- shape;
- pattern;
- decorative element;
- image wahana;
- image event;
- image galeri.

Agent tidak diperbolehkan mengganti aset tersebut dengan:

- Unsplash;
- Lorem Picsum;
- stock image acak;
- gambar hasil pencarian internet;
- icon library yang berbeda;

kecuali dokumentasi secara eksplisit mengizinkannya.

Aset yang diekspor harus mengikuti aturan format, naming, dan lokasi yang ditentukan di `ASSETS.md`.

---

# 10. Konten yang Belum Lengkap

Agent harus membaca:

- `CONTENT.md`
- `ASSETS.md`

untuk mengetahui konten atau aset yang belum tersedia.

Jika **teks** belum tersedia, gunakan placeholder yang jelas, misalnya:

`[PLACEHOLDER: Visi Perusahaan]`

Agent **tidak boleh mengarang fakta perusahaan**.

Untuk **aset visual**, placeholder hanya digunakan jika:

1. aset tidak tersedia di Figma;
2. aset produksi belum tersedia;
3. dokumentasi mengizinkan penggunaan placeholder.

Jika aset tersedia di Figma, gunakan aset Figma.

---

# 11. Scope Fase

Agent hanya boleh mengerjakan workstream yang sedang aktif dan memiliki requirement cukup.

Saat ini:

- Landing Page / Company Profile aktif tetapi belum selesai;
- Dashboard Internal aktif untuk modul yang memiliki dokumentasi final, termasuk Data Absensi, Kelola Karyawan, dan Closing Event;
- KPI belum aktif; Closing Event aktif hanya sesuai dokumen final `docs/DASHBOARD/CLOSING-EVENT/`.

Jangan memperluas Dashboard ke KPI atau permission granular yang belum diputuskan. Closing Event hanya boleh mengikuti PRD/PERMISSIONS final modulnya.

Fase berikutnya yang belum aktif adalah Sistem KPI Karyawan.

Fondasi yang sudah tersedia di proyek boleh dipertahankan. Keberadaan source tidak otomatis menjadikannya requirement bisnis final; mismatch harus didokumentasikan.

---

# 12. Prinsip Desain Kode

## 12.1 Reusable Tanpa Mengorbankan Visual

Reusable component dianjurkan, tetapi:

> **reusability tidak boleh menyebabkan perubahan visual dari Figma.**

Jangan memaksakan dua desain berbeda masuk ke satu komponen jika hasilnya membuat implementasi menyimpang.

---

## 12.2 Jangan Membuat Abstraksi Terlalu Dini

Hindari membuat:

- design system baru;
- wrapper kompleks;
- generic component berlebihan;
- helper abstraction yang belum diperlukan;

jika hanya memperumit implementasi atau membuat visual lebih sulit dikontrol.

---

## 12.3 Komponen

Sebelum membuat komponen baru, baca:

`COMPONENTS.md`

Komponen yang berulang dapat dibuat reusable.

Komponen spesifik halaman diperbolehkan jika memang dibutuhkan untuk mempertahankan visual Figma.

---

# 13. Data dan Hardcode

Pisahkan antara:

### Data bisnis

Data yang memang harus dikelola dari database harus dibuat dinamis sesuai PRD.

### Data presentasional

Data sementara yang hanya digunakan untuk mereplikasi desain Figma dapat menggunakan data mock/seed sementara jika backend final belum tersedia.

Agent tidak boleh memaksakan seluruh konten menjadi dinamis jika hal tersebut belum termasuk scope pekerjaan.

Sebaliknya, agent tidak boleh meng-hardcode data bisnis yang menurut PRD harus dikelola melalui sistem.

---

# 14. Accessibility

Accessibility tetap harus diperhatikan tanpa merusak desain.

Minimal:

- semantic HTML;
- `alt` untuk image informatif;
- button menggunakan elemen yang benar;
- link menggunakan elemen yang benar;
- keyboard interaction untuk elemen interaktif;
- focus state yang dapat digunakan.

Jika terdapat konflik antara requirement accessibility dan tampilan Figma, implementasikan solusi yang menjaga intent visual sedekat mungkin tanpa menghilangkan accessibility dasar.

---

# 15. Performance

Optimasi performance diperbolehkan selama tidak mengubah tampilan.

Gunakan praktik seperti:

- image compression;
- format image yang tepat;
- lazy loading untuk media yang sesuai;
- responsive image bila diperlukan;
- caching yang sesuai;
- code splitting jika relevan.

Agent tidak boleh menghapus image, video, animation, atau decorative element hanya untuk meningkatkan performance tanpa instruksi.

---

# 16. Penanganan Ambiguitas

Agent harus membedakan antara:

## Ambiguitas Kecil

Contoh:

- nama internal variable;
- nama helper;
- struktur internal component;
- detail implementasi CSS.

Agent boleh mengambil keputusan teknis yang wajar selama tidak mengubah requirement atau visual.

## Ambiguitas Besar

Contoh:

- section baru;
- perubahan layout;
- perubahan alur pengguna;
- permission;
- struktur data;
- business rule;
- konten perusahaan;
- penggantian asset;
- perubahan desain.

Agent **tidak boleh berasumsi sendiri**.

Catat pada:

`TODO.md`

dengan status:

`[PERLU KLARIFIKASI]`

---

# 17. Dokumentasi Asumsi

Jika agent terpaksa membuat asumsi teknis karena informasi tidak tersedia, asumsi tersebut harus:

1. seminimal mungkin;
2. tidak mengubah business requirement;
3. tidak mengubah desain approved;
4. dicatat pada `TODO.md` atau `REFERENCE.md` jika berdampak pada pekerjaan berikutnya.

Jangan menyembunyikan keputusan penting hanya di dalam kode.

---

# 18. Definition of Done — Fitur Umum

Fitur dianggap selesai jika:

- [ ] Requirement PRD yang relevan sudah terpenuhi
- [ ] Tidak ada error aplikasi
- [ ] Tidak ada console error yang relevan
- [ ] State utama berjalan
- [ ] Data kosong tidak menyebabkan crash
- [ ] Accessibility dasar diterapkan
- [ ] TODO terkait sudah diperbarui

---

# 19. Definition of Done — Frontend dengan Figma

Untuk halaman yang memiliki desain Figma, halaman **belum boleh dianggap selesai** sampai seluruh item berikut terpenuhi:

- [ ] Frame Figma yang benar sudah digunakan sebagai acuan
- [ ] Semua section yang ada pada Figma sudah diimplementasikan
- [ ] Tidak ada section tambahan tanpa requirement
- [ ] Layout mengikuti Figma
- [ ] Container mengikuti Figma
- [ ] Alignment mengikuti Figma
- [ ] Typography mengikuti Figma
- [ ] Spacing mengikuti Figma
- [ ] Warna mengikuti Figma
- [ ] Radius/border/shadow mengikuti Figma
- [ ] Icon mengikuti Figma
- [ ] Asset mengikuti Figma atau `ASSETS.md`
- [ ] Image crop dan aspect ratio sesuai
- [ ] Desktop sudah diuji
- [ ] Tablet sudah diuji jika desain tersedia
- [ ] Mobile sudah diuji
- [ ] Visual QA sudah dilakukan pada viewport yang sesuai
- [ ] Perbedaan visual signifikan sudah diperbaiki
- [ ] Tidak ada placeholder visual acak jika aset Figma tersedia
- [ ] `TODO.md` sudah diperbarui

---

# 20. Aturan Sebelum Menyatakan Pekerjaan Selesai

Sebelum melaporkan bahwa pekerjaan selesai, agent harus menjelaskan secara ringkas:

1. halaman/fitur yang diimplementasikan;
2. frame Figma yang digunakan;
3. aset yang digunakan;
4. breakpoint/viewport yang diuji;
5. apakah visual QA sudah dilakukan;
6. apakah masih terdapat perbedaan atau TODO.

Agent tidak boleh menyatakan hasil:

> “pixel-perfect”

atau:

> “100% sama dengan Figma”

tanpa proses pembandingan visual yang dapat dipertanggungjawabkan.

---

# 21. Larangan Agent

Agent dilarang:

- mengerjakan fase yang belum aktif;
- membuat requirement bisnis sendiri;
- melakukan redesign tanpa instruksi;
- mengganti aset Figma secara sepihak;
- menggunakan stock image acak ketika aset Figma tersedia;
- menghilangkan bagian desain agar implementasi lebih mudah;
- mengubah warna/typography tanpa dasar;
- membuat halaman baru tanpa requirement;
- menambah package tanpa kebutuhan jelas;
- melakukan refactor besar di luar scope;
- mengubah struktur database di luar requirement;
- menyatakan visual selesai sebelum QA;
- menyembunyikan asumsi penting.

---

# 22. Prinsip Akhir

Jika terdapat pilihan antara:

**implementasi yang lebih mudah**

dan

**implementasi yang lebih akurat terhadap requirement dan Figma**,

maka untuk Fase 1 agent harus memilih:

> **implementasi yang lebih akurat terhadap requirement dan Figma, selama tetap maintainable dan tidak melanggar keputusan arsitektur proyek.**
