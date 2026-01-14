# Manajemen Aset Kendaraan Disnaker Indramayu (PHP + MySQL + Bootstrap)

## Cara Cepat Menjalankan
1. Buat database MySQL `kendaraan_disnaker`.
2. Import `db/migrations.sql`.
3. Konfigurasi kredensial di `app/config/config.php`.
4. Pastikan folder `assets/uploads/vehicles` writable.
5. Set document root ke folder `public/`.
6. Login awal: `admin` / `admin123`.

## Catatan
- Untuk export PDF, pasang `dompdf` via Composer.
- Untuk export Excel, bisa tambah `phpoffice/phpspreadsheet` (opsional).
