# Manajemen Aset Kendaraan Disnaker Indramayu (v1.2.0)

Aplikasi sistem informasi pengelolaan aset kendaraan dinas operasional dan jabatan di lingkungan Disnaker Indramayu berbasis PHP, MySQL, Bootstrap 5, dan Google Gemini AI.

## Cara Cepat Menjalankan
1. Pasang dependensi via Composer:
   ```bash
   composer install
   ```
2. Buat database MySQL `kendaraan_disnaker` (atau sesuaikan dengan kebutuhan).
3. Import `db/migrations.sql`.
4. Salin file `.env.example` menjadi `.env` dan sesuaikan kredensial database, URL, serta Gemini API Key:
   ```bash
   cp .env.example .env
   ```
5. Pastikan folder `public/assets/uploads/vehicles` writable.
6. Jalankan development server:
   ```bash
   composer serve
   ```
   Akses melalui browser di [http://localhost:8080](http://localhost:8080).
7. Login awal: `admin` / `admin123`.

## Fitur Utama & Pembaruan
- **Pengaturan Anggaran Presisi per Kendaraan**: Pagu anggaran individual dapat disetel per unit armada dinas langsung dari menu Anggaran atau Form Kendaraan.
- **Rekomendasi Cerdas Gemini AI**: Asisten rekomendasi suku cadang dan estimasi biaya berbasis AI dengan mekanisme multi-model fallback otomatis.
- **Penguncian Status Realisasi**: Menjaga integritas data pemeliharaan yang telah berstatus Realisasi agar tidak dapat diubah sembarangan tanpa otorisasi Admin.
- **Manajemen Riwayat & Komponen**: Visualisasi detail suku cadang/jasa yang dapat di-expand/collapse serta fitur penyesuaian harga manual saat terjadi perubahan harga pasar.
- **Konfigurasi Lingkungan (.env)**: Konfigurasi database, kredensial AI, dan aplikasi terpusat via `.env`.

Riwayat lengkap perubahan dan catatan versi dapat dilihat di berkas [CHANGELOG.md](CHANGELOG.md).

