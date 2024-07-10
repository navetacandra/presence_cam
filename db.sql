CREATE TABLE roles (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(50) NOT NULL
);

CREATE TABLE karyawan (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  foto_profil VARCHAR(255) NOT NULL DEFAULT "/upload/profile/default.png",
  CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE absensi (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  karyawan_id INT NOT NULL,
  tanggal DATE NOT NULL DEFAULT CURRENT_DATE,
  waktu_masuk DATETIME NOT NULL DEFAULT NOW(),
  waktu_pulang DATETIME,
  selfie_masuk_path VARCHAR(255) NOT NULL,
  selfie_pulang_path VARCHAR(255),
  latitude_masuk DECIMAL(10, 8) NOT NULL,
  longitude_masuk DECIMAL(11, 8) NOT NULL,
  latitude_pulang DECIMAL(10, 8),
  longitude_pulang DECIMAL(11, 8),
  CONSTRAINT fk_karyawan FOREIGN key (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE
);

-- roles Data
INSERT INTO roles (id, nama) VALUES (1, 'admin'), (2, 'karyawan');

-- Admin Data
INSERT INTO karyawan (id, nama, email, password, role_id) VALUES (1, "Admin", "admin@admin.local", "$2y$10$8Wp5DmD4nGgL1HHyhX1Nx.Lug31irYcq0/p44mIMeMVdd8OgSotjS", 1);

-- Dummy absensi
INSERT INTO absensi (id, karyawan_id, selfie_masuk_path, latitude_masuk, longitude_masuk) VALUES (1, 1, "/upload/absensi/masuk_123.png", -6.123456, 103.123456)
