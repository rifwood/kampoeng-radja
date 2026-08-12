# BRAND_GUIDELINE.md
## Panduan Brand Global — Kampoeng Radja

**Cakupan:** Berlaku untuk seluruh produk dalam ekosistem digital Kampoeng Radja.

Dokumen ini mendefinisikan identitas visual dasar Kampoeng Radja yang berlaku lintas produk.

> **PENTING — Fase 1 Landing Page**  
> Dokumen ini adalah **fondasi brand dan fallback**, bukan source of truth utama untuk detail visual implementasi.
>
> Untuk Fase 1, **Figma approved mengendalikan seluruh nilai visual yang terlihat**, termasuk:
>
> - font family;
> - font size;
> - font weight;
> - line-height;
> - letter-spacing;
> - warna aktual pada komponen;
> - spacing;
> - ukuran;
> - radius;
> - border;
> - shadow;
> - icon;
> - state;
> - layout;
> - responsive composition.
>
> Lihat:
>
> - `docs/LANDING-PAGE/FIGMA.md`
> - `docs/LANDING-PAGE/FIGMA_ACCURACY.md`
> - `docs/LANDING-PAGE/UI_SPEC.md`
>
> Agent tidak boleh menggunakan panduan global ini untuk melakukan redesign terhadap Figma.

---

# 1. Tujuan Brand

Kampoeng Radja adalah taman wisata keluarga.

Identitas visual digital Kampoeng Radja harus mampu memberikan kesan:

- fun;
- playful;
- colorful;
- energetic;
- ramah keluarga;
- mudah didekati;
- profesional;
- terpercaya.

Brand tidak boleh terasa:

- terlalu korporat;
- terlalu formal;
- gelap dan muram;
- terlalu minimal hingga kehilangan karakter wisata keluarga;
- ramai tanpa hierarchy visual yang jelas.

---

# 2. Hubungan Brand Guideline dan Figma

Brand Guideline menentukan **identitas dasar**.

Figma menentukan **manifestasi visual konkret pada produk yang sedang dibuat**.

Contoh:

Jika Brand Guideline menyatakan bahwa Kampoeng Radja menggunakan warna biru sebagai warna utama, tetapi sebuah button pada Figma approved menggunakan orange:

> button tersebut harus tetap orange seperti Figma.

Jika Brand Guideline memberi rekomendasi font tetapi Figma approved menggunakan font berbeda:

> gunakan font pada Figma.

Jika ukuran, radius, shadow, atau spacing pada Figma berbeda dari nilai fallback dokumentasi:

> gunakan nilai Figma.

---

# 3. Hierarki Visual Source of Truth

Untuk Fase 1, urutan keputusan visual adalah:

1. **Figma approved**
2. `LANDING-PAGE/FIGMA.md`
3. `LANDING-PAGE/FIGMA_ACCURACY.md`
4. `LANDING-PAGE/UI_SPEC.md`
5. `GLOBAL/BRAND_GUIDELINE.md`

Brand Guideline digunakan sebagai fallback jika nilai tertentu **tidak tersedia atau belum ditentukan di Figma**.

---

# 4. Warna Brand

## 4.1 Warna Utama

| Nama | Hex | Peran Brand |
|---|---|---|
| Brand Blue | `#00D2FC` | Warna identitas utama |
| Brand Orange | `#FFA20D` | Warna aksen utama |
| White | `#FFFFFF` | Warna netral utama / ruang negatif |

---

## 4.2 Warna Pendukung

| Nama | Hex | Peran Brand |
|---|---|---|
| Pink | `#FA3E9F` | Aksen playful |
| Yellow | `#FFFC00` | Aksen cerah / highlight |
| Green | `#006905` | Aksen tambahan |

Warna ini merupakan palette brand dasar.

> **Jangan mengasumsikan setiap warna harus digunakan pada setiap halaman.**

Pemakaian aktual mengikuti Figma.

---

# 5. Aturan Penggunaan Warna

Agent harus:

1. mengambil warna aktual dari Figma ketika tersedia;
2. menggunakan palette global sebagai fallback;
3. menghindari membuat shade/tint baru tanpa kebutuhan;
4. tidak mengganti warna Figma hanya karena nilai global berbeda;
5. tidak memaksa seluruh komponen menggunakan `Brand Blue` atau `Brand Orange`.

---

## 5.1 Token Warna

Warna brand yang benar-benar digunakan berulang dapat didefinisikan sebagai token CSS/Tailwind.

Contoh konseptual:

```js
colors: {
  brand: {
    blue: '#00D2FC',
    orange: '#FFA20D',
    pink: '#FA3E9F',
    yellow: '#FFFC00',
    green: '#006905',
    white: '#FFFFFF',
  },
}
```

Nama dan struktur token aktual boleh disesuaikan dengan konfigurasi project.

> Jangan membuat token hanya untuk setiap nilai unik dari Figma jika nilai tersebut hanya digunakan sekali.

Nilai visual khusus dapat digunakan langsung melalui utility/arbitrary value jika memang diperlukan untuk menjaga akurasi desain.

---

# 6. Aksesibilitas Warna

Warna cerah seperti:

- `#00D2FC`
- `#FFFC00`

dapat memiliki kontras rendah terhadap putih.

Agent harus tetap memperhatikan keterbacaan.

Prinsip:

- hindari teks body panjang dengan kontras rendah;
- gunakan warna teks yang memenuhi keterbacaan;
- decorative element tidak memiliki requirement kontras yang sama dengan informasi utama;
- jangan mengubah warna visible Figma secara sepihak hanya berdasarkan estimasi.

Jika terdapat masalah aksesibilitas nyata pada desain approved, catat untuk review daripada melakukan redesign diam-diam.

---

# 7. Tipografi

## 7.1 Aturan Fase 1

Untuk Landing Page:

> **font yang digunakan harus mengikuti Figma approved.**

Agent tidak boleh memilih sendiri:

- Fredoka;
- Baloo;
- Nunito;
- Poppins;
- Inter;
- atau font lain;

jika Figma sudah menentukan font.

---

## 7.2 Fallback Jika Font Belum Ditentukan

Jika suatu produk belum memiliki desain visual final, font dapat dipilih dengan karakter:

### Heading

- friendly;
- rounded atau expressive;
- playful tanpa mengorbankan readability.

### Body

- bersih;
- mudah dibaca;
- cocok untuk paragraf;
- memiliki weight yang cukup lengkap.

Namun pemilihan font baru harus dicatat dan tidak boleh dianggap sebagai keputusan brand permanen sebelum disetujui.

---

## 7.3 Konsistensi Typography

Untuk produk yang sudah memiliki Figma:

ikuti secara akurat:

- font family;
- font size;
- font weight;
- line-height;
- letter-spacing;
- casing;
- alignment;
- text wrapping.

Jangan mengubah ukuran typography hanya agar sesuai dengan skala Tailwind default.

---

# 8. Font Assets

Jika font Figma:

- tersedia melalui web font legal;
- tersedia sebagai dependency yang diizinkan;
- atau sudah tersedia di project;

gunakan sumber tersebut.

Jika font tidak tersedia, agent harus mencatat kebutuhan font pada:

`LANDING-PAGE/ASSETS.md`

atau:

`LANDING-PAGE/TODO.md`

Agent tidak boleh diam-diam mengganti font dengan font yang mirip lalu menyatakan hasil sama dengan Figma.

---

# 9. Spacing dan Layout

Brand Guideline **tidak menentukan spacing layout Fase 1**.

Untuk Landing Page, nilai berikut harus diambil dari Figma:

- container width;
- section padding;
- gap;
- margin;
- alignment;
- grid;
- column width;
- section height;
- card spacing;
- navbar spacing;
- footer spacing.

Jangan menggunakan aturan generik seperti:

```text
max-w-7xl
py-16
px-4
rounded-2xl
```

hanya karena nilai tersebut umum digunakan.

Nilai tersebut hanya boleh digunakan jika memang menghasilkan ukuran yang sesuai desain Figma.

---

# 10. Border Radius, Border, dan Shadow

Tidak ada satu radius atau shadow global yang wajib diterapkan pada seluruh produk.

Untuk produk dengan Figma, ikuti nilai desain.

Agent tidak boleh:

- membuat semua card rounded;
- menambah shadow pada semua card;
- membuat semua button berbentuk pill;
- menambah border;
- menghilangkan border;

tanpa dasar dari Figma.

---

# 11. Tombol

Brand Guideline hanya menentukan bahwa button harus:

- jelas sebagai elemen interaktif;
- mudah dibaca;
- konsisten dengan identitas Kampoeng Radja;
- memiliki state yang dapat digunakan.

Untuk Fase 1, seluruh detail button mengikuti Figma:

- warna;
- ukuran;
- bentuk;
- typography;
- icon;
- border;
- radius;
- shadow;
- hover;
- focus;
- active;
- disabled;
- spacing.

Jangan membuat state tambahan yang mengubah karakter visual secara signifikan jika tidak dibutuhkan.

---

# 12. Card

Tidak ada satu bentuk `BaseCard` global yang wajib dipakai untuk seluruh layout.

Card dapat berbeda berdasarkan konteks.

Untuk Fase 1:

> bentuk card harus mengikuti komponen Figma.

Reusability diperbolehkan hanya jika tidak mengubah hasil visual.

---

# 13. Badge dan Label

Warna dan bentuk badge mengikuti desain Figma.

Palette brand dapat digunakan sebagai fallback jika desain belum menentukan nilai tertentu.

Agent tidak boleh membuat pemetaan seperti:

- anak-anak = pink;
- wahana darat = green;
- promo = yellow;

kecuali mapping tersebut benar-benar tercantum pada Figma atau requirement produk.

---

# 14. Iconography

Untuk Fase 1:

> gunakan icon yang terdapat atau direferensikan pada Figma.

Prioritas:

1. icon asset Figma;
2. icon component/library yang identik atau sangat sesuai;
3. fallback icon set yang disepakati.

Agent tidak boleh mengganti icon hanya karena library lain lebih mudah digunakan.

---

## 14.1 Konsistensi Icon

Jika sebuah icon library digunakan sebagai fallback, gunakan satu keluarga icon secara konsisten dalam konteks yang sama.

Namun aturan ini tidak melarang penggunaan custom SVG dari Figma.

Custom icon Figma memiliki prioritas lebih tinggi daripada konsistensi dengan library generic.

---

# 15. Illustration dan Decorative Element

Decorative element merupakan bagian dari desain.

Agent tidak boleh menganggap elemen seperti:

- blob;
- pattern;
- cloud;
- star;
- wave;
- shape;
- line;
- floating object;
- illustration;

sebagai elemen opsional jika elemen tersebut terlihat pada Figma approved.

Elemen dekoratif tetap harus direplikasi selama tidak ada alasan teknis kuat untuk menghilangkannya.

---

# 16. Fotografi

Secara brand, fotografi Kampoeng Radja sebaiknya memberikan kesan:

- cerah;
- aktif;
- menyenangkan;
- ramah keluarga;
- natural;
- menampilkan pengalaman wisata;
- menampilkan ekspresi kebahagiaan;
- menampilkan wahana atau suasana Kampoeng Radja secara jelas.

Hindari secara umum:

- tone muram;
- fotografi korporat kaku;
- stock photo yang tidak relevan;
- visual yang tidak mewakili Kampoeng Radja.

---

# 17. Fotografi Fase 1

Untuk Fase 1:

> **foto yang ada di Figma harus digunakan sebagai referensi utama dan boleh digunakan sebagai aset sementara sampai aset produksi resmi tersedia.**

Agent tidak boleh mengganti foto Figma dengan:

- Unsplash;
- Lorem Picsum;
- stock image acak;
- hasil pencarian internet;

jika aset Figma tersedia.

Detail export dan pengelolaan asset berada di:

`LANDING-PAGE/ASSETS.md`

---

# 18. Image Treatment

Hal-hal berikut mengikuti Figma:

- aspect ratio;
- crop;
- object position;
- overlay;
- mask;
- radius;
- opacity;
- filter;
- blend;
- decorative frame.

Menggunakan gambar yang sama tetapi crop atau proporsinya salah tetap dianggap sebagai perbedaan visual.

---

# 19. Video

Jika Figma atau PRD menggunakan video:

- gunakan asset yang ditentukan jika tersedia;
- pertahankan aspect ratio;
- pertahankan framing;
- jangan mengganti video dengan gambar statis tanpa instruksi.

Strategi compression/loading ditentukan secara teknis tanpa mengubah tampilan.

---

# 20. Tone of Voice Visual

Secara visual, Kampoeng Radja harus terasa:

> **Fun tanpa terlihat asal-asalan.**

> **Colorful tanpa terlihat berantakan.**

> **Playful tanpa kehilangan hierarchy.**

> **Profesional tanpa kehilangan karakter taman wisata.**

Figma approved merupakan interpretasi utama tone tersebut untuk Landing Page.

---

# 21. Sistem Internal

Fase 2 dan Fase 3 tetap berada dalam keluarga brand Kampoeng Radja.

Namun sistem internal dapat memiliki karakter lebih:

- functional;
- information-dense;
- restrained;
- productivity-oriented.

Keputusan visual final sistem internal **tidak dibuat pada Fase 1**.

Brand global dipertahankan, tetapi detail UI akan mengikuti desain dan dokumentasi fase masing-masing.

---

# 22. Larangan untuk Agent

Untuk Fase 1, agent tidak diperbolehkan:

- memilih font sendiri jika Figma sudah menentukan;
- membuat skala typography sendiri;
- menggunakan spacing generik sebagai pengganti nilai Figma;
- menambah gradient tanpa dasar;
- membuat seluruh card memiliki shadow;
- membuat seluruh button rounded/pill tanpa dasar;
- membuat hover scale otomatis pada semua komponen;
- mengubah warna Figma agar sesuai token;
- menghilangkan decorative element;
- mengganti icon Figma;
- mengganti asset Figma dengan stock asset;
- menganggap brand guideline sebagai izin redesign.

---

# 23. Fallback Rule

Brand Guideline digunakan sebagai fallback hanya jika:

1. Figma tidak menentukan nilai tersebut;
2. `UI_SPEC.md` tidak menentukan nilai tersebut;
3. tidak ada component reference;
4. keputusan diperlukan untuk menyelesaikan implementasi.

Jika fallback digunakan pada keputusan yang terlihat secara visual, agent harus mencatatnya bila berpotensi memengaruhi visual QA.

---

# 24. Hal yang Masih Perlu Dikonfirmasi

## Asset Brand Resmi

Masih perlu dikonfirmasi/diterima secara produksi:

- logo vector resmi;
- varian logo background terang;
- varian logo background gelap;
- aturan clear space logo jika perusahaan memilikinya;
- font brand resmi jika ada;
- asset guideline resmi perusahaan jika tersedia.

Sampai asset produksi tersebut tersedia, asset Figma dapat digunakan sesuai `ASSETS.md`.

---

## Sistem Internal

Masih perlu ditentukan pada fase berikutnya:

> apakah KPI dan Closing Event Marketing akan menggunakan bahasa visual yang sama persis dengan Landing Page atau adaptasi yang lebih fungsional.

Keputusan tersebut tidak memengaruhi implementasi Fase 1.

---

# 25. Ringkasan Source of Truth Fase 1

Untuk memutuskan tampilan Landing Page:

```text
Figma Approved
      ↓
FIGMA.md
      ↓
FIGMA_ACCURACY.md
      ↓
UI_SPEC.md
      ↓
BRAND_GUIDELINE.md
```

Jika Figma tersedia:

> **jangan redesign.**

> **jangan menebak nilai visual.**

> **jangan mengganti asset.**

> **implementasikan desain approved seakurat mungkin.**
