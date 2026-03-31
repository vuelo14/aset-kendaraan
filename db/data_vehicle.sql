-- Adminer 4.8.1 MySQL 12.2.2-MariaDB-ubu2404 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

INSERT INTO `vehicles` (`id`, `plat`, `merk`, `tipe`, `tahun`, `jenis`, `status_penggunaan`, `status_kendaraan`, `pajak_status`, `foto_path`, `kondisi`, `current_responsible`, `created_at`) VALUES
(1,	'E 1394 P',	'Toyota',	'New Avanza Veloz',	2015,	'roda4',	'jabatan',	'aktif',	NULL,	NULL,	'B',	'',	'2026-01-14 06:03:03'),
(2,	'E 633 P',	'Toyota',	'Rush',	2011,	'roda4',	'jabatan',	'aktif',	NULL,	NULL,	'B',	'',	'2026-01-14 07:47:34'),
(3,	'E 1732 P',	'Toyota',	'Kijang Innova',	2009,	'roda4',	'operasional',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-14 09:37:55'),
(4,	'E 4449 P',	'Honda',	'Supra Fit X',	2008,	'roda2',	'operasional',	'aktif',	NULL,	NULL,	'RR',	NULL,	'2026-01-21 08:00:54'),
(5,	'E 3176 R',	'Yamaha',	'Lexi',	2019,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:01:36'),
(6,	'E 6845 P',	'Honda',	'Vario Tekno',	2014,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:02:21'),
(7,	'E 3805 R',	'Yamaha',	'Lexi',	2021,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:03:03'),
(8,	'E 3277 R',	'Yamaha',	'Lexi',	2019,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:04:01'),
(9,	'E 3414 R',	'Yamaha',	'Lexi',	2019,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:05:02'),
(10,	'E 4845 P',	'Honda',	'Revo',	2009,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:05:30'),
(11,	'E 4448 P',	'Honda',	'Supra Fit X',	2008,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:05:51'),
(12,	'E 2330 P',	'Yamaha',	'Mio Soul',	2016,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:06:19'),
(13,	'E 4450 P',	'Honda',	'Supra Fit X',	2008,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'RR',	NULL,	'2026-01-21 08:06:42'),
(14,	'E 3205 P',	'Yamaha',	'Vega',	1999,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'RR',	NULL,	'2026-01-21 08:07:07'),
(15,	'E 2880 P',	'Honda',	'Vario Tekno',	2015,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:07:31'),
(16,	'E 4446 P',	'Honda',	'Supra Fit X',	2008,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'RR',	NULL,	'2026-01-21 08:08:26'),
(17,	'E 3819 R',	'Yamaha',	'Lexi',	2021,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:09:07'),
(18,	'E 3181 R',	'Yamaha',	'Lexi',	2019,	'roda2',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:09:40'),
(19,	'E 23 P',	'Toyota',	'Kijang Innova',	2019,	'roda4',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:10:38'),
(20,	'E 1831 P',	'Daihatsu',	'Xenia',	2012,	'roda4',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:11:31'),
(21,	'E 1295 P',	'Toyota',	'New Avanza Veloz',	2014,	'roda4',	'jabatan',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:12:04'),
(22,	'E 1740 P',	'Toyota',	'Kijang Innova',	2009,	'roda4',	'operasional',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:12:49'),
(23,	'E 647 P',	'Isuzu',	'Panther',	2005,	'roda4',	'operasional',	'aktif',	NULL,	NULL,	'RR',	NULL,	'2026-01-21 08:14:10'),
(24,	'E 347 P',	'Toyota',	'Kijang Standar',	1999,	'roda4',	'operasional',	'aktif',	NULL,	NULL,	'RR',	NULL,	'2026-01-21 08:14:48'),
(25,	'E 1154 P',	'Toyota',	'Veloz',	2023,	'roda4',	'operasional',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:15:21'),
(26,	'D 7384 C',	'Mitsubishi',	'Bus',	2022,	'roda4',	'operasional',	'aktif',	NULL,	NULL,	'B',	NULL,	'2026-01-21 08:16:00');

-- 2026-02-23 05:53:30