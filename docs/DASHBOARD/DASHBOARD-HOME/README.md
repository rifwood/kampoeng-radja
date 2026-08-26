# Dashboard Home

Status requirement: **Approved — redesign 22 Agustus 2026**
Status implementasi: lihat `LOG.md`.

Dashboard Home adalah landing page canonical sistem internal pada `/dashboard`. `/admin` tetap mengarah ke `/dashboard` untuk backward compatibility, sedangkan route management `/admin/*` tidak berubah.

## Urutan Baca

1. `AGENTS.md`
2. `LOG.md`
3. `docs/README.md`
4. dokumentasi global access/architecture
5. `PRD.md`
6. `PERMISSIONS.md`
7. `UI_SPEC.md`
8. `references/dashboard-home.png`
9. source aktual

## Struktur Final

1. Welcome Banner
2. Empat summary card: Karyawan Aktif, Hadir, Terlambat, Izin/Alfa
3. Grafik Pendapatan Harian dari nilai Closing Event pada tanggal mulai
4. Ringkasan Absensi Hari Ini
5. Ringkasan Closing Event
6. Akses Cepat berbasis capability

Kalender Kerja dan Karyawan Terbaru tidak lagi menjadi bagian Dashboard Home.

## Source of Truth

- Business/data: `PRD.md`
- Access: `PERMISSIONS.md`
- Interaction/responsive: `UI_SPEC.md`
- Desktop visual: `references/dashboard-home.png`
- Progress/verification: `LOG.md`

Screenshot berisi contoh visual, bukan data production. Seluruh angka, identitas, dan daftar periode berasal dari database/backend.
