# ASSETS.md
## Manajemen Aset — Fase 1 Landing Page Kampoeng Radja

Dokumen ini mengatur sumber, status, penamaan, penyimpanan, penggantian, dan validasi seluruh aset visual yang digunakan pada Landing Page Kampoeng Radja.

> **Prinsip utama Fase 1:**  
> Jika aset sudah tersedia di Figma approved, gunakan aset tersebut untuk menyamai desain.
>
> Aset produksi resmi menggantikan aset Figma hanya setelah peran visual, rasio, crop, framing, dan kualitasnya diverifikasi terhadap desain.

---

# 1. Hierarki Sumber Aset

Untuk Fase 1, gunakan urutan prioritas berikut:

1. **Aset produksi resmi perusahaan yang sudah tervalidasi terhadap Figma**
2. **Aset dari Figma approved**
3. Placeholder terdokumentasi hanya jika aset memang tidak tersedia
4. Jangan menggunakan stock image, dummy image, atau aset generatif sebagai pengganti aset Figma yang sudah ada

Jika aset Figma tersedia:

> **jangan menggantinya dengan Unsplash, Picsum, stock photo, hasil pencarian internet, atau aset generatif.**

---

# 2. Status Aset

Gunakan status berikut secara konsisten.

| Status | Arti |
|---|---|
| `[PRODUKSI RESMI]` | Aset resmi perusahaan dan sudah tervalidasi untuk implementasi |
| `[FIGMA SEMENTARA]` | Aset berasal dari Figma dan digunakan sampai aset produksi resmi tersedia |
| `[PERLU ASET RESMI]` | Aset produksi belum tersedia |
| `[PERLU NODE FIGMA]` | File aset tersedia tetapi referensi node sumber belum tercatat |
| `[PLACEHOLDER TERDOKUMENTASI]` | Hanya boleh digunakan jika aset benar-benar tidak tersedia di Figma maupun dari perusahaan |
| `[TIDAK DIPERLUKAN]` | Requirement/aset sudah dinyatakan tidak dibutuhkan |

---

# 3. Aturan Wajib Aset Figma

Jika suatu elemen visual melekat pada node Figma dan dibutuhkan untuk implementasi, agent wajib:

1. mengidentifikasi node sumber;
2. mengekspor aset asli/source image jika tersedia;
3. tidak mengekspor screenshot seluruh frame sebagai pengganti aset individual;
4. mempertahankan rasio dan kualitas visual yang diperlukan;
5. menyimpan aset pada struktur project yang sesuai;
6. mencatat path project;
7. mencatat node sumber;
8. memberi status `[FIGMA SEMENTARA]`;
9. memverifikasi hasil render terhadap frame Figma.

---

# 4. Lokasi Aset Figma Sementara

Untuk Fase 1, aset Figma sementara dapat ditempatkan pada:

```text
public/assets/figma/
```

Struktur subfolder boleh digunakan agar lebih terorganisasi.

Contoh:

```text
public/assets/figma/
├── brand/
├── home/
├── about/
├── attractions/
├── gallery/
├── news/
├── icons/
└── decorations/
```

Jangan membuat struktur folder berlebihan jika jumlah aset masih sedikit.

---

# 5. Naming Convention

Gunakan nama file yang:

- deskriptif;
- konsisten;
- lowercase;
- menggunakan `kebab-case`;
- tidak bergantung pada nama acak export Figma jika dapat dirapikan.

Contoh:

```text
home-hero-main.webp
home-news-01.webp
about-history-01.webp
attraction-water-slide.webp
icon-instagram.svg
logo-main.png
```

Jangan mengubah nama file jika hal tersebut akan memutus referensi existing tanpa memperbarui kode.

---

# 6. Inventaris Aset Figma Existing

Inventaris saat ini:

| Path | Asal Figma | Status | Catatan |
|---|---|---|---|
| `public/assets/figma/logo-main.png` | node `1:675` | `[FIGMA SEMENTARA]` | Ganti hanya setelah logo produksi resmi tersedia dan lolos verifikasi visual |
| `public/assets/figma/figma-news-1.png` | `[NODE PERLU DICATAT]` | `[PERLU NODE FIGMA]` | Catat node asal sebelum perubahan berikutnya |
| `public/assets/figma/figma-news-2.png` | `[NODE PERLU DICATAT]` | `[PERLU NODE FIGMA]` | Catat node asal sebelum perubahan berikutnya |
| `public/assets/figma/figma-news-3.png` | `[NODE PERLU DICATAT]` | `[PERLU NODE FIGMA]` | Catat node asal sebelum perubahan berikutnya |

> Inventaris ini harus diperbarui setiap kali aset Figma baru diekspor.

---

# 7. Asset Mapping Wajib

Untuk setiap aset yang digunakan pada UI Figma, dokumentasikan minimal:

```text
Nama asset
Path project
Page Figma
Frame
Node
Status
Peran visual
Catatan crop / mask / positioning
```

Contoh:

```text
Asset: home-hero-main.webp
Path: public/assets/figma/home/home-hero-main.webp
Page: Landing Page
Frame: Home / Desktop
Node: Hero Image
Status: [FIGMA SEMENTARA]
Role: Hero background
Crop: mengikuti frame hero
```

Nama aktual mengikuti Figma project.

---

# 8. Logo dan Brand Asset

## Kebutuhan Produksi

| Aset | Format yang Disarankan | Kegunaan | Status |
|---|---|---|---|
| Logo utama full color | SVG preferred + raster fallback bila perlu | Navbar/footer/branding | `[PERLU ASET RESMI]` |
| Logo versi terang/putih jika memang digunakan | SVG preferred | Background gelap/berwarna | `[PERLU ASET RESMI]` |
| Favicon | SVG/PNG/ICO sesuai pipeline | Browser/site metadata | `[PERLU ASET RESMI]` |

Jika Figma sudah menyediakan logo yang digunakan pada desain:

> gunakan versi Figma sebagai `[FIGMA SEMENTARA]`.

Logo produksi resmi hanya menggantikan jika secara visual cocok dengan frame.

---

# 9. Foto dan Image

Foto tidak memiliki satu spesifikasi rasio global.

Untuk setiap foto, ikuti:

- rasio dari Figma;
- crop;
- object-position;
- mask;
- radius;
- overlay;
- opacity;
- treatment visual.

Jangan memaksa semua foto menjadi:

```text
16:9
1:1
1200px
1920×1080
```

jika desain menggunakan rasio berbeda.

Resolusi harus cukup untuk viewport target tanpa menyebabkan blur yang terlihat.

---

# 10. Foto Hero

Jika hero menggunakan image:

- gunakan source image dari Figma jika tersedia;
- jangan gunakan screenshot frame;
- pertahankan crop dan focal point;
- cek desktop/mobile secara terpisah jika framing berbeda.

Jika aset resmi perusahaan datang kemudian, cocokkan framingnya dengan Figma sebelum mengganti.

---

# 11. Video Hero

Jika Figma/PRD final menggunakan video hero, status dan spesifikasinya harus dicatat berdasarkan implementasi aktual.

Hal yang perlu ditentukan:

- source video;
- format;
- poster;
- autoplay behavior;
- mute;
- loop;
- mobile fallback jika diperlukan.

Jangan mengunci:

```text
10–20 detik
16:9
<15 MB
1920×1080
```

sebagai requirement visual mutlak kecuali memang disepakati.

Optimasi teknis tetap diperlukan, tetapi hasil visual harus dipertahankan.

---

# 12. Wahana

Untuk aset wahana, dokumentasikan:

- nama/identifier wahana;
- image utama;
- image tambahan jika ada;
- label/category jika digunakan;
- node Figma;
- status asset;
- crop per card/lightbox jika berbeda.

Contoh konseptual:

```text
Wahana: Flying Fox
Card image: ...
Lightbox image: ...
Figma node: ...
Status: [FIGMA SEMENTARA]
```

Data final mengikuti PRD dan content inventory.

---

# 13. Event dan Galeri

Untuk event/gallery:

- aset Figma digunakan untuk mereplikasi layout;
- dokumentasikan hubungan gambar dengan event jika memang data tersebut dinamis;
- jangan mengasumsikan jumlah foto per event dari desain demo sebagai requirement bisnis;
- jangan mengganti gambar Figma hanya untuk membuat variasi visual.

---

# 14. Media, Berita, dan Promo

Jika section media/news/promo terdapat pada scope final:

setiap thumbnail/image harus memiliki mapping:

```text
content item → asset → node Figma → status
```

Aset demo dari Figma dapat tetap dipakai sebagai `[FIGMA SEMENTARA]` sampai konten resmi tersedia.

---

# 15. Logo Mitra / Sponsor

Jika Figma menampilkan logo mitra:

1. gunakan logo yang ada di Figma sebagai referensi;
2. export source vector/raster bila tersedia;
3. pertahankan ukuran visual dan spacing;
4. jangan mengganti dengan logo lain.

Jika perusahaan memberikan logo produksi resmi, lakukan visual validation sebelum mengganti.

---

# 16. Icon

Prioritas icon:

1. SVG/icon asli dari Figma;
2. icon asset resmi;
3. library existing yang secara visual cocok;
4. library baru hanya jika memang dibutuhkan.

Agent tidak boleh mengganti icon custom Figma dengan icon generik hanya karena lebih mudah.

---

# 17. Decorative Asset

Elemen seperti:

- shape;
- blob;
- wave;
- star;
- cloud;
- pattern;
- background ornament;
- illustration;
- decorative SVG;

harus dianggap bagian dari desain jika terlihat pada frame approved.

Jangan menghapusnya hanya karena bukan konten utama.

---

# 18. Placeholder Policy

Placeholder visual hanya diperbolehkan jika:

1. aset tidak tersedia di Figma;
2. aset resmi belum tersedia;
3. UI tetap harus dibangun untuk menguji struktur;
4. placeholder diberi status jelas.

Gunakan:

```text
[PLACEHOLDER TERDOKUMENTASI]
```

Placeholder tidak boleh diam-diam tampil sebagai konten produksi.

---

# 19. Placeholder yang Dilarang

Untuk aset yang sudah tersedia di Figma, dilarang mengganti dengan:

- Unsplash;
- Picsum;
- stock image;
- hasil Google Images;
- AI-generated image;
- dummy avatar;
- generic placeholder photo.

Ini berlaku walaupun aset pengganti dianggap “lebih bagus”.

---

# 20. Penggantian Aset Figma dengan Aset Produksi

Aset `[FIGMA SEMENTARA]` hanya boleh diganti setelah memeriksa:

- peran visual sama;
- aspect ratio sesuai;
- focal point sesuai;
- crop dapat direplikasi;
- resolusi cukup;
- tone tidak merusak komposisi;
- hasil render masih mengikuti Figma.

Jika aset produksi memiliki komposisi berbeda drastis:

> jangan mengganti secara diam-diam.

Catat untuk review desain/content.

---

# 21. Image Optimization

Optimasi diperbolehkan dan dianjurkan selama tidak merusak visual.

Pertimbangkan:

- WebP;
- AVIF;
- optimized JPEG/PNG;
- SVG optimization;
- responsive source;
- lazy loading;
- width/height attribute untuk mengurangi layout shift.

Jangan mengompres sampai image terlihat blur pada viewport target.

---

# 22. Runtime Asset Rule

Aset Figma yang digunakan aplikasi harus tersedia sebagai file project/runtime yang valid.

Jangan bergantung pada:

- URL preview Figma;
- temporary Figma URL;
- local machine path;
- screenshot clipboard;
- URL yang membutuhkan login.

---

# 23. Uploaded Media

Untuk media yang dikelola admin:

- gunakan Laravel filesystem;
- simpan reference/path di database sesuai arsitektur;
- lakukan validation;
- optimasi jika diperlukan;
- jangan menaruh upload user langsung ke source frontend.

Storage provider production mengikuti keputusan deployment.

---

# 24. Fallback Saat Image Gagal

Fallback teknis harus mencegah layout rusak.

Namun fallback tidak boleh menjadi alasan untuk selalu menampilkan placeholder generic.

Perilaku fallback dapat berupa:

- menyembunyikan elemen jika opsional;
- background aman;
- placeholder lokal yang memang disetujui;
- default asset yang sudah terdokumentasi.

Behavior final mengikuti UI/PRD.

---

# 25. Lokasi / Maps

Google Maps iframe bukan asset image statis.

Yang diperlukan:

- lokasi/URL embed resmi;
- label lokasi jika diperlukan;
- URL directions jika diperlukan.

Koordinat atau lokasi final perusahaan tetap perlu divalidasi sebelum produksi.

---

# 26. Asset Intake dari Perusahaan

Aset produksi yang masih dibutuhkan dapat meliputi:

- logo vector resmi;
- favicon;
- foto fasilitas/wahana;
- foto event;
- foto sejarah;
- foto company profile;
- logo sponsor/mitra;
- video;
- content thumbnail;
- asset lain yang ditentukan Figma/PRD.

Daftar aktual harus mengikuti section yang benar-benar ada pada desain approved.

Jangan meminta aset yang tidak lagi digunakan di Figma.

---

# 27. Checklist Export dari Figma

Sebelum asset dianggap siap:

- [ ] Node sumber tercatat
- [ ] Export berasal dari asset/layer asli
- [ ] Bukan screenshot frame
- [ ] Format sesuai jenis asset
- [ ] Resolusi mencukupi
- [ ] Transparansi benar
- [ ] Nama file rapi
- [ ] Disimpan di path project
- [ ] Status `[FIGMA SEMENTARA]` tercatat
- [ ] Crop/mask diuji pada UI
- [ ] Visual dibandingkan dengan Figma

---

# 28. Checklist Penggantian dengan Asset Produksi

Sebelum mengganti `[FIGMA SEMENTARA]`:

- [ ] Aset resmi diterima
- [ ] Identitas asset benar
- [ ] Rasio cocok atau dapat dicrop sesuai desain
- [ ] Focal point sesuai
- [ ] Resolusi cukup
- [ ] Tidak merusak layout
- [ ] Desktop diverifikasi
- [ ] Mobile diverifikasi jika berbeda
- [ ] Status inventaris diperbarui

---

# 29. Blocker dan Klarifikasi

Jika aset dibutuhkan tetapi tidak tersedia, catat di `TODO.md`.

Gunakan format seperti:

```text
[PERLU ASET RESMI]
Hero About — membutuhkan foto produksi resmi.
Saat ini menggunakan node Figma X sebagai [FIGMA SEMENTARA].
```

atau:

```text
[PERLU NODE FIGMA]
figma-news-1.png sudah ada di project tetapi node sumber belum tercatat.
```

---

# 30. Definition of Done — Asset

Asset untuk sebuah section dianggap selesai secara implementasi jika:

- [ ] Asset yang benar digunakan
- [ ] Node Figma tercatat jika berasal dari Figma
- [ ] Status asset tercatat
- [ ] Path project valid
- [ ] Tidak memakai stock/dummy jika Figma asset tersedia
- [ ] Crop/aspect ratio sesuai
- [ ] Tidak blur pada viewport target
- [ ] Desktop/mobile diperiksa sesuai kebutuhan
- [ ] Asset produksi yang masih dibutuhkan tercatat sebagai blocker/intake

---

# 31. Prinsip Akhir

Untuk Fase 1:

> **Aset adalah bagian dari visual fidelity, bukan sekadar isi sementara.**

Menggunakan layout yang benar dengan gambar, icon, crop, atau dekorasi yang berbeda tetap dapat menghasilkan implementasi yang jauh dari Figma.

Karena itu:

> **ambil dari Figma jika tersedia, dokumentasikan sumbernya, gunakan sebagai sementara, dan ganti hanya setelah asset produksi tervalidasi.**

---

# 32. Asset Produksi Struktur Organisasi

| Asset | Path Project | Sumber | Status | Penggunaan |
|---|---|---|---|---|
| Struktur Organisasi PT Anjungan Buana Wisata | `public/assets/about/struktur-organisasi-kampoeng-radja.png` | Asset stakeholder, 24 Agustus 2026 | `[PRODUKSI RESMI]` | Satu-satunya konten visual section Struktur Organisasi pada halaman Tentang Kami |
