-- ============================================
-- KasirApp Database Initialization Script
-- Version: 1.0
-- Description: Script untuk membuat database dan tabel aplikasi kasir
-- ============================================

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `db_kasir` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE `db_kasir`;

-- ============================================
-- Tabel: login
-- Deskripsi: Menyimpan data user/admin dan info toko
-- ============================================
CREATE TABLE IF NOT EXISTS `login` (
  `id_login` int(11) NOT NULL AUTO_INCREMENT,
  `nama_toko` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  PRIMARY KEY (`id_login`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: kategori
-- Deskripsi: Menyimpan kategori produk
-- ============================================
CREATE TABLE IF NOT EXISTS `kategori` (
  `idkategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idkategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: produk
-- Deskripsi: Menyimpan data produk
-- ============================================
CREATE TABLE IF NOT EXISTS `produk` (
  `idproduk` int(11) NOT NULL AUTO_INCREMENT,
  `idkategori` int(11) NOT NULL,
  `kode_produk` varchar(100) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga_modal` int(11) NOT NULL DEFAULT 0,
  `harga_jual` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `tgl_input` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idproduk`),
  UNIQUE KEY `kode_produk` (`kode_produk`),
  KEY `fk_produk_kategori` (`idkategori`),
  CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`idkategori`) REFERENCES `kategori` (`idkategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: pelanggan
-- Deskripsi: Menyimpan data pelanggan
-- ============================================
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `idpelanggan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(30) NOT NULL,
  `telepon_pelanggan` varchar(15) NOT NULL,
  `alamat_pelanggan` text NOT NULL,
  PRIMARY KEY (`idpelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: keranjang
-- Deskripsi: Menyimpan item keranjang belanja sementara
-- ============================================
CREATE TABLE IF NOT EXISTS `keranjang` (
  `idcart` int(11) NOT NULL AUTO_INCREMENT,
  `no_nota` varchar(100) NOT NULL,
  `idproduk` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idcart`),
  KEY `fk_keranjang_produk` (`idproduk`),
  CONSTRAINT `fk_keranjang_produk` FOREIGN KEY (`idproduk`) REFERENCES `produk` (`idproduk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: laporan
-- Deskripsi: Menyimpan data transaksi/penjualan
-- ============================================
CREATE TABLE IF NOT EXISTS `laporan` (
  `idlaporan` int(11) NOT NULL AUTO_INCREMENT,
  `no_nota` varchar(50) NOT NULL,
  `idpelanggan` int(11) NOT NULL DEFAULT 0,
  `catatan` text NOT NULL,
  `totalbeli` int(11) NOT NULL DEFAULT 0,
  `pembayaran` int(11) NOT NULL DEFAULT 0,
  `kembalian` int(11) NOT NULL DEFAULT 0,
  `tgl_sub` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idlaporan`),
  UNIQUE KEY `no_nota` (`no_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Tabel: tb_nota
-- Deskripsi: Menyimpan detail item per transaksi
-- ============================================
CREATE TABLE IF NOT EXISTS `tb_nota` (
  `idnota` int(11) NOT NULL AUTO_INCREMENT,
  `no_nota` varchar(100) NOT NULL,
  `idproduk` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idnota`),
  KEY `fk_nota_produk` (`idproduk`),
  KEY `idx_no_nota` (`no_nota`),
  CONSTRAINT `fk_nota_produk` FOREIGN KEY (`idproduk`) REFERENCES `produk` (`idproduk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Data Awal: User Admin Default
-- Password: admin (di-hash dengan password_hash)
-- ============================================
INSERT INTO `login` (`nama_toko`, `username`, `password`, `alamat`, `telepon`) VALUES
('Toko Saya', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jl. Contoh Alamat No. 123', '081234567890')
ON DUPLICATE KEY UPDATE `username` = `username`;

-- ============================================
-- Data Awal: Kategori Contoh
-- ============================================
INSERT INTO `kategori` (`nama_kategori`) VALUES
('Makanan'),
('Minuman'),
('Snack'),
('Elektronik'),
('Lainnya')
ON DUPLICATE KEY UPDATE `nama_kategori` = VALUES(`nama_kategori`);

-- ============================================
-- Selesai
-- ============================================
-- Untuk menjalankan script ini:
-- 1. Buka terminal/command prompt
-- 2. Jalankan: mysql -u root -p < db/init.sql
-- 
-- Atau melalui phpMyAdmin:
-- 1. Buka phpMyAdmin
-- 2. Pilih tab "Import"
-- 3. Pilih file init.sql
-- 4. Klik "Go"
-- ============================================
