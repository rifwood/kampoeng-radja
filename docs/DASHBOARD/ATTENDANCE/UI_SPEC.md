# UI Spec — Data Absensi

Status: **behavior aktif; visual mengikuti screenshot approved jika tersedia di repository**

## Page Structure

- Sidebar Dashboard penuh tinggi viewport.
- Top navbar memuat search, notifikasi, settings, dan identitas user sesuai reference.
- Judul content: `Data Absensi`.
- Card informasi menampilkan tanggal dinamis, status input, badge view, dan action sesuai state.
- Legenda H/I/A berada di atas tabel.
- Tabel menampilkan nomor, nama karyawan, jabatan, kehadiran, dan keterangan.

## State UI

### Create State

- Semua radio H/I/A dan input keterangan enabled.
- Tombol `Simpan Absensi` enabled setelah data valid.

### Saved State

- Radio dan input read-only/disabled.
- Tampilkan `Edit Absensi` hanya jika tanggal adalah hari berjalan.

### Edit State

- Radio dan input kembali aktif.
- Tampilkan `Batal` dan `Simpan Perubahan`.
- `Batal` mengembalikan nilai terakhir dari server.

### Past State

- Seluruh data read-only.
- Tidak ada action mutation.

### Loading / Error

- Disable submit selama request.
- Pertahankan input ketika validation gagal.
- Tampilkan feedback sukses/error melalui mekanisme Inertia existing.

## Kehadiran

- Pilihan H/I/A merupakan satu radio group per karyawan.
- H aktif: biru, teks putih.
- I aktif: kuning, teks gelap.
- A aktif: merah/pink yang konsisten.
- Pilihan tidak aktif: putih, border abu-abu, teks gelap.
- Informasi status tidak boleh hanya mengandalkan warna; label huruf tetap terlihat.

## Karyawan dan Avatar

- Nama dan jabatan berasal dari master data.
- Gunakan foto profil hanya jika field/sumber yang disetujui tersedia.
- Jangan menggunakan foto KTP sebagai avatar secara otomatis.
- Fallback saat ini boleh berupa inisial.

## Search

- Search memfilter daftar yang sedang tampil secara client-side selama dataset masih dimuat penuh.
- Search tidak mengubah permission atau data scope.

## Responsive

- Sidebar dapat berubah menjadi drawer.
- Tabel boleh horizontal scroll.
- Action tidak boleh keluar viewport.
- Semua input tetap dapat digunakan pada touch viewport.
- Jangan klaim pixel-accurate pada viewport tanpa reference.

## Visual Verification

Screenshot yang pernah diberikan melalui percakapan belum disimpan sebagai file repository. Sampai reference dipersist dan dibandingkan, status visual adalah `NEEDS VERIFICATION`.
