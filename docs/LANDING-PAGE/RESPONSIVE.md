# RESPONSIVE.md
## Kontrak Responsive — Fase 1 Landing Page Kampoeng Radja

Dokumen ini menentukan aturan responsive resmi untuk viewport non-desktop pada Fase 1.

> **Keputusan stakeholder:**  
> Tim desain hanya membuat **desain desktop** di Figma.
>
> Tidak akan tersedia frame Figma khusus:
>
> - tablet;
> - mobile.
>
> Karena itu, dokumen ini menjadi **source of truth responsive fallback** untuk tablet dan mobile.

Status visual non-desktop:

```text
[RESPONSIVE FALLBACK]
```

Viewport non-desktop **tidak boleh diklaim pixel-accurate terhadap Figma**.

---

# 1. Tujuan Responsive

Responsive implementation harus:

- mempertahankan hierarchy desain desktop;
- mempertahankan urutan konten;
- mempertahankan identitas visual;
- mempertahankan proporsi media sebisa mungkin;
- menjaga readability;
- menjaga interaction usability;
- mencegah overflow;
- menghindari redesign baru.

Tujuannya bukan membuat versi visual baru, tetapi:

> **mengadaptasi desain desktop agar usable pada viewport lebih kecil.**

---

# 2. Source of Truth

Untuk responsive:

1. `PRD.md` — fungsi
2. Figma desktop approved — visual intent
3. `RESPONSIVE.md` — transformasi non-desktop
4. `UI_SPEC.md` — behavior
5. `COMPONENTS.md` — struktur implementasi

Jika terdapat konflik:

- fungsi → PRD menang;
- visual desktop → Figma menang;
- behavior responsive yang tidak didesain → dokumen ini menang.

---

# 3. Desktop Reference

Desktop Figma approved merupakan baseline utama.

Agent harus memahami terlebih dahulu:

- section order;
- content hierarchy;
- container;
- spacing;
- typography hierarchy;
- media proportion;
- alignment;
- component grouping.

Baru setelah itu menurunkannya ke tablet/mobile.

---

# 4. Mobile-First

Mobile-first boleh digunakan sebagai strategi CSS/engineering.

Namun:

> **mobile-first bukan sumber keputusan desain.**

Agent tidak boleh merancang ulang mobile secara bebas hanya karena implementasi dimulai dari viewport kecil.

Responsive harus tetap berasal dari intent desktop approved.

---

# 5. Breakpoint Strategy

Tidak ada kewajiban menggunakan breakpoint default Tailwind secara kaku.

Breakpoint dipilih berdasarkan:

- kapan layout mulai sempit;
- kapan content wrapping rusak;
- kapan interaction menjadi sulit;
- kapan grouping desktop tidak lagi nyaman.

Tailwind default boleh digunakan jika cocok.

Contoh:

```text
sm
md
lg
xl
2xl
```

tetapi custom breakpoint diperbolehkan jika lebih tepat.

> Breakpoint adalah alat teknis, bukan design token wajib.

---

# 6. Prinsip Transformasi Layout

Saat viewport mengecil, prioritaskan transformasi berikut secara berurutan:

1. kurangi whitespace;
2. kecilkan gap secara proporsional;
3. izinkan wrapping;
4. ubah multi-column menjadi lebih sedikit kolom;
5. stack content jika perlu;
6. ubah navigation interaction jika perlu;
7. gunakan horizontal scroll hanya bila memang paling masuk akal;
8. hindari menghapus content.

Jangan langsung mengubah seluruh section menjadi satu kolom jika belum diperlukan.

---

# 7. Urutan Konten

Urutan section harus tetap mengikuti Figma/PRD.

Dalam satu section, urutan internal dapat berubah hanya jika diperlukan agar:

- readability tetap baik;
- hierarchy tetap jelas;
- media dan copy tidak saling merusak.

Perubahan urutan internal harus tetap mempertahankan meaning.

---

# 8. Typography Responsive

Typography desktop tidak harus dipertahankan pada ukuran yang sama.

Pada viewport lebih kecil, agent boleh menyesuaikan:

- font size;
- line-height;
- letter-spacing bila perlu;
- text width;
- alignment;

selama hierarchy visual tetap jelas.

Prinsip:

```text
H1 tetap dominan
H2 tetap lebih rendah dari H1
Body tetap readable
CTA tetap jelas
```

Jangan mengecilkan font terlalu agresif hanya agar semua teks muat satu baris.

---

# 9. Spacing Responsive

Spacing boleh diperkecil secara bertahap.

Yang boleh berubah:

- section padding;
- container padding;
- gap;
- margin;
- card spacing.

Yang harus dijaga:

- hierarchy;
- grouping;
- breathing room;
- keterbacaan.

Jangan menggunakan satu nilai global untuk semua section jika desain desktop memiliki ritme berbeda.

---

# 10. Container Responsive

Desktop container mengikuti Figma.

Pada viewport sempit:

- container width menjadi fluid;
- gunakan horizontal padding yang cukup;
- jangan menyebabkan content menempel ke edge;
- jangan mempertahankan fixed desktop width.

Agent boleh menggunakan:

```text
width: 100%
max-width
padding-inline
```

sesuai kebutuhan.

---

# 11. Navbar Responsive

Navbar mobile/tablet harus mempertahankan:

- brand/logo;
- akses ke seluruh halaman utama;
- struktur navigasi yang jelas.

Jika menu horizontal tidak lagi muat, agent boleh menggunakan pattern seperti:

- hamburger;
- drawer;
- dropdown;
- compact menu;

pilih yang paling sederhana dan sesuai visual identity.

Tidak ada kewajiban menggunakan off-canvas jika pattern lain lebih tepat.

Navigation mobile harus:

- dapat dibuka/tutup;
- keyboard-usable;
- tidak overflow;
- memiliki state aktif bila sistem desktop memilikinya.

---

# 12. Footer Responsive

Footer mempertahankan seluruh informasi penting.

Pada viewport kecil:

- kolom boleh stack;
- alignment boleh berubah;
- spacing boleh dipadatkan;
- CTA/login tetap mudah diakses.

Jangan menghapus kontak/social/link hanya agar footer lebih pendek.

---

# 13. Hero Responsive

Hero harus mempertahankan:

- pesan utama;
- visual utama;
- CTA jika ada;
- hierarchy teks;
- focal point media.

Pada mobile:

- tinggi tidak harus sama dengan desktop;
- copy dapat wrap;
- media crop boleh disesuaikan;
- overlay dapat disesuaikan;
- alignment dapat berubah jika diperlukan.

Jika desktop memakai video dan performa mobile menjadi masalah, fallback teknis dapat digunakan sesuai `UI_SPEC.md`/`ASSETS.md`.

---

# 14. Image Crop Responsive

Crop mobile tidak harus identik dengan desktop.

Agent boleh menyesuaikan:

```text
object-position
aspect-ratio
height
mask
```

untuk menjaga focal point.

Namun:

> jangan mengganti source image hanya karena crop mobile sulit.

Jika crop yang baik tidak mungkin dicapai, catat deviasi.

---

# 15. Beranda — Insight / Information

Pada desktop, ikuti Figma.

Pada viewport lebih kecil:

- jumlah kolom boleh berkurang;
- item dapat wrap atau stack;
- hierarchy dan order tetap sama.

Jangan mengubah content menjadi carousel jika desktop bukan carousel kecuali itu benar-benar solusi paling tepat dan terdokumentasi.

---

# 16. Beranda — Media & Berita

Responsive behavior harus mempertahankan:

- hierarchy card;
- urutan konten;
- visual importance;
- image treatment.

Grid desktop dapat berubah menjadi:

- lebih sedikit kolom;
- stack;
- horizontal scroll hanya jika memang dibutuhkan.

Jangan otomatis mengubah menjadi carousel.

---

# 17. Beranda — Promo & Event

Pada viewport kecil:

- card dapat stack;
- grid dapat dikurangi;
- media tetap proporsional;
- CTA tetap usable.

Carousel hanya digunakan jika UI spec/implementasi memang menetapkannya.

---

# 18. Beranda — Wahana Unggulan

Pertahankan:

- urutan highlight;
- hierarchy copy/media;
- focal point.

Multi-column desktop dapat menjadi lebih sedikit kolom atau stack.

Jangan menyederhanakan visual sampai kehilangan karakter section.

---

# 19. Beranda — Mitra

Jika desktop menggunakan deretan logo:

pada mobile dapat menggunakan:

- wrapping;
- horizontal scroll;
- carousel;

berdasarkan behavior yang paling sederhana dan masuk akal.

Auto-scroll/pause-on-hover/drag hanya diterapkan jika UI spec menetapkannya.

---

# 20. Beranda — Lokasi

Pada desktop dapat mengikuti layout berdampingan.

Pada viewport kecil:

- map dan info dapat stack;
- map tetap usable;
- CTA directions tetap terlihat;
- alamat tidak terpotong.

Urutan map vs info mengikuti hierarchy desktop dan usability.

---

# 21. Tentang Kami — Hero

Pertahankan:

- visual utama;
- identity;
- slogan/copy;
- focal point.

Copy boleh wrap dan ukuran dapat diturunkan secara proporsional.

---

# 22. Tentang Kami — Sejarah

Jika desktop memiliki layout zig-zag/timeline:

pada viewport sempit agent boleh:

- menyederhanakan menjadi vertical flow;
- stack image dan text;
- mempertahankan urutan kronologis.

Jangan mengubah sequence sejarah.

---

# 23. Tentang Kami — Visi & Misi

Jika desktop side-by-side:

mobile boleh stack.

Urutan:

```text
Visi
Misi
```

dipertahankan kecuali Figma/PRD menunjukkan urutan lain.

---

# 24. Tentang Kami — Struktur Organisasi

Prioritas:

1. tetap terbaca;
2. hierarchy jabatan jelas;
3. hubungan antar level masih dapat dipahami.

Solusi mobile dapat berupa:

- vertical hierarchy;
- horizontal scroll;
- simplified tree;
- grouped levels;

pilih yang paling mempertahankan meaning tanpa membuat redesign besar.

Accordion hanya digunakan jika memang dibutuhkan untuk usability, bukan default.

---

# 25. Wahana — Filter

Pada viewport kecil:

filter harus tetap:

- readable;
- touch-usable;
- tidak overflow;
- mempertahankan category/label relationship.

Pattern yang diperbolehkan:

- wrapping chips;
- stacked group;
- horizontal chip scroll;
- collapsible group jika benar-benar diperlukan.

Tombol Cari/Reset tidak wajib full-width kecuali layout memerlukannya.

---

# 26. Wahana — Grid

Jumlah kolom ditentukan berdasarkan lebar card dan readability.

Tidak ada aturan kaku:

```text
1–2
2–3
3–4
```

sebagai requirement.

Gunakan jumlah kolom yang membuat card tetap proporsional dan mendekati karakter desktop.

---

# 27. Wahana — Lightbox / Detail

Jika fitur ada:

mobile dapat menggunakan modal yang lebih besar/fullscreen jika diperlukan.

Pertahankan:

- image visibility;
- close action;
- detail content;
- keyboard/touch usability.

---

# 28. Galeri Event — Sort Control

Control harus tetap terlihat dan mudah digunakan.

Posisi boleh berpindah saat viewport sempit jika:

- hierarchy tetap jelas;
- behavior tidak berubah.

Jangan mengganti control type tanpa kebutuhan.

---

# 29. Galeri Event — Event Card

Pada viewport kecil:

- card dapat stack;
- photo grid dapat dikurangi;
- image dapat menjadi lebih besar;
- text wrapping harus tetap nyaman.

Pertahankan hubungan:

```text
event
→ date
→ description
→ photos
```

sesuai desain.

---

# 30. Modal / Overlay Responsive

Jika modal/lightbox digunakan:

mobile harus:

- mudah ditutup;
- tidak melebihi viewport;
- tidak menyebabkan scroll body yang rusak;
- memiliki touch-friendly controls.

Fullscreen diperbolehkan jika lebih usable.

---

# 31. Touch Target

Target sentuh sebaiknya nyaman.

Guidance umum:

```text
~44px
```

untuk kontrol penting.

Namun:

> angka ini adalah accessibility guidance, bukan kewajiban visual mutlak untuk seluruh elemen.

Jika desain membutuhkan penyesuaian, usahakan memperbesar hit area tanpa mengubah visual.

---

# 32. Body Text

Body text mobile harus tetap readable.

Hindari ukuran terlalu kecil.

Tidak ada kewajiban semua body harus tepat `16px`, tetapi input text harus memperhatikan behavior browser mobile seperti auto-zoom.

---

# 33. Horizontal Scroll

Horizontal scroll hanya digunakan jika:

- content memang sulit direflow;
- hierarchy tetap jelas;
- interaction mudah ditemukan.

Jangan menggunakan horizontal scroll sebagai solusi default.

---

# 34. Hover

Mobile/touch tidak memiliki hover yang dapat diandalkan.

Interaction penting tidak boleh bergantung hanya pada hover.

Jika desktop memiliki hover-only information:

mobile harus menyediakan cara alternatif untuk mengakses informasi tersebut.

---

# 35. Animation

Animation responsive harus:

- tidak mengganggu usability;
- mempertimbangkan `prefers-reduced-motion`;
- tidak membuat layout shift besar.

Jangan menambah animation baru hanya untuk mobile.

---

# 36. Performance Mobile

Pada mobile:

- image size harus sesuai;
- media besar dioptimasi;
- video dipertimbangkan ulang secara teknis bila perlu;
- jangan load asset desktop berukuran sangat besar tanpa kebutuhan.

Optimasi tidak boleh mengubah intent visual secara signifikan.

---

# 37. Testing Viewport

Karena tidak ada frame tablet/mobile Figma, QA non-desktop menggunakan viewport representatif.

Minimum engineering QA yang disarankan:

```text
~360–390px mobile
~768px tablet
~1024px small desktop/tablet landscape
desktop frame Figma aktual
```

Angka ini adalah **testing coverage**, bukan source of truth desain.

Desktop wajib diuji pada ukuran frame Figma yang tepat.

---

# 38. Intermediate Width Testing

Selain breakpoint utama, periksa width di antara breakpoint untuk menemukan:

- wrapping buruk;
- overlap;
- overflow;
- navigation break;
- image crop aneh;
- card terlalu sempit.

Responsive tidak boleh hanya “bagus tepat di breakpoint”.

---

# 39. Responsive QA Status

Gunakan status:

```text
[NOT REVIEWED]
[NEEDS FIX]
[RESPONSIVE VERIFIED]
[RESPONSIVE FALLBACK]
```

Untuk tablet/mobile Fase 1:

status final yang benar adalah:

```text
[RESPONSIVE FALLBACK — VERIFIED]
```

jika sudah diuji dan usable.

Bukan:

```text
[FIGMA VERIFIED]
```

---

# 40. Responsive Deviation

Jika mobile/tablet membutuhkan perubahan yang cukup besar:

catat di `TODO.md`.

Format:

```text
[RESPONSIVE DEVIATION]

Page:
Section:
Viewport:
Desktop intent:
Fallback:
Reason:
Impact:
Status:
```

---

# 41. Larangan

Agent tidak boleh:

- sekadar scale down desktop;
- menghapus section;
- menghapus CTA;
- mengubah content order tanpa alasan;
- membuat redesign mobile yang tidak terkait desktop;
- membuat carousel untuk semua grid;
- membuat accordion untuk semua content;
- memaksa Tailwind breakpoint default;
- mengklaim pixel-perfect non-desktop;
- menganggap 375/768/1024 sebagai ukuran Figma.

---

# 42. Definition of Done — Tablet/Mobile

Responsive fallback dianggap selesai jika:

- [ ] Tidak ada horizontal overflow yang tidak disengaja
- [ ] Navigation usable
- [ ] Content order benar
- [ ] Hierarchy tetap jelas
- [ ] Typography readable
- [ ] Image/media proporsional
- [ ] CTA usable
- [ ] Filter/modal/interactions usable
- [ ] Touch behavior diuji
- [ ] Intermediate width tidak rusak
- [ ] Deviasi signifikan tercatat
- [ ] Status tidak diklaim pixel-accurate

---

# 43. Prinsip Akhir

> **Desktop Figma menentukan intent visual.**

> **RESPONSIVE.md menentukan bagaimana intent tersebut bertahan pada tablet/mobile.**

Agent harus:

> **adapt, not redesign.**

Responsive Fase 1 dinilai dari:

- consistency;
- usability;
- hierarchy;
- technical robustness;

bukan dari pixel comparison terhadap frame yang memang tidak tersedia.
