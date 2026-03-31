-- Tabel Konfigurasi Pagu Anggaran per Tipe Kendaraan
CREATE TABLE IF NOT EXISTS `budget_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL, -- Contoh: "Roda 2", "Roda 4 Jabatan", "Roda 4 Operasional", "E 23 P"
  `max_unit_budget` decimal(15,2) DEFAULT NULL, -- Limit maksimal per unit (cth: 1 juta Roda 2)
  `max_total_budget` decimal(15,2) DEFAULT NULL, -- Limit maksimal total plafon (cth: 8 juta)
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name_unique` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Pagu Anggaran (Sesuai Aturan)
-- Gunakan INSERT IGNORE agar tidak error jika dieksekusi berkali-kali
INSERT IGNORE INTO `budget_categories` (`category_name`, `max_unit_budget`, `max_total_budget`) VALUES
('Roda 2', 1000000.00, 8000000.00),
('Kijang Innova E 23 P', NULL, 25000000.00),
('Roda 4 Jabatan', NULL, 25000000.00),
('Roda 4 Operasional', NULL, 25000000.00);

-- Kolom tambahan untuk vehicles (menghubungkan kendaraan dgn kategori anggarannya)
-- PERHATIAN: Pastikan Anda menjalankan ALTER TABLE ini jika eksekusi terpisah.
-- Hilangkan tanda komentar ('--') pada dua baris di bawah pada saat PERTAMA KALI eksekusi.
-- ALTER TABLE `vehicles` ADD COLUMN `budget_category_id` INT DEFAULT NULL;
-- ALTER TABLE `vehicles` ADD CONSTRAINT `fk_vehicle_budget` FOREIGN KEY (`budget_category_id`) REFERENCES `budget_categories`(`id`) ON DELETE SET NULL;


-- View Pembantu: Monitoring Realisasi vs Pagu Anggaran per Kategori
CREATE OR REPLACE VIEW `vw_budget_monitoring` AS
SELECT 
    b.id,
    b.category_name,
    b.max_unit_budget,
    b.max_total_budget,
    COUNT(DISTINCT v.id) as unit_count,
    COALESCE(SUM(m.biaya), 0) as total_realisasi,
    (b.max_total_budget - COALESCE(SUM(m.biaya), 0)) as sisa_anggaran
FROM 
    budget_categories b
LEFT JOIN vehicles v ON v.budget_category_id = b.id
LEFT JOIN maintenance m ON m.vehicle_id = v.id AND YEAR(m.date) = YEAR(CURRENT_DATE)
GROUP BY 
    b.id, b.category_name, b.max_unit_budget, b.max_total_budget;
    
-- View Pembantu: Monitoring Limit Per Unit (Khusus Roda 2 yg punya limit per unit)
CREATE OR REPLACE VIEW `vw_unit_budget_monitoring` AS
SELECT 
    v.id as vehicle_id,
    v.plat,
    v.merk,
    b.category_name,
    b.max_unit_budget,
    COALESCE(SUM(m.biaya), 0) as realisasi_unit,
    (b.max_unit_budget - COALESCE(SUM(m.biaya), 0)) as sisa_budget_unit
FROM 
    vehicles v
JOIN budget_categories b ON v.budget_category_id = b.id
LEFT JOIN maintenance m ON m.vehicle_id = v.id AND YEAR(m.date) = YEAR(CURRENT_DATE)
WHERE 
    b.max_unit_budget IS NOT NULL
GROUP BY 
    v.id, v.plat, v.merk, b.category_name, b.max_unit_budget;
