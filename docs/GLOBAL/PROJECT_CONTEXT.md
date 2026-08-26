# PROJECT_CONTEXT.md
## Konteks Global Proyek — Sistem Digital Kampoeng Radja

**Perusahaan:** Taman Wisata Kampoeng Radja, Jambi  
**Cakupan dokumen:** Global — berlaku sebagai konteks dasar untuk seluruh produk dalam proyek Kampoeng Radja.

---

## 1. Tujuan Dokumen

Dokumen ini menjelaskan konteks utama proyek Kampoeng Radja agar developer, AI coding agent, designer, dan pihak lain memiliki pemahaman yang sama mengenai:

- latar belakang proyek;
- produk yang akan dikembangkan;
- urutan fase pengembangan;
- tujuan bisnis;
- struktur pengguna secara umum;
- hubungan antarproduk;
- prinsip dasar proyek;
- sumber acuan utama yang harus digunakan selama pengembangan.

Dokumen ini **tidak digunakan untuk menentukan detail visual, struktur komponen frontend, atau implementasi teknis halaman tertentu**.

Detail tersebut berada pada dokumentasi produk masing-masing.

---

## 2. Latar Belakang

Taman Wisata Kampoeng Radja merupakan perusahaan wisata keluarga di Jambi yang membutuhkan ekosistem digital berbasis web untuk mendukung:

1. pemasaran dan penyampaian informasi kepada publik;
2. pengelolaan konten perusahaan;
3. manajemen kinerja karyawan;
4. proses operasional internal;
5. pengelolaan proses closing event marketing.

Pengembangan dilakukan secara bertahap agar setiap sistem dapat dirancang, diuji, dan dikembangkan secara terstruktur.

---

## 3. Produk yang Direncanakan

Ekosistem digital Kampoeng Radja terdiri dari website publik dan sistem internal yang dikembangkan bertahap.

| # | Produk | Folder Dokumentasi | Status |
|---|---|---|---|
| 1 | **Landing Page / Company Profile** | `docs/LANDING-PAGE/` | **Aktif — implementasi parsial** |
| 2 | **Dashboard Internal Foundation** | `docs/DASHBOARD/` | **Aktif terbatas — Absensi dan fondasi master data** |
| 3 | **Sistem KPI Karyawan** | Belum dibuat | **Planned / belum aktif** |
| 4 | **Sistem Closing Event Marketing** | `docs/DASHBOARD/CLOSING-EVENT/` | **Aktif — implementasi tersedia** |

Setiap produk akan memiliki dokumentasi sendiri yang menjelaskan requirement, alur pengguna, UI, data, komponen, dan aturan implementasinya.

Dokumentasi produk hanya dibuat atau dikembangkan ketika fase tersebut sudah mulai dikerjakan.

---

## 4. Fokus Pengembangan Saat Ini

Fokus proyek saat ini terbagi menjadi:

> **Landing Page / Company Profile yang belum release-ready**

> **Dashboard Internal Foundation untuk modul yang sudah memiliki requirement aktif**

Landing page berfungsi sebagai website publik resmi Kampoeng Radja.

Pada fase ini pekerjaan difokuskan pada:

- implementasi desain Figma;
- penyajian informasi perusahaan;
- informasi wahana;
- galeri dan event;
- responsive website;
- pengelolaan aset visual;
- struktur frontend yang dapat dikembangkan kembali pada fase selanjutnya.

Data Absensi Super Admin sudah menjadi workstream aktif berdasarkan requirement stakeholder. Kelola Karyawan menjadi dependency berikutnya, tetapi detail requirement dan permission masih harus disediakan.

Fitur internal KPI tetap **belum aktif**. Closing Event Marketing sudah diaktifkan melalui PRD final dan diimplementasikan sebagai modul Dashboard Internal.

---

## 5. Tujuan Bisnis

Ekosistem digital Kampoeng Radja dikembangkan untuk mencapai beberapa tujuan berikut.

### 5.1 Branding dan Company Profile

Menghadirkan citra Kampoeng Radja sebagai taman wisata keluarga yang:

- fun;
- playful;
- colorful;
- ramah keluarga;
- profesional;
- terpercaya.

Website publik harus mampu merepresentasikan identitas tersebut secara visual dan komunikasi.

### 5.2 Informasi Publik

Memberikan informasi yang mudah diakses oleh calon pengunjung mengenai:

- Kampoeng Radja;
- wahana;
- fasilitas;
- aktivitas;
- event;
- galeri;
- informasi lain yang tersedia pada desain dan konten resmi.

Landing page dapat diakses tanpa autentikasi.

### 5.3 Digitalisasi Sistem Internal

Pada fase selanjutnya, proyek akan menyediakan sistem internal untuk membantu perusahaan mengelola:

- KPI karyawan;
- proses penilaian kinerja;
- aktivitas operasional terkait KPI;
- proses Closing Event Marketing;
- data internal lain yang akan ditentukan pada fase berikutnya.

---

## 6. Struktur Jabatan Global

Struktur jabatan perusahaan secara umum:

**Dirut → Direktur → Manager → Supervisor (SPV) → Mitra → Ops → FLT / Facility**

Untuk kebutuhan sistem digital, jabatan dapat dipetakan menjadi tiga kelompok akses utama.

| Role Sistem | Jabatan Terkait | Cakupan Umum |
|---|---|---|
| **Super Admin** | Dirut, Direktur, Super Admin Sistem | Akses penuh terhadap sistem dan konfigurasi yang diizinkan |
| **Admin** | Manager, Supervisor / SPV | Pengelolaan data, tim, atau konten sesuai kewenangan |
| **User** | Mitra, Ops, FLT / Facility | Akses terhadap fitur operasional yang relevan dengan tugas masing-masing |

Role di atas hanya merupakan **pemetaan global**.

Hak akses sebenarnya harus ditentukan secara spesifik pada PRD masing-masing produk.

Tidak boleh mengasumsikan bahwa seluruh fitur menggunakan matriks permission yang sama.

---

## 7. Prinsip Autentikasi

Landing page merupakan website publik.

Pengunjung **tidak memerlukan login** untuk mengakses halaman publik.

Autentikasi digunakan untuk sistem internal perusahaan.

Jika akses login ditampilkan pada landing page, posisinya mengikuti desain Figma dan requirement Fase 1 yang berlaku.

Agent tidak diperbolehkan menambahkan:

- form login;
- tombol login;
- registrasi;
- authentication flow;
- menu internal;

ke area publik apabila tidak terdapat dalam requirement atau desain yang menjadi acuan.

---

## 8. Prinsip Source of Truth

Pengembangan proyek menggunakan beberapa jenis dokumentasi.

Untuk menghindari konflik requirement, gunakan urutan prioritas berikut.

### 8.1 Requirement Bisnis

Requirement bisnis ditentukan oleh:

1. `PROJECT_CONTEXT.md`
2. PRD produk terkait
3. keputusan terbaru yang sudah didokumentasikan

### 8.2 Implementasi Visual

Untuk seluruh pekerjaan frontend yang memiliki desain Figma:

> **Figma adalah source of truth utama untuk tampilan visual.**

Hal ini mencakup:

- struktur halaman;
- komposisi layout;
- posisi elemen;
- hierarchy visual;
- typography;
- ukuran font;
- warna;
- spacing;
- padding;
- margin;
- ukuran komponen;
- border;
- radius;
- shadow;
- icon;
- image;
- illustration;
- responsive layout;
- visual state yang tersedia pada desain.

Dokumentasi seperti:

- `FIGMA.md`
- `UI_SPEC.md`
- `COMPONENTS.md`
- `RESPONSIVE.md`

berfungsi menjelaskan dan menerjemahkan desain tersebut agar dapat diimplementasikan secara konsisten.

Dokumentasi tersebut **tidak boleh digunakan untuk mengubah desain Figma secara sepihak**.

---

## 9. Aturan Implementasi Desain

Ketika desain Figma tersedia, developer atau coding agent harus:

1. memeriksa frame Figma yang sesuai;
2. memahami struktur dan hierarchy desain;
3. menggunakan nilai visual dari Figma sebagai acuan utama;
4. menggunakan aset dari Figma apabila aset produksi resmi belum tersedia;
5. mempertahankan proporsi dan komposisi desain;
6. menerapkan responsive behavior berdasarkan desain dan dokumentasi responsive;
7. menghindari improvisasi visual yang tidak diperlukan.

Target implementasi frontend adalah:

> **semirip mungkin dengan desain Figma pada ukuran viewport yang setara.**

Agent tidak diperbolehkan mengganti desain hanya karena terdapat alternatif implementasi yang dianggap lebih sederhana atau lebih umum.

---

## 10. Kebijakan Aset Sementara

Selama aset produksi resmi Kampoeng Radja belum tersedia secara lengkap:

> aset visual yang terdapat pada Figma dapat digunakan sebagai aset sementara untuk implementasi.

Aset tersebut dapat meliputi:

- logo;
- foto;
- background;
- icon;
- illustration;
- decorative element;
- image wahana;
- image event;
- visual pendukung lainnya.

Aturan penggunaan, penamaan, export, format, dan lokasi penyimpanan aset dijelaskan lebih lanjut dalam:

`docs/LANDING-PAGE/ASSETS.md`

Agent tidak diperbolehkan mengganti aset Figma dengan placeholder atau aset internet secara acak apabila aset yang benar tersedia di Figma.

---

## 11. Prinsip Pengembangan

Seluruh pengembangan Kampoeng Radja mengikuti prinsip berikut.

### Requirement First

Implementasi harus berdasarkan requirement yang sudah terdokumentasi.

### Figma First untuk Visual

Untuk halaman yang memiliki desain Figma, keputusan visual mengikuti Figma.

### No Unnecessary Assumption

Agent tidak boleh membuat requirement bisnis baru berdasarkan asumsi.

### No Unnecessary Redesign

Agent tidak boleh melakukan redesign terhadap desain Figma tanpa instruksi.

### Reusable Architecture

Komponen yang memiliki pola sama dapat dibuat reusable selama tidak mengubah tampilan akhir.

### Responsive by Design

Implementasi harus mendukung desktop, tablet, dan mobile berdasarkan dokumentasi desain.

### Maintainability

Kode harus tetap memiliki struktur yang jelas, reusable, dan dapat dikembangkan pada fase berikutnya.

---

## 12. Scope Fase 1 — Landing Page

Fase 1 berfokus pada website company profile Kampoeng Radja.

Halaman publik yang dikembangkan mengikuti:

- PRD Landing Page;
- struktur navigasi yang telah disepakati;
- desain Figma terbaru;
- dokumentasi Fase 1.

Jumlah halaman, section, nama navigasi, dan konten final **mengikuti PRD dan desain Figma Fase 1**, bukan asumsi dari dokumen global ini.

---

## 13. Dokumentasi Fase 1

Dokumentasi Landing Page berada di:

`docs/LANDING-PAGE/`

Dokumen utama yang digunakan:

- `PRD.md` — requirement produk;
- `FIGMA.md` — referensi file, page, frame, dan node Figma;
- `UI_SPEC.md` — spesifikasi visual;
- `CONTENT.md` — konten teks;
- `ASSETS.md` — aset visual;
- `USER_FLOW.md` — alur pengguna;
- `COMPONENTS.md` — struktur dan aturan komponen;
- `RESPONSIVE.md` — perilaku responsive;
- `REFERENCE.md` — referensi tambahan;
- `TODO.md` — pekerjaan yang belum selesai.

Semua dokumentasi Fase 1 harus konsisten satu sama lain.

Jika ditemukan konflik antar dokumen, developer atau agent tidak boleh memilih secara acak dan harus mengikuti hierarki source of truth yang telah ditentukan.

---

## 14. Batasan Dokumen Global

`PROJECT_CONTEXT.md` hanya menjelaskan konteks tingkat proyek.

Dokumen ini tidak boleh digunakan untuk menentukan secara langsung:

- ukuran font;
- pixel spacing;
- struktur grid;
- breakpoint;
- nama component Vue;
- struktur folder frontend;
- Tailwind class;
- animation;
- asset filename;
- detail layout halaman.

Detail tersebut harus berada pada dokumentasi produk dan dokumentasi teknis yang sesuai.
