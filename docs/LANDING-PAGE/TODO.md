# TODO.md
## Backlog Aktif — Fase 1 Landing Page Kampoeng Radja

Dokumen ini mencatat pekerjaan yang **masih harus dilakukan**, blocker, klarifikasi, deviasi, dan QA untuk Fase 1.

> **Rebaseline 15 Agustus 2026:** status implementasi aktual diringkas di `LOG.md`. Checklist di file ini adalah backlog requirement/QA Landing Page; item fungsional hanya dicentang jika memiliki bukti source/test, sedangkan item visual tetap terbuka sampai QA Figma dilakukan.

## Snapshot Implementasi Saat Rebaseline

| Area | Status aktual | Pekerjaan tersisa utama |
| --- | --- | --- |
| Halaman publik | `PARTIAL` | Visual/content/asset QA dan CMS minimum belum lengkap |
| Authentication | `IMPLEMENTED` | Destination setelah login masih perlu keputusan |
| Media & Berita admin | `IMPLEMENTED` | Detail page tetap di luar scope sampai diputuskan |
| Event Promo admin | `IMPLEMENTED` | Verifikasi asset/content production |
| Wahana guest | `PARTIAL` | Filter AND bekerja; data category/label dinamis dan CRUD admin belum sesuai PRD |
| Galeri Event guest | `PARTIAL` | Display/sort tersedia; CRUD admin belum ada |
| Mitra | `PARTIAL` | Public read tersedia; CRUD/asset resmi belum lengkap |

Dashboard Internal dan Absensi dilacak di `docs/DASHBOARD/` dan `LOG.md`, bukan di backlog Landing Page ini.

> **Prinsip utama:**  
> `TODO.md` bukan tempat menyimpan requirement lama yang sudah tidak berlaku.
>
> Item hanya dipertahankan jika masih relevan terhadap:
>
> - `PRD.md`
> - Figma approved
> - `FIGMA.md`
> - `FIGMA_ACCURACY.md`
> - `UI_SPEC.md`
> - `RESPONSIVE.md`

---

# 1. Status Label

Gunakan label berikut:

```text
[TODO]
[IN PROGRESS]
[BLOCKED]
[PERLU KLARIFIKASI]
[PERLU ASET RESMI]
[PERLU KONTEN RESMI]
[VISUAL DEVIATION]
[RESPONSIVE DEVIATION]
[DONE]
```

Checklist dicentang hanya jika sudah benar-benar diverifikasi.

---

# 2. Gate Wajib Sebelum Iterasi UI

Sebelum mengubah halaman/section frontend:

- [ ] Pastikan frame halaman tercatat di `FIGMA.md`
- [ ] Pastikan status frame `[APPROVED FOR DEVELOPMENT]`
- [ ] Identifikasi node section yang akan diubah
- [ ] Catat node tersebut di `FIGMA.md` jika belum ada
- [ ] Inspect ukuran/layout/typography/spacing/media/state
- [ ] Cocokkan asset dengan `ASSETS.md`
- [ ] Baca `FIGMA_ACCURACY.md`
- [ ] Baca behavior terkait di `UI_SPEC.md`/`USER_FLOW.md`
- [ ] Pastikan task tidak melebar ke scope lain

---

# 3. Status Frame Utama

Frame berikut sudah dianggap approved:

- [x] Beranda — `1:318`
- [x] Tentang Kami — `1:2`
- [x] Wahana — `1:149`
- [x] Galeri Event — `1:679`
- [x] Navbar — `1:285`
- [x] Footer — `1:251`
- [x] Logo utama — `1:675`
- [x] Logo footer — `1:677`

Detail Media & Berita:

- [x] Visual reference node `1:650` approved
- [ ] `[PERLU KLARIFIKASI]` Tentukan apakah halaman Detail Media & Berita termasuk scope implementasi Fase 1

---

# 4. Node Section yang Masih Perlu Dicatat

Agent boleh menemukan child node sendiri melalui integrasi Figma dan wajib memperbarui `FIGMA.md`.

## Beranda

- [ ] Hero
- [ ] Insight / Information
- [ ] Media & Berita section
- [ ] Promo / Event
- [ ] Wahana Unggulan
- [ ] Mitra / Sponsor
- [ ] Lokasi

## Tentang Kami

- [ ] Hero
- [ ] Profile / Intro jika ada
- [ ] History / Sejarah
- [ ] Vision / Mission
- [ ] Organization

## Wahana

- [ ] Header / Hero
- [ ] Filter
- [ ] Result / Grid
- [ ] Empty State jika ada desain khusus
- [ ] Lightbox / Detail jika ada desain khusus

## Galeri Event

- [ ] Header / Hero
- [ ] Sort Control
- [ ] Event List / Grid
- [ ] Event Card
- [ ] Lightbox / Detail jika ada desain khusus

---

# 5. Responsive Decision

Keputusan stakeholder:

- [x] Tim hanya membuat desain desktop
- [x] Tidak ada frame tablet
- [x] Tidak ada frame mobile
- [x] Tablet/mobile menggunakan `RESPONSIVE.md`
- [x] Status non-desktop adalah `[RESPONSIVE FALLBACK]`
- [x] Tablet/mobile tidak boleh diklaim pixel-accurate terhadap Figma

---

# 6. Audit Existing Project

Sebelum implementasi ulang:

- [x] Audit `routes/web.php`
- [x] Audit `resources/js/Pages/`
- [x] Audit `resources/js/Components/`
- [x] Audit `resources/js/Layouts/`
- [x] Audit controller existing
- [x] Audit model existing
- [x] Audit migration existing
- [x] Audit seeders/factories
- [x] Audit auth existing
- [ ] Audit Tailwind config
- [ ] Audit font loading
- [x] Audit package/dependency
- [x] Audit asset existing
- [ ] Audit apakah ada code lama yang harus dipertahankan atau dibuang

Tujuan:

> jangan membangun ulang fondasi yang sebenarnya sudah ada.

---

# 7. Database & Backend — Verifikasi Sebelum Mengubah

Jangan membuat migration hanya karena pernah tercantum pada TODO lama.

Untuk setiap entity, cek PRD dulu.

## Kandidat data Fase 1

- [ ] Verifikasi kebutuhan `categories`
- [ ] Verifikasi kebutuhan `labels`
- [ ] Verifikasi kebutuhan entity/item Wahana
- [ ] Verifikasi kebutuhan relasi label many-to-many
- [ ] Verifikasi kebutuhan `events`
- [ ] Verifikasi kebutuhan event photos
- [ ] Verifikasi kebutuhan `news`
- [ ] Verifikasi kebutuhan `promotions`
- [ ] Verifikasi kebutuhan `partners`
- [ ] Verifikasi kebutuhan `users`
- [ ] Verifikasi kebutuhan `roles`

Setelah kebutuhan jelas:

- [x] Review migration existing
- [ ] Buat/perbaiki migration hanya bila perlu
- [ ] Tambahkan foreign key/index yang relevan
- [ ] Pastikan tidak ada tabel Fase 2/3 yang dibuat spekulatif

---

# 8. Routing & Page Scope

- [x] Verifikasi route Beranda
- [x] Verifikasi route Tentang Kami
- [x] Verifikasi route Wahana
- [x] Verifikasi route Galeri Event
- [x] Verifikasi route Login
- [ ] Pastikan tidak ada route publik tambahan tanpa PRD
- [ ] Pastikan Detail Media & Berita belum dibuat sebelum keputusan scope

---

# 9. Shared Layout

Berdasarkan Figma approved:

- [ ] Implementasi/rapikan Navbar `1:285`
- [ ] Implementasi/rapikan Footer `1:251`
- [ ] Gunakan logo utama `1:675`
- [ ] Gunakan logo footer `1:677`
- [ ] Verifikasi desktop pada ukuran frame
- [ ] Implementasikan responsive fallback navbar
- [ ] Implementasikan responsive fallback footer

Jangan otomatis membuat `PageContainer`, `SectionWrapper`, atau `Base*` component tanpa kebutuhan nyata.

---

# 10. Beranda

Frame:

```text
1:318
```

## Mapping

- [ ] Catat node Hero
- [ ] Catat node Insight
- [ ] Catat node Media & Berita
- [ ] Catat node Promo/Event
- [ ] Catat node Wahana Unggulan
- [ ] Catat node Mitra
- [ ] Catat node Lokasi

## Implementasi

- [ ] Hero sesuai Figma
- [ ] Insight sesuai Figma
- [ ] Media & Berita sesuai Figma
- [ ] Promo/Event sesuai Figma
- [ ] Wahana Unggulan sesuai Figma
- [ ] Mitra sesuai Figma
- [ ] Lokasi sesuai Figma
- [ ] Footer sesuai Figma

## QA

- [ ] Desktop visual QA
- [ ] Responsive fallback tablet
- [ ] Responsive fallback mobile
- [ ] Asset mapping lengkap
- [ ] Content temporary/resmi status jelas

---

# 11. Tentang Kami

Frame:

```text
1:2
```

## Mapping

- [ ] Catat node Hero
- [ ] Catat node Profile/Intro jika ada
- [ ] Catat node History
- [ ] Catat node Vision/Mission
- [ ] Catat node Organization

## Implementasi

- [ ] Hero sesuai Figma
- [ ] Profile/Intro sesuai Figma jika ada
- [ ] History sesuai Figma
- [ ] Vision/Mission sesuai Figma
- [ ] Organization sesuai Figma
- [ ] Footer sesuai Figma

## QA

- [ ] Desktop visual QA
- [ ] Responsive fallback tablet
- [ ] Responsive fallback mobile
- [ ] Content resmi/fallback jelas

---

# 12. Wahana

Frame:

```text
1:149
```

## Mapping

- [ ] Catat node Header/Hero
- [ ] Catat node Filter
- [ ] Catat node Grid
- [ ] Catat node Empty State jika ada
- [ ] Catat node Lightbox/Detail jika ada

## Fungsi

- [ ] Category/label berasal dari data dinamis
- [x] Multi-label selection berfungsi
- [x] Logika AND berfungsi
- [x] Toggle label berfungsi
- [x] Cari berfungsi jika UI final tetap menggunakan pola apply
- [x] Reset berfungsi
- [x] Empty result aman
- [ ] Filter lintas kategori siap secara model data

## Admin

- [ ] CRUD category
- [ ] CRUD label
- [ ] Upload/manage item/foto Wahana
- [ ] Assignment label
- [ ] Authorization sesuai role

## QA

- [ ] Desktop visual QA
- [ ] Filter edge cases
- [ ] Responsive fallback tablet
- [ ] Responsive fallback mobile
- [ ] Lightbox/detail jika termasuk desain

---

# 13. Galeri Event

Frame:

```text
1:679
```

## Mapping

- [ ] Catat node Header/Hero
- [ ] Catat node Sort Control
- [ ] Catat node Event List/Grid
- [ ] Catat node Event Card
- [ ] Catat node Lightbox/Detail jika ada

## Fungsi

- [x] Event data tampil
- [x] Sorting Terbaru berfungsi
- [x] Sorting Terlama berfungsi
- [x] Empty state aman
- [x] Foto event tampil
- [ ] Lightbox/detail jika termasuk desain

## Admin

- [ ] CRUD event
- [ ] Upload/manage foto event
- [ ] Validation
- [ ] Authorization

## QA

- [ ] Desktop visual QA
- [ ] Sorting diuji
- [ ] Responsive fallback tablet
- [ ] Responsive fallback mobile

---

# 14. Media & Berita

## List / Section Beranda

- [x] Verifikasi data source
- [x] Verifikasi admin CRUD
- [x] Verifikasi thumbnail
- [x] Verifikasi content fields

## Detail Page

- [ ] `[PERLU KLARIFIKASI]` Apakah node `1:650` masuk scope?
- [ ] Jika YA → tambahkan route/page/behavior ke PRD
- [ ] Jika TIDAK → tandai node sebagai reference only untuk implementasi

Jangan membuat page detail sebelum keputusan ini dibuat.

---

# 15. Promo

Jika tetap termasuk scope:

- [x] Verifikasi fields sesuai schema final `event_promo`
- [x] Verifikasi CRUD admin
- [ ] Verifikasi active period
- [ ] Verifikasi asset
- [ ] Verifikasi display Figma

Jangan membuat promo fiktif untuk production.

---

# 16. Mitra / Sponsor

- [ ] Verifikasi partner yang tampil
- [ ] Verifikasi logo dan izin publikasi
- [ ] Verifikasi behavior UI dari Figma/UI spec
- [ ] Implementasi admin jika memang diperlukan PRD

Tidak ada kewajiban auto-scroll/pause/drag kecuali behavior final menetapkannya.

---

# 17. Authentication

- [x] Verifikasi login existing
- [x] Pastikan login untuk user internal
- [x] Pastikan guest tetap dapat mengakses halaman publik
- [x] Verifikasi role dasar
- [x] Pastikan user tanpa hak tidak dapat mengelola content

Jangan membuat dashboard KPI/Closing Event sebagai placeholder yang seolah sudah bagian produk.

Jika perlu destination setelah login dan sistem internal belum siap:

- [ ] Tentukan destination sementara secara eksplisit

---

# 18. Panel Admin Minimum

Scope PRD saat ini mencakup admin content minimum.

- [x] Tentukan struktur route admin
- [x] Tentukan layout admin minimum
- [ ] Tentukan content modules yang benar-benar wajib
- [ ] Implement Wahana management
- [ ] Implement Galeri Event management
- [x] Implement Media & Berita management jika aktif
- [x] Implement Promo management jika aktif
- [ ] Implement Mitra management jika aktif
- [x] Authorization admin/super admin
- [x] Validation untuk modul Media & Berita serta Event & Promotion
- [x] Upload/media handling untuk modul Media & Berita serta Event & Promotion

---

# 19. Struktur Organisasi

Status:

```text
[PRODUKSI RESMI — STATIC ASSET]
```

- [x] Struktur organisasi ditampilkan sebagai satu asset resmi dari stakeholder.
- [x] Path: `public/assets/about/struktur-organisasi-kampoeng-radja.png`.
- [x] Tidak memerlukan CMS atau penyusunan ulang data organisasi pada fase ini.

---

# 20. Multi-Bahasa

Status:

```text
[BELUM DIPUTUSKAN]
```

Baseline:

- [x] Bahasa Indonesia

Untuk Fase 1:

- [ ] Jangan membangun translation architecture sebelum ada requirement eksplisit

---

# 21. Konten

- [ ] Audit seluruh `[FIGMA SEMENTARA]`
- [ ] Audit seluruh `[PERLU KONTEN RESMI]`
- [ ] Kumpulkan data melalui `CONTENT_INTAKE_TEMPLATE.md`
- [ ] Update `CONTENT.md`
- [ ] Pastikan tidak ada fakta buatan agent
- [ ] Pastikan placeholder produksi sudah direview

---

# 22. Aset

- [ ] Inventaris seluruh asset Figma
- [ ] Catat node sumber
- [ ] Pastikan bukan screenshot full-frame
- [ ] Simpan di path project
- [ ] Tandai `[FIGMA SEMENTARA]`
- [ ] Update `ASSETS.md`
- [ ] Verifikasi crop/focal point
- [ ] Ganti dengan asset resmi hanya setelah validasi

Aset existing yang perlu dilengkapi node:

- [ ] `figma-news-1.png`
- [ ] `figma-news-2.png`
- [ ] `figma-news-3.png`

---

# 23. Visual QA Desktop

Untuk setiap halaman:

## Beranda

- [ ] Render viewport frame
- [ ] Side-by-side/overlay
- [ ] Fix Critical
- [ ] Fix Major
- [ ] Update status di `DELIVERY_CHECKLIST.md`

## Tentang Kami

- [ ] Render viewport frame
- [ ] Side-by-side/overlay
- [ ] Fix Critical
- [ ] Fix Major
- [ ] Update status

## Wahana

- [ ] Render viewport frame
- [ ] Side-by-side/overlay
- [ ] Fix Critical
- [ ] Fix Major
- [ ] Update status

## Galeri Event

- [ ] Render viewport frame
- [ ] Side-by-side/overlay
- [ ] Fix Critical
- [ ] Fix Major
- [ ] Update status

---

# 24. Responsive QA

Tablet/mobile menggunakan responsive fallback.

## Tablet

- [ ] Navigation usable
- [ ] Tidak overflow
- [ ] Hierarchy jelas
- [ ] Media proporsional
- [ ] Filter/modal usable
- [ ] Intermediate widths aman

## Mobile

- [ ] Navigation usable
- [ ] Tidak overflow
- [ ] Hierarchy jelas
- [ ] Typography readable
- [ ] Touch interaction usable
- [ ] Media crop masuk akal
- [ ] Filter/modal usable

Status final:

```text
[RESPONSIVE FALLBACK — VERIFIED]
```

---

# 25. Accessibility QA

- [ ] Semantic HTML
- [ ] Alt text image informatif
- [ ] Button/link semantics
- [ ] Keyboard navigation
- [ ] Focus state
- [ ] Modal focus/close behavior jika ada
- [ ] Informasi tidak hanya bergantung pada hover

---

# 26. Performance QA

- [ ] Image optimization
- [ ] Lazy loading hanya untuk media yang sesuai
- [ ] Hero/LCP tidak salah strategi loading
- [ ] Video optimized jika ada
- [ ] Tidak ada dependency berat tanpa alasan
- [ ] Tidak ada Figma temporary URL runtime
- [ ] Tidak ada asset blur signifikan

---

# 27. SEO QA

- [ ] Title per halaman
- [ ] Meta description
- [ ] Heading hierarchy
- [ ] Alt image
- [ ] Slug/route jelas
- [ ] Open Graph jika termasuk release scope
- [ ] Canonical/sitemap/robots jika diperlukan deployment

---

# 28. Build & Test

Setelah perubahan besar:

- [x] Frontend build berhasil — `npm.cmd run build`, 15 Agustus 2026
- [x] Backend test relevan berhasil — 50 test / 405 assertions, 15 Agustus 2026
- [ ] Tidak ada runtime error
- [ ] Tidak ada console error utama
- [ ] Route utama diuji
- [ ] Interaction utama diuji

Catat command dan hasil aktual pada handoff/review.

---

# 29. Cross-Browser

Sebelum go-live:

- [ ] Chrome
- [ ] Edge
- [ ] Firefox
- [ ] Safari jika environment/device tersedia

Fokus:

- layout;
- font rendering;
- form;
- modal;
- media;
- overflow;
- interaction.

---

# 30. Deployment

Status hosting:

```text
[PERLU KEPUTUSAN]
```

Sebelum production:

- [ ] Tentukan hosting
- [ ] Tentukan storage media
- [ ] Siapkan environment production
- [ ] Database production
- [ ] SSL
- [ ] Backup strategy
- [ ] Build/deploy process
- [ ] Staging jika digunakan

---

# 31. Go-Live Gate

Sebelum production:

- [ ] Semua halaman scope aktif selesai
- [ ] Desktop visual QA selesai
- [ ] Responsive fallback verified
- [ ] Tidak ada Critical visual deviation
- [ ] Major deviation sudah selesai/approved
- [ ] Tidak ada placeholder tidak sengaja
- [ ] Kontak/alamat/maps valid
- [ ] Asset legal/approved
- [ ] Build/test valid
- [ ] TODO kritis selesai
- [ ] `DELIVERY_CHECKLIST.md` diperbarui
- [ ] Stakeholder review selesai

---

# 32. Riwayat Implementasi Lama

Implementasi yang pernah dilakukan sebelum dokumentasi Figma-first **tidak dianggap selesai otomatis**.

Status lama seperti:

```text
route sudah ada
filter sudah ada
sorting sudah ada
modal sudah ada
build lulus
test lulus
```

harus diverifikasi kembali terhadap:

- PRD terbaru;
- Figma approved;
- architecture terbaru;
- QA terbaru.

Jika masih valid setelah verifikasi, baru tandai task terkait `[DONE]`.

---

# 33. Item yang Dihapus dari TODO Lama

Item berikut tidak lagi menjadi kewajiban otomatis:

```text
vue-grid-layout
BaseButton.vue
BaseCard.vue
BaseBadge.vue
BaseModal.vue
BaseTooltip.vue
LazyImage.vue
AutoScrollSlider.vue
mobile frame Figma
tablet frame Figma
API terpisah untuk Inertia
placeholder dashboard KPI
semua breakpoint Tailwind default
```

Jika nanti memang diperlukan, tambahkan kembali dengan dasar requirement yang jelas.

---

# 34. Prioritas Pengerjaan Saat Ini

Prioritas Landing Page setelah rebaseline:

## P0 — Requirement/data mismatch

1. Putuskan apakah schema Wahana boleh dikembangkan untuk category/label/assignment dinamis.
2. Putuskan scope Detail Media & Berita node `1:650`.
3. Lengkapi inventaris konten/aset production dan status placeholder.

## P1 — CMS minimum

4. Wahana/category/label management setelah keputusan schema.
5. Galeri Event/foto management.
6. Mitra management dan Site Settings bila dikonfirmasi.

## P2 — Visual/release QA

7. Lengkapi mapping child node Figma.
8. Desktop side-by-side/overlay untuk empat frame utama.
9. Responsive fallback, accessibility, performance, dan SEO QA.
10. Perbarui `DELIVERY_CHECKLIST.md` berdasarkan bukti aktual.

---

# 35. Prinsip Akhir

> **TODO hanya memuat pekerjaan yang benar-benar masih relevan.**

Jangan mencentang item berdasarkan implementasi lama tanpa verifikasi.

Urutan kerja Fase 1:

```text
Map Figma
   ↓
Audit Existing
   ↓
Implement
   ↓
Visual QA
   ↓
Responsive QA
   ↓
Functional QA
   ↓
Delivery
```
# 2026-08-11 — [BLOCKED: FIGMA ACCESS] / CSS fallback

- Kuota Figma MCP Starter telah habis. Stakeholder memberikan ekspor CSS desktop untuk Beranda, Tentang Kami, Wahana, Galeri Event, Navbar, dan Footer sebagai acuan fallback visual sementara.
- Semua deklarasi `url(.jpg)` pada ekspor tidak memuat URL atau file gambar nyata. Area tersebut memakai aset lokal yang sudah tersedia atau placeholder terdokumentasi sampai aset diberikan.
- Desktop belum dapat disebut Figma verified; CSS export tidak menggantikan QA overlay terhadap frame Figma.
- Beranda memakai `public/assets/temporary/hero-waterpark-v2.png` sebagai `[FIGMA SEMENTARA]` sampai aset video hero diterima. Aset ini adalah versi yang dibersihkan dari floating bar bawaan gambar atas instruksi stakeholder.
- Promo carousel memakai dua banner sementara di `public/assets/promotions/` atas instruksi stakeholder. Data CMS kini berasal dari schema final `event_promo`; `link_wa` bersifat opsional dan tombol detail nonaktif secara aman bila nilainya belum tersedia.
- Footer memakai alamat, telepon, email, dan deskripsi yang disediakan pada screenshot stakeholder sebagai `[FIGMA SEMENTARA]`, menunggu konfirmasi konten resmi.
- Tentang Kami memakai `public/assets/temporary/about-hero-aerial.png`, aset generatif sementara atas instruksi stakeholder.
- Blok cerita Tentang Kami memakai `about-foundation.png`, `about-hero-aerial.png`, dan `about-opening.png` sebagai aset generatif sementara atas instruksi stakeholder. Narasi dari screenshot tetap `[FIGMA SEMENTARA]` sambil menunggu konten resmi.
- Kartu fallback Wahana memakai lima aset generatif sementara: `ride-waterpark.png`, `ride-flying-fox.png`, `ride-go-kart.png`, `ride-perahu-bebek.png`, dan `ride-carousel.png`, atas instruksi stakeholder.
- 2026-08-12: Beranda mendapat visual refresh berdasarkan instruksi stakeholder terbaru dengan nuansa lebih fun, dominan biru dan aksen oranye. Refresh dibuat hanya melalui CSS (gradient, border, radius, shadow, hover) tanpa aset/elemen dekor tambahan; struktur, isi, dan behavior bisnis tidak diubah. Status desktop: QA browser lokal 1440px, bukan Figma verified. Mobile: responsive fallback diperiksa pada 375px.
