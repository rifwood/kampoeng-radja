# CONTENT.md
## Inventaris Konten — Fase 1 Landing Page Kampoeng Radja

Dokumen ini mencatat seluruh kebutuhan konten teks dan data presentasional untuk Landing Page Kampoeng Radja.

> **Prinsip utama:**  
> Konten yang terlihat di Figma dapat digunakan untuk mereproduksi visual development, tetapi **tidak otomatis menjadi fakta atau konten produksi**.
>
> Konten dari Figma yang belum dikonfirmasi perusahaan harus diberi status:
>
> `[FIGMA SEMENTARA]`
>
> Agent tidak boleh mengarang fakta perusahaan.

---

# 1. Fungsi Dokumen

`CONTENT.md` digunakan untuk:

- mencatat konten yang dibutuhkan setiap halaman/section;
- membedakan konten resmi dan sementara;
- mencatat sumber konten;
- mencatat status approval;
- membantu agent mengetahui kapan harus menggunakan copy Figma;
- mencegah fakta fiktif masuk ke implementasi;
- menghubungkan kebutuhan copy dengan `CONTENT_INTAKE_TEMPLATE.md`.

Dokumen ini **tidak menentukan layout visual**.

Layout mengikuti Figma approved.

---

# 2. Status Konten

Gunakan status berikut:

| Status | Arti |
|---|---|
| `[PRODUKSI RESMI]` | Konten sudah disetujui perusahaan dan boleh dipakai produksi |
| `[FIGMA SEMENTARA]` | Konten berasal dari Figma dan digunakan untuk menyamai desain, belum dianggap fakta final |
| `[PERLU KONTEN RESMI]` | Konten produksi belum tersedia |
| `[PLACEHOLDER TERDOKUMENTASI]` | Placeholder sementara hanya karena konten Figma juga tidak tersedia |
| `[PERLU KLARIFIKASI]` | Isi/requirement belum jelas |
| `[TIDAK DIPERLUKAN]` | Konten tidak lagi termasuk scope final |

---

# 3. Hierarki Sumber Konten

Untuk konten teks/data:

1. **Konten resmi perusahaan yang sudah disetujui**
2. **Konten yang tercantum pada PRD/dokumen resmi**
3. **Copy pada Figma approved sebagai `[FIGMA SEMENTARA]`**
4. Placeholder terdokumentasi jika benar-benar tidak ada sumber lain

Agent tidak boleh membuat:

- sejarah perusahaan;
- visi/misi;
- alamat;
- jam operasional;
- klaim promosi;
- nama partner;
- nama jabatan;
- fakta wahana;
- fakta event;

berdasarkan asumsi.

---

# 4. Konten Figma

Jika copy Figma diperlukan agar layout dapat direproduksi dengan akurat:

> copy tersebut boleh digunakan sementara.

Contoh pencatatan:

```text
Section: Home Hero
Copy: "..."
Source: Figma node 123:456
Status: [FIGMA SEMENTARA]
```

Copy Figma tidak boleh otomatis dianggap sebagai fakta perusahaan.

---

# 5. Placeholder Policy

Placeholder hanya digunakan jika:

1. konten resmi belum tersedia;
2. Figma juga tidak menyediakan copy yang bisa digunakan;
3. UI perlu tetap dibangun.

Gunakan placeholder eksplisit seperti:

```text
[PLACEHOLDER: Deskripsi Promo]
```

Jangan menggunakan lorem ipsum sebagai default jika placeholder deskriptif lebih jelas.

---

# 6. Beranda

Daftar section aktual harus mengikuti PRD dan Figma approved.

| Section | Konten Dibutuhkan | Sumber Saat Ini | Status | Catatan |
|---|---|---|---|---|
| Hero | Headline, subheadline/tagline, CTA | `[PERLU CEK FIGMA]` | `[PERLU KLARIFIKASI]` | Gunakan copy Figma jika tersedia |
| Insight / Informasi | Judul, isi/poin informasi | `[PERLU CEK FIGMA]` | `[PERLU KLARIFIKASI]` | Jangan mengarang jam/aturan |
| Media / Berita | Judul, tanggal, ringkasan, thumbnail, link/detail jika ada | `[PERLU CEK FIGMA]` | `[PERLU KONTEN RESMI]` | Data Figma boleh dipakai sementara |
| Promo / Event | Judul, periode, deskripsi, CTA, image | `[PERLU CEK FIGMA]` | `[PERLU KONTEN RESMI]` | Klaim promo harus resmi |
| Wahana Unggulan | Nama, deskripsi, image | `[PERLU CEK FIGMA]` | `[PERLU KONTEN RESMI]` | Item demo Figma dapat sementara |
| Mitra / Sponsor | Nama/logo partner | `[PERLU CEK FIGMA]` | `[PERLU KONTEN RESMI]` | Wajib izin publikasi |
| Lokasi | Alamat, jam operasional, link Maps, CTA | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | Fakta harus diverifikasi |
| Footer | Kontak, alamat, social links, copyright | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | Struktur visual mengikuti Figma |

---

# 7. Tentang Kami

| Section | Konten Dibutuhkan | Sumber Saat Ini | Status | Catatan |
|---|---|---|---|---|
| Hero | Headline/slogan/subheadline | `[PERLU CEK FIGMA]` | `[PERLU KLARIFIKASI]` | Copy Figma dapat sementara |
| Profil Perusahaan | Deskripsi singkat/panjang | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | Jangan mengarang |
| Sejarah / Kisah | Narasi kronologis, milestone | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | Fakta perusahaan |
| Visi | Pernyataan visi | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | |
| Misi | Daftar misi | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | |
| Struktur Organisasi | Nama/jabatan/foto jika dipublikasikan | `[PERLU KONTEN RESMI]` | `[PERLU KONTEN RESMI]` | Perlu approval publikasi |

---

# 8. Slogan Brand

Slogan yang pernah tercatat:

```text
Kesenangan Tiada Akhir di Kampoeng Radja
```

Status:

`[PERLU VERIFIKASI]`

Sebelum digunakan sebagai copy produksi final, pastikan perusahaan menyetujui wording tersebut.

Jika slogan memang tampil pada Figma approved, boleh digunakan sebagai:

`[FIGMA SEMENTARA]`

sampai dikonfirmasi.

---

# 9. Wahana

Konten Wahana harus mengikuti PRD terbaru.

Jangan menganggap daftar kategori/label lama sebagai final tanpa verifikasi.

## Data yang Mungkin Dibutuhkan

| Item | Status |
|---|---|
| Nama wahana | `[PERLU KONTEN RESMI]` |
| Deskripsi | `[PERLU KONTEN RESMI]` |
| Foto | Lihat `ASSETS.md` |
| Category | `[PERLU VERIFIKASI PRD]` |
| Label | `[PERLU VERIFIKASI PRD]` |
| Informasi tambahan | Hanya jika ada di PRD/Figma |

---

# 10. Kategori dan Label Wahana

Daftar lama yang pernah dicatat:

```text
Kategori:
- Wahana
- Tempat Makan

Label:
- Anak-anak
- Dewasa
- Air
- Darat
- Adrenaline
- Santai
```

Status:

`[PERLU VERIFIKASI PRD/FIGMA]`

Daftar tersebut tidak boleh dianggap final hanya karena ada pada dokumentasi versi lama.

Jika PRD terbaru menetapkan daftar yang sama, ubah status menjadi resmi/approved.

---

# 11. Tempat Makan

Keputusan lama menyebut kategori `Tempat Makan` dapat ada tanpa label.

Status saat ini:

`[PERLU VERIFIKASI PRD]`

Jangan membangun behavior khusus berdasarkan keputusan lama jika PRD terbaru sudah berubah.

---

# 12. Galeri Event

Data per event dapat meliputi:

| Field | Status |
|---|---|
| Judul Event | `[PERLU KONTEN RESMI]` |
| Tanggal Event | `[PERLU KONTEN RESMI]` |
| Deskripsi | `[PERLU KONTEN RESMI]` |
| Foto Event | Lihat `ASSETS.md` |
| CTA/detail | Hanya jika termasuk scope |

Jumlah event dan foto aktual ditentukan oleh data produksi.

Figma hanya menentukan representasi visual/layout.

---

# 13. Media dan Berita

Jika section ini masih termasuk scope:

data per item dapat meliputi:

```text
title
publication date
excerpt
thumbnail
link/detail
```

Status data produksi:

`[PERLU KONTEN RESMI]`

Data yang terlihat di Figma boleh dipakai sebagai `[FIGMA SEMENTARA]`.

---

# 14. Promo / Event Promosi

Data produksi dapat meliputi:

```text
title
description
period
CTA
image
terms jika diperlukan
```

Agent tidak boleh mengarang:

- diskon;
- harga;
- tanggal promo;
- syarat;
- benefit;

tanpa sumber resmi.

---

# 15. Mitra / Sponsor

Untuk setiap mitra diperlukan:

- nama;
- logo;
- URL jika digunakan;
- izin publikasi jika diperlukan.

Konten/logo dari Figma dapat digunakan sementara jika memang ada pada desain approved, tetapi statusnya tetap harus dicatat.

---

# 16. Konten Global

| Kebutuhan | Status | Catatan |
|---|---|---|
| Nama brand publik | `[PERLU VERIFIKASI]` | Taman Wisata Kampoeng Radja |
| Nama badan hukum | `[PERLU KONTEN RESMI]` | Jangan menebak |
| Alamat | `[PERLU KONTEN RESMI]` | |
| Jam operasional | `[PERLU KONTEN RESMI]` | |
| Nomor telepon/WA | `[PERLU KONTEN RESMI]` | |
| Email publik | `[PERLU KONTEN RESMI]` | |
| Social links | `[PERLU KONTEN RESMI]` | |
| Copyright | `[PERLU KONTEN RESMI]` | |
| Maps link | `[PERLU KONTEN RESMI]` | |

---

# 17. SEO Content

SEO content per halaman dapat meliputi:

- page title;
- meta description;
- OG title;
- OG description;
- OG image;
- canonical slug jika diperlukan.

Status:

`[PERLU KONTEN RESMI / COPY REVIEW]`

Agent boleh menyusun draft teknis jika diminta, tetapi klaim bisnis harus tetap berasal dari sumber resmi.

---

# 18. Panjang Copy dan Visual Fidelity

Panjang teks memengaruhi visual.

Karena itu:

> untuk development awal, jika Figma memiliki copy, gunakan copy Figma agar line wrapping dan tinggi komponen dapat direproduksi.

Jika konten resmi nanti jauh lebih panjang atau lebih pendek:

1. masukkan konten resmi;
2. cek dampak layout;
3. lakukan visual/content review;
4. jangan memotong informasi penting tanpa persetujuan.

---

# 19. Content Mapping ke Figma

Konten penting sebaiknya dicatat bersama referensi:

```text
Page
Frame
Section
Node
Copy
Status
Source
```

Contoh:

```text
Page: Home
Frame: Desktop
Section: Hero
Node: 123:456
Copy: ...
Status: [FIGMA SEMENTARA]
```

Mapping detail dapat ditempatkan di `FIGMA.md` atau dokumen ini jika lebih praktis.

---

# 20. Proses Pengumpulan Konten Resmi

Gunakan:

`CONTENT_INTAKE_TEMPLATE.md`

untuk meminta data dari perusahaan.

Alur yang disarankan:

```text
Figma / Requirement
        ↓
Identifikasi kebutuhan konten
        ↓
CONTENT_INTAKE_TEMPLATE
        ↓
Perusahaan memberikan konten
        ↓
Review / approval
        ↓
CONTENT.md diperbarui
        ↓
Implementasi
        ↓
Visual QA
```

---

# 21. Development Saat Konten Belum Lengkap

Development tetap dapat berjalan dengan:

1. `[FIGMA SEMENTARA]` jika copy ada di Figma;
2. `[PLACEHOLDER TERDOKUMENTASI]` jika Figma juga kosong.

Prioritas:

```text
Figma temporary copy
        >
descriptive placeholder
        >
lorem ipsum
```

Lorem ipsum sebaiknya dihindari karena:

- panjangnya tidak mencerminkan copy desain;
- sulit direview stakeholder;
- dapat tertinggal sampai produksi.

---

# 22. Replacement Rule

Ketika konten resmi diterima:

1. cek approval;
2. ganti copy sementara;
3. cek line wrapping;
4. cek tinggi section/card;
5. cek mobile;
6. lakukan visual QA;
7. update status menjadi `[PRODUKSI RESMI]`.

Jika konten resmi menyebabkan layout rusak:

> jangan diam-diam memotong copy.

Catat kebutuhan review.

---

# 23. Hal yang Tidak Boleh Dilakukan Agent

Agent dilarang:

- mengarang sejarah perusahaan;
- mengarang visi/misi;
- membuat alamat palsu;
- membuat nomor kontak dummy yang terlihat nyata;
- membuat promo fiktif;
- membuat event fiktif sebagai konten produksi;
- membuat nama partner;
- membuat kategori/label tanpa requirement;
- menganggap seluruh copy Figma sebagai fakta resmi;
- menggunakan lorem ipsum jika copy Figma tersedia;
- mempertahankan placeholder sampai produksi tanpa status yang jelas.

---

# 24. Definition of Done — Content per Section

Konten sebuah section dianggap siap produksi jika:

- [ ] Isi final diterima
- [ ] Sumber jelas
- [ ] Approval jelas
- [ ] Tidak ada fakta buatan agent
- [ ] Asset terkait sudah sesuai
- [ ] Copy sudah diuji pada layout
- [ ] Desktop/mobile tidak rusak
- [ ] Status diperbarui menjadi `[PRODUKSI RESMI]`

---

# 25. Checklist Sebelum Go-Live

Sebelum production release:

- [ ] Tidak ada `[PLACEHOLDER TERDOKUMENTASI]` yang tidak disengaja
- [ ] Semua `[FIGMA SEMENTARA]` telah direview
- [ ] Item yang memang tetap memakai konten Figma sudah mendapat approval
- [ ] Kontak valid
- [ ] Alamat valid
- [ ] Maps valid
- [ ] Jam operasional valid
- [ ] Partner/logo memiliki izin yang sesuai
- [ ] Event/promo masih relevan
- [ ] SEO metadata sudah direview
- [ ] Copyright menggunakan nama yang benar

---

# 26. Prinsip Akhir

> **Figma dapat menjadi sumber copy sementara untuk visual fidelity.**

> **Perusahaan tetap menjadi source of truth untuk fakta.**

Agent boleh menggunakan konten Figma untuk membuat tampilan akurat, tetapi:

> **jangan mengubah copy sementara menjadi fakta produksi tanpa approval.**
