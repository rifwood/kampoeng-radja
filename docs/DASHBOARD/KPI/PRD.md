# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# MODUL KPI — KAMPOENG RADJA

**Nama Sistem:** Sistem Informasi Terintegrasi Kampoeng Radja  
**Modul:** KPI (Key Performance Indicator)  
**Status Dokumen:** FINAL  
**Versi:** September 2026  
**Timezone Sistem:** Asia/Jakarta (WIB)

---

# 1. Pendahuluan

Modul KPI digunakan untuk mengelola proses evaluasi kinerja karyawan Kampoeng Radja secara terpusat, terjadwal, bertingkat, dan terhubung dengan struktur organisasi perusahaan.

Sebelumnya, proses KPI dijalankan melalui beberapa file spreadsheet terpisah yang mencakup:

1. Daily Report
2. Kinerja Individu
3. Kinerja OPS / Indeks Prestasi Kerja Perorangan
4. Monthly Performance Appraisal (MPA)
5. Penilaian Absensi
6. Reward & Punishment
7. Nilai Akhir

Sistem KPI yang dikembangkan harus menyatukan seluruh proses tersebut ke dalam satu workflow terintegrasi yang:

- terikat pada periode;
- mengikuti struktur atasan dan bawahan;
- membedakan pihak yang mengisi, menilai, memantau, dan menandatangani;
- memiliki deadline otomatis;
- mendukung approval/tanda tangan;
- menyimpan histori struktur dan parameter KPI per periode;
- menghasilkan Nilai Akhir secara otomatis;
- menyediakan akses administratif penuh bagi Super Admin;
- menetapkan Direktur/HRD sebagai pelaksana operasional utama administrasi KPI.

---

# 2. Tujuan

Modul KPI bertujuan untuk:

1. Memusatkan pengelolaan seluruh proses KPI.
2. Mengurangi penggunaan file Excel terpisah.
3. Memastikan proses penilaian mengikuti timeline perusahaan.
4. Memudahkan atasan memantau KPI bawahan.
5. Mendukung approval/tanda tangan berdasarkan struktur organisasi.
6. Mendukung penilaian Monthly oleh penilai berbeda setiap periode.
7. Menghubungkan aktivitas harian, evaluasi diri, target kerja, MPA, absensi, reward/punishment, dan Nilai Akhir.
8. Menjaga histori KPI tetap konsisten meskipun struktur organisasi berubah.
9. Menyediakan akses administrasi kepada Super Admin.
10. Menyelesaikan siklus KPI sebelum proses penggajian.

---

# 3. Fondasi Sistem yang Sudah Tersedia

| Fondasi | Status |
|---|---|
| Data Karyawan | Sudah |
| Jabatan | Sudah |
| Departemen | Sudah |
| Penempatan | Sudah |
| Atasan Langsung | Sudah |
| Relasi Bawahan | Sudah |
| Foto Tanda Tangan | Sudah |
| Role `super_admin`, `admin`, `user` | Sudah |
| Sinkronisasi Role berdasarkan Jabatan | Sudah |
| Menu KPI | Belum |
| Database KPI | Belum |
| Workflow KPI | Belum |
| Scheduler KPI | Belum |

---

# 4. Role Sistem dan Kewajiban KPI

## 4.1 Mapping Role

| Jabatan | Role |
|---|---|
| Dirut | `super_admin` |
| Direktur | `super_admin` |
| Manajer / Manager | `super_admin` |
| SPV / Supervisor | `admin` |
| Marketing | `user` |
| Marcom / Markom | `user` |
| IT | `user` |
| Finance | `user` |
| Kasir | `user` |
| Operasional | `user` |
| General | `user` |
| Facility | `user` |

## 4.2 Kewajiban KPI Berdasarkan Jabatan

### Dirut
- Tidak mengisi KPI Individu.
- Menu **Individu** tidak ditampilkan.
- Tetap memiliki full access sebagai Super Admin.
- Seluruh tombol aksi administratif Super Admin tetap tersedia.
- Bukan pelaksana normal administrasi KPI.

### Direktur / HRD
- Tidak mengisi KPI Individu.
- Menu **Individu** tidak ditampilkan.
- Memiliki full access sebagai Super Admin.
- Menjadi pelaksana operasional utama administrasi KPI.
- Mengelola parameter KPI.
- Menilai karyawan yang sedang menjadi penilai MPA.
- Melengkapi kekurangan MPA jika penilai gagal menyelesaikan tugas.
- Mengisi Penilaian Absensi.
- Mengisi Reward & Punishment.
- Menyelesaikan dan mem-publish Monthly secara keseluruhan.

### Manajer
- Tetap wajib mengisi KPI Individu.
- Menu **Individu** ditampilkan.
- Memiliki full access sebagai Super Admin.
- Memiliki KPI-Karyawan.
- Seluruh tombol administratif Super Admin tetap tampil.
- Bukan pelaksana normal administrasi KPI; tanggung jawab operasional tetap pada Direktur/HRD.

### SPV
- Wajib mengisi KPI Individu.
- KPI-Karyawan tampil apabila mempunyai bawahan.
- MPA aktif apabila ditetapkan sebagai penilai.

### User
- Wajib mengisi KPI Individu.
- KPI-Karyawan hanya tampil apabila mempunyai bawahan.
- MPA aktif apabila ditetapkan sebagai penilai.

---

# 5. Prinsip Akses Super Admin

Semua Super Admin mempunyai full capability:

```text
Dirut
Direktur
Manajer
```

Seluruh tombol aksi administratif tetap tersedia untuk ketiganya.

Namun tanggung jawab operasional ditetapkan sebagai:

```text
Full Capability Sistem       = Semua Super Admin
Pelaksana Normal Administrasi = Direktur / HRD
```

Sistem tidak membedakan tanggung jawab bisnis Super Admin melalui hide/disable tombol.

---

# 6. Struktur Menu KPI

```text
KPI
├── Individu
│   ├── Daily Report
│   ├── Kinerja Individu
│   ├── Kinerja OPS
│   ├── Monthly
│   └── Nilai Akhir
│
├── KPI-Karyawan
│
└── MPA
```

---

# 7. Visibilitas Menu

| Pengguna | Individu | KPI-Karyawan | MPA |
|---|---|---|---|
| Dirut | Hide | Full Access | Full Access |
| Direktur/HRD | Hide | Full Access | Full Access |
| Manajer | Tampil | Full Access | Full Access |
| SPV dengan bawahan | Tampil | Tampil | Jika ditunjuk |
| SPV tanpa bawahan | Tampil | Hide | Jika ditunjuk |
| User dengan bawahan | Tampil | Tampil | Jika ditunjuk |
| User tanpa bawahan | Tampil | Hide | Jika ditunjuk |

---

# 8. Periodisasi KPI

## 8.1 Prinsip Periode

Semua KPI bulanan menggunakan **bulan performa**, bukan bulan saat form diisi.

Contoh:

```text
Performa             : Agustus 2026
Pengisian            : September 2026
periode_bulan        : 8
periode_tahun        : 2026
```

## 8.2 Peserta KPI Bulanan

Peserta KPI periode adalah:

- karyawan yang masih aktif pada hari terakhir bulan performa;
- bukan Dirut;
- bukan Direktur.

Contoh:

```text
Masuk 20 Agustus dan aktif pada 31 Agustus
→ menjadi peserta KPI Agustus
```

```text
Keluar 25 Agustus dan sudah nonaktif sebelum 31 Agustus
→ tidak dibuatkan siklus KPI Agustus
```

Daily sebelum tanggal keluar tetap menjadi histori.

## 8.3 Atasan Langsung

Untuk seluruh peserta KPI:

```text
atasan_langsung_id
```

secara operasional wajib tersedia sebelum periode KPI dibentuk.

Jika kosong:

```text
KPI Configuration Error
```

HRD wajib memperbaiki struktur karyawan. Sistem tidak boleh menebak atasan.

## 8.4 Snapshot Struktur Organisasi

Saat periode dibentuk, sistem menyimpan snapshot:

- jabatan;
- departemen;
- penempatan;
- atasan langsung;
- atasan kedua.

Minimal:

```text
jabatan_id + label snapshot
departemen_id + label snapshot
penempatan_id + label snapshot
atasan_langsung_id snapshot
atasan_kedua_id snapshot
```

Perubahan struktur pada periode berikutnya tidak mengubah histori periode lama.

Daily Report menyimpan snapshot atasan langsung pada tanggal Daily tersebut.

## 8.5 Precision

- kalkulasi intermediate: minimal 4 angka desimal;
- nilai komponen final: 2 angka desimal;
- Nilai Akhir: 2 angka desimal.

Contoh:

```text
78.567 → 78.57
94.925 → 94.93
```

---

# 9. Timeline Resmi KPI

| Proses | Waktu |
|---|---|
| Daily Report | Setiap hari kerja aktual |
| Penetapan penilai MPA | Maksimal hari terakhir bulan performa pukul 23:59 WIB |
| Kinerja Individu | Tanggal 1–2 bulan berikutnya |
| Kinerja OPS | Tanggal 1–2 bulan berikutnya |
| MPA Penilai | Tanggal 1–5 |
| Monthly bagian HRD | Tanggal 6–8 |
| TTD KI / K-OPS / Monthly | Maksimal tanggal 9 |
| Auto-Sign | Tanggal 9 pukul 23:59 WIB |
| TTD Nilai Akhir | Tanggal 10 |

---

# 10. Individu — Daily Report

## 10.1 Pengguna

Wajib bagi seluruh karyawan peserta KPI kecuali Dirut dan Direktur.

Manajer tetap wajib mengisi.

## 10.2 Header

- Nama
- NIK
- Perusahaan
- Jabatan
- Departemen
- Penempatan
- Atasan Langsung
- Tanggal

## 10.3 Tabel

| Field | Pengisi |
|---|---|
| No | Sistem |
| Rincian Kegiatan | Karyawan |
| Keterangan | Karyawan |
| Bukti | Karyawan — upload foto |

## 10.4 Batas Pengisian

Daily hanya dapat diisi:

- hari ini;
- kemarin.

Contoh pada 3 September:

```text
2 September → boleh isi/edit
3 September → boleh isi/edit
1 September → tidak boleh
```

Jika sudah approved, Daily tetap locked walaupun masih berada dalam periode edit.

## 10.5 Hari Kerja Aktual

Daily wajib hanya ketika karyawan benar-benar seharusnya bekerja/hadir.

Daily wajib untuk:

- Hari Normal;
- Hari Event;
- hari kerja aktual lainnya.

Daily tidak memicu `Tidak Mengisi` apabila:

- izin;
- sakit;
- off;
- libur;
- tidak dijadwalkan;
- alfa/tidak hadir;
- sudah nonaktif.

Alfa tidak diberi penalti Daily tambahan karena telah ditangani Absensi/Disiplin.

## 10.6 Approval

Simpan tidak langsung mengunci record.

```text
Karyawan Simpan
→ Menunggu TTD Atasan Langsung
→ Atasan Approve
→ Locked
```

Selama belum approved dan masih dalam periode edit, karyawan boleh memperbarui Daily.

## 10.7 Tanda Tangan

Wajib:

- Atasan Langsung berdasarkan snapshot Daily.

Manual sign menyimpan:

- approver;
- timestamp;
- status;
- referensi tanda tangan.

Daily tidak terkena auto-sign bulanan.

## 10.8 Tanda Tangani Semua

Pada Daily bawahan langsung tersedia:

**Tanda Tangani Semua**

Aksi ini:

1. hanya berlaku untuk bawahan langsung;
2. hanya menandatangani Daily yang masih pending;
3. tidak mengubah Daily yang sudah approved;
4. membutuhkan konfirmasi;
5. mencatat approver dan timestamp untuk setiap record.

## 10.9 Tidak Mengisi dan SP1

Counter `Tidak Mengisi Daily` dihitung **per bulan/periode**.

```text
3× Tidak Mengisi dalam bulan yang sama
→ Perlu Tindak Lanjut SP1
```

Counter reset bulan berikutnya, namun histori tetap disimpan.

Sistem **tidak menerbitkan SP1 otomatis**.

HRD menerima indikator dan mengonfirmasi/mencatat tindak lanjut SP1.

---

# 11. Individu — Kinerja Individu

## 11.1 Pengguna

Diisi seluruh peserta KPI kecuali Dirut dan Direktur.

Periode:

**tanggal 1–2 bulan berikutnya**

## 11.2 Komponen Tetap

| Komponen | Bobot |
|---|---:|
| Capaian Departemen | 70% |
| Perawatan Aset Kerja sesuai bidang | 5% |
| Kebersihan & Kerapihan Lingkungan Kerja | 5% |
| **Total Normal** | **80%** |

Nama komponen utama dan bobot bersifat **fixed** pada baseline final.

HRD dapat mengelola:

- target;
- deskripsi;
- indikator pendukung;
- kriteria;
- isi dinamis lainnya.

Tetapi tidak mengubah tiga nama komponen utama dan bobotnya.

## 11.3 Pengisian

Karyawan mengisi:

- Evaluasi Diri: 1–100.

HRD mengelola parameter pendukung pada tiga komponen tetap tersebut.

## 11.4 Formula

```text
Nilai KI =
(Capaian Departemen × 0.70)
+ (Perawatan Aset × 0.05)
+ (Kebersihan & Kerapihan × 0.05)
```

Contoh:

```text
100 × 0.70 = 70
80 × 0.05  = 4
90 × 0.05  = 4.5

Total = 78.5
```

## 11.5 Deadline dan Auto Submit

Lewat tanggal 2:

```text
input_value = NULL
status      = not_filled
submit_type = automatic
score       = 0
```

Dengan begitu sistem dapat membedakan nilai 0 yang benar-benar diinput dengan tidak mengisi sama sekali.

## 11.6 Tanda Tangan

Wajib:

- Atasan Langsung.

Setelah approval:

- locked.

---

# 12. Individu — Kinerja OPS

## 12.1 Konsep

Kinerja OPS dikonfigurasi **per karyawan per periode**.

Template per jabatan/penempatan dapat ditambahkan di kemudian hari, tetapi record final tetap milik:

```text
karyawan + periode
```

## 12.2 Field

| Field | Sumber/Pengisi |
|---|---|
| No | Sistem |
| KPI Item | HRD |
| Maintenance | HRD |
| Target Unit | HRD |
| Tanda (+/-) | HRD |
| Beban Target | Sistem |
| Sumber Data | Sistem dari snapshot Jabatan |
| Frekuensi | HRD |
| Bulan | Sistem dari periode |
| Target Bulanan | Sistem = Target Unit |
| Hasil | Karyawan |
| Aktivitas Pencapaian — teks | Karyawan |
| Aktivitas Pencapaian — foto | Karyawan |

## 12.3 Parameter HRD

Direktur/HRD mengisi:

1. KPI Item
2. Maintenance
3. Target Unit
4. Tanda (+/-)
5. Frekuensi

Semua Super Admin memiliki tombol aksi, tetapi Direktur/HRD adalah pelaksana normal.

Untuk item aktif:

```text
Target Unit > 0
Target Bulanan > 0
```

Jika item tidak memiliki target pada periode tersebut, item tidak dibuat aktif.

## 12.4 Field Otomatis

### Sumber Data

Menggunakan snapshot Jabatan periode.

### Bulan

Diambil dari periode KPI.

### Target Bulanan

```text
Target Bulanan = Target Unit
```

Frekuensi hanya menjadi informasi operasional dan tidak ikut scoring pada baseline final.

### Beban Target

Beban Target tetap merupakan **bobot persentase bisnis dari total 10%**, tetapi backend merepresentasikan angka bobot sebagai **percentage points**, bukan pecahan desimal.

Contoh:

```text
2.00 = 2 percentage points dari total bobot 10%
```

bukan:

```text
0.02
```

Rumus:

```text
Beban Target Item =
(Target Unit Item / Total Target Unit) × 10
```

Contoh:

```text
Target Unit: 2,2,2,2,2
→ Beban: 2.00,2.00,2.00,2.00,2.00
→ Total = 10.00
```

Contoh:

```text
Target Unit: 5,1,1,1
→ Beban: 6.25,1.25,1.25,1.25
→ Total = 10.00
```

## 12.5 Pengisian Karyawan

Karyawan hanya mengisi:

- Hasil;
- Aktivitas Pencapaian berupa teks;
- Bukti Aktivitas Pencapaian berupa foto.

## 12.6 Formula Nilai

```text
Nilai Item =
(Hasil / Target Unit)
× Beban Target
```

```text
Nilai Kinerja OPS =
SUM(Nilai Item)
```

Contoh:

```text
Target = 2
Hasil  = 2
Bobot  = 2.00

Nilai Item = 2.00
```

Jika:

```text
2 + 2 + 2 + 0 + 2
```

maka:

```text
Nilai Kinerja OPS = 8.00
```

Kinerja OPS **boleh melebihi 10** apabila Hasil melampaui Target. Nilai tidak di-clamp.

## 12.7 Tanda (+/-)

Field tetap tersedia sebagai atribut K-OPS.

**Fungsi matematisnya belum digunakan dalam engine scoring sampai perusahaan memberikan rule final.**

Implementor tidak boleh membuat formula sendiri.

## 12.8 Deadline

Lewat tanggal 2:

```text
input = NULL
status = not_filled
submit_type = automatic
score = 0
```

## 12.9 Tanda Tangan

Wajib:

- Karyawan bersangkutan;
- Atasan Langsung.

Jika belum lengkap sebelum deadline, dapat terkena auto-sign.

## 12.10 Snapshot Parameter

Seluruh konfigurasi K-OPS disnapshot per karyawan/periode.

Perubahan master di periode baru tidak mengubah histori periode lama.

---

# 13. KPI-Karyawan

## 13.1 Tujuan

Digunakan untuk:

- memantau KPI bawahan;
- melihat status pengisian;
- melihat status tanda tangan;
- memberikan approval;
- melihat seluruh rantai bawahan;
- membuka seluruh karyawan untuk Super Admin.

## 13.2 Hirarki

Menggunakan:

```text
karyawan.atasan_langsung_id
```

Contoh:

```text
Naftalio
↓
Surwono
↓
Jon
```

Untuk Naftalio:

- Surwono = Bawahan Langsung
- Jon = Bawahan Kedua

## 13.3 Kategori

```text
Bawahan Langsung
Bawahan Kedua
Bawahan Ketiga
Bawahan Keempat
dst.
```

### Super Admin

Memiliki tambahan:

```text
Seluruh Karyawan
```

Kategori ini tidak dibatasi hubungan bawahan pribadi.

## 13.4 CTA

Setiap baris:

```text
[ DR ] [ KI ] [ K-OPS ] [ M ] [ NA ]
```

## 13.5 Monitoring vs Approval

Monitoring dapat dilakukan terhadap seluruh bawahan.

Approval hanya sesuai kewenangan:

| KPI | Approval |
|---|---|
| Daily | Atasan Langsung |
| Kinerja Individu | Atasan Langsung |
| Kinerja OPS | Karyawan + Atasan Langsung |
| Monthly | HRD + Karyawan + Atasan Langsung + Atasan Kedua |
| Nilai Akhir | Atasan Langsung |

---

# 14. MPA — Monthly Performance Appraisal

## 14.1 Hubungan MPA dan Monthly

MPA dan Monthly adalah **satu record yang sama**.

```text
MPA
= tempat memberi penilaian

Monthly pada Individu
= tampilan hasil penilaian milik karyawan
```

---

# 15. Penetapan Penilai MPA

## 15.1 Lokasi

Penetapan penilai dilakukan langsung pada:

```text
KPI → MPA
```

Tidak ada halaman pengaturan penilai terpisah.

## 15.2 Aturan

1. Satu periode hanya memiliki satu penilai utama.
2. Penilai menilai seluruh peserta KPI selain dirinya sendiri.
3. Penilai dapat berubah tiap periode.
4. Super Admin dapat menetapkan penilai.
5. Penilai dapat disiapkan untuk beberapa periode ke depan.
6. Deadline masing-masing periode tetap hari terakhir bulan performa pukul 23:59 WIB.
7. Setelah tanggal 1, assignment periode tersebut locked.
8. Penetapan lebih awal tidak membuka akses penilaian lebih awal.
9. Akses penilai normal hanya tanggal 1–5 bulan berikutnya.

## 15.3 Penilai Eligible

Penilai normal harus:

- karyawan aktif;
- memiliki akun;
- `users.is_active = true`;
- termasuk peserta KPI;
- bukan Dirut;
- bukan Direktur.

Dirut/Direktur bukan random evaluator reguler.

## 15.4 Penilai Tidak Ditentukan

Jika sampai deadline tidak ada penilai:

```text
MPA = UNASSIGNED / BLOCKED
```

Tidak boleh ada late assignment biasa.

HRD mengambil alih penilaian.

## 15.5 Penilai Nonaktif

### Sebelum tanggal 1
Super Admin boleh mengganti sampai deadline akhir bulan.

### Setelah tanggal 1
Tidak dilakukan reassignment.

Histori assignment tetap.

HRD melanjutkan record yang belum selesai.

---

# 16. Akses MPA Penilai

Penilai melihat seluruh peserta KPI kecuali:

- Dirut;
- Direktur;
- dirinya sendiri.

Penilai boleh menilai karyawan pada level lebih tinggi.

Kolom minimal:

- Nama
- Jabatan
- Departemen
- Penempatan
- Status
- Aksi

Aksi:

```text
Beri Nilai
```

Setelah selesai:

```text
Sudah Dinilai
```

---

# 17. Penilaian MPA

## 17.1 Kinerja Operasional

Skala:

| Nilai | Kriteria |
|---:|---|
| 1–15 | Di bawah rata-rata |
| 16–30 | Mencapai target/standar |
| 31–45 | Luar biasa |

Penilai mengisi:

- angka;
- keterangan/bukti pendukung text.

## 17.2 Penilaian Umum

Dimensi:

1. Sikap Kerja
2. Team Work
3. Inisiatif
4. Kepemimpinan/Potensi Kepemimpinan

## 17.3 Level 1 / Tidak Memiliki Bawahan

Dinilai:

- Kinerja Operasional
- Sikap Kerja
- Team Work
- Inisiatif

Rumus:

```text
((KO + Sikap + Team Work + Inisiatif) / 4) / 45 × 5
```

## 17.4 Level 2+ / Memiliki Bawahan

Tambahan:

- Kepemimpinan

Rumus:

```text
((KO + Sikap + Team Work + Inisiatif + Kepemimpinan) / 5) / 45 × 5
```

Maksimum normal:

**5 poin**

## 17.5 Performance dan Coaching

Penilai juga mengisi:

- Penjelasan Berkaitan dengan Performance
- Rencana Perbaikan / Coaching / Counseling

Bukti MPA berbentuk **text only**.

---

# 18. Deadline MPA

Periode:

**Tanggal 1–5**

Setelah deadline:

- akses penilai ditutup;
- tidak ada toleransi;
- tidak ada reopen otomatis.

Jika belum selesai:

```text
HRD takeover
```

HRD melengkapi record yang sama dan aksi takeover wajib diaudit.

---

# 19. MPA Milik Penilai

Penilai tidak boleh menilai dirinya sendiri.

Monthly milik penilai dinilai oleh:

**Direktur / HRD**

Rule ini wajib.

---

# 20. Akses Super Admin di MPA

Semua Super Admin:

- full access;
- seluruh tombol tersedia.

Namun Direktur/HRD adalah pelaksana normal penilaian administratif dan takeover.

---

# 21. Monthly — Lanjutan oleh HRD

Setelah MPA:

```text
Penilai
→ Penilaian Umum
→ Performance
→ Coaching

HRD
→ Absensi
→ Reward/Punishment
```

Seluruh pihak melanjutkan **record yang sama**.

---

# 22. Penilaian Absensi Monthly

Diisi manual oleh HRD.

| Kriteria | Kode | Potongan |
|---|---|---:|
| Urusan Pribadi | P1 | 0.5 |
| Datang Lambat | DL | 0.3 |
| Pulang Cepat | PC | 0.3 |
| Lupa Catat | LC | 0.3 |
| Mangkir | M | 3 |

Rumus:

```text
Total = Potongan × Jumlah Hari
```

```text
Nilai Absensi =
(10 - SUM Total Potongan) × 0.5
```

Tidak diterapkan clamp tambahan.

---

# 23. Reward & Punishment

Diisi HRD.

| Kriteria | Nilai |
|---|---:|
| Major Award | +7 |
| Minor Award | +3 |
| Minor Demerit | -4 |
| Major Demerit | -8 |

```text
Total Baris = Nilai × Jumlah
Nilai R/P = SUM(Total Baris)
```

Reward/Punishment opsional.

---

# 24. Penyelesaian Monthly oleh HRD

HRD tidak publish per karyawan.

Workflow:

```text
Penilai selesai MPA
↓
HRD melengkapi seluruh Monthly
↓
Absensi seluruh karyawan
↓
Reward/Punishment
↓
Seluruh record selesai
↓
Publish satu periode sekaligus
```

Tidak boleh publish sebagian.

## 24.1 HRD Incomplete

Jika lewat tanggal 8 belum lengkap:

```text
status = HRD_INCOMPLETE
```

HRD dapat melakukan late administrative completion.

Aksi tersebut wajib diaudit.

## 24.2 Late Publish

Jika publish setelah 9 pukul 23:59:

```text
Publish
→ cek deadline
→ catch-up auto-sign
```

Monthly yang belum publish tidak boleh auto-sign.

---

# 25. Tanda Tangan Monthly

Wajib:

1. HRD
2. Karyawan
3. Atasan Langsung
4. Atasan Kedua jika tersedia

TTD HRD otomatis saat publish.

Urutan TTD:

**bebas**

Tidak wajib Atasan 1 → Atasan 2.

Jika Atasan Kedua tidak tersedia:

```text
TTD Atasan Kedua = N/A
```

Tidak memblokir Monthly.

---

# 26. Atasan Kedua

```text
Atasan Kedua =
Atasan Langsung dari Atasan Langsung
```

Menggunakan snapshot periode.

---

# 27. Auto-Sign

Deadline:

**Tanggal 9 pukul 23:59 WIB**

Berlaku untuk:

- Kinerja Individu
- Kinerja OPS
- Monthly yang sudah publish

Tidak berlaku:

- Daily
- Nilai Akhir

## 27.1 Manual Sign

Manual sign dapat menggunakan:

- foto tanda tangan;
- nama;
- waktu.

Jika belum punya foto tanda tangan, pengguna harus melengkapinya sebelum manual sign.

## 27.2 Auto-Sign

Auto-sign tidak menggunakan foto tanda tangan pribadi.

Metadata:

```text
status = auto_signed
signed_for_user_id
auto_signed_at
reason = deadline
source = automatic
```

UI/report menampilkan bahwa tanda tangan dilakukan otomatis oleh sistem.

## 27.3 Catch-Up Auto-Sign

Jika record baru publish setelah deadline:

```text
Publish terlambat
→ Catch-Up Auto-Sign
```

---

# 28. Nilai Akhir

## 28.1 Formula

```text
Nilai Akhir =
Kinerja Individu
+ Kinerja OPS
+ Penilaian Umum
+ Disiplin/Absensi
+ Reward/Punishment
```

## 28.2 Bobot Normal

| Komponen | Nilai Normal |
|---|---:|
| Kinerja Individu | 80 |
| Kinerja OPS | 10 |
| Penilaian Umum | 5 |
| Absensi | 5 |
| Reward/Punishment | Tambahan/Pengurangan |

## 28.3 Nilai >100

Tidak dilakukan clamp.

Contoh:

```text
Nilai Akhir = 107.00
```

tetap disimpan 107.

Kelebihan dapat berhubungan dengan insentif tambahan.

## 28.4 Kategori

| Nilai | Kategori |
|---:|---|
| >= 90 | Sangat Baik |
| 80–<90 | Baik |
| 70–<80 | Cukup |
| 60–<70 | Kurang |
| <60 | Sangat Kurang |

---

# 29. Tanda Tangan Nilai Akhir

Wajib:

- Atasan Langsung.

Waktu normal:

**Tanggal 10**

Jika prerequisite terlambat:

```text
Prerequisite selesai
→ Nilai Akhir dihitung
→ Status Late Finalization
→ CTA TTD Atasan tersedia saat itu
```

---

# 30. Matriks Tanda Tangan

| KPI | Karyawan | Atasan Langsung | Atasan Kedua | HRD |
|---|:---:|:---:|:---:|:---:|
| Daily Report | - | ✓ | - | - |
| Kinerja Individu | - | ✓ | - | - |
| Kinerja OPS | ✓ | ✓ | - | - |
| Monthly | ✓ | ✓ | ✓ / N/A | ✓ |
| Nilai Akhir | - | ✓ | - | - |

---

# 31. CRUD Administratif KPI

Semua Super Admin memiliki full CRUD capability.

Pelaksana normal:

**Direktur/HRD**

CRUD dapat mengelola:

- KPI Item
- Maintenance
- Target Unit
- Tanda
- Frekuensi
- target
- indikator
- kriteria
- parameter pendukung KPI

Parameter yang memengaruhi histori harus disnapshot/version per periode.

---

# 32. Shared Record

Semua bagian KPI berbagi record periode yang sama.

Contoh Monthly:

```text
Jon + Agustus 2026
│
├── Penilai isi Penilaian Umum
├── Penilai isi Performance
├── Penilai isi Coaching
├── HRD isi Absensi
├── HRD isi Reward/Punishment
├── HRD Publish
├── Jon TTD
├── Atasan 1 TTD
└── Atasan 2 TTD / N/A
```

Bukan membuat record baru untuk setiap aktor.

---

# 33. Snapshot dan Historisasi Parameter

Parameter yang digunakan suatu periode tidak boleh berubah mengikuti master terbaru.

Prinsip:

```text
MASTER
↓
Generate Periode
↓
SNAPSHOT
↓
Scoring + Histori
```

Berlaku untuk:

- struktur organisasi;
- Kinerja Individu;
- KPI Item;
- Maintenance;
- Target;
- Bobot;
- Kriteria MPA;
- parameter lain yang memengaruhi nilai.

---

# 34. Koreksi Administratif

KPI historis yang sudah final boleh dikoreksi oleh Super Admin hanya melalui:

**Koreksi Administratif**

Bukan edit biasa.

Wajib mencatat:

- alasan koreksi;
- pengguna yang melakukan;
- timestamp;
- data/nilai sebelum;
- data/nilai sesudah;
- revision history;
- recalculation nilai terdampak.

Record lama tidak dihapus.

---

# 35. Status Record

Status yang dapat digunakan:

```text
draft
submitted
waiting_approval
approved
not_filled
auto_submitted
auto_signed
locked
completed
late_completion
late_finalization
```

Status MPA:

```text
unassigned
blocked
scheduled
open
completed
closed
hrd_takeover
```

Status Monthly:

```text
HRD_INCOMPLETE
published
late_publish
```

Nama teknis final boleh menyesuaikan implementasi selama makna bisnis tetap sama.

---

# 36. Indikator UI

Contoh:

```text
Jon — Teknisi

[ DR 🟡 ] [ KI 🟢 ] [ K-OPS ⚠️ ] [ M 🟡 ] [ NA ⚪ ]
```

Sistem harus dapat menandai:

- belum diisi;
- sedang diproses;
- menunggu TTD;
- selesai;
- tidak mengisi;
- locked;
- perlu tindakan;
- auto-submitted;
- auto-signed;
- late completion;
- late finalization.

---

# 37. Notifikasi MVP

Scope awal:

- badge;
- indicator;
- status di dalam aplikasi.

Web Push / notifikasi HP tidak termasuk scope awal.

---

# 38. Hard Deadline

Deadline harus dieksekusi oleh sistem.

Sistem harus:

- menutup input;
- mengganti status;
- menjalankan auto-submit;
- menjalankan auto-sign;
- menjalankan catch-up auto-sign;
- mencegah edit setelah locked;
- mencatat late administrative completion.

---

# 39. Acceptance Criteria

## 39.1 Menu dan Akses
- Dirut tidak melihat Individu.
- Direktur tidak melihat Individu.
- Manajer tetap melihat dan mengisi Individu.
- Semua Super Admin memiliki full access.
- Semua Super Admin melihat tombol aksi administratif.
- KPI-Karyawan Super Admin mempunyai kategori Seluruh Karyawan.
- Admin/User tanpa bawahan tidak melihat KPI-Karyawan.

## 39.2 Daily
- Hanya hari ini dan kemarin.
- Bukti foto tersedia.
- Hanya hari kerja aktual yang diwajibkan.
- Izin/Sakit/Off/Libur/Alfa tidak memicu pelanggaran Daily.
- Simpan tidak lock.
- TTD Atasan Langsung lock.
- Bulk `Tanda Tangani Semua` tersedia.
- Counter 3× dihitung per bulan.
- 3× memunculkan indikator SP1.
- SP1 tidak dibuat otomatis.

## 39.3 Kinerja Individu
- Aktif tanggal 1–2.
- Evaluasi diri 1–100.
- Struktur utama 70% + 5% + 5% fixed.
- Auto-submit setelah deadline.
- Kosong menghasilkan NULL + status not_filled + score 0.
- TTD Atasan Langsung wajib.

## 39.4 Kinerja OPS
- Konfigurasi per karyawan/periode.
- HRD mengelola KPI Item, Maintenance, Target Unit, Tanda, Frekuensi.
- Sumber Data dari snapshot Jabatan.
- Bulan dari periode.
- Target Bulanan = Target Unit.
- Target aktif >0.
- Beban Target dihitung otomatis total normal 10 percentage points.
- Karyawan hanya mengisi Hasil + aktivitas teks + foto.
- Nilai boleh melampaui 10.
- TTD karyawan + Atasan Langsung wajib.
- Fungsi `Tanda (+/-)` belum memengaruhi scoring sampai rule bisnis diberikan.

## 39.5 KPI-Karyawan
- Hirarki dinamis.
- Bawahan Langsung, Kedua, Ketiga, dst.
- CTA DR/KI/K-OPS/M/NA.
- Super Admin melihat Seluruh Karyawan.
- Monitoring tidak sama dengan approval.

## 39.6 MPA
- Penilai ditetapkan di halaman MPA.
- Satu penilai per periode.
- Bisa disiapkan beberapa periode ke depan.
- Deadline assignment akhir bulan.
- Penilai eligible harus aktif dan memiliki akun aktif.
- Jika tidak ada penilai → blocked → HRD takeover.
- Penilai hanya aktif tanggal 1–5.
- Penilai tidak menilai diri sendiri/Dirut/Direktur.
- Penilai boleh menilai level lebih tinggi.
- Bukti MPA text only.
- Penilai isi Performance + Coaching.
- Deadline lewat → akses normal ditutup.
- HRD menilai si penilai.
- HRD melengkapi penilaian yang tertinggal.

## 39.7 Monthly
- HRD isi Absensi manual.
- HRD isi Reward/Punishment.
- Tidak boleh publish sebagian.
- Publish satu periode sekaligus.
- TTD HRD otomatis.
- TTD karyawan + Atasan 1 + Atasan 2 bila tersedia.
- Urutan TTD bebas.
- Atasan 2 tidak tersedia → N/A.
- Late publish memicu catch-up auto-sign.

## 39.8 Finalisasi
- Auto-sign tanggal 9 pukul 23:59.
- Auto-sign tidak menggunakan foto tanda tangan pribadi.
- Nilai Akhir normal ditandatangani tanggal 10.
- Late Finalization diperbolehkan setelah prerequisite lengkap.
- Nilai >100 tetap disimpan.
- Nilai final 2 desimal.
- Koreksi KPI final hanya melalui Koreksi Administratif.

---

# 40. Status Implementasi

| Area | Status |
|---|---|
| Fondasi Karyawan | Sudah |
| Jabatan/Departemen/Penempatan | Sudah |
| Atasan Langsung | Sudah |
| Foto Tanda Tangan | Sudah |
| Role Mapping | Sudah |
| Menu KPI | Belum |
| Daily | Belum |
| Kinerja Individu | Belum |
| Kinerja OPS | Belum |
| KPI-Karyawan | Belum |
| MPA | Belum |
| Monthly | Belum |
| Approval/TTD | Belum |
| Auto Submit | Belum |
| Auto Sign | Belum |
| Nilai Akhir | Belum |
| Scheduler | Belum |
| CRUD Parameter KPI | Belum |
| Koreksi Administratif | Belum |

---

# 41. Outstanding Business Rule

Satu business rule yang belum boleh diasumsikan implementor:

## Fungsi Matematis `Tanda (+/-)` pada Kinerja OPS

- field tetap disimpan;
- HRD tetap dapat mengisi;
- belum memengaruhi formula K-OPS;
- engine scoring tidak boleh menggunakan `Tanda (+/-)` sampai rule bisnis final diberikan perusahaan.

---

# 42. Deferred Scope

Fitur berikut ditunda:

1. Export KPI
2. Cetak/PDF KPI
3. Web Push / notifikasi HP

Deferred scope tidak menghalangi implementasi workflow utama.

---

# 43. Prinsip Implementasi Wajib

1. **Period-First**  
   Seluruh KPI bulanan menggunakan bulan performa.

2. **Shared Record**  
   Aktor berbeda melanjutkan record yang sama.

3. **Hierarchy-Aware**  
   Monitoring dan approval mengikuti snapshot struktur organisasi.

4. **Full Super Admin Capability**  
   Seluruh Super Admin memiliki full capability.

5. **HRD Operational Ownership**  
   Direktur/HRD adalah pelaksana operasional normal administrasi KPI.

6. **Role ≠ KPI Obligation**  
   Manajer tetap wajib KPI; Dirut/Direktur tidak.

7. **Hard Deadline**  
   Deadline harus dijalankan sistem.

8. **Traceable Signature**  
   Manual sign dan auto-sign dibedakan secara eksplisit.

9. **Historical Snapshot**  
   Struktur dan parameter KPI di-snapshot per periode.

10. **No Partial Monthly Publish**  
    Monthly dipublish satu periode sekaligus.

11. **Precision Consistency**  
    Intermediate ≥4 desimal; final 2 desimal.

12. **Administrative Correction Only**  
    KPI final hanya dikoreksi melalui mekanisme audit.

13. **No Silent Assumptions**  
    Implementor tidak boleh mengisi rule yang belum ditetapkan.

---

# 44. Alur End-to-End

```text
SELAMA BULAN BERJALAN
Karyawan bekerja/hadir
→ Isi Daily
→ Atasan Langsung TTD

3× Tidak Mengisi dalam periode
→ Perlu Tindak Lanjut SP1
→ HRD menentukan tindak lanjut

AKHIR BULAN
Bentuk peserta KPI
→ Snapshot struktur
→ Snapshot parameter
→ Tetapkan penilai

Jika tidak ada penilai
→ MPA BLOCKED
→ HRD takeover

TANGGAL 1–2
Karyawan isi Kinerja Individu
Karyawan isi Kinerja OPS

Jika kosong saat deadline
→ auto-submitted
→ NULL + not_filled
→ score 0

TANGGAL 1–5
Penilai isi MPA seluruh peserta
kecuali diri sendiri/Dirut/Direktur
→ HRD menilai si penilai
→ HRD takeover bila diperlukan

TANGGAL 6–8
HRD isi Absensi + Reward/Punishment
→ seluruh Monthly harus selesai
→ publish satu periode
→ TTD HRD otomatis

Jika belum selesai tanggal 8
→ HRD_INCOMPLETE
→ late administrative completion

SAMPAI TANGGAL 9
TTD KI
TTD K-OPS
TTD Monthly

9 PUKUL 23:59
→ Auto-Sign yang masih pending

Jika publish terlambat
→ Catch-Up Auto-Sign

SISTEM HITUNG NILAI AKHIR

TANGGAL 10
Atasan Langsung TTD Nilai Akhir

Jika prerequisite terlambat
→ Late Finalization

KPI FINAL

Jika perlu perubahan histori
→ Koreksi Administratif
→ Audit + Revision + Recalculation
```

---

**END OF PRD**
