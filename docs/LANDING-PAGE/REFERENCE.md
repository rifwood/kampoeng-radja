# REFERENCE.md
## Referensi & Catatan Pendukung — Fase 1 Landing Page Kampoeng Radja

Dokumen ini menampung:

- referensi eksternal;
- catatan UX/teknis tambahan;
- glosarium istilah;
- keputusan historis;
- konteks yang membantu development tetapi bukan source of truth utama.

> **Prinsip utama:**  
> Referensi eksternal hanya boleh digunakan ketika Figma dan dokumentasi produk **tidak menentukan** elemen atau behavior terkait.
>
> Referensi eksternal tidak boleh menggeser keputusan visual yang sudah ada di Figma approved.

---

# 1. Posisi Dokumen dalam Source of Truth

Dokumen ini memiliki prioritas lebih rendah dibanding:

1. `PRD.md`
2. Figma approved
3. `FIGMA.md`
4. `FIGMA_ACCURACY.md`
5. `UI_SPEC.md`
6. `USER_FLOW.md`
7. `RESPONSIVE.md`
8. `COMPONENTS.md`
9. `ASSETS.md`
10. `CONTENT.md`

`REFERENCE.md` digunakan untuk membantu memahami konteks, bukan untuk mengubah requirement atau desain.

---

# 2. Aturan Penggunaan Referensi Eksternal

Referensi eksternal boleh digunakan untuk:

- memahami pola UX yang belum didesain;
- mencari solusi teknis;
- membandingkan behavior umum;
- membantu menyelesaikan state yang belum tersedia;
- mencari pola responsive fallback jika dokumentasi belum cukup.

Referensi eksternal tidak boleh digunakan untuk:

- mengganti layout Figma;
- mengganti typography;
- mengganti warna;
- mengganti radius/shadow;
- mengganti card style;
- mengubah grid;
- menambah animasi;
- mengubah section order;
- melakukan redesign.

---

# 3. Referensi Visual

Saat ini:

> **Figma approved adalah referensi visual utama Fase 1.**

Tidak diperlukan pencarian referensi visual tambahan untuk section yang sudah memiliki desain Figma.

Jika stakeholder memberikan situs/brand referensi tambahan, catat di bagian ini dan jelaskan tujuan referensinya.

Contoh format:

```text
Reference:
URL:
Digunakan untuk:
Status:
Catatan:
```

---

# 4. Referensi yang Tidak Lagi Berlaku sebagai Keputusan

Catatan lama mengenai:

```text
"style dari Vue"
vue-grid-layout
masonry-style sebagai requirement
```

tidak lagi dianggap keputusan teknis maupun visual.

Status:

`[DEPRECATED AS ASSUMPTION]`

Jika Figma memang menampilkan layout non-seragam, implementasikan berdasarkan Figma dengan:

- CSS Grid;
- Flexbox;
- CSS Columns;
- atau solusi lain yang sesuai;

tanpa otomatis menggunakan `vue-grid-layout`.

---

# 5. Catatan SEO

Landing Page publik perlu memperhatikan SEO dasar.

Minimal:

- page title;
- meta description;
- semantic heading;
- alt text;
- URL yang jelas.

Open Graph, canonical, sitemap, robots, dan metadata lain diterapkan sesuai kebutuhan release/deployment.

> Laravel + Inertia tidak otomatis berarti seluruh metadata dirender server-side atau SEO sudah optimal.

Jika SEO rendering menjadi concern produksi, evaluasi implementasi aktual dan pertimbangkan strategi seperti Inertia SSR bila diperlukan.

Lihat:

`GLOBAL/ARCHITECTURE.md`

---

# 6. Google Maps

Keputusan Fase 1:

> gunakan Google Maps iframe embed untuk kebutuhan lokasi sederhana.

Jangan menggunakan Maps JavaScript API kecuali requirement berubah.

Detail layout map mengikuti Figma.

---

# 7. Glosarium Proyek

| Istilah | Arti |
|---|---|
| **Kategori** | Pengelompokan tingkat atas dalam filter Wahana |
| **Label** | Tag yang dapat dihubungkan ke item/foto Wahana dan dapat dipilih sebagai filter |
| **AND Filter** | Item hanya tampil jika memiliki seluruh label yang dipilih |
| **USP** | Unique Selling Point / Wahana Unggulan |
| **FLT** | Facility |
| **SPV** | Supervisor |
| **Guest** | Pengunjung website publik tanpa autentikasi |
| **Admin** | Role pengelola konten sesuai kewenangan |
| **Super Admin** | Role dengan akses tertinggi sesuai sistem |
| **Figma Approved** | Frame/node yang berstatus `[APPROVED FOR DEVELOPMENT]` |
| **FIGMA SEMENTARA** | Konten/aset dari Figma yang digunakan sampai sumber produksi resmi tersedia |
| **Responsive Fallback** | Implementasi tablet/mobile tanpa frame Figma pembanding |
| **Visual QA** | Proses membandingkan implementasi browser terhadap frame Figma |

---

# 8. Decision Log — Landing Page

| Tanggal | Keputusan | Status / Alasan |
|---|---|---|
| 09 Agu 2026 | Fase 1 memiliki 4 halaman publik: Beranda, Tentang Kami, Wahana, Galeri Event | Aktif |
| 09 Agu 2026 | Login tidak menjadi menu utama publik dan diarahkan melalui footer sesuai requirement saat ini | Aktif, visual mengikuti Figma |
| 09 Agu 2026 | Filter Wahana menggunakan logika AND antar label | Aktif |
| 09 Agu 2026 | Category/label Wahana dikelola dinamis melalui admin | Aktif |
| 09 Agu 2026 | Section lokasi memakai Google Maps iframe | Aktif |
| 09 Agu 2026 | Kategori `Tempat Makan` disiapkan tanpa label untuk saat ini | Aktif, review jika kebutuhan bisnis berubah |
| 11 Agu 2026 | Figma approved menjadi source of truth visual desktop | Final |
| 11 Agu 2026 | Tim desain hanya menyediakan frame desktop | Final |
| 11 Agu 2026 | Tablet/mobile menggunakan `[RESPONSIVE FALLBACK]` | Final |
| 11 Agu 2026 | Aset Figma diprioritaskan sebagai `[FIGMA SEMENTARA]` sampai aset resmi tersedia | Final |
| 11 Agu 2026 | `vue-grid-layout` tidak dianggap dependency atau requirement otomatis | Final |
| 11 Agu 2026 | Semua frame utama yang sudah memiliki node dianggap `[APPROVED FOR DEVELOPMENT]` | Final |

---

# 9. Catatan Scope Detail Media & Berita

Figma memiliki node:

```text
1:650
```

Status visual:

`[APPROVED FOR DEVELOPMENT]`

Namun status requirement implementasi:

`[PERLU KEPUTUSAN]`

Node tersebut tidak otomatis berarti halaman Detail Media & Berita wajib dibangun.

Lihat:

- `PRD.md`
- `FIGMA.md`
- `TODO.md`

---

# 10. Catatan Struktur Organisasi

Struktur organisasi merupakan requirement halaman Tentang Kami.

Yang masih perlu diputuskan:

- konten statis;
- atau dikelola melalui panel admin.

Jangan membuat CMS khusus sebelum keputusan ini final.

---

# 11. Catatan Multi-Bahasa

Status:

`[BELUM DIPUTUSKAN]`

Baseline Fase 1:

> Bahasa Indonesia.

Jangan menambahkan translation layer atau database multilingual sebelum requirement disetujui.

---

# 12. Catatan Responsive

Tim desain tidak membuat frame tablet/mobile.

Karena itu:

- desktop mengikuti Figma approved;
- tablet/mobile mengikuti `RESPONSIVE.md`;
- viewport non-desktop tidak boleh disebut pixel-perfect terhadap Figma.

Jika di masa depan frame mobile/tablet dibuat, keputusan ini dapat diperbarui.

---

# 13. Catatan Asset

Untuk aset yang sudah ada di Figma:

> gunakan source asset Figma dan dokumentasikan di `ASSETS.md`.

Jangan menggunakan referensi visual eksternal sebagai pengganti aset Figma.

---

# 14. Catatan Component / UI Library

Tidak ada UI library generik yang diwajibkan.

Jika mencari referensi implementasi component:

- prioritaskan dokumentasi resmi library/framework;
- jangan mengubah visual Figma untuk menyesuaikan component library.

Lihat:

- `GLOBAL/TECH_STACK.md`
- `COMPONENTS.md`

---

# 15. Referensi Teknis

Referensi teknis yang digunakan agent sebaiknya berasal dari sumber primer/resmi, misalnya:

- Laravel documentation;
- Vue documentation;
- Inertia documentation;
- Tailwind documentation;
- browser/MDN documentation.

Catat keputusan teknis penting jika berdampak pada implementasi jangka panjang.

---

# 16. Format Penambahan Referensi

Gunakan format berikut:

```text
## Reference: [Nama]

Type:
[Visual / UX / Technical / Content]

Source:
[URL / dokumen / node]

Purpose:
[Kenapa referensi ini digunakan]

Related:
[Page / section / feature]

Status:
[ACTIVE / REFERENCE ONLY / DEPRECATED]

Notes:
...
```

---

# 17. Format Decision Log Baru

Jika ada keputusan baru:

| Tanggal | Keputusan | Status / Alasan |
|---|---|---|
| YYYY-MM-DD | ... | ... |

Keputusan besar juga harus diperbarui pada dokumen source of truth utama, bukan hanya disimpan di `REFERENCE.md`.

---

# 18. Larangan

Agent tidak boleh menggunakan `REFERENCE.md` sebagai alasan untuk:

- mengubah Figma;
- mengganti asset;
- menambah fitur;
- menghapus fitur;
- memilih library spekulatif;
- mengubah business logic;
- membuat redesign.

Jika referensi bertentangan dengan source of truth yang lebih tinggi:

> abaikan referensi tersebut.

---

# 19. Prinsip Akhir

> **Reference membantu keputusan yang belum ditentukan.**

> **Reference tidak mengalahkan requirement atau Figma approved.**

Jika Figma sudah menentukan elemen:

> implementasikan Figma.

Jika PRD sudah menentukan fungsi:

> implementasikan fungsi.

Gunakan referensi hanya untuk ruang yang memang masih kosong.
