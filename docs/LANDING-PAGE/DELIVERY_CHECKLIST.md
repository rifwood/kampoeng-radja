# DELIVERY_CHECKLIST.md
## Checklist Serah-Terima — Fase 1 Landing Page Kampoeng Radja

Dokumen ini digunakan untuk memverifikasi bahwa implementasi Fase 1 benar-benar siap diserahkan.

> **Prinsip utama:**  
> Checklist hanya boleh dicentang jika terdapat bukti verifikasi yang relevan.
>
> Status “selesai” tidak boleh diberikan hanya karena kode sudah dibuat atau build berhasil.

---

# 1. Prinsip Penggunaan Checklist

Sebelum mencentang item:

1. pastikan requirement masih aktif pada PRD terbaru;
2. pastikan referensi Figma yang benar digunakan;
3. lakukan verifikasi aktual;
4. catat blocker/deviasi jika ada;
5. jangan membawa status centang dari implementasi lama tanpa verifikasi ulang.

---

# 2. Figma Reference

- [ ] URL/file Figma tercatat di `FIGMA.md`
- [ ] Page Figma yang relevan tercatat
- [ ] Frame approved/current untuk setiap halaman tercatat
- [ ] Node/section penting tercatat
- [ ] Status approved/current jelas
- [ ] Viewport/frame size tercatat
- [ ] Mapping halaman → section → node Figma lengkap

Jika Figma tidak dapat diakses:

- [ ] Blocker `[BLOCKED: FIGMA ACCESS]` tercatat di `TODO.md`
- [ ] Tidak ada klaim pixel-perfect/pixel-accurate
- [ ] Fallback dokumentasi yang digunakan dicatat

---

# 3. Bukti Akurasi Figma

Untuk setiap halaman/section yang memiliki Figma:

- [ ] Layout dibandingkan dengan Figma
- [ ] Container width dibandingkan
- [ ] Section height dibandingkan
- [ ] Alignment dibandingkan
- [ ] Spacing dibandingkan
- [ ] Typography dibandingkan
- [ ] Line wrapping dibandingkan
- [ ] Warna dibandingkan
- [ ] Border/radius/shadow dibandingkan
- [ ] Icon dibandingkan
- [ ] Media crop dibandingkan
- [ ] Decorative element dibandingkan
- [ ] Interactive state dibandingkan jika tersedia
- [ ] Responsive composition dibandingkan

---

# 4. Visual QA Evidence

Sebelum halaman dinyatakan selesai secara visual:

- [ ] Browser diuji pada viewport yang sama dengan frame Figma
- [ ] Screenshot implementasi tersedia
- [ ] Side-by-side comparison dilakukan, jika memungkinkan
- [ ] Overlay comparison dilakukan, jika tools/workflow memungkinkan
- [ ] Selisih visual signifikan dicatat
- [ ] Perbaikan visual signifikan sudah dilakukan
- [ ] Sisa deviasi yang diterima terdokumentasi

Tidak wajib menggunakan satu tool tertentu.

Yang wajib adalah:

> terdapat bukti bahwa implementasi benar-benar dibandingkan dengan desain.

---

# 5. Klaim Akurasi

Istilah berikut hanya boleh digunakan jika telah diverifikasi:

```text
pixel-accurate
pixel-perfect
100% sama
identik dengan Figma
```

Checklist:

- [ ] Klaim akurasi menyebut viewport yang diverifikasi
- [ ] Klaim tidak mencakup breakpoint yang belum diuji
- [ ] Deviasi yang diketahui tetap disebutkan
- [ ] Tidak ada klaim “100%” jika masih ada perbedaan visual terlihat

---

# 6. Scope Halaman

Daftar halaman final harus mengikuti:

1. `PRD.md`
2. Figma approved
3. `USER_FLOW.md`

Checklist:

- [ ] Semua halaman yang termasuk scope sudah tersedia
- [ ] Tidak ada halaman tambahan tanpa requirement
- [ ] Tidak ada halaman requirement yang hilang
- [ ] Route sesuai requirement
- [ ] Navigasi mengarah ke halaman yang benar

> Dokumen checklist ini tidak mengunci jumlah halaman menjadi empat.

---

# 7. Navbar

Verifikasi berdasarkan Figma/PRD:

- [ ] Struktur navbar sesuai
- [ ] Logo sesuai
- [ ] Menu sesuai
- [ ] Active state sesuai jika ada
- [ ] CTA/login sesuai jika ada
- [ ] Spacing sesuai
- [ ] Responsive navbar sesuai
- [ ] Mobile interaction berfungsi jika ada
- [ ] Keyboard navigation dasar berfungsi

Jangan menganggap hamburger/off-canvas wajib jika Figma menggunakan pola berbeda.

---

# 8. Footer

- [ ] Struktur footer sesuai Figma
- [ ] Kontak yang ditampilkan merupakan data valid/approved
- [ ] Social links valid
- [ ] Copyright benar
- [ ] Logo/asset sesuai
- [ ] Login/internal entry hanya muncul jika requirement menetapkan
- [ ] Responsive footer sesuai

---

# 9. Konten

- [ ] Tidak ada fakta perusahaan yang dibuat agent
- [ ] Konten `[PRODUKSI RESMI]` sudah disetujui
- [ ] Konten `[FIGMA SEMENTARA]` masih tercatat jika belum diganti
- [ ] Placeholder terdokumentasi
- [ ] Tidak ada lorem ipsum yang tertinggal tanpa alasan
- [ ] Kontak publik sudah diverifikasi
- [ ] Jam operasional sudah diverifikasi jika ditampilkan
- [ ] Alamat/Maps sudah diverifikasi jika ditampilkan
- [ ] Promo/event yang tampil masih valid
- [ ] Copy final tidak merusak layout tanpa review

---

# 10. Aset

- [ ] Semua aset Figma berasal dari source/layer export
- [ ] Tidak ada screenshot full-frame yang dipakai sebagai pengganti asset individual
- [ ] Node sumber aset Figma tercatat
- [ ] Status `[FIGMA SEMENTARA]` tercatat
- [ ] Tidak ada stock/dummy/generative asset menggantikan asset Figma yang tersedia
- [ ] Asset produksi resmi sudah diverifikasi terhadap Figma
- [ ] Crop sesuai
- [ ] Focal point sesuai
- [ ] Asset tidak blur pada viewport target
- [ ] Decorative assets tidak hilang tanpa alasan
- [ ] Tidak ada URL Figma temporary digunakan pada runtime

---

# 11. Responsive

Gunakan ukuran frame Figma aktual sebagai viewport utama QA.

Checklist:

- [ ] Desktop frame diuji
- [ ] Tablet frame diuji jika tersedia
- [ ] Mobile frame diuji
- [ ] Intermediate viewport diperiksa bila diperlukan
- [ ] Tidak ada overflow horizontal yang tidak disengaja
- [ ] Text wrapping masih masuk akal
- [ ] Navigation tetap usable
- [ ] Image crop tetap sesuai intent
- [ ] Interactive controls tetap dapat digunakan

Jangan mengunci QA pada:

```text
375px
768px
1024px
1440px
```

kecuali ukuran tersebut memang sesuai frame/reference yang digunakan.

---

# 12. Component Architecture

- [ ] Component sesuai `COMPONENTS.md`
- [ ] Tidak ada abstraction generik yang memaksa desain berubah
- [ ] Reuse dilakukan hanya pada pola yang benar-benar sama
- [ ] Tidak ada `Base*` component spekulatif yang menambah style generik
- [ ] Page-specific component digunakan jika lebih tepat
- [ ] Component tidak memiliki override berlebihan hanya untuk mengikuti Figma

---

# 13. Interaction

Untuk semua interaction yang termasuk scope:

- [ ] CTA berfungsi
- [ ] Link berfungsi
- [ ] Navigation berfungsi
- [ ] Modal/lightbox berfungsi jika ada
- [ ] Filter berfungsi jika ada
- [ ] Sorting berfungsi jika ada
- [ ] Carousel/slider berfungsi jika ada
- [ ] Hover/focus/active state sesuai requirement
- [ ] Escape/keyboard interaction tersedia jika relevan
- [ ] Touch interaction diuji jika relevan

> Hanya centang fitur yang memang ada pada PRD/Figma final.

---

# 14. Wahana

Jika halaman/fitur Wahana termasuk scope final:

- [ ] Struktur halaman sesuai Figma
- [ ] Data tampil sesuai requirement
- [ ] Filter sesuai PRD
- [ ] Category/label sesuai data approved
- [ ] Logic AND/OR sesuai PRD
- [ ] Reset/toggle/search behavior sesuai requirement
- [ ] Empty state tidak error
- [ ] Card/image/lightbox sesuai Figma
- [ ] Responsive sudah diverifikasi

Jika logic filter belum final:

> jangan mencentang berdasarkan dokumentasi versi lama.

---

# 15. Galeri Event

Jika fitur Galeri Event termasuk scope final:

- [ ] Struktur halaman sesuai Figma
- [ ] Data event sesuai requirement
- [ ] Sorting tersedia jika memang disyaratkan
- [ ] Urutan sorting benar
- [ ] Gallery layout sesuai
- [ ] Lightbox/detail sesuai jika ada
- [ ] Empty state aman
- [ ] Responsive terverifikasi

---

# 16. Carousel / Slider

Jika ada carousel/slider:

- [ ] Behavior mengikuti UI spec/Figma
- [ ] Auto-scroll hanya jika requirement
- [ ] Drag hanya jika requirement
- [ ] Pause-on-hover hanya jika requirement
- [ ] Navigation control sesuai desain
- [ ] Mobile behavior diuji

Jangan menganggap behavior tertentu wajib hanya karena pernah ada pada dokumen lama.

---

# 17. Maps / Location

Jika lokasi termasuk scope:

- [ ] Google Maps iframe valid
- [ ] Lokasi benar
- [ ] CTA directions valid
- [ ] Alamat benar
- [ ] Layout mengikuti Figma
- [ ] Responsive aman
- [ ] Tidak ada API key yang tidak diperlukan

---

# 18. Accessibility Dasar

- [ ] Semantic HTML digunakan
- [ ] Image informatif memiliki alt
- [ ] Decorative image ditangani sesuai semantics
- [ ] Button menggunakan elemen button
- [ ] Navigation link menggunakan link
- [ ] Keyboard interaction untuk komponen interaktif
- [ ] Focus state usable
- [ ] Modal focus behavior masuk akal jika ada
- [ ] Kontras teks utama cukup atau deviasi desain telah dicatat

Target sentuh seperti `44px` dapat digunakan sebagai guidance, tetapi tidak boleh dianggap bukti bahwa seluruh desain otomatis accessible.

---

# 19. Performance

- [ ] Asset tidak berukuran berlebihan
- [ ] Image optimization dilakukan
- [ ] Lazy loading diterapkan pada media yang memang cocok
- [ ] Hero/LCP asset tidak salah dilazy-load jika merugikan load utama
- [ ] Video dioptimasi jika ada
- [ ] Tidak ada dependency berat tanpa kebutuhan
- [ ] Tidak ada runtime request ke source asset Figma
- [ ] Tidak ada layout shift besar yang jelas

---

# 20. SEO

Untuk halaman publik yang termasuk scope:

- [ ] `<title>` tersedia
- [ ] Meta description tersedia
- [ ] Heading hierarchy masuk akal
- [ ] Alt text tersedia
- [ ] URL/slug sesuai
- [ ] Open Graph diterapkan jika memang masuk requirement/release scope
- [ ] Canonical/sitemap/robots ditangani bila diperlukan untuk deployment

> Open Graph per halaman tidak otomatis wajib jika PRD/release scope belum menetapkannya.

---

# 21. Data dan Backend

Jika halaman menggunakan data dinamis:

- [ ] Data source sesuai PRD
- [ ] Tidak ada hardcode untuk data yang wajib dinamis
- [ ] Tidak ada tabel spekulatif
- [ ] Empty data tidak menyebabkan crash
- [ ] Validation tersedia untuk input admin jika ada
- [ ] Authorization sesuai scope
- [ ] Upload media menggunakan storage yang benar

---

# 22. Build dan Test

Centang hanya berdasarkan hasil aktual terbaru.

- [ ] Frontend build berhasil
- [ ] Backend test relevan berhasil
- [ ] Tidak ada error runtime utama
- [ ] Tidak ada console error yang belum dijelaskan
- [ ] Route utama diuji
- [ ] Interaksi utama diuji

Catat command dan hasil aktual pada laporan task/handoff.

---

# 23. Status Implementasi

Jangan menyimpan status lama sebagai fakta permanen.

Gunakan format berikut setiap kali melakukan release review:

```text
Tanggal verifikasi:
Commit/branch:
Figma version/reference:
Environment:
Reviewer:
```

Lalu isi status aktual.

---

# 24. Template Status Review

## Review Metadata

```text
Tanggal:
Branch/commit:
Figma file:
Figma page:
Frame(s):
Environment:
Reviewer:
```

## Verified

- [ ] ...

## Failed / Needs Fix

- [ ] ...

## Blocked

- [ ] ...

## Notes

```text
...
```

---

# 25. Dokumentasi Handoff

Sebelum serah-terima:

- [ ] `TODO.md` diperbarui
- [ ] `ASSETS.md` diperbarui
- [ ] `CONTENT.md` diperbarui
- [ ] `FIGMA.md` diperbarui
- [ ] Deviasi visual tercatat
- [ ] Blocker tercatat
- [ ] Build/test terbaru dirangkum
- [ ] Asset produksi yang masih kurang dirangkum
- [ ] Tidak ada fitur Fase 2/3 yang ikut dibangun tanpa requirement

---

# 26. Blocker

Blocker harus diberi label jelas.

Contoh:

```text
[BLOCKED: FIGMA ACCESS]
[PERLU ASET RESMI]
[PERLU KONTEN RESMI]
[PERLU KLARIFIKASI]
```

Checklist tidak boleh dicentang hanya untuk “menyelesaikan” handoff jika blocker masih relevan.

---

# 27. Definition of Done — Halaman

Sebuah halaman dianggap selesai jika:

- [ ] Requirement aktif terpenuhi
- [ ] Figma mapping tersedia
- [ ] Asset benar
- [ ] Content benar
- [ ] Interaction utama bekerja
- [ ] Responsive terverifikasi
- [ ] Visual QA dilakukan
- [ ] Build/runtime valid
- [ ] Tidak ada deviasi signifikan yang tidak dicatat
- [ ] TODO terkait diperbarui

---

# 28. Definition of Done — Fase 1

Fase 1 dianggap siap diserahkan jika:

- [ ] Semua halaman/fitur scope final selesai
- [ ] Tidak ada requirement Fase 1 kritis yang belum dikerjakan
- [ ] Visual QA selesai untuk frame utama
- [ ] Aset/konten sementara sudah direview
- [ ] Blocker produksi diketahui
- [ ] Build/test terbaru valid
- [ ] Dokumentasi sinkron
- [ ] Stakeholder mengetahui deviasi/hal yang belum final

---

# 29. Prinsip Akhir

> **Build hijau bukan berarti desain benar.**

> **Fitur bekerja bukan berarti visual selesai.**

> **Figma mirip secara kasat mata bukan berarti sudah terverifikasi.**

Serah-terima Fase 1 harus membuktikan tiga hal:

1. requirement benar;
2. implementasi teknis berfungsi;
3. visual telah dibandingkan dengan Figma approved.
