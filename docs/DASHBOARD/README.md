# Dashboard Internal — Documentation Map

Status workstream: **aktif secara terbatas**

Dashboard Internal menggunakan Laravel, Inertia, Vue, Tailwind, dan session authentication yang sama dengan Landing Page. Visual Dashboard dapat menggunakan screenshot/reference approved; workflow Figma-first hanya wajib jika modul tersebut memang diserahkan melalui Figma.

## Status Modul

| Modul | Requirement | Implementation | Dokumentasi |
| --- | --- | --- | --- |
| Dashboard Home | Role-aware Super Admin/Admin/User | `IMPLEMENTED` | `DASHBOARD-HOME/` |
| Data Absensi | Flow Super Admin aktif | `PARTIAL` — flow utama Super Admin tersedia; role lain TBD | `ATTENDANCE/` |
| Data Karyawan | CRUD, scoped read-only, akun Karyawan | `IMPLEMENTED` | `EMPLOYEE/` |
| Jabatan & Departemen | Master data Employee | `IMPLEMENTED` | `EMPLOYEE/` |
| CMS | Requirement berasal dari Landing Page Fase 1 | Media Berita dan Event Promo tersedia | `LANDING-PAGE/PRD.md` + `LOG.md` |
| KPI | Belum aktif | `NOT STARTED` | Jangan membuat docs modul sebelum requirement aktif |
| Closing Event | Belum aktif | `NOT STARTED` | Jangan membuat docs modul sebelum requirement aktif |

## Urutan Baca Dashboard

1. `AGENTS.md`
2. `LOG.md`
3. `docs/README.md`
4. `docs/GLOBAL/PROJECT_CONTEXT.md`
5. `docs/GLOBAL/ARCHITECTURE.md`
6. `docs/GLOBAL/ACCESS_CONTROL.md`
7. `docs/GLOBAL/ACCESS_CONTROL_MATRIX.md`
8. Dokumen modul Dashboard terkait
9. Reference visual modul
10. Source dan test aktual

## Aturan Visual

- Screenshot approved menjadi visual truth jika handoff modul berupa screenshot.
- UI spec hanya menjelaskan behavior yang tidak terlihat dari screenshot.
- Jangan mengarang desain yang belum diberikan.
- Jangan mengklaim pixel-accurate tanpa comparison pada viewport reference.

## Aturan Scope

- Data Absensi terdokumentasi karena requirement dan implementation aktif.
- Data Karyawan, Jabatan/Departemen, dan account lifecycle mengikuti requirement aktif pada `EMPLOYEE/`.
- KPI dan Closing Event tetap planned/not started dan tidak boleh diimplementasikan melalui asumsi.
