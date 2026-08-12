# README.md
## Dokumentasi Proyek Kampoeng Radja

Folder dokumentasi ini adalah sumber kerja utama untuk:

- agent;
- developer;
- designer;
- reviewer;
- stakeholder teknis.

Fokus aktif saat ini:

> **Fase 1 — Landing Page / Company Profile Kampoeng Radja**

Fase lain tidak boleh ikut diimplementasikan tanpa requirement yang sudah aktif.

---

# 1. Source of Truth Fase 1

Untuk Fase 1, source of truth dibagi berdasarkan jenis keputusan.

## 1.1 Scope dan Fungsi

Untuk:

- halaman;
- fitur;
- behavior bisnis;
- data;
- akses;
- acceptance criteria;

ikuti:

1. `LANDING-PAGE/PRD.md`
2. dokumen global terkait
3. `LANDING-PAGE/USER_FLOW.md`
4. `LANDING-PAGE/UI_SPEC.md`

---

## 1.2 Visual Desktop

Untuk seluruh keputusan visual desktop:

> **Figma berstatus `[APPROVED FOR DEVELOPMENT]` adalah source of truth utama.**

Figma mengendalikan:

- layout;
- section order;
- sizing;
- spacing;
- typography;
- colors;
- media;
- crop;
- radius;
- border;
- shadow;
- icons;
- decorative elements;
- component appearance;
- visible states.

Agent tidak boleh mendesain ulang frame Figma approved.

Dokumen utama:

- `LANDING-PAGE/FIGMA.md`
- `LANDING-PAGE/FIGMA_ACCURACY.md`

---

# 2. Responsive Fase 1

Keputusan stakeholder:

> **Tim desain hanya membuat desain desktop.**

Frame Figma khusus:

- tablet;
- mobile;

**tidak akan dibuat** pada Fase 1.

Karena itu:

- desktop → mengikuti Figma approved;
- tablet/mobile → mengikuti `LANDING-PAGE/RESPONSIVE.md`;
- non-desktop berstatus `[RESPONSIVE FALLBACK]`;
- tablet/mobile tidak boleh diklaim pixel-accurate terhadap Figma.

---

# 3. Aturan Jika Figma Tidak Jelas

Jika visual Figma tidak dapat ditentukan:

1. cek `FIGMA.md`;
2. cek `FIGMA_ACCURACY.md`;
3. cek `UI_SPEC.md`;
4. cek `RESPONSIVE.md` jika terkait non-desktop;
5. cek `USER_FLOW.md` jika terkait alur;
6. catat ambiguity/blocker di `TODO.md`.

Jangan menebak atau melakukan redesign.

---

# 4. Urutan Baca Wajib Agent

Sebelum mengimplementasikan Fase 1, baca dengan urutan berikut.

## Global

1. `GLOBAL/PROJECT_CONTEXT.md`
2. `GLOBAL/TECH_STACK.md`
3. `GLOBAL/BRAND_GUIDELINE.md`
4. `GLOBAL/ARCHITECTURE.md`
5. `GLOBAL/AGENT_RULES.md`

## Landing Page

6. `LANDING-PAGE/PRD.md`
7. `LANDING-PAGE/FIGMA.md`
8. `LANDING-PAGE/FIGMA_ACCURACY.md`
9. `LANDING-PAGE/UI_SPEC.md`
10. `LANDING-PAGE/USER_FLOW.md`
11. `LANDING-PAGE/COMPONENTS.md`
12. `LANDING-PAGE/RESPONSIVE.md`
13. `LANDING-PAGE/CONTENT.md`
14. `LANDING-PAGE/ASSETS.md`
15. `LANDING-PAGE/REFERENCE.md`
16. `LANDING-PAGE/TODO.md`
17. `LANDING-PAGE/AGENT_HANDOFF.md`

Sebelum release/review:

18. `LANDING-PAGE/DELIVERY_CHECKLIST.md`

Jika sedang mengumpulkan konten produksi:

19. `LANDING-PAGE/CONTENT_INTAKE_TEMPLATE.md`

---

# 5. Urutan Kerja Implementasi

Agent wajib bekerja dengan urutan:

```text
Read docs
   ↓
Identify PRD scope
   ↓
Open approved Figma frame
   ↓
Map section/node
   ↓
Inspect measured values
   ↓
Audit existing implementation
   ↓
Implement
   ↓
Desktop visual QA
   ↓
Responsive fallback
   ↓
Functional QA
   ↓
Update documentation
```

Jangan langsung coding berdasarkan deskripsi teks tanpa menginspeksi Figma.

---

# 6. Status Informasi

Gunakan kosakata status berikut secara konsisten.

## `[APPROVED FOR DEVELOPMENT]`

Frame/node Figma yang boleh menjadi source of truth visual final.

---

## `[PRODUKSI RESMI]`

Konten/aset resmi yang sudah disetujui untuk penggunaan produksi.

---

## `[FIGMA SEMENTARA]`

Konten atau aset dari Figma yang digunakan untuk menjaga visual fidelity sampai aset/konten produksi resmi tersedia.

Ini **boleh digunakan dalam development**.

---

## `[PERLU KONTEN RESMI]`

Konten produksi belum diterima/disetujui.

---

## `[PERLU ASET RESMI]`

Asset produksi belum tersedia.

---

## `[PERLU DIISI]`

Informasi belum tersedia dan tidak boleh dianggap sebagai fakta.

---

## `[PERLU KLARIFIKASI]`

Ada keputusan yang belum ditentukan stakeholder.

---

## `[PLACEHOLDER TERDOKUMENTASI]`

Placeholder boleh digunakan karena sumber sebenarnya belum tersedia dan statusnya sudah dicatat.

Jangan gunakan placeholder generik jika asset Figma yang benar tersedia.

---

## `[RESPONSIVE FALLBACK]`

Implementasi tablet/mobile yang diturunkan dari desktop karena tidak tersedia frame Figma pembanding.

---

## `[BLOCKED: FIGMA ACCESS]`

Figma tidak dapat diakses sehingga visual QA/inspection tidak dapat dilakukan.

---

# 7. Aturan Konten dan Fakta

Agent tidak boleh mengarang:

- alamat;
- jam operasional;
- harga;
- promo;
- sejarah;
- nama personel;
- kontak;
- social media;
- partner;
- event;
- statistik;
- fakta perusahaan lain.

Jika Figma menampilkan copy yang belum dikonfirmasi:

> tandai `[FIGMA SEMENTARA]`.

Lihat:

- `LANDING-PAGE/CONTENT.md`
- `LANDING-PAGE/CONTENT_INTAKE_TEMPLATE.md`

---

# 8. Aturan Asset

Jika Figma sudah menyediakan asset yang digunakan oleh desain:

> export source/layer Figma dan gunakan sebagai `[FIGMA SEMENTARA]`.

Dilarang mengganti dengan:

- stock image;
- Unsplash;
- Picsum;
- generative image;
- screenshot full-frame Figma.

Screenshot hanya digunakan untuk visual QA.

Lihat:

`LANDING-PAGE/ASSETS.md`

---

# 9. Aturan Component

Component dibuat berdasarkan pola UI yang benar-benar berulang.

Agent tidak wajib membuat:

```text
BaseButton
BaseCard
BaseBadge
BaseModal
PageContainer
SectionWrapper
```

hanya karena merupakan praktik umum.

Jika abstraction menyebabkan visual Figma sulit direplikasi:

> prioritaskan visual fidelity.

Lihat:

`LANDING-PAGE/COMPONENTS.md`

---

# 10. Aturan Teknologi

Stack teknis mengikuti:

`GLOBAL/TECH_STACK.md`

Agent wajib menginspeksi project existing sebelum:

- menambah dependency;
- mengubah versi;
- mengubah struktur;
- membuat routing baru;
- membuat API baru;
- membuat database entity baru.

Jangan menganggap dependency dari dokumentasi lama masih wajib.

---

# 11. Visual QA

Sebuah desktop frame hanya boleh disebut:

```text
[FIGMA VERIFIED]
```

atau:

```text
[PIXEL-ACCURATE VERIFIED]
```

setelah:

1. browser dirender pada ukuran frame Figma;
2. comparison dilakukan;
3. Critical deviation = 0;
4. Major deviation = 0 atau disetujui;
5. status dicatat di `DELIVERY_CHECKLIST.md`.

Build sukses tidak sama dengan visual QA sukses.

---

# 12. Dokumen Utama dan Fungsinya

| Dokumen | Fungsi |
|---|---|
| `GLOBAL/PROJECT_CONTEXT.md` | Konteks produk, fase, dan bisnis global |
| `GLOBAL/TECH_STACK.md` | Stack dan batasan teknis |
| `GLOBAL/BRAND_GUIDELINE.md` | Identitas brand/fallback global |
| `GLOBAL/ARCHITECTURE.md` | Architecture dan boundaries |
| `GLOBAL/AGENT_RULES.md` | Aturan wajib agent |
| `LANDING-PAGE/PRD.md` | Scope, requirement, fungsi, acceptance criteria |
| `LANDING-PAGE/FIGMA.md` | Mapping page/frame/node dan status approval |
| `LANDING-PAGE/FIGMA_ACCURACY.md` | Protokol visual fidelity dan QA |
| `LANDING-PAGE/UI_SPEC.md` | Behavior UI yang tidak ditentukan lengkap oleh Figma |
| `LANDING-PAGE/USER_FLOW.md` | Alur pengguna dan hasil aksi |
| `LANDING-PAGE/COMPONENTS.md` | Strategi componentization |
| `LANDING-PAGE/RESPONSIVE.md` | Kontrak responsive fallback |
| `LANDING-PAGE/CONTENT.md` | Status dan sumber content |
| `LANDING-PAGE/ASSETS.md` | Inventaris dan status asset |
| `LANDING-PAGE/REFERENCE.md` | Referensi pendukung dan decision log |
| `LANDING-PAGE/TODO.md` | Backlog aktif, blocker, deviasi, keputusan terbuka |
| `LANDING-PAGE/AGENT_HANDOFF.md` | Brief eksekusi untuk coding agent |
| `LANDING-PAGE/CONTENT_INTAKE_TEMPLATE.md` | Pengumpulan konten/aset resmi |
| `LANDING-PAGE/DELIVERY_CHECKLIST.md` | Release/serah-terima dan bukti QA |

---

# 13. Decision Changes

Jika stakeholder membuat keputusan baru:

1. update dokumen source of truth terkait;
2. update `TODO.md` jika masih membutuhkan pekerjaan;
3. update `REFERENCE.md` jika perlu decision log;
4. jangan menyimpan keputusan penting hanya di chat atau komentar code.

---

# 14. Konflik Dokumen

Jika ditemukan konflik:

## Business/function

Ikuti:

`PRD.md`

dan klarifikasi jika diperlukan.

## Visual desktop

Ikuti:

Figma approved.

## Behavior

Ikuti:

`UI_SPEC.md` / `USER_FLOW.md`

selama tidak bertentangan dengan PRD.

## Responsive

Ikuti:

`RESPONSIVE.md`.

## Asset/content

Ikuti:

`ASSETS.md` / `CONTENT.md`.

Jika tetap tidak dapat diputuskan:

> catat di `TODO.md`.

---

# 15. Larangan Utama Agent

Agent tidak boleh:

- melakukan redesign tanpa instruksi;
- mengganti asset Figma dengan stock/generative asset;
- mengarang data bisnis;
- membuat fitur Fase 2/3;
- menambahkan library spekulatif;
- memaksa component abstraction;
- mengklaim pixel-perfect tanpa visual QA;
- mengklaim tablet/mobile Figma verified;
- menganggap implementasi lama otomatis valid;
- mengabaikan blocker karena ingin menutup task.

---

# 16. Prinsip Akhir

Untuk Fase 1:

> **PRD menentukan apa yang dibangun.**

> **Figma approved menentukan seperti apa desktop terlihat.**

> **UI_SPEC dan USER_FLOW menentukan bagaimana UI bertindak.**

> **RESPONSIVE.md menentukan adaptasi tablet/mobile.**

> **CONTENT dan ASSETS menentukan apa yang boleh dipublikasikan.**

> **FIGMA_ACCURACY dan DELIVERY_CHECKLIST membuktikan apakah implementasi benar.**

Workflow wajib:

> **inspect → map → audit → implement → compare → fix → verify**
