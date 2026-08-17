# ARCHITECTURE.md
## Arsitektur Global Sistem — Kampoeng Radja

**Cakupan:** Berlaku untuk seluruh produk dalam ekosistem digital Kampoeng Radja.

Dokumen ini berisi keputusan arsitektur tingkat global, batas antar-layer, prinsip integrasi, dan fondasi yang digunakan lintas fase.

> Dokumen ini **bukan** source of truth untuk detail visual UI.  
> Seluruh keputusan visual frontend tetap mengikuti Figma approved dan dokumentasi produk terkait.

---

# 1. Tujuan Arsitektur

Arsitektur proyek dirancang agar:

- mendukung pengembangan bertahap per fase;
- menggunakan satu fondasi aplikasi yang konsisten;
- menghindari duplikasi autentikasi dan data pengguna;
- tetap mudah dikembangkan saat Fase 2 dan Fase 3 dimulai;
- memungkinkan Landing Page dan sistem internal berada dalam satu ekosistem;
- menjaga implementasi tetap maintainable tanpa mengorbankan akurasi desain Figma.

---

# 2. Arsitektur Aplikasi Utama

## 2.1 Laravel + Vue + Inertia.js

✅ **Keputusan arsitektur utama:**

Aplikasi menggunakan:

- Laravel sebagai backend utama;
- Vue untuk layer UI;
- Inertia.js sebagai penghubung Laravel dan Vue;
- Tailwind CSS sebagai utility styling layer.

Frontend **tidak dibangun sebagai SPA terpisah** yang bergantung pada REST API untuk navigasi utama.

---

## 2.2 Implikasi Arsitektur Inertia

Dengan keputusan ini:

- routing utama menggunakan Laravel route;
- route web berada di `routes/web.php`;
- page component Vue berada di area `resources/js/Pages/`;
- reusable component berada di `resources/js/Components/`;
- autentikasi menggunakan mekanisme session Laravel;
- Vue Router tidak digunakan untuk routing utama aplikasi kecuali di masa depan terdapat kebutuhan yang benar-benar terpisah;
- REST API tidak dibuat hanya untuk komunikasi Laravel ↔ halaman Inertia internal.

Controller Laravel dapat mengirim data ke page Vue melalui Inertia props.

Contoh konseptual:

```text
Browser
   ↓
Laravel Route
   ↓
Controller
   ↓
Inertia Response
   ↓
Vue Page
   ↓
Components
```

---

# 3. SEO Landing Page

Landing Page merupakan website publik dan harus memperhatikan SEO.

Penggunaan Laravel + Inertia **tidak boleh dianggap otomatis menjamin SEO sempurna**.

SEO tetap harus diperhatikan melalui:

- title halaman;
- meta description;
- semantic HTML;
- heading hierarchy;
- alt text;
- canonical URL bila dibutuhkan;
- Open Graph metadata bila dibutuhkan;
- struktur URL yang jelas;
- performa halaman;
- sitemap bila masuk kebutuhan produksi;
- robots configuration bila diperlukan.

Jika kemampuan indexing atau rendering client-side menjadi masalah pada tahap produksi, strategi seperti **Inertia SSR atau solusi rendering lain** dapat dievaluasi secara terpisah.

Keputusan SSR **belum otomatis menjadi bagian Fase 1** kecuali ditetapkan pada requirement/deployment.

---

# 4. Pembagian Layer Aplikasi

Secara umum aplikasi dibagi menjadi:

```text
Presentation Layer
├── Vue Pages
├── Vue Components
├── Layouts
└── Styling

Application Layer
├── Controllers
├── Requests / Validation
├── Services jika benar-benar diperlukan
└── Authorization

Domain / Data Layer
├── Models
├── Relationships
├── Query logic
└── Business rules

Persistence Layer
├── Migrations
├── Database
├── Storage
└── Seeders / Factories
```

Agent tidak harus membuat service/repository abstraction untuk setiap fitur.

> Abstraction hanya dibuat jika memang memberi manfaat nyata pada maintainability.

Hindari over-engineering pada Fase 1.

---

# 5. Routing

## 5.1 Public Route

Landing Page menggunakan route publik Laravel.

Contoh konseptual:

```text
/
tentang
wahana
galeri
...
```

Nama dan jumlah route final mengikuti:

1. `LANDING-PAGE/PRD.md`
2. desain Figma approved
3. `LANDING-PAGE/USER_FLOW.md`

Dokumen global ini **tidak mengunci jumlah halaman publik**.

---

## 5.2 Internal Route

Route sistem internal harus berada di balik autentikasi sesuai kebutuhan produk.

Contoh konseptual:

```text
/login

/internal/...
/admin/...
```

Path final tidak boleh diasumsikan dari contoh di atas.

Detail routing internal ditentukan pada PRD modul terkait. Kondisi implementation aktual dicatat di `LOG.md`; konsep akses dan matrix berada di `GLOBAL/ACCESS_CONTROL.md` dan `GLOBAL/ACCESS_CONTROL_MATRIX.md`.

---

# 6. Autentikasi

Autentikasi digunakan untuk pengguna internal perusahaan.

Fondasi autentikasi menggunakan mekanisme Laravel session.

Landing Page publik tidak memerlukan autentikasi.

Sistem tidak perlu membuat:

- JWT;
- Sanctum SPA token;
- OAuth flow khusus;

hanya untuk komunikasi standar antara Laravel dan Inertia.

Teknologi tersebut baru dipertimbangkan jika di kemudian hari terdapat kebutuhan API eksternal, mobile application, atau integrasi pihak ketiga.

---

# 7. Fondasi User dan Role

Fase 1 dapat mempertahankan atau menyiapkan fondasi dasar akun internal untuk menghindari perubahan besar pada fase berikutnya.

Model konseptual:

```text
users
roles
```

Hubungan konseptual:

```text
roles
  └── users
```

Role global awal:

- `super_admin`
- `admin`
- `user`

Namun struktur database final harus mengikuti hasil analisis kebutuhan dan migration yang benar-benar diperlukan.

---

## 7.1 Batas Workstream Aktif

Agent **tidak boleh membangun struktur data KPI/Closing atau modul internal lain sebelum requirement tersedia**, seperti:

```text
employees
kpi_records
kpi_assessments
daily_reports
closing_events
marketing_targets
```

atau tabel internal lain yang belum disetujui.

Landing Page tetap mengikuti PRD Fase 1. Dashboard hanya boleh berkembang untuk modul yang diaktifkan dan didokumentasikan pada `docs/DASHBOARD/`.

## 7.2 Schema Aktual vs Model Konseptual

Contoh entity pada dokumen arsitektur ini bersifat konseptual dan tidak membatalkan migration final. Daftar tabel, constraint, dan status implementasi aktual harus dibaca dari migration dan diringkas di `LOG.md`.

Per 15 Agustus 2026, source juga memiliki master karyawan, role, akun username/PIN, CMS final, serta Absensi. Keberadaan source tersebut tidak otomatis menetapkan permission atau requirement bisnis modul berikutnya.

---

# 8. Struktur Data Landing Page

Struktur data Landing Page harus ditentukan terutama oleh:

- `LANDING-PAGE/PRD.md`;
- `LANDING-PAGE/CONTENT.md`;
- `LANDING-PAGE/USER_FLOW.md`;
- requirement pengelolaan konten;
- kebutuhan aktual Figma.

Struktur database pada dokumen global ini bersifat **konseptual**, bukan migration specification final.

---

## 8.1 Entitas Konten Konseptual

Fase 1 dapat membutuhkan entitas seperti:

```text
categories
labels
wahana / attractions
wahana_photos

events
event_photos

news
promotions
partners
```

Tetapi agent **tidak boleh membuat seluruh tabel tersebut hanya karena tercantum di dokumen global**.

Sebelum membuat migration, agent wajib memastikan entitas tersebut memang diperlukan oleh PRD Fase 1.

---

## 8.2 Kategori dan Label Wahana

Jika fitur filter Wahana tetap menjadi requirement Fase 1, model konseptualnya:

```text
categories
  └── labels

wahana_photos
  ↔ labels
```

Satu foto atau item wahana dapat memiliki lebih dari satu label jika requirement memang memerlukannya.

Relasi many-to-many dapat digunakan melalui pivot table.

Contoh konseptual:

```text
label_wahana_photo
```

Nama tabel, field, foreign key, index, dan constraint final harus ditentukan saat migration dibuat.

---

## 8.3 Logika Filter

Jika PRD menetapkan beberapa label dapat dipilih sekaligus, logika filter harus mengikuti PRD.

Jangan mengubah logika:

- AND;
- OR;
- grouping per category;

berdasarkan asumsi teknis.

Jika aturan filter belum jelas, tandai sebagai:

`[PERLU KLARIFIKASI]`

di `LANDING-PAGE/TODO.md`.

---

# 9. Data Dinamis vs Presentasional

Tidak semua elemen Figma wajib langsung menjadi tabel database.

Agent harus membedakan:

## 9.1 Data Bisnis Dinamis

Data yang memang harus dikelola admin atau berubah secara operasional harus berasal dari backend/database.

Contoh:

- data wahana;
- event;
- berita;
- promosi;

**jika PRD menetapkannya sebagai konten dinamis.**

---

## 9.2 Data Presentasional

Elemen berikut tidak otomatis membutuhkan database:

- decorative text;
- static headline;
- visual ornaments;
- background;
- icon;
- section label;
- gambar presentasional sementara dari Figma.

Elemen presentasional dapat berada pada frontend atau asset lokal selama sesuai requirement.

> Jangan membuat CMS/database berlebihan hanya karena suatu teks atau gambar muncul di Figma.

---

# 10. Arsitektur Component Frontend

Komponen Vue harus dibuat berdasarkan pola desain yang benar-benar berulang.

Struktur umum:

```text
resources/js/
├── Components/
├── Layouts/
└── Pages/
```

Subfolder dapat ditambahkan berdasarkan kebutuhan nyata proyek.

---

## 10.1 Reusability

Prinsip:

> **Reusable jika visual dan behavior memang sama atau memiliki variasi yang terdefinisi.**

Agent tidak boleh memaksakan reuse jika menyebabkan perbedaan terhadap Figma.

Contoh:

Dua tombol boleh menggunakan satu komponen jika variasinya dapat direpresentasikan tanpa mengubah visual approved.

Sebaliknya, jangan memaksakan `BaseCard` generik untuk seluruh kartu jika desain Figma memiliki struktur yang berbeda secara signifikan.

---

## 10.2 Shared Component Lintas Produk

Komponen tidak otomatis ditempatkan sebagai komponen global hanya karena mungkin akan digunakan pada Fase 2/3.

Pindahkan komponen menjadi shared/global hanya jika:

- benar-benar dipakai di lebih dari satu area;
- API komponen sudah cukup stabil;
- tidak mengorbankan visual masing-masing produk.

Hindari premature abstraction.

---

# 11. Styling Architecture

Tailwind CSS menjadi utility styling utama.

Agent boleh menggunakan:

- utility class;
- extracted component;
- CSS tambahan;
- scoped style;

sesuai kebutuhan.

Namun:

> struktur styling harus dipilih berdasarkan kemampuan mencapai desain Figma secara akurat dan maintainable.

Agent tidak boleh memaksakan utility tertentu jika hasil visual menjadi menyimpang.

Nilai visual yang berasal dari Figma dapat menggunakan arbitrary value bila diperlukan, terutama jika nilai tersebut memang bagian dari desain approved.

Contoh konseptual:

```text
w-[1180px]
rounded-[22px]
tracking-[0.01em]
```

Penggunaan arbitrary value tidak perlu dihindari hanya demi membuat seluruh desain masuk ke token generik.

---

# 12. Design Token

Token global hanya digunakan untuk nilai yang benar-benar konsisten.

Contoh:

- brand primary;
- brand secondary;
- font family utama;
- container umum;
- radius umum jika memang konsisten.

Jika Figma memiliki nilai khusus pada suatu section, agent diperbolehkan menggunakan nilai khusus tersebut.

> Jangan mengubah desain Figma hanya agar seluruh nilai masuk ke design token global.

Referensi visual tetap:

`GLOBAL/BRAND_GUIDELINE.md`

dan Figma approved.

---

# 13. Asset Architecture

Aset frontend ditempatkan secara terstruktur dan tidak menggunakan external random URL sebagai default.

Lokasi final mengikuti `LANDING-PAGE/ASSETS.md`.

Secara konseptual aset dapat dipisahkan menjadi:

```text
brand/
icons/
illustrations/
wahana/
events/
gallery/
backgrounds/
```

Struktur folder aktual harus sederhana dan mengikuti kebutuhan implementasi.

---

## 13.1 Asset dari Figma

Selama aset produksi belum lengkap:

> aset Figma boleh dan harus diprioritaskan sebagai sumber sementara jika aset tersebut tersedia di desain.

Aset harus diekspor ke project dan tidak bergantung pada URL internal Figma pada runtime produksi.

---

# 14. Storage Upload

Untuk konten yang diunggah melalui sistem admin, aplikasi harus menggunakan storage abstraction Laravel.

Jangan menyimpan path upload secara sembarangan di folder source frontend.

Pilihan storage production masih terbuka:

- local storage;
- object storage / S3-compatible;
- solusi cloud lain.

Implementasi Fase 1 tidak boleh terlalu terikat pada satu provider jika keputusan hosting belum final.

---

# 15. Google Maps

✅ **Keputusan saat ini:**

Untuk kebutuhan lokasi sederhana di Landing Page, gunakan **Google Maps iframe embed**, bukan Google Maps JavaScript API.

Alasan:

- kebutuhan hanya menampilkan lokasi;
- implementasi lebih sederhana;
- tidak memerlukan fitur map interaktif custom;
- menghindari kompleksitas API yang belum dibutuhkan.

Jika Figma menggunakan representasi visual tertentu untuk map, ukuran container, radius, spacing, dan surrounding layout tetap harus mengikuti Figma.

---

# 16. Responsiveness

Responsive architecture mengikuti:

1. Figma approved;
2. `LANDING-PAGE/RESPONSIVE.md`;
3. kebutuhan teknis browser.

Breakpoint Tailwind tidak otomatis menjadi ukuran frame desain.

Agent boleh menggunakan breakpoint default maupun custom jika diperlukan untuk mempertahankan behavior desain.

> Jangan mengubah layout Figma hanya agar sesuai dengan breakpoint default Tailwind.

---

# 17. Skalabilitas Antar Fase

Fase baru harus menambah kemampuan proyek secara incremental.

Prinsip:

```text
Fase 1
Landing Page + fondasi

        ↓

Fase 2
KPI System

        ↓

Fase 3
Closing Event Marketing
```

Setiap fase memiliki dokumentasi sendiri.

Data dan komponen hanya dibagikan jika memang relevan.

---

# 18. Single Login Internal

Target jangka panjang:

> satu akun karyawan dapat digunakan untuk mengakses sistem internal yang menjadi haknya.

Hal ini berarti Fase 2 dan Fase 3 tidak seharusnya membuat sistem akun karyawan yang terpisah tanpa alasan khusus.

Detail authorization dan permission akan ditentukan pada PRD fase masing-masing.

---

# 19. Error Handling

Aplikasi harus menangani:

- record tidak ditemukan;
- data kosong;
- image tidak tersedia;
- validation error;
- unauthorized access;
- expired session;
- storage failure yang relevan.

UI tidak boleh crash hanya karena data opsional tidak tersedia.

Fallback visual harus tetap mengikuti aturan Figma/ASSETS jika tersedia.

---

# 20. Database Principles

Database mengikuti prinsip:

- normalized secukupnya;
- foreign key bila sesuai;
- index pada field pencarian/filter yang relevan;
- timestamp bila dibutuhkan;
- soft delete hanya jika ada requirement;
- hindari duplicate source of truth;
- hindari field yang dibuat untuk kebutuhan fase yang belum ada.

Jangan menambahkan tabel atau kolom spekulatif untuk Fase 2/3.

---

# 21. Migration Rule

Sebelum membuat atau mengubah migration:

1. cek PRD;
2. cek architecture;
3. cek model yang sudah ada;
4. cek migration existing;
5. pastikan perubahan berada dalam scope;
6. hindari destructive change tanpa alasan yang jelas.

Agent tidak boleh menjalankan perubahan schema besar hanya berdasarkan asumsi.

---

# 22. Keputusan Arsitektur yang Masih Terbuka

| Topik | Status | Dampak |
|---|---|---|
| Target hosting/server | Belum diputuskan | Deployment, queue, filesystem, SSL, environment |
| Storage media production | Belum diputuskan | Upload foto/video dan scalability |
| Multi-bahasa | Belum diputuskan | Database content dan UI |
| Inertia SSR | Belum diputuskan | SEO/rendering/performance |
| CDN media | Belum diputuskan | Performance asset publik |
| Strategy admin panel final | Perlu dikonfirmasi melalui PRD | Struktur route, authorization, CMS |

Keputusan terbuka **tidak boleh dianggap final oleh agent**.

---

# 23. Decision Log

| Tanggal | Keputusan | Status / Alasan |
|---|---|---|
| 09 Agu 2026 | Laravel + Vue + Inertia.js sebagai fondasi aplikasi | Final |
| 09 Agu 2026 | Laravel session untuk autentikasi internal | Final untuk fondasi saat ini |
| 09 Agu 2026 | Google Maps menggunakan iframe embed untuk kebutuhan lokasi sederhana | Final untuk Fase 1 saat ini |
| 09 Agu 2026 | Fondasi user/role dipertahankan untuk sistem internal berikutnya | Final secara konseptual |
| 11 Agu 2026 | Figma approved menjadi source of truth visual frontend | Final |
| 11 Agu 2026 | Reusable component tidak boleh mengorbankan akurasi Figma | Final |
| 11 Agu 2026 | Struktur data Landing Page di dokumen global bersifat konseptual; migration final harus berasal dari PRD | Final |
| 11 Agu 2026 | Aset Figma diprioritaskan sebagai aset sementara sampai aset produksi resmi tersedia | Final |

---

# 24. Batas Dokumen Ini

`ARCHITECTURE.md` menentukan arsitektur global.

Dokumen ini **tidak menentukan**:

- pixel spacing;
- ukuran font per section;
- posisi elemen;
- layout visual;
- image crop;
- detail animasi;
- warna section tertentu;
- isi copywriting final;
- frame responsive final.

Semua keputusan tersebut mengikuti dokumentasi Fase 1 dan Figma approved.

Jika architecture dan Figma tampak bertentangan pada **visual**, Figma menang untuk visual selama tidak melanggar requirement bisnis atau constraint teknis fundamental.

Jika konflik menyangkut **data, security, atau business logic**, jangan mengambil keputusan visual sebagai dasar arsitektur.
