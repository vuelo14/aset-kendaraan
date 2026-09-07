# Changelog

Semua perubahan penting pada proyek **Manajemen Aset Kendaraan Disnaker Indramayu** dicatat dalam dokumen ini.

Format berkas ini mengacu pada [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan menganut prinsip [Semantic Versioning](https://semver.org/lang/id/).

---

## [1.2.0] - 2026-09-07

### Added
- **Penyetelan Anggaran Presisi per Unit Kendaraan (Precision Budget)**:
  - Penambahan kolom `budget_limit DECIMAL(15,2) NULL` pada tabel `vehicles` untuk menyimpan pagu anggaran individual per armada secara independen.
  - Modal interaktif *"Atur Pagu Kendaraan"* di menu Anggaran (`/budget`) dengan tombol pintas nominal cepat (`1 Jt`, `2.5 Jt`, `5 Jt`, `10 Jt`, `15 Jt`, `25 Jt`), input kustom dengan preview format mata uang, serta rute `POST /budget/vehicle/update`.
  - Tab filter kategori interaktif pada tabel monitoring pagu unit (`Semua`, `Roda 4 Jabatan`, `Roda 4 Operasional`, `Roda 2`).
  - Indikator status anggaran dengan badge modern:
    - `🎯 Presisi` untuk kendaraan dengan pagu khusus individual.
    - `Standar Kategori` untuk kendaraan yang mengikuti pagu bawaan kategori (mis. Roda 2 = Rp 1 Jt).
    - `⚠️ Belum Diatur` untuk kendaraan yang belum memiliki batas anggaran unit (sisa anggaran ditampilkan tanda strip `-` agar tidak menghasilkan minus semu).
  - Kolom pilihan *Kategori Anggaran* dan input *Batas Pagu Khusus (Rp)* pada form pembuatan dan pengeditan kendaraan (`/vehicles/create` dan `/vehicles/edit`).
- **Pengelolaan Harga & Penguncian Status Realisasi Pemeliharaan**:
  - Modal dan tombol interaktif *Edit Harga* pada halaman detail pemeliharaan (`/maintenance/details`) untuk memperbarui harga satuan komponen/jasa secara fleksibel saat terjadi perubahan harga pasar.
  - Logika penguncian otomatis catatan pemeliharaan berstatus **Realisasi**: rincian komponen/jasa tidak dapat ditambah, diubah harganya, atau dihapus saat status terkunci.
  - Fitur *Buka Kunci Realisasi (Unlock)* khusus hak akses Administrator dengan konfirmasi keamanan untuk membuka kembali akses modifikasi rincian.
- **Rekomendasi Cerdas Google Gemini AI & Multi-Model Fallback**:
  - Integrasi layanan Google Gemini AI (`GeminiAI`) pada rincian pemeliharaan untuk menganalisis keluhan, riwayat servis armada, katalog komponen, dan ketersediaan sisa anggaran unit secara otomatis.
  - Mekanisme **Multi-Model Fallback** otomatis: apabila model utama mengalami *high demand* atau kendala kuota, sistem secara mulus beralih ke model cadangan (misal: `gemini-3.1-flash-lite`, `gemini-3.5-flash-lite`, `gemini-2.0-flash`).
  - Prompt AI cerdas yang membaca tipe anggaran (*Presisi* vs *Belum Ditentukan*) untuk memastikan estimasi biaya tetap berada dalam batas pagu yang tersedia.
- **Identitas Versi Aplikasi**:
  - Definisi konstanta `APP_VERSION` pada konfigurasi dan environment `.env`.
  - Penambahan penanda badge versi aplikasi (`v1.2.0`) pada sidebar dan navbar.

### Changed
- `Budget::getUnitBudgetMonitoring()` kini memantau **seluruh unit kendaraan (28 unit armada)**, tidak lagi mengecualikan kendaraan Roda 4.
- `Budget::getVehicleBudget()` menerapkan hirarki pagu presisi: memprioritaskan `vehicles.budget_limit`, lalu `b.max_unit_budget`, dan tidak lagi menarik pagu total kategori (`b.max_total_budget`) secara keliru ke unit perorangan.
- Tampilan kartu monitoring anggaran pada halaman detail servis disesuaikan dengan badge status presisi, persentase serapan, dan panduan pengaturan anggaran.

### Fixed
- Memperbaiki kendala pada kendaraan Roda 4 Jabatan dan Roda 4 Operasional yang sebelumnya tidak muncul pada monitoring unit karena view database membatasi `WHERE b.max_unit_budget IS NOT NULL`.
- Menormalkan data relasi kategori anggaran kendaraan (*orphan category*) pada armada dinas.
- Memperbaiki perhitungan sisa anggaran agar presisi dan tidak terjadi distorsi perhitungan serapan biaya.

---

## [1.1.0] - 2026-09-07

### Added
- **Konfigurasi Lingkungan (.env) & Development Server**:
  - Pembacaan konfigurasi database dan parameter aplikasi melalui berkas `.env` menggunakan library `vlucas/phpdotenv` serta fallback parser bawaan.
  - Perintah instan `composer serve` untuk menjalankan server pengembangan lokal pada `http://localhost:8080` (`php -S localhost:8080 -t public public/index.php`).
  - Berkas template `.env.example` untuk memudahkan standarisasi deployment.
- **Detail Riwayat Servis Kendaraan**:
  - Tampilan *expand / collapse* rincian komponen suku cadang dan jasa servis pada riwayat pemeliharaan di halaman detail kendaraan (`/vehicles/show`).
  - Halaman dan formulir pengeditan riwayat pemeliharaan (`/maintenance/edit`).

### Changed
- Refaktor koneksi database pada `app/core/Database.php` dan `app/config/config.php` agar sepenuhnya terpusat membaca variabel lingkungan `.env`.
- Pembaruan berkas dokumentasi `README.md` dengan panduan instalasi `.env` dan instruksi `composer serve`.

---

## [1.0.0] - 2026-09-06

### Added
- **Rilis Perdana Sistem Manajemen Aset Kendaraan**:
  - Manajemen data master kendaraan operasional dan jabatan (Roda 2 dan Roda 4).
  - Manajemen riwayat mutasi plat nomor dinas kendaraan.
  - Manajemen transaksi pemeliharaan / servis berkala armada dan rincian biaya.
  - Manajemen katalog master suku cadang (komponen) dan jasa perbengkelan.
  - Pemantauan alokasi dan serapan anggaran pemeliharaan aset per kategori.
  - Pengingat dan pemantauan jadwal jatuh tempo pajak tahunan serta 5 tahunan STNK.
  - Manajemen pengguna dengan otentikasi berbasis peran (*Administrator* dan *Operator*).
  - Fitur Import dan Export laporan aset ke format Microsoft Excel (.xlsx) dan dokumen PDF (.pdf).
  - Audit trail dan pencatatan log riwayat aktivitas sistem.
  - Fitur pencadangan (*backup*) dan pemulihan (*restore*) database MySQL.
  - Antarmuka responsif berbasis Bootstrap 5 dengan dukungan *Dark Mode* dan navigasi *collapsible sidebar*.
