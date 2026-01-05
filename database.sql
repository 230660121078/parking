CREATE DATABASE db_parkir;
USE db_parkir;

CREATE TABLE admin (
 id_admin INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50),
 password VARCHAR(255)
);

INSERT INTO admin VALUES (NULL,'admin',MD5('admin'));

CREATE TABLE kendaraan (
 id_kendaraan INT AUTO_INCREMENT PRIMARY KEY,
 plat_nomor VARCHAR(20),
 jenis_kendaraan VARCHAR(20),
 waktu_masuk DATETIME,
 waktu_keluar DATETIME,
 biaya INT
);
