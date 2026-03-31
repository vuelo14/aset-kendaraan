-- 1. Sisipkan Kategori Anggaran pada skema yg baru kita buat ke kendaraan
-- (Asumsi kita telah menjalankan ALTER TABLE `vehicles` ADD COLUMN `budget_category_id` INT DEFAULT NULL;)

-- SET Budget Roda 2 (Motor)
UPDATE vehicles 
SET budget_category_id = (SELECT id FROM budget_categories WHERE category_name='Roda 2')
WHERE jenis = 'roda2';

-- SET Budget Kijang Innova Khusus (E 23 P)
UPDATE vehicles 
SET budget_category_id = (SELECT id FROM budget_categories WHERE category_name='Kijang Innova E 23 P')
WHERE plat = 'E 23 P';

-- SET Budget Roda 4 (Jabatan) selain Innova di atas
UPDATE vehicles 
SET budget_category_id = (SELECT id FROM budget_categories WHERE category_name='Roda 4 Jabatan')
WHERE jenis = 'roda4' AND status_penggunaan = 'jabatan' AND plat != 'E 23 P';

-- SET Budget Roda 4 (Operasional) selain Innova di atas
UPDATE vehicles 
SET budget_category_id = (SELECT id FROM budget_categories WHERE category_name='Roda 4 Operasional')
WHERE jenis = 'roda4' AND status_penggunaan = 'operasional' AND plat != 'E 23 P';

-- -------------------------------------------------------------
-- 2. DUMMY DATA: JADWAL PEMELIHARAAN (MAINTENANCE SCHEDULES)
-- Disusun masuk akal & tidak melebihi batasan plafon tahunan 
-- -------------------------------------------------------------

-- Kategori: RODA 2 (Maks 1 JT / Unit, Total Maks 8 JT)
-- Estimasi rutin: Ganti Oli tiap 2-3 bulan (±72rb), Tune up / Service ringan tiap 4-6 bulan (±54rb)
-- Total unit: 8 unit. Misal revo, vario, mio (setahun tidak lbh dari 400-500rb)
INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Service Berkala & Ganti Oli Mesin', 90, CURRENT_DATE + INTERVAL 14 DAY
FROM vehicles v WHERE v.jenis = 'roda2';

INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Ganti V-belt / Gear Set & Cek Rem', 180, CURRENT_DATE + INTERVAL 60 DAY
FROM vehicles v WHERE v.jenis = 'roda2';


-- Kategori: KIJANG INNOVA KHUSUS (E 23 P) (Maks Total 25 JT)
-- Estimasi: Full Tuneup + Spooring/Balancing + Ganti Semua Filter + Tambahan
INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Service Besar (Tune Up, Ganti Oli Mesin & Transmisi, Aki, Filter-filter)', 180, CURRENT_DATE + INTERVAL 30 DAY
FROM vehicles v WHERE v.plat = 'E 23 P';

INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Service AC Berkala & Cek Kaki-kaki', 365, CURRENT_DATE + INTERVAL 120 DAY
FROM vehicles v WHERE v.plat = 'E 23 P';

INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Pergantian Ban R15/R16 Berkala', 730, CURRENT_DATE + INTERVAL 200 DAY
FROM vehicles v WHERE v.plat = 'E 23 P';


-- Kategori: RODA 4 JABATAN & OPERASIONAL (Maks Total masing-masing 25 JT)
-- Estimasi per unit mobil jabatan (Cth: ada 3 unit = ~8 JT/tahun/unit limit aman)
-- Estimasi rutin: Cuci + Ganti Oli (tiap 3 bln), Tuneup + Filter (tiap 6 bulan)
INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Ganti Oli Mesin & Filter Oli', 90, CURRENT_DATE + INTERVAL 20 DAY
FROM vehicles v WHERE v.jenis = 'roda4' AND v.plat != 'E 23 P';

INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Tune-up Mesin & Spooring/Balancing', 180, CURRENT_DATE + INTERVAL 90 DAY
FROM vehicles v WHERE v.jenis = 'roda4' AND v.plat != 'E 23 P';

INSERT INTO `maintenance_schedule` (`vehicle_id`, `description`, `interval_days`, `next_date`)
SELECT v.id, 'Check dan Ganti Kampas Rem (Kondisional)', 365, CURRENT_DATE + INTERVAL 150 DAY
FROM vehicles v WHERE v.jenis = 'roda4' AND v.plat != 'E 23 P';
