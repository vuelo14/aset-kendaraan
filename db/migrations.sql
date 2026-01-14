-- db/migrations.sql
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS vehicles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  plat VARCHAR(20) NOT NULL,
  merk VARCHAR(100) NOT NULL,
  tipe VARCHAR(100) NULL,
  tahun INT NOT NULL,
  jenis ENUM('roda2','roda4') NOT NULL,
  status_penggunaan ENUM('jabatan','operasional') NOT NULL,
  status_kendaraan ENUM('aktif','non-aktif','rusak','perbaikan') NOT NULL DEFAULT 'aktif',
  pajak_status ENUM('sudah','belum') NULL,
  foto_path VARCHAR(255) NULL,
  current_responsible VARCHAR(150) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS usage_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id INT NOT NULL,
  pemakai VARCHAR(150) NOT NULL,
  jabatan VARCHAR(150) NULL,
  start_date DATE NOT NULL,
  end_date DATE NULL,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS maintenance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id INT NOT NULL,
  date DATE NOT NULL,
  jenis VARCHAR(120) NOT NULL,
  biaya DECIMAL(12,2) NOT NULL,
  bengkel VARCHAR(150) NULL,
  notes VARCHAR(255) NULL,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS maintenance_budget (
  id INT AUTO_INCREMENT PRIMARY KEY,
  year INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS tax_payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id INT NOT NULL,
  date DATE NOT NULL,
  jenis ENUM('tahunan','5_tahunan') NOT NULL,
  biaya DECIMAL(12,2) NOT NULL,
  status ENUM('sudah','belum') NOT NULL,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tax_budget (
  id INT AUTO_INCREMENT PRIMARY KEY,
  year INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS maintenance_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id INT NOT NULL,
  description VARCHAR(150) NOT NULL,
  interval_days INT NOT NULL,
  next_date DATE NOT NULL,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tax_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id INT NOT NULL,
  description VARCHAR(150) NOT NULL,
  interval_days INT NOT NULL,
  next_date DATE NOT NULL,
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS audit_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(50) NOT NULL,
  table_name VARCHAR(50) NOT NULL,
  record_id INT NULL,
  changes JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, username, password, role)
VALUES ('Administrator', 'admin', '$2y$10$WQpY1qbw4GzG0H2GQ5kqQe9YlVv1Vh8mFh6gTtLwzEo8OeYqTnSgW', 'admin');
-- Password: admin123
