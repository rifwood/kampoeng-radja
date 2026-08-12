# COMPONENTS.md
## Strategi Komponen Vue — Fase 1 Landing Page Kampoeng Radja

Dokumen ini menjelaskan prinsip pembentukan komponen Vue untuk Fase 1 agar kode tetap terstruktur dan reusable **tanpa menyeragamkan desain Figma**.

> **Prinsip utama:**  
> Komponen dibuat untuk menjaga konsistensi implementasi dan mengurangi duplikasi yang nyata, bukan untuk memaksa seluruh desain menggunakan bentuk UI generik yang sama.
>
> API, props, slot, variant, dan styling component harus mampu mereproduksi Figma approved tanpa menambah:
>
> - card;
> - padding;
> - radius;
> - shadow;
> - border;
> - animation;
> - hover effect;
> - layout behavior;
>
> yang tidak ada pada desain.

---

# 1. Source of Truth

Untuk pembentukan komponen frontend:

1. **Figma approved**
2. `FIGMA.md`
3. `FIGMA_ACCURACY.md`
4. `UI_SPEC.md`
5. dokumen ini

Dokumen ini tidak boleh digunakan untuk mengubah desain Figma.

---

# 2. Prinsip Pembentukan Komponen

Buat komponen ketika salah satu kondisi berikut terpenuhi:

- struktur visual benar-benar berulang;
- behavior berulang;
- logic interaktif cukup kompleks;
- elemen dipakai di lebih dari satu tempat;
- pemisahan komponen meningkatkan keterbacaan file halaman;
- komponen memiliki boundary yang jelas.

Jangan membuat komponen hanya karena:

- elemen terlihat seperti card;
- elemen memiliki button;
- elemen memiliki gambar;
- pola “biasanya reusable” pada proyek lain;
- agent ingin membuat design system generik sejak awal.

---

# 3. Jangan Premature Abstraction

Agent tidak boleh otomatis membuat:

```text
BaseButton.vue
BaseCard.vue
BaseBadge.vue
BaseModal.vue
SectionWrapper.vue
PageContainer.vue
```

hanya karena nama tersebut umum digunakan.

Komponen tersebut hanya dibuat jika setelah menginspeksi Figma memang terdapat pola visual/behavior yang cukup konsisten untuk diwakili satu API.

---

# 4. Reusability vs Visual Accuracy

Jika terdapat pilihan antara:

```text
1 komponen generik tetapi hasil sedikit berbeda dari Figma
```

dan

```text
2 komponen yang lebih spesifik tetapi hasil akurat
```

maka pilih solusi yang lebih akurat selama struktur kode tetap masuk akal.

> Reusability tidak boleh mengorbankan visual fidelity.

---

# 5. Kategori Komponen

Komponen dapat dikelompokkan secara konseptual menjadi:

```text
Layout
Shared UI
Section
Feature
Page-specific
Interactive
```

Namun struktur folder aktual tidak wajib mengikuti kategori ini secara kaku.

---

# 6. Layout Components

Layout component hanya dibuat untuk struktur yang memang digunakan lintas halaman.

Contoh potensial:

| Komponen | Kapan Dibuat |
|---|---|
| `AppNavbar.vue` | Jika navbar yang sama digunakan di beberapa halaman |
| `AppFooter.vue` | Jika footer yang sama digunakan di beberapa halaman |
| `PublicLayout.vue` | Jika navbar/footer/layout shell memang sama |
| `PageContainer.vue` | Hanya jika container width/padding benar-benar konsisten di banyak section |
| `SectionWrapper.vue` | Hanya jika section wrapper memang memiliki pola yang sama |

Nama aktual mengikuti implementasi project.

---

# 7. Navbar

`AppNavbar.vue` tidak boleh mengasumsikan:

- selalu 4 menu;
- selalu sticky;
- selalu hamburger;
- selalu tinggi tertentu;
- selalu warna tertentu.

Detail tersebut mengikuti Figma dan PRD.

Jika responsive navbar berbeda secara signifikan, component boleh memiliki variant atau internal responsive structure.

---

# 8. Footer

`AppFooter.vue` hanya mengenkapsulasi footer jika footer memang shared.

Jangan otomatis menambahkan:

- tooltip;
- login;
- sosial media;
- kolom kontak;
- animation;

jika elemen tersebut tidak ada di Figma/PRD.

---

# 9. Container dan Section Wrapper

`PageContainer` atau `SectionWrapper` hanya boleh dibuat bila ditemukan nilai yang benar-benar berulang.

Agent tidak boleh menjadikan satu container global sebagai penyebab seluruh section memiliki:

- max-width sama;
- padding sama;
- vertical rhythm sama;

jika Figma menunjukkan perbedaan.

Section khusus diperbolehkan memiliki container/layout sendiri.

---

# 10. Button Components

Komponen button reusable boleh dibuat jika:

- struktur sama;
- typography sama atau variant terdefinisi;
- padding/radius/state memiliki pola jelas.

Contoh API konseptual:

```vue
<AppButton variant="primary" size="lg">
  ...
</AppButton>
```

Namun jangan memaksakan seluruh CTA memakai satu button component jika bentuk visualnya berbeda signifikan.

---

# 11. Card Components

Tidak ada kewajiban membuat `BaseCard.vue`.

Sebelum membuat reusable card, cek apakah beberapa card memang berbagi:

- structure;
- media treatment;
- spacing;
- radius;
- border/shadow;
- interaction.

Jika News Card, Promo Card, dan Attraction Card berbeda secara visual:

> buat komponen terpisah.

Contoh:

```text
NewsCard.vue
PromoCard.vue
AttractionCard.vue
```

lebih baik daripada satu `BaseCard.vue` yang dipenuhi conditional styling kompleks.

---

# 12. Badge / Chip / Filter Item

Badge atau filter chip dapat dibuat reusable jika style dan behavior benar-benar sama.

Jangan menyatukan:

- decorative badge;
- filter chip;
- status badge;

ke satu komponen hanya karena bentuknya mirip.

Semantics dan interaction harus tetap jelas.

---

# 13. Modal dan Lightbox

Modal generic boleh dibuat jika beberapa fitur memang membutuhkan modal dengan shell yang sama.

Lightbox boleh menjadi komponen tersendiri jika behavior gallery cukup khusus.

Jangan membangun `BaseModal` hanya sebagai abstraction wajib jika hanya ada satu kebutuhan lightbox.

---

# 14. Image Component

Tidak ada kewajiban seluruh gambar menggunakan wrapper custom.

Gunakan `<img>` biasa jika:

- kebutuhan sederhana;
- browser native lazy loading cukup;
- tidak ada logic tambahan.

Buat component seperti `AppImage.vue` atau `LazyImage.vue` hanya jika terdapat kebutuhan reusable seperti:

- fallback;
- shared loading state;
- source switching;
- consistent responsive image behavior;
- asset metadata.

> Jangan menambah wrapper hanya karena “best practice” jika tidak memberi manfaat nyata.

---

# 15. Section Components

Section yang besar/mandiri sebaiknya dipisah menjadi component jika hal tersebut meningkatkan readability.

Contoh konseptual berdasarkan desain yang mungkin ada:

```text
HeroSection.vue
NewsSection.vue
PromoSection.vue
LocationSection.vue
HistorySection.vue
VisionMissionSection.vue
AttractionFilterSection.vue
EventGallerySection.vue
```

Nama dan keberadaan component final harus mengikuti Figma/PRD aktual.

---

# 16. Page-Specific Components

Komponen yang hanya digunakan pada satu halaman **tetap boleh dibuat**.

Tidak semua component harus reusable lintas halaman.

Contoh:

```text
HomeHero.vue
AboutHistoryTimeline.vue
AttractionFilterPanel.vue
GalleryEventGroup.vue
```

Jika struktur tersebut unik dan kompleks, page-specific component justru lebih jelas.

---

# 17. Hero Components

Jangan memaksa hero image dan hero video menggunakan satu abstraction jika:

- layout berbeda;
- overlay berbeda;
- text position berbeda;
- responsive behavior berbeda;
- media behavior berbeda.

Shared primitive hanya dibuat bila memang ada bagian yang identik.

---

# 18. Beranda — Candidate Components

Komponen berikut **hanya kandidat**, bukan daftar wajib:

```text
HomeHeroSection.vue
InsightSection.vue
NewsSection.vue
PromoEventSection.vue
FeaturedAttractionSection.vue
PartnerSection.vue
LocationSection.vue
```

Gunakan nama/struktur sesuai desain Figma aktual.

Jika dua section lebih sederhana dibiarkan langsung di page component, itu diperbolehkan.

---

# 19. Tentang Kami — Candidate Components

Candidate:

```text
AboutHeroSection.vue
HistorySection.vue
VisionMissionSection.vue
OrganizationSection.vue
```

Jika organization chart memiliki node berulang, komponen seperti:

```text
OrganizationNode.vue
```

boleh dibuat.

Namun jangan memaksakan recursive tree jika desain sebenarnya statis dan sederhana.

---

# 20. Wahana — Candidate Components

Candidate:

```text
AttractionHeader.vue
AttractionFilter.vue
FilterGroup.vue
AttractionGrid.vue
AttractionCard.vue
AttractionLightbox.vue
EmptyState.vue
```

Nama final mengikuti terminology project.

Filter component harus mencerminkan behavior PRD dan state Figma.

---

# 21. Galeri Event — Candidate Components

Candidate:

```text
EventSortControl.vue
EventCard.vue
EventGallery.vue
EventPhotoGrid.vue
GalleryLightbox.vue
```

Jangan memecah menjadi terlalu banyak component kecil jika hanya menambah kompleksitas.

---

# 22. Interactive Components

Komponen interaktif reusable dapat dibuat jika behavior sama.

Contoh potensial:

```text
Carousel.vue
Lightbox.vue
Tabs.vue
FilterChip.vue
```

Namun behavior harus mengikuti Figma/UI spec, bukan API library.

---

# 23. Carousel / Slider

Jangan otomatis membuat `AutoScrollSlider.vue` generik.

Jika Partner slider dan Promo carousel memiliki:

- speed berbeda;
- interaction berbeda;
- layout berbeda;
- responsive behavior berbeda;

maka component terpisah bisa lebih tepat.

Shared composable/helper boleh digunakan untuk logic yang benar-benar sama.

---

# 24. Composables

Logic non-visual yang berulang dapat dipindahkan ke composable.

Contoh:

```text
useCarousel()
useLightbox()
useAttractionFilter()
```

Hanya lakukan jika reuse nyata atau logic cukup kompleks.

Jangan membuat composable untuk logic trivial.

---

# 25. Props

Props harus:

- memiliki tujuan jelas;
- tidak membuat API terlalu generik;
- tidak digunakan untuk mengontrol puluhan detail style unik.

Jika sebuah component membutuhkan banyak prop seperti:

```text
shadow
radius
padding
gap
imagePosition
titleSize
titleWeight
...
```

untuk mereproduksi desain yang sangat berbeda-beda, pertimbangkan memisahkan component.

---

# 26. Slots

Slots cocok digunakan ketika shell visual sama tetapi content berbeda.

Gunakan slot jika membantu.

Jangan menggunakan slot hanya untuk membuat semua desain masuk ke satu component generik.

---

# 27. Variant

Variant diperbolehkan jika perbedaannya memang bagian dari family component yang sama.

Contoh:

```text
primary
secondary
outline
```

hanya jika varian tersebut memang ada di Figma.

Jangan membuat variant spekulatif.

---

# 28. Component Styling

Styling component mengikuti Figma.

Component tidak boleh otomatis memiliki:

- shadow;
- rounded;
- hover scale;
- hover lift;
- transition;
- border;

kecuali desain/state memang membutuhkannya.

---

# 29. Responsive Components

Responsive behavior boleh berada:

- di dalam component;
- melalui parent layout;
- melalui props jika memang perlu.

Pilih struktur yang paling sederhana dan akurat.

Jangan membuat satu component sangat generik hanya untuk mendukung semua breakpoint jika struktur DOM sebenarnya berbeda secara signifikan.

---

# 30. Accessibility

Reusable component interaktif harus menjaga semantics.

Contoh:

- CTA link → `<a>` / Inertia Link
- action → `<button>`
- dialog → semantics dialog yang sesuai
- filter control → state yang dapat dipahami

Reusability tidak boleh mengorbankan accessibility.

---

# 31. Component Naming

Gunakan `PascalCase`.

Nama harus mencerminkan fungsi/role.

Lebih baik:

```text
NewsCard.vue
AttractionFilter.vue
HomeHero.vue
```

daripada nama terlalu abstrak seperti:

```text
Box.vue
Item.vue
Widget.vue
```

---

# 32. Foldering

Contoh struktur yang diperbolehkan:

```text
resources/js/Components/
├── Shared/
├── Home/
├── About/
├── Attractions/
└── Gallery/
```

atau struktur lebih flat jika project masih kecil.

Jangan membuat folder hanya untuk mengikuti dokumen jika tidak diperlukan.

---

# 33. Shared vs Page-Specific

Sebuah component dipindahkan ke `Shared/` hanya setelah terbukti digunakan oleh lebih dari satu konteks atau memang merupakan shell global.

Jangan memprediksi kebutuhan Fase 2/3 terlalu dini.

---

# 34. Checklist Sebelum Membuat Component Baru

Sebelum membuat component, agent harus bertanya:

- [ ] Apakah struktur ini berulang?
- [ ] Apakah behavior ini berulang?
- [ ] Apakah pemisahan meningkatkan readability?
- [ ] Apakah component ini dapat mereproduksi Figma tanpa kompromi?
- [ ] Apakah sudah ada component yang cocok?
- [ ] Apakah reuse nyata atau hanya spekulatif?
- [ ] Apakah component terlalu generik?

---

# 35. Checklist Sebelum Reuse Component

Sebelum memakai component existing:

- [ ] Struktur Figma sesuai
- [ ] Spacing sesuai
- [ ] Typography sesuai
- [ ] Radius/border/shadow sesuai
- [ ] Interaction sesuai
- [ ] Responsive behavior sesuai
- [ ] Tidak perlu override berlebihan

Jika banyak override diperlukan, pertimbangkan component baru.

---

# 36. Red Flag Abstraction

Pertimbangkan memecah component jika:

- terlalu banyak conditional class;
- terlalu banyak boolean props;
- props mengontrol hampir setiap visual detail;
- slot menjadi sangat kompleks;
- markup berbeda drastis per variant;
- perubahan satu variant sering merusak variant lain.

---

# 37. Definition of Done — Component

Component dianggap siap jika:

- [ ] Mereproduksi Figma pada konteksnya
- [ ] API cukup sederhana
- [ ] Tidak menambah style generik yang tidak ada
- [ ] Responsive state benar
- [ ] Accessibility dasar benar
- [ ] Tidak ada abstraction berlebihan
- [ ] Reuse tidak merusak visual

---

# 38. Prinsip Akhir

> **Component architecture mengikuti desain, bukan desain mengikuti component architecture.**

Agent harus mengutamakan:

1. akurasi Figma;
2. readability;
3. maintainability;
4. reuse yang nyata.

Bukan:

> membuat sebanyak mungkin `Base*` component lalu memaksa seluruh halaman menggunakannya.
