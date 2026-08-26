# Documentation Map — Kampoeng Radja

Terakhir direbaseline: **17 Agustus 2026**

File ini adalah peta dokumentasi. Mulai dari `AGENTS.md`, lalu `LOG.md`, kemudian gunakan peta di bawah sesuai modul yang dikerjakan.

## Lima Jenis Kebenaran

| Jenis | Source of truth | Pertanyaan yang dijawab |
| --- | --- | --- |
| Implementation Truth | Source code, migration, route, dan test | Apa yang benar-benar tersedia sekarang? |
| Business Requirement Truth | PRD dan keputusan stakeholder terdokumentasi | Sistem seharusnya bekerja bagaimana? |
| Permission Truth | `GLOBAL/ACCESS_CONTROL.md`, matrix, dan `PERMISSIONS.md` modul | Siapa boleh mengakses data/aksi tertentu? |
| Visual Truth | Figma approved untuk Landing Page; screenshot/reference approved untuk Dashboard | Tampilan seharusnya seperti apa? |
| Progress Truth | `LOG.md` | Project sudah sampai mana? |

Source code tidak boleh dipakai untuk mengubah requirement bisnis secara otomatis. Ketidaksesuaian dicatat sebagai documentation/implementation mismatch.

## Struktur Dokumentasi

```text
docs/
├── README.md
├── DOCUMENTATION_AUDIT.md
├── GLOBAL/
│   ├── PROJECT_CONTEXT.md
│   ├── TECH_STACK.md
│   ├── BRAND_GUIDELINE.md
│   ├── ARCHITECTURE.md
│   ├── ACCESS_CONTROL.md
│   ├── ACCESS_CONTROL_MATRIX.md
│   └── AGENT_RULES.md
├── LANDING-PAGE/
│   └── dokumentasi Figma-first untuk website publik dan CMS Fase 1
    └── DASHBOARD/
    ├── README.md
    ├── ATTENDANCE/
    │   ├── PRD.md
    │   ├── UI_SPEC.md
    │   ├── PERMISSIONS.md
    │   └── references/README.md
    ├── EMPLOYEE/
        ├── README.md
        ├── PRD.md
        ├── PERMISSIONS.md
        ├── UI_SPEC.md
        └── references/
    └── CLOSING-EVENT/
        ├── README.md
        ├── PRD.md
        ├── PERMISSIONS.md
        ├── UI_SPEC.md
        └── references/
```

Closing Event sudah memiliki requirement final dan implementasi aktif. KPI tetap belum aktif; jangan membuat kumpulan file kosong untuk KPI.

## Urutan Baca — Semua Task

1. `AGENTS.md`
2. `LOG.md`
3. `docs/README.md`
4. Dokumen `docs/GLOBAL/` yang relevan
5. Dokumen modul yang dikerjakan
6. Source code aktual untuk mengetahui status implementasi

## Jika Mengerjakan Landing Page atau CMS Publik

1. Baca urutan global di atas.
2. `LANDING-PAGE/PRD.md`
3. `LANDING-PAGE/FIGMA.md`
4. `LANDING-PAGE/FIGMA_ACCURACY.md`
5. `LANDING-PAGE/UI_SPEC.md`
6. `LANDING-PAGE/USER_FLOW.md`
7. `LANDING-PAGE/COMPONENTS.md`
8. `LANDING-PAGE/RESPONSIVE.md`
9. `LANDING-PAGE/CONTENT.md`
10. `LANDING-PAGE/ASSETS.md`
11. `LANDING-PAGE/REFERENCE.md`
12. `LANDING-PAGE/TODO.md`
13. `LANDING-PAGE/AGENT_HANDOFF.md`
14. Untuk release/review: `LANDING-PAGE/DELIVERY_CHECKLIST.md`

Visual desktop Landing Page mengikuti Figma approved. Tablet/mobile adalah responsive fallback karena tidak tersedia frame khusus.

## Jika Mengerjakan Dashboard Internal

1. Baca urutan global di atas.
2. `DASHBOARD/README.md`
3. `DASHBOARD/<MODULE>/PRD.md` jika tersedia
4. `DASHBOARD/<MODULE>/PERMISSIONS.md` jika tersedia
5. `DASHBOARD/<MODULE>/UI_SPEC.md` jika tersedia
6. Screenshot/reference pada `DASHBOARD/<MODULE>/references/`
7. Audit route, middleware, controller, request, model, migration, page, layout, dan test modul tersebut

Dashboard tidak otomatis memakai workflow Figma-first. Jika handoff visual berupa screenshot, screenshot approved menjadi visual reference dan behavior yang tidak terlihat harus ditulis di UI spec.

## Source of Truth per Area

| Area | Requirement | Visual | Progress |
| --- | --- | --- | --- |
| Landing Page publik | `LANDING-PAGE/PRD.md` | Figma approved + dokumen Figma | `LOG.md` |
| CMS Landing Page | `LANDING-PAGE/PRD.md` | UI existing atau desain admin approved jika kelak tersedia | `LOG.md` |
| Data Absensi | `DASHBOARD/ATTENDANCE/PRD.md` | Screenshot approved yang disimpan di `references/` | `LOG.md` |
| Kelola Karyawan | `DASHBOARD/EMPLOYEE/PRD.md` | `DASHBOARD/EMPLOYEE/references/*.png` untuk list/master; UI turunan terdokumentasi di `UI_SPEC.md` | `LOG.md` |
| KPI | Belum aktif | Belum tersedia | `LOG.md` |
| Closing Event | `DASHBOARD/CLOSING-EVENT/PRD.md` | Screenshot rough reference pada `references/` | `LOG.md` |

## Status Informasi

- `[PRODUKSI RESMI]`: konten/aset resmi dan tervalidasi.
- `[FIGMA SEMENTARA]`: konten/aset dari Figma untuk development.
- `[PLACEHOLDER TERDOKUMENTASI]`: placeholder yang sengaja digunakan.
- `[PERLU KONTEN RESMI]`: konten produksi belum tersedia.
- `[PERLU ASET RESMI]`: aset produksi belum tersedia.
- `[PERLU KLARIFIKASI]`: keputusan bisnis/visual belum ditetapkan.
- `[RESPONSIVE FALLBACK]`: adaptasi non-desktop tanpa frame pembanding.
- `[BLOCKED: FIGMA ACCESS]`: inspeksi/QA visual Figma tidak dapat dilakukan.
- `TBD — menunggu keputusan tim`: permission atau rule belum boleh diasumsikan.

## Konflik dan Perubahan Keputusan

1. Business requirement tidak diubah hanya agar cocok dengan source.
2. Permission yang belum diputuskan tetap `TBD`.
3. Visual Landing Page mengikuti Figma approved; visual Dashboard mengikuti reference approved yang tersedia.
4. Mismatch dicatat di `DOCUMENTATION_AUDIT.md` dan progresnya diringkas di `LOG.md`.
5. Keputusan baru harus diperbarui pada PRD/permissions/UI spec yang authoritative, bukan hanya disimpan di chat.

## Audit Terbaru

Hasil klasifikasi seluruh dokumen, gap, archive candidate, dan mismatch per 15 Agustus 2026 tersedia di `DOCUMENTATION_AUDIT.md`.
