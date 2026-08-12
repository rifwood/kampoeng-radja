# FIGMA_ACCURACY.md
## Protokol Akurasi Figma — Fase 1 Landing Page Kampoeng Radja

Dokumen ini adalah kontrak eksekusi visual untuk seluruh halaman publik Kampoeng Radja yang memiliki desain Figma.

Target implementasi:

> **sedekat mungkin dengan Figma approved (pixel-accurate) pada setiap viewport yang memiliki frame pembanding.**

Dokumen ini **bukan izin untuk membuat interpretasi desain baru**.

---

# 1. Tujuan

Protokol ini dibuat agar agent:

- mengimplementasikan desain berdasarkan inspeksi Figma, bukan tebakan;
- tidak melakukan redesign;
- tidak mengganti aset secara sembarangan;
- menggunakan ukuran dan nilai visual aktual dari Figma;
- melakukan visual QA sebelum menyatakan pekerjaan selesai;
- membedakan viewport tervalidasi dan responsive fallback;
- mencatat seluruh deviasi yang tidak dapat dihindari.

---

# 2. Hierarki Keputusan

## 2.1 Business dan Scope

Untuk:

- fitur;
- halaman;
- data;
- business rule;
- hak akses;
- fungsi;

ikuti:

1. `PRD.md`
2. `GLOBAL/PROJECT_CONTEXT.md`
3. `GLOBAL/ARCHITECTURE.md`
4. dokumen behavior terkait

---

## 2.2 Visual

Untuk keputusan yang terlihat di UI:

1. **Figma approved**
2. `FIGMA.md`
3. dokumen ini
4. `UI_SPEC.md`
5. `RESPONSIVE.md`
6. `GLOBAL/BRAND_GUIDELINE.md`

Figma mengendalikan:

- urutan layout;
- hierarchy;
- container;
- grid;
- sizing;
- spacing;
- typography;
- warna;
- media;
- crop;
- radius;
- border;
- shadow;
- icon;
- decorative element;
- visual state.

---

## 2.3 Behavior yang Tidak Terlihat

Jika behavior tidak dapat ditentukan dari Figma, gunakan:

1. `UI_SPEC.md`
2. `USER_FLOW.md`
3. PRD
4. keputusan yang sudah terdokumentasi

Contoh:

- filter logic;
- sorting;
- modal close behavior;
- keyboard interaction;
- data loading;
- empty state;
- submission flow.

---

# 3. Status Figma

Setiap frame/reference yang digunakan harus memiliki status yang jelas.

Gunakan salah satu:

```text
[APPROVED FOR DEVELOPMENT]
[CURRENT — BELUM APPROVED]
[REFERENCE ONLY]
[DEPRECATED]
[PERLU KONFIRMASI]
```

Agent hanya boleh menggunakan:

`[APPROVED FOR DEVELOPMENT]`

sebagai dasar klaim visual final.

Frame lain boleh dipakai sebagai referensi sementara, tetapi harus dicatat.

---

# 4. Definition of Ready — UI

Sebelum coding suatu halaman/section, minimal harus diketahui:

- page Figma;
- frame;
- node/section;
- viewport/frame size;
- status frame;
- asset yang digunakan;
- breakpoint/frame responsive yang tersedia.

Jika salah satu tidak tersedia tetapi pekerjaan tetap dapat dilakukan:

> kerjakan bagian yang tidak terblokir dan catat blocker.

---

# 5. Prosedur Wajib Sebelum Coding

Untuk setiap halaman atau section:

1. buka referensi Figma dari `FIGMA.md`;
2. pastikan page/frame yang benar;
3. cek status approved;
4. catat ukuran frame;
5. identifikasi node section;
6. inspect hierarchy layer;
7. inspect container;
8. inspect grid/layout;
9. inspect section height;
10. inspect spacing;
11. inspect typography;
12. inspect color;
13. inspect border;
14. inspect radius;
15. inspect shadow;
16. inspect image/media;
17. inspect crop/mask;
18. inspect icon/decorative element;
19. inspect state yang tersedia;
20. cek frame responsive lain;
21. baru mulai implementasi.

---

# 6. Nilai Visual

Nilai implementasi harus berasal dari inspeksi Figma jika tersedia.

Contoh:

```text
width
height
max-width
gap
padding
margin
font-size
font-weight
line-height
letter-spacing
border-radius
border-width
shadow
opacity
object-position
```

Token global hanya digunakan jika:

1. nilainya memang sama dengan Figma;
2. token tersebut sudah mewakili desain;
3. nilai Figma tidak tersedia.

> Jangan membulatkan nilai hanya agar sesuai skala Tailwind default jika hasil visual berubah.

---

# 7. Tailwind dan Pixel Accuracy

Tailwind merupakan alat implementasi, bukan sumber nilai desain.

Agent boleh menggunakan:

- default utility;
- arbitrary value;
- custom CSS;
- scoped style;

sesuai kebutuhan.

Contoh:

```html
max-w-[1180px]
rounded-[22px]
leading-[1.18]
```

diperbolehkan jika memang sesuai Figma.

---

# 8. Aset Figma Sementara

Sampai aset produksi resmi tersedia:

> agent boleh dan harus menggunakan asset sumber dari Figma jika asset tersebut diperlukan untuk menyamai desain.

Aturan:

- export asset/layer asli;
- gunakan source image jika tersedia;
- catat node asal;
- simpan di project;
- beri status `[FIGMA SEMENTARA]`;
- catat di `ASSETS.md`.

---

# 9. Larangan Asset

Dilarang:

- memakai screenshot full-frame sebagai elemen situs;
- mengganti aset Figma dengan stock image;
- mengganti dengan Picsum/Unsplash;
- mengganti dengan hasil AI/generative;
- menggunakan temporary Figma URL pada runtime;
- mengganti icon custom dengan icon random.

Screenshot Figma hanya digunakan untuk:

> **visual QA / comparison.**

---

# 10. Penggantian dengan Asset Produksi

Aset resmi hanya boleh menggantikan `[FIGMA SEMENTARA]` setelah diverifikasi:

- role visual sama;
- aspect ratio sesuai;
- crop dapat direplikasi;
- focal point sesuai;
- resolusi cukup;
- desktop sesuai;
- mobile/tablet sesuai jika framing berbeda.

Perubahan harus dicatat di `ASSETS.md`.

---

# 11. Responsive — Frame yang Tersedia

Jika Figma menyediakan frame untuk viewport tertentu:

> frame tersebut adalah source of truth untuk viewport itu.

Contoh:

```text
Desktop 1440
Tablet 834
Mobile 390
```

Gunakan ukuran aktual Figma, bukan angka contoh di atas.

Breakpoint framework tidak mengalahkan frame Figma.

---

# 12. Responsive — Tanpa Frame Pembanding

Jika tidak ada frame mobile/tablet:

status implementasi adalah:

```text
[RESPONSIVE FALLBACK — BELUM TERVALIDASI FIGMA]
```

Fallback harus menjaga:

- hierarchy;
- urutan konten;
- relative importance;
- readability;
- media proportion;
- interaction usability;
- intent visual.

> Jangan sekadar mengecilkan desktop.

Namun jangan pula membuat redesign baru.

Gunakan `RESPONSIVE.md` sebagai acuan utama untuk behavior yang belum memiliki frame.

---

# 13. Mobile-First

Mobile-first adalah strategi implementasi teknis yang boleh digunakan jika sesuai.

Namun:

> **mobile-first bukan source of truth visual.**

Jika desain Figma desktop memiliki struktur yang berbeda dan responsive behavior sudah terdokumentasi, implementasi harus mengikuti desain tersebut.

Agent tidak boleh mengubah composition hanya demi mempertahankan pendekatan mobile-first.

---

# 14. Viewport QA

## Viewport dengan Frame Figma

Wajib diuji pada ukuran frame aktual.

Status setelah lolos QA:

```text
[FIGMA VERIFIED]
```

atau:

```text
[PIXEL-ACCURATE VERIFIED]
```

jika memang standar tersebut terpenuhi.

---

## Viewport tanpa Frame

Boleh diuji pada ukuran representatif untuk browser/device.

Status:

```text
[RESPONSIVE FALLBACK]
```

Bukan:

```text
[PIXEL-PERFECT]
```

Tidak ada viewport minimum generik yang mengalahkan ukuran frame Figma.

---

# 15. Larangan Redesign

Jika Figma sudah menentukan suatu elemen, agent tidak boleh mengubah:

- section order;
- grid;
- font;
- font size;
- font weight;
- color;
- spacing;
- width;
- height;
- radius;
- border;
- shadow;
- image crop;
- icon;
- CTA;
- decorative element;
- component form;
- visible state;

hanya karena alternatif lain dianggap lebih baik.

---

# 16. Deviasi yang Diperbolehkan

Deviasi hanya boleh dilakukan jika ada alasan nyata.

Kategori deviasi yang diperbolehkan:

## Accessibility

Contoh:

- semantic element;
- keyboard handling;
- focus management;
- hidden accessible label.

Usahakan tidak mengubah visual.

---

## Browser / Platform Constraint

Contoh:

- rendering font;
- video autoplay restriction;
- scrollbar behavior;
- unsupported CSS feature.

---

## Asset Constraint

Contoh:

- aset produksi memiliki rasio berbeda;
- source image Figma tidak dapat diekspor;
- kualitas aset terbatas.

---

## Undefined Behavior

Contoh:

- Figma hanya menunjukkan satu state;
- tidak ada desain error/empty/loading;
- tidak ada tablet frame.

---

# 17. Deviasi yang Tidak Diperbolehkan

Bukan alasan valid:

```text
lebih mudah dibuat
lebih modern
lebih bagus menurut agent
lebih sesuai Tailwind
lebih reusable
lebih umum dipakai website lain
library tidak mendukung
```

Jika library menyebabkan hasil tidak sesuai:

> ubah implementasi/library, bukan desain.

---

# 18. Dokumentasi Deviasi

Setiap deviasi visual yang terlihat harus dicatat di `TODO.md`.

Format:

```text
[VISUAL DEVIATION]

Page:
Section:
Figma node:
Viewport:
Expected:
Actual:
Reason:
Impact:
Status:
```

---

# 19. Severity Deviasi

Gunakan klasifikasi:

## Critical

- section hilang;
- layout salah total;
- asset utama salah;
- urutan salah;
- typography utama sangat berbeda;
- behavior utama tidak sesuai.

Status:

> halaman belum selesai.

---

## Major

- spacing terlihat signifikan;
- crop salah;
- card sizing salah;
- container mismatch;
- responsive composition berbeda.

Status:

> harus diperbaiki sebelum visual sign-off.

---

## Minor

- perbedaan rendering font kecil;
- subpixel;
- antialiasing;
- perbedaan browser kecil.

Status:

> dapat diterima jika terdokumentasi.

---

# 20. Visual QA Wajib

Setelah implementasi:

1. render browser pada ukuran frame yang sama;
2. gunakan zoom/skala 100%;
3. capture screenshot;
4. bandingkan dengan Figma;
5. mulai dari macro layout;
6. lanjut ke typography;
7. lanjut ke spacing;
8. lanjut ke media;
9. lanjut ke detail component/state;
10. perbaiki deviasi Critical/Major;
11. ulangi comparison.

---

# 21. Urutan Pemeriksaan Visual

Gunakan urutan berikut agar tidak membuang waktu pada detail sebelum layout benar.

## Pass 1 — Macro

- canvas/background;
- navbar;
- hero;
- section order;
- section boundary;
- container;
- grid.

## Pass 2 — Typography

- family;
- size;
- weight;
- line-height;
- wrapping;
- alignment.

## Pass 3 — Spacing

- padding;
- margin;
- gap;
- vertical rhythm.

## Pass 4 — Media

- size;
- crop;
- focal point;
- radius;
- mask.

## Pass 5 — Detail

- icon;
- border;
- shadow;
- decorative element;
- hover;
- active;
- focus;
- modal state.

---

# 22. Side-by-Side dan Overlay

Visual QA dapat menggunakan:

- side-by-side;
- overlay;
- difference image;
- screenshot comparison tool.

Tidak ada satu tool wajib.

Namun comparison harus cukup jelas untuk menemukan selisih visual nyata.

---

# 23. Status QA

Gunakan status per frame:

```text
[NOT REVIEWED]
[IN REVIEW]
[NEEDS FIX]
[FIGMA VERIFIED]
[BLOCKED]
```

Jika ingin menggunakan label `PIXEL-ACCURATE VERIFIED`, hanya gunakan setelah comparison benar-benar dilakukan pada viewport tersebut.

---

# 24. Klaim Pixel-Accurate

Sebuah halaman/frame hanya boleh disebut:

```text
pixel-accurate
```

jika:

- frame approved tersedia;
- viewport sama;
- visual QA dilakukan;
- Critical deviation = 0;
- Major deviation = 0 atau telah disetujui eksplisit;
- asset sesuai;
- typography telah diperiksa;
- status dicatat di `DELIVERY_CHECKLIST.md`.

---

# 25. Jangan Menggunakan Klaim Global

Jika hanya desktop yang sudah diverifikasi:

boleh:

```text
Desktop 1440px — Figma verified.
```

tidak boleh:

```text
Website sudah 100% pixel-perfect.
```

Viewport harus disebut secara spesifik.

---

# 26. Tanpa Akses Figma

Jika Figma tidak dapat diakses:

status:

```text
[BLOCKED: FIGMA ACCESS]
```

Agent boleh:

- memperbaiki code structure;
- mengerjakan backend;
- mengimplementasikan fallback berdasarkan dokumentasi;
- menggunakan asset existing yang sudah terpetakan.

Agent tidak boleh:

- mengklaim visual accuracy;
- membuat keputusan visual besar berdasarkan tebakan.

---

# 27. Sinkronisasi dengan FIGMA.md

`FIGMA.md` harus memuat mapping minimal:

```text
Page
Frame
Node
Viewport
Status
URL/reference
```

Dokumen ini menjelaskan **bagaimana mengimplementasikan dan memverifikasi** mapping tersebut.

---

# 28. Sinkronisasi dengan DELIVERY_CHECKLIST.md

Setelah visual QA:

catat:

- frame;
- viewport;
- tanggal review;
- hasil;
- deviasi;
- status.

`DELIVERY_CHECKLIST.md` menjadi bukti serah-terima.

---

# 29. Sinkronisasi dengan TODO.md

`TODO.md` digunakan untuk:

- blocker;
- deviasi;
- responsive fallback;
- asset missing;
- behavior ambiguous;
- content mismatch;
- design conflict.

Jangan menyembunyikan isu hanya di komentar kode.

---

# 30. Definition of Done — Frame

Satu frame dianggap selesai jika:

- [ ] Reference benar
- [ ] Status approved benar
- [ ] Implementasi tersedia
- [ ] Asset sesuai
- [ ] Viewport sama
- [ ] Macro layout sesuai
- [ ] Typography sesuai
- [ ] Spacing sesuai
- [ ] Media sesuai
- [ ] State relevan sesuai
- [ ] Critical deviation = 0
- [ ] Major deviation = 0 atau approved
- [ ] QA status dicatat

---

# 31. Definition of Done — Responsive Fallback

Viewport tanpa Figma dianggap cukup secara responsive jika:

- [ ] Tidak overflow
- [ ] Hierarchy tetap jelas
- [ ] Content order benar
- [ ] Media proporsional
- [ ] Interaction usable
- [ ] Tidak ada redesign besar
- [ ] Status `[RESPONSIVE FALLBACK]` dicatat
- [ ] Tidak diklaim pixel-accurate

---

# 32. Prinsip Akhir

Untuk Fase 1:

> **Inspect before coding.**

> **Implement from measured values.**

> **Compare before claiming done.**

> **Figma controls visual; documentation explains behavior.**

Jika frame approved tersedia:

> jangan menebak.

Jika frame tidak tersedia:

> jangan mengklaim akurasi yang belum dapat dibuktikan.
