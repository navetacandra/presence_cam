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
  CONSTRAINT fk_role FOREIGN KEY (role_id) REFRENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE absensi (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  karyawan_id INT NOT NULL,
  tanggal DATE NOT NULL DEFAULT CURRENT_DATE,
  waktu_masuk DATETIME NOT NULL DEFAULT CURRENT_DATETIME,
  waktu_pulang DATETIME,
  selfie_masuk_path VARCHAR(255) NOT NULL,
  selfie_pulang_path VARCHAR(255),
  latitude_masuk DECIMAL(10, 8) NOT NULL,
  longitude_masuk DECIMAL(11, 8) NOT NULL,
  latitude_pulang DECIMAL(10, 8),
  longitude_pulang DECIMAL(11, 8),
  CONSTRAINT fk_karyawan FOREIGN key (karyawan_id) REFRENCES karyawan(id) ON DELETE CASCADE
);

-- roles Data
INSERT INTO roles (nama) VALUES ('admin'), ('karyawan');

-- Admin Data
INSERT INTO karyawan (nama, email, password, role_id) VALUES ("Admin", "admin@admin.local", "$2y$10$8Wp5DmD4nGgL1HHyhX1Nx.Lug31irYcq0/p44mIMeMVdd8OgSotjS", 1);
