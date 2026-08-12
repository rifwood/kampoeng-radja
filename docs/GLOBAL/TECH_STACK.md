# TECH_STACK.md
## Stack Teknologi Global — Kampoeng Radja

**Cakupan:** Berlaku untuk seluruh produk dalam ekosistem digital Kampoeng Radja.

Dokumen ini mendefinisikan teknologi utama, konvensi teknis, dependency policy, dan struktur implementasi global.

> Dokumen ini **tidak menentukan desain visual**.
>
> Untuk Fase 1, keputusan visual tetap mengikuti Figma approved dan dokumentasi `LANDING-PAGE/`.

---

# 1. Stack Utama

| Layer | Teknologi | Status |
|---|---|---|
| Backend Framework | **Laravel** | ✅ Final |
| Frontend Bridge | **Inertia.js** | ✅ Final |
| Frontend Framework | **Vue 3** | ✅ Final |
| Vue Style | **Composition API** | ✅ Final |
| Styling | **Tailwind CSS** | ✅ Final |
| Database | **MySQL** | ✅ Final |
| Authentication | Laravel session-based authentication | ✅ Final |
| Location Map | Google Maps iframe embed | ✅ Final untuk kebutuhan lokasi sederhana Fase 1 |

---

# 2. Prinsip Versi Framework

Gunakan versi framework/package yang **sudah ditetapkan dan terpasang di project**, bukan otomatis melakukan upgrade ke versi terbaru.

Agent wajib memeriksa:

- `composer.json`
- `composer.lock`
- `package.json`
- lock file JavaScript yang digunakan

sebelum mengambil keputusan versi.

> Jangan melakukan major upgrade Laravel, Vue, Inertia, Tailwind, atau dependency lain di luar scope pekerjaan.

Istilah seperti **“versi LTS terbaru”** tidak boleh menjadi alasan untuk mengubah versi project yang sudah berjalan.

---

# 3. Arsitektur Frontend

Frontend Vue berjalan melalui Inertia.js.

Alur utama:

```text
Laravel Route
      ↓
Controller
      ↓
Inertia Response
      ↓
Vue Page
      ↓
Vue Components
```

Routing utama menggunakan Laravel.

Jangan membuat Vue Router untuk navigasi utama aplikasi kecuali ada requirement khusus yang disetujui.

---

# 4. Struktur Folder Utama

Struktur umum:

```text
resources/
└── js/
    ├── Pages/
    ├── Components/
    ├── Layouts/
    └── ...

routes/
└── web.php

app/
├── Http/
│   └── Controllers/
└── Models/

database/
├── migrations/
├── seeders/
└── factories/
```

Subfolder tambahan boleh dibuat jika benar-benar membantu organisasi kode.

Contoh yang mungkin:

```text
resources/js/
├── Components/
│   ├── Landing/
│   ├── Shared/
│   └── ...
├── Layouts/
└── Pages/
```

Struktur final harus mengikuti kebutuhan aktual, bukan dibuat terlalu kompleks sejak awal.

---

# 5. Page dan Component

## 5.1 Vue Page

Page component digunakan untuk halaman yang dirender melalui Inertia.

Contoh konseptual:

```text
Pages/
├── Home.vue
├── About.vue
└── Attractions.vue
```

Nama aktual mengikuti route dan struktur produk.

---

## 5.2 Component

Reusable component berada di `resources/js/Components/`.

Agent wajib membaca:

`LANDING-PAGE/COMPONENTS.md`

sebelum membuat abstraction frontend baru.

Prinsip:

> Reusable component dibuat jika visual dan behavior memang berulang.

Jangan memaksakan komponen generik jika hal tersebut membuat hasil berbeda dari Figma.

---

# 6. Styling Strategy

Tailwind CSS merupakan styling tool utama.

Agent boleh menggunakan:

- utility classes;
- responsive utilities;
- arbitrary values;
- extracted Vue components;
- CSS tambahan;
- scoped styles;

jika diperlukan.

Prioritasnya adalah:

1. akurat terhadap Figma;
2. mudah dipahami;
3. maintainable;
4. tidak over-engineered.

---

## 6.1 Arbitrary Value

Arbitrary values diperbolehkan jika Figma memiliki nilai spesifik.

Contoh:

```html
<div class="max-w-[1180px] rounded-[22px]">
```

Jangan mengganti nilai Figma menjadi token Tailwind default hanya demi menghindari arbitrary value.

---

## 6.2 Inline Style

Inline style boleh digunakan secara terbatas jika:

- nilai benar-benar dinamis;
- berasal dari data;
- lebih tepat dibanding class utility.

Jangan menggunakan inline style secara luas untuk menggantikan struktur styling yang seharusnya reusable.

---

# 7. Tailwind Configuration

`tailwind.config.*` hanya diperluas jika nilai tersebut benar-benar digunakan secara konsisten.

Contoh:

- brand color;
- font family global;
- breakpoint khusus yang memang dibutuhkan berulang;
- reusable design token.

Jangan memasukkan setiap nilai unik dari Figma ke konfigurasi Tailwind.

---

# 8. Responsive Implementation

Responsive behavior mengikuti:

1. Figma approved;
2. `LANDING-PAGE/RESPONSIVE.md`;
3. kebutuhan browser.

Agent tidak boleh memaksakan semua desain mengikuti breakpoint default Tailwind.

Custom breakpoint boleh digunakan jika memang dibutuhkan untuk mempertahankan visual behavior desain.

---

# 9. Konvensi Penamaan

## Database

Gunakan konvensi Laravel/MySQL:

```text
snake_case
plural
```

Contoh:

```text
users
categories
event_photos
```

---

## Vue Component

Gunakan:

```text
PascalCase
```

Contoh:

```text
HeroSection.vue
AttractionCard.vue
GalleryModal.vue
```

---

## PHP Class

Gunakan:

```text
PascalCase
```

sesuai standar Laravel/PSR.

---

## Route URL

URL publik gunakan format yang:

- mudah dibaca;
- konsisten;
- sesuai requirement;
- SEO-friendly bila relevan.

`kebab-case` direkomendasikan untuk multi-word URL.

Contoh:

```text
/tentang-kami
/galeri-event
```

Tetapi route final mengikuti PRD.

---

## Variables

JavaScript:

```text
camelCase
```

PHP:

ikuti konvensi Laravel/PSR.

---

# 10. Package Management

## PHP

Gunakan:

```text
Composer
```

`composer.lock` wajib dipertahankan dan di-commit.

---

## JavaScript

Gunakan package manager yang sudah digunakan project.

Agent wajib memeriksa lock file:

```text
package-lock.json
pnpm-lock.yaml
yarn.lock
```

Jika project menggunakan npm:

> terus gunakan npm.

Jangan mengganti package manager tanpa keputusan tim.

---

# 11. Dependency Policy

Agent **tidak boleh menambah dependency hanya karena implementasi lebih cepat**.

Sebelum memasang package baru:

1. cek apakah package serupa sudah tersedia;
2. cek apakah kebutuhan dapat diselesaikan dengan Vue/Tailwind/native browser;
3. cek ukuran dan maintenance package;
4. cek kompatibilitas dengan versi Vue/Laravel saat ini;
5. pastikan package memang membantu requirement;
6. hindari package yang hanya digunakan untuk satu efek sederhana.

Dependency baru harus memiliki alasan teknis yang jelas.

---

# 12. Library UI

Tidak ada library UI global yang wajib digunakan pada Fase 1.

Agent tidak boleh otomatis menambahkan:

- Vuetify;
- PrimeVue;
- Element Plus;
- Bootstrap;
- DaisyUI;
- shadcn-style component system;
- library UI lain;

kecuali secara eksplisit dibutuhkan.

Alasan utama:

> komponen generik dari UI library sering menyulitkan pencapaian visual Figma yang spesifik.

Landing Page Fase 1 sebaiknya dibuat menggunakan Vue + Tailwind + komponen custom sesuai desain.

---

# 13. Icon Library

Jangan menetapkan icon library global sebelum memeriksa Figma.

Prioritas:

1. SVG/icon dari Figma;
2. library yang sudah terpasang;
3. library ringan jika memang diperlukan.

Jika icon Figma custom tersedia, gunakan icon tersebut.

Jangan memasang library icon besar hanya untuk beberapa icon.

---

# 14. Slider / Carousel

Library carousel **tidak otomatis wajib**.

Jika desain Figma membutuhkan carousel:

1. cek apakah behavior dapat dibuat sederhana dengan native CSS/JS;
2. jika kompleks, pilih library ringan dan matang;
3. pastikan behavior identik dengan requirement;
4. jangan mengubah desain agar sesuai API library.

Contoh library seperti Swiper hanya merupakan opsi, bukan keputusan final.

---

# 15. Grid / Masonry

Tidak ada keputusan untuk menggunakan `vue-grid-layout`.

> `vue-grid-layout` tidak boleh dianggap dependency Fase 1 tanpa requirement teknis yang nyata.

Jika desain memerlukan:

- masonry;
- asymmetric grid;
- collage;
- editorial layout;

prioritaskan:

- CSS Grid;
- Flexbox;
- CSS columns jika tepat;

sebelum menambah dependency.

Library tambahan hanya dipakai jika layout/behavior tidak dapat dicapai secara maintainable dengan CSS standar.

---

# 16. Modal / Lightbox

Modal atau lightbox dapat dibuat dengan Vue + Tailwind/native browser jika behavior sederhana.

Library eksternal hanya dipakai jika terdapat kebutuhan seperti:

- advanced gallery navigation;
- zoom/pinch;
- gesture complex;
- accessibility behavior yang sulit direplikasi;
- kebutuhan khusus lain.

Pemilihan library harus tetap mempertahankan visual Figma.

---

# 17. Animation

Animation tidak memerlukan library otomatis.

Prioritas:

1. CSS transition/animation;
2. Vue transition;
3. Web Animations API bila sesuai;
4. library khusus hanya jika memang diperlukan.

Jangan memasang animation library untuk efek sederhana.

Animation final mengikuti Figma/UI spec jika tersedia.

---

# 18. Image Handling

Untuk Landing Page:

- gunakan asset lokal hasil export Figma jika tersedia;
- optimalkan format;
- pertahankan crop/aspect ratio desain;
- gunakan lazy loading pada image yang sesuai;
- jangan menurunkan kualitas sampai terlihat berbeda signifikan.

Format dapat berupa:

```text
SVG
WebP
AVIF
PNG
JPEG
```

tergantung jenis asset dan kebutuhan browser.

---

# 19. SVG

SVG direkomendasikan untuk:

- logo;
- icon;
- simple illustration;
- decorative vector.

Jika export SVG dari Figma memiliki struktur yang terlalu kompleks, optimasi diperbolehkan selama hasil visual tidak berubah.

---

# 20. Font

Font Fase 1 harus mengikuti Figma.

Teknik loading dapat menggunakan:

- package/font dependency legal;
- web font;
- local font asset yang memang tersedia untuk project.

Jangan mengganti font Figma tanpa dokumentasi.

---

# 21. Database

Database:

```text
MySQL
```

Gunakan Eloquent untuk interaksi data utama.

Raw query diperbolehkan jika benar-benar diperlukan untuk performa atau query kompleks, tetapi bukan default.

---

# 22. Migration

Migration harus mengikuti Laravel convention.

Nama migration mengikuti generator Laravel.

Contoh konseptual:

```text
create_events_table
add_published_at_to_news_table
```

Agent tidak boleh membuat migration spekulatif untuk fase berikutnya.

---

# 23. Validation

Gunakan validation Laravel.

Untuk request yang kompleks atau reusable, gunakan Form Request.

Jangan hanya mengandalkan validation frontend.

---

# 24. Authentication

Gunakan session-based Laravel authentication.

Tidak perlu Sanctum SPA token untuk flow Inertia standar.

Jika project saat ini sudah memiliki autentikasi dasar, pertahankan dan modifikasi hanya jika requirement membutuhkan.

---

# 25. Authorization

Authorization dapat menggunakan:

- middleware;
- policies;
- gates;

sesuai kompleksitas fitur.

Jangan membangun permission framework kompleks pada Fase 1 jika belum diperlukan.

---

# 26. Maps

Untuk section lokasi sederhana Fase 1:

> gunakan Google Maps iframe embed.

Jangan memasang Google Maps JavaScript API hanya untuk menampilkan lokasi statis.

Jika kebutuhan berubah menjadi interactive/custom map, keputusan arsitektur harus diperbarui terlebih dahulu.

---

# 27. SEO

Landing Page publik harus mendukung praktik SEO dasar.

Implementasi dapat menggunakan kemampuan Laravel/Inertia yang tersedia.

Jika metadata per halaman dibutuhkan, implementasikan secara konsisten.

Jangan menambah framework SEO besar tanpa kebutuhan nyata.

---

# 28. Accessibility

Gunakan HTML semantic dan interaction pattern yang tepat.

Frontend stack tidak boleh dijadikan alasan untuk menggunakan:

```text
<div @click>
```

untuk seluruh elemen interaktif jika sebenarnya harus menjadi button/link.

---

# 29. Testing

Testing harus proporsional terhadap scope.

Backend/business logic penting dapat menggunakan PHPUnit/Pest sesuai setup project.

Frontend tidak diwajibkan menambah testing framework baru pada Fase 1 hanya demi memenuhi checklist.

Jika project sudah memiliki frontend testing stack, gunakan yang tersedia.

---

# 30. Code Quality

Ikuti standar project yang sudah ada.

Agent harus memeriksa konfigurasi seperti:

- ESLint;
- Prettier;
- Laravel Pint;
- formatter/linter lain;

jika tersedia.

Jangan menambahkan tool format baru jika project sudah memiliki standar.

---

# 31. Environment

Gunakan `.env` untuk configuration yang environment-specific.

Jangan commit:

- password;
- API key;
- secret;
- credential production.

Gunakan `.env.example` untuk key yang perlu diketahui developer.

---

# 32. Environment Separation

Target:

```text
local
staging
production
```

Namun keberadaan staging bergantung pada deployment strategy yang nanti dipilih.

---

# 33. Deployment

Target hosting belum final.

Pilihan dapat meliputi:

- shared hosting;
- VPS;
- managed hosting;
- cloud infrastructure.

Keputusan deployment harus mempertimbangkan:

- Laravel compatibility;
- build process;
- storage;
- SSL;
- database;
- backup;
- queue jika nantinya diperlukan;
- media volume.

---

# 34. Storage

Storage media production belum final.

Gunakan Laravel filesystem abstraction agar implementasi tidak terlalu terikat pada provider tertentu.

Pilihan potensial:

- local storage;
- S3-compatible object storage;
- cloud storage provider.

---

# 35. Hal yang Tidak Boleh Dilakukan Agent

Agent tidak boleh:

- melakukan major framework upgrade tanpa instruksi;
- mengganti package manager;
- menambah UI framework tanpa kebutuhan;
- memasang `vue-grid-layout` hanya karena pernah disebut;
- memasang carousel/lightbox library sebelum mengecek kebutuhan;
- membuat Vue Router untuk routing utama;
- membuat REST API hanya untuk page Inertia;
- mengganti Tailwind dengan CSS framework lain;
- memaksakan default Tailwind sehingga visual menyimpang dari Figma;
- memasang dependency hanya untuk mempercepat coding;
- membuat abstraction teknis yang belum diperlukan.

---

# 36. Prinsip Akhir

Untuk Fase 1:

> **Gunakan stack yang sudah ada untuk menerjemahkan Figma ke implementasi secara akurat.**

Teknologi dan library adalah alat.

Agent tidak boleh mengubah visual atau requirement hanya agar sesuai dengan preferensi teknis tertentu.

Jika implementasi Figma dapat dicapai dengan Vue + Tailwind + browser API bawaan:

> prioritaskan solusi tersebut sebelum menambah dependency baru.
