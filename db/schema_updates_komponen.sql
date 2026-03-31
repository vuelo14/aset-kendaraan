-- Tabel Komponen (Barang & Jasa)
CREATE TABLE `komponen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jenis` enum('komponen','jasa') NOT NULL DEFAULT 'komponen',
  `satuan` varchar(50) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Maintenance Details
CREATE TABLE `maintenance_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maintenance_id` int(11) NOT NULL,
  `komponen_id` int(11) NOT NULL,
  `jumlah` decimal(8,2) NOT NULL DEFAULT '1.00',
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_maintenance_details_maintenance` (`maintenance_id`),
  KEY `fk_maintenance_details_komponen` (`komponen_id`),
  CONSTRAINT `fk_maintenance_details_komponen` FOREIGN KEY (`komponen_id`) REFERENCES `komponen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_maintenance_details_maintenance` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
