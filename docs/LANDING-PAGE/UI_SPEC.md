# UI_SPEC.md
## Spesifikasi Perilaku UI & Interaksi — Fase 1 Landing Page Kampoeng Radja

Dokumen ini mengatur **behavior, interaction, state, dan urutan logis UI** yang tidak selalu terlihat lengkap di Figma.

> **Prinsip utama:**  
> Figma approved menentukan bentuk visual persis pada desktop.
>
> `UI_SPEC.md` tidak boleh digunakan untuk membuat desain baru jika Figma sudah menentukan:
>
> - layout;
> - ukuran;
> - spacing;
> - typography;
> - warna;
> - radius;
> - border;
> - shadow;
> - crop;
> - icon;
> - decorative element;
> - visible state.
>
> Jika deskripsi visual pada dokumen ini berbeda dari Figma approved:
>
> **ikuti Figma dan catat konflik di `TODO.md`.**

---

# 1. Scope Dokumen

Dokumen ini digunakan untuk menentukan:

- interaction behavior;
- trigger;
- state;
- sorting/filtering;
- modal behavior;
- fallback behavior;
- keyboard interaction;
- loading/empty/error handling;
- behavior responsive yang tidak bisa diperoleh langsung dari desktop Figma.

Untuk visual desktop:

> gunakan `FIGMA.md` dan `FIGMA_ACCURACY.md`.

Untuk tablet/mobile:

> gunakan `RESPONSIVE.md`.

---

# 2. Global — Navbar

Requirement fungsi:

- menyediakan akses ke halaman publik yang aktif;
- menunjukkan halaman aktif jika desain menyediakan active state;
- tetap usable saat scroll;
- responsive behavior mengikuti `RESPONSIVE.md`.

Jika Figma menunjukkan navbar sticky:

> implementasikan sticky.

Jika Figma tidak menunjukkan behavior scroll:

> jangan otomatis membuat transparent-to-solid transition.

Mobile/tablet:

- gunakan responsive fallback;
- pattern hamburger/drawer hanya digunakan jika memang diperlukan agar navigation tetap usable.

Tidak ada tombol Login pada navigasi utama publik sesuai PRD aktif.

---

# 3. Global — Footer

Footer merupakan shared area publik.

Requirement:

- tampil di seluruh halaman publik;
- memuat informasi yang memang tersedia dan approved;
- menyediakan akses login karyawan sesuai PRD/Figma;
- social links dapat diklik;
- external links menggunakan target yang sesuai jika memang harus membuka tab baru.

Tooltip social media hanya wajib jika:

- Figma menunjukkan tooltip;
- atau requirement final memang meminta label hover.

Jika tidak ada desain/requirement tooltip:

> jangan menambahkannya hanya sebagai efek dekoratif.

---

# 4. Beranda — Hero

Visual:

> ikuti Figma approved.

Behavior media mengikuti media yang ada pada desain.

## Jika Hero Menggunakan Video

Gunakan behavior berikut:

- `muted` agar autoplay dapat bekerja di browser modern;
- autoplay hanya jika sesuai desain;
- loop jika sesuai desain;
- poster/fallback image jika tersedia;
- `playsinline` untuk mobile;
- jangan bergantung pada audio untuk menyampaikan informasi utama.

Jika autoplay gagal karena browser policy:

> fallback tidak boleh merusak layout.

## Jika Hero Menggunakan Image

- pertahankan crop/focal point sesuai Figma desktop;
- responsive crop mengikuti `RESPONSIVE.md`.

Scroll indicator hanya dibuat jika terdapat di Figma.

---

# 5. Beranda — Insight / Informasi

Section menampilkan informasi praktis/panduan.

Behavior:

- content read-only;
- tidak membutuhkan interaction khusus kecuali Figma menunjukkan;
- data/fakta mengikuti `CONTENT.md`.

Jangan membuat accordion, carousel, tooltip, atau modal jika desain tidak membutuhkannya.

---

# 6. Beranda — Media & Berita

Visual layout mengikuti Figma.

Tidak ada requirement penggunaan:

```text
vue-grid-layout
masonry library
generic news card
```

Data item dapat mencakup:

- title;
- date;
- excerpt;
- thumbnail.

## Klik Item

Behavior bergantung keputusan scope:

### Jika Detail Media & Berita Masuk Scope

- card dapat membuka route detail;
- node visual detail `1:650` dapat digunakan sebagai acuan.

### Jika Detail Tidak Masuk Scope

- jangan membuat halaman detail;
- card tidak boleh diarahkan ke halaman kosong/fiktif;
- behavior klik dapat dinonaktifkan atau mengikuti desain yang disepakati.

Status saat ini:

`[PERLU KEPUTUSAN DI PRD/TODO]`

---

# 7. Beranda — Promo & Event

Visual mengikuti Figma.

Behavior:

- item menampilkan content approved;
- CTA hanya aktif jika URL/action tersedia;
- expired promo/event tidak boleh ditampilkan sebagai aktif jika backend menggunakan periode aktif.

Carousel hanya digunakan jika memang ditentukan desain/behavior.

Jangan otomatis mengubah grid menjadi carousel berdasarkan jumlah item.

---

# 8. Beranda — Wahana Unggulan

Behavior:

- item dapat mengarah ke halaman Wahana jika CTA/link memang tersedia;
- jangan membuat auto-filter ke wahana tertentu kecuali requirement tersebut benar-benar diputuskan;
- data item mengikuti backend/content source.

Visual mengikuti Figma.

---

# 9. Beranda — Mitra / Sponsor

Visual mengikuti Figma.

Behavior default:

- logo/link read-only atau clickable jika URL mitra tersedia.

Behavior berikut **tidak otomatis wajib**:

- auto-scroll;
- infinite loop;
- pause-on-hover;
- drag/swipe;
- grayscale-to-color.

Aktifkan hanya jika Figma atau requirement interaksi menetapkannya.

Jika section memerlukan horizontal movement pada responsive fallback, ikuti `RESPONSIVE.md`.

---

# 10. Beranda — Lokasi

Teknologi:

> Google Maps iframe embed.

Behavior:

- iframe menampilkan lokasi resmi;
- CTA `Petunjuk Arah` membuka URL Google Maps yang sudah diverifikasi;
- external map dapat dibuka pada tab/app sesuai platform.

Jangan menggunakan Maps JavaScript API tanpa perubahan architecture.

Layout visual mengikuti Figma.

---

# 11. Tentang Kami — Hero

Visual mengikuti Figma.

Copy yang berasal dari Figma tetapi belum dikonfirmasi:

> gunakan status `[FIGMA SEMENTARA]`.

Tidak ada behavior interaktif wajib kecuali Figma menunjukkan.

---

# 12. Tentang Kami — Sejarah / Kisah

Requirement behavior:

- content mengikuti urutan kronologis;
- user membaca dengan scroll halaman biasa;
- media dan text harus tetap terhubung secara jelas.

Jika Figma menggunakan timeline:

> implementasikan visual timeline sesuai Figma.

Mobile/tablet fallback mengikuti `RESPONSIVE.md`.

Tidak ada kewajiban animation timeline.

---

# 13. Tentang Kami — Visi & Misi

Requirement:

- visi tampil sebagai satu blok informasi;
- misi tampil sebagai daftar/poin jika content memang berbentuk list;
- content read-only.

Layout mengikuti Figma.

Responsive stacking mengikuti `RESPONSIVE.md`.

---

# 14. Tentang Kami — Struktur Organisasi

Requirement:

- hierarchy jabatan harus dapat dipahami;
- data yang dipublikasikan harus approved.

Visual mengikuti Figma.

Behavior responsive boleh menggunakan solusi seperti:

- vertical hierarchy;
- horizontal scroll;
- grouped levels;

sesuai `RESPONSIVE.md`.

Accordion tidak otomatis wajib.

Data source:

`[PERLU KEPUTUSAN: STATIS ATAU DINAMIS]`

---

# 15. Wahana — Header

Visual mengikuti Figma.

Tidak ada behavior khusus kecuali Figma/PRD menunjukkan.

---

# 16. Wahana — Filter

Filter menggunakan data category/label dari backend.

## Selection

- label dapat dipilih;
- label dapat dipilih lebih dari satu;
- klik label aktif → toggle off;
- visual active/inactive mengikuti Figma.

## Apply

Requirement aktif saat ini:

- user memilih label;
- user menekan `Cari`;
- filter diterapkan.

Jika behavior di masa depan berubah menjadi auto-apply:

> PRD harus diperbarui terlebih dahulu.

## Reset

`Reset` harus:

- menghapus seluruh pilihan;
- mengembalikan hasil ke kondisi default/all items;
- memperbarui active state UI.

---

# 17. Wahana — Logika Filter

Filter menggunakan:

> **AND antar seluruh label aktif.**

Contoh:

```text
Air
+
Anak-anak
```

hasil hanya item yang memiliki kedua label.

Behavior harus konsisten di frontend dan backend/query layer jika filtering dilakukan server-side.

---

# 18. Wahana — State Filter

Minimal state yang harus ditangani:

```text
default
selected
applied
empty result
loading jika request async
error jika request gagal
```

Figma mengatur visual state yang tersedia.

Jika state tidak didesain:

> gunakan fallback minimal yang tidak merusak visual dan catat bila signifikan.

---

# 19. Wahana — Empty State

Jika tidak ada hasil:

- jangan crash;
- tampilkan pesan yang jelas;
- user harus tetap dapat Reset/mengubah filter.

Copy final mengikuti `CONTENT.md`.

Ilustrasi hanya digunakan jika Figma/aset menyediakan.

Jangan membuat ilustrasi generik sendiri.

---

# 20. Wahana — Grid

Visual dan jumlah kolom desktop mengikuti Figma.

Responsive behavior mengikuti `RESPONSIVE.md`.

Tidak ada jumlah kolom tetap yang dikunci oleh `UI_SPEC.md`.

---

# 21. Wahana — Lightbox / Detail

Jika Figma/PRD menggunakan lightbox/detail:

## Open

- klik/tap item membuka preview.

## Close

Minimal dapat ditutup melalui:

- close button;
- `Escape` pada desktop keyboard.

Klik backdrop untuk close boleh digunakan jika sesuai UX implementation dan tidak bertentangan dengan desain.

## Focus

Jika modal digunakan:

- focus harus masuk ke dialog;
- focus kembali ke trigger setelah ditutup jika feasible;
- background interaction dibatasi.

## Content

Hanya tampilkan field yang tersedia:

- image;
- title;
- description;
- metadata lain jika memang ada.

Jangan membuat data fiktif.

---

# 22. Galeri Event — Sorting

Sorting wajib:

```text
Terbaru
Terlama
```

## Terbaru

Urutkan berdasarkan tanggal event descending.

## Terlama

Urutkan berdasarkan tanggal event ascending.

Default selection:

`[PERLU DITETAPKAN]`

Jika tidak ada keputusan sebelum coding, rekomendasi default teknis:

> `Terbaru`

karena umum untuk content chronology, tetapi tetap harus dicatat sebagai keputusan sementara sampai disetujui.

---

# 23. Galeri Event — Daftar Event

Setiap event dapat menampilkan:

- title;
- event date;
- description;
- photos.

Visual card/section mengikuti Figma.

Jumlah foto yang terlihat pada preview mengikuti layout Figma/data availability.

---

# 24. Galeri Event — Detail / Lightbox

Jika desain memakai detail/modal:

behavior mengikuti aturan modal Wahana.

Jika tidak:

> jangan membuat lightbox hanya karena dianggap umum pada gallery.

---

# 25. Galeri Event — Pagination / Infinite Scroll

Tidak ada requirement otomatis untuk:

- pagination;
- infinite scroll;
- load more.

Strategi list hanya ditambahkan jika volume data nyata memerlukannya dan requirement diperbarui.

Default Fase 1:

> tampilkan data dengan cara paling sederhana yang sesuai scope dan performa.

---

# 26. Loading State

Untuk data async:

- hindari layout shift besar;
- jangan menampilkan blank page;
- gunakan state sederhana.

Jika Figma tidak menyediakan skeleton:

> skeleton generik tidak wajib.

Gunakan loading state minimal yang konsisten dengan visual.

---

# 27. Error State

Jika request content gagal:

- halaman tidak boleh crash;
- tampilkan fallback/error message jika diperlukan;
- jangan menampilkan data fiktif.

Error state visual yang tidak didesain harus dibuat minimal.

---

# 28. Empty Data

Jika suatu section dinamis tidak memiliki data:

behavior harus ditentukan berdasarkan pentingnya section.

Pilihan:

- sembunyikan section jika optional;
- tampilkan empty state jika user perlu tahu;
- tampilkan fallback content hanya jika approved.

Jangan membuat content palsu untuk mengisi ruang kosong.

---

# 29. External Links

Link menuju:

- social media;
- Google Maps;
- website partner;

harus menggunakan URL resmi.

Gunakan `target="_blank"` hanya jika memang sesuai konteks.

Tambahkan `rel="noopener noreferrer"` jika membuka tab baru.

---

# 30. Hover State

Hover hanya relevan untuk pointer device.

Informasi penting tidak boleh hanya tersedia melalui hover.

Jika Figma menunjukkan hover effect:

> implementasikan.

Jika tidak:

> jangan menambah hover scale/elevation generik.

---

# 31. Focus State

Semua interactive control harus memiliki focus yang usable.

Jika focus visual default browser terlalu mengganggu desain:

- boleh distyle ulang;
- jangan dihapus tanpa pengganti.

---

# 32. Disabled State

Disabled state hanya dibuat untuk control yang memang bisa disabled.

Contoh:

- submit button saat request;
- pagination unavailable;
- action yang tidak tersedia.

Jangan menambah disabled state spekulatif pada komponen yang tidak memerlukannya.

---

# 33. Animation

Animation hanya digunakan jika:

- terlihat di Figma/prototype;
- mendukung behavior;
- atau diperlukan untuk transisi interaction.

Jangan menambah:

- hover lift;
- scale;
- fade;
- parallax;
- auto-animation;

sebagai default.

Perhatikan `prefers-reduced-motion`.

---

# 34. Responsive Interaction

Tablet/mobile tidak memiliki frame Figma.

Behavior responsive mengikuti `RESPONSIVE.md`.

Hal penting:

- hover-only behavior harus memiliki fallback touch;
- menu harus usable;
- modal harus muat viewport;
- chip/filter tetap dapat dipilih;
- CTA tetap mudah diakses.

Status:

`[RESPONSIVE FALLBACK]`

---

# 35. Keyboard Interaction

Minimal:

## Navbar / Links

- dapat dicapai dengan Tab;
- Enter mengaktifkan link.

## Filter

- control dapat difokuskan;
- Space/Enter mengubah state jika menggunakan button semantics.

## Modal

- Escape menutup;
- close button dapat difokuskan.

## Sort Control

- menggunakan native/select/button semantics yang keyboard-accessible.

---

# 36. Data Submission Admin

Untuk panel admin:

- form validation dilakukan frontend untuk UX;
- backend validation tetap wajib;
- error field ditampilkan jelas;
- success feedback diberikan;
- submit tidak boleh membuat duplicate request tanpa guard yang sesuai.

Visual admin belum diatur oleh Figma Landing Page kecuali ada desain khusus.

---

# 37. Delete Confirmation

Untuk destructive action seperti:

- delete category;
- delete label;
- delete event;
- delete media;

gunakan confirmation step.

Bentuk confirmation dapat berupa:

- modal;
- native dialog;
- dedicated confirmation UI;

sesuai implementasi admin.

---

# 38. Upload Media

Admin upload harus menangani:

- valid file type;
- file size limit;
- failed upload;
- preview jika relevan;
- assignment metadata/label jika diperlukan.

Backend menjadi source of truth validation.

---

# 39. State Synchronization

Untuk filter/sorting:

- UI state harus sama dengan data yang sedang ditampilkan;
- reset harus benar-benar mengembalikan hasil;
- sort active state harus sesuai urutan data.

Jika query parameter digunakan:

> pertahankan consistency saat reload/navigation jika requirement menginginkannya.

Query parameter tidak otomatis wajib.

---

# 40. Komponen Interaktif Aktif

Behavior yang saat ini dikunci oleh requirement:

| Interaksi | Status |
|---|---|
| Wahana multi-select | Wajib |
| Wahana toggle label | Wajib |
| Wahana AND filter | Wajib |
| Wahana Cari | Wajib selama PRD belum diubah |
| Wahana Reset | Wajib |
| Galeri Event Terbaru/Terlama | Wajib |
| Modal/lightbox | Hanya jika desain/PRD final menggunakannya |
| Mitra auto-scroll | Tidak otomatis wajib |
| Tooltip sosial | Tidak otomatis wajib |
| Sticky filter | Tidak otomatis wajib |
| Infinite scroll | Tidak wajib |

---

# 41. Perbedaan Figma vs UI Spec

Jika ditemukan conflict:

```text
[UI SPEC CONFLICT]
```

catat di `TODO.md`:

```text
Page:
Section:
Figma node:
UI_SPEC behavior:
Figma behavior/visual:
Decision needed:
```

Jika conflict hanya visual:

> Figma menang.

Jika conflict menyangkut fungsi:

> PRD/business requirement harus diklarifikasi.

---

# 42. Definition of Done — Interaction

Interaction dianggap selesai jika:

- [ ] Behavior sesuai PRD/UI spec
- [ ] Visual state mengikuti Figma
- [ ] Keyboard behavior dasar berfungsi
- [ ] Touch fallback berfungsi jika relevan
- [ ] Error/empty state aman
- [ ] Tidak ada behavior spekulatif
- [ ] Responsive interaction usable
- [ ] TODO conflict sudah ditangani

---

# 43. Prinsip Akhir

> **Figma menentukan bagaimana UI terlihat.**

> **UI_SPEC menentukan bagaimana UI bertindak.**

Agent tidak boleh menggunakan `UI_SPEC.md` untuk membuat visual baru jika Figma sudah menentukan bentuknya.

Dan agent tidak boleh menghapus behavior wajib hanya karena state tersebut tidak digambar lengkap di Figma.
