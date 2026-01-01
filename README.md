# 🛒 KasirApp - Aplikasi Point of Sale (POS)

Aplikasi kasir berbasis web untuk mengelola transaksi penjualan, stok produk, dan laporan penjualan. Dibuat menggunakan PHP native dengan tampilan modern menggunakan Tailwind CSS.

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.0-06B6D4?style=flat&logo=tailwindcss&logoColor=white)

## ✨ Fitur

- **📋 Transaksi Penjualan** - Input produk dengan pencarian otomatis, keranjang belanja, pembayaran
- **🖨️ Cetak Struk** - Format thermal printer 58mm
- **📦 Manajemen Produk** - CRUD produk dengan kategori, harga modal & jual, stok
- **🏷️ Manajemen Kategori** - Kelola kategori produk
- **📊 Laporan Penjualan** - Statistik transaksi, produk terjual, keuntungan
- **⚙️ Pengaturan** - Edit profil toko, ubah password, zona berbahaya (hapus data)
- **📱 Responsive** - Tampilan mobile-friendly
- **🔐 Autentikasi** - Login dengan password terenkripsi

## 📁 Struktur Folder

```
APP-KASIE/
├── assets/
│   ├── fonts/          # Font Inter (local)
│   ├── images/         # Logo dan gambar
│   └── js/
│       └── tailwind.js # Tailwind CSS CDN/local
├── db/
│   └── init.sql        # Script inisialisasi database
├── helper/
│   ├── auth.php        # Fungsi autentikasi
│   ├── connection.php  # Koneksi database
│   └── utils.php       # Fungsi utility
├── pages/
│   ├── index.php       # Halaman transaksi
│   ├── produk.php      # Halaman produk
│   ├── kategori.php    # Halaman kategori
│   ├── laporan.php     # Halaman laporan
│   └── settings.php    # Halaman pengaturan
├── template/
│   ├── header.php      # HTML head, meta tags
│   └── layout.php      # Layout utama dengan sidebar
├── index.php           # Entry point transaksi
├── login.php           # Halaman login
├── logout.php          # Proses logout
├── produk.php          # Entry point produk
├── kategori.php        # Entry point kategori
├── laporan.php         # Entry point laporan
└── settings.php        # Entry point pengaturan
```

## 🚀 Cara Setup

### Prasyarat

- [XAMPP](https://www.apachefriends.org/) (PHP 7.4+ & MySQL 5.7+)
- Web browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

#### 1. Clone/Download Project

Letakkan folder project di direktori `htdocs` XAMPP:

```
C:\xampp\htdocs\   (Windows)
/opt/lampp/htdocs/ (Linux)
```

#### 2. Jalankan XAMPP

Buka XAMPP Control Panel dan start:
- ✅ Apache
- ✅ MySQL

#### 3. Setup Database

**Opsi A: Menggunakan phpMyAdmin**
1. Buka http://localhost/phpmyadmin
2. Klik tab "Import"
3. Pilih file `db/init.sql`
4. Klik "Go"

**Opsi B: Menggunakan Terminal/CMD**

```bash
# Windows (CMD)
cd C:\xampp\mysql\bin
mysql -u root < C:\xampp\htdocs\db\init.sql

# Atau dengan path lengkap
C:\xampp\mysql\bin\mysql -u root < C:\xampp\htdocs\db\init.sql
```

```bash
# Linux
/opt/lampp/bin/mysql -u root < /opt/lampp/htdocs/db/init.sql
```

#### 4. Konfigurasi Database (Opsional)

Jika MySQL menggunakan password, edit file `helper/connection.php`:

```php
<?php
$conn = mysqli_connect("localhost", "root", "PASSWORD_ANDA", "db_kasir");
```

#### 5. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/
```

### 🔑 Login Default

| Username | Password |
|----------|----------|
| `admin`  | `admin`  |

> ⚠️ **Penting:** Segera ubah password default setelah login pertama melalui menu Pengaturan.

## 🗄️ Struktur Database

Database `db_kasir` terdiri dari 7 tabel:

| Tabel | Deskripsi |
|-------|-----------|
| `login` | Data user/admin dan info toko |
| `kategori` | Kategori produk |
| `produk` | Data produk (kode, nama, harga, stok) |
| `pelanggan` | Data pelanggan |
| `keranjang` | Item keranjang belanja (sementara) |
| `laporan` | Data transaksi penjualan |
| `tb_nota` | Detail item per transaksi |

## 💡 Cara Penggunaan

### Transaksi Penjualan
1. Tekan `Tab` untuk fokus ke input kode produk
2. Ketik kode/nama produk, pilih dari dropdown
3. Produk otomatis masuk ke keranjang
4. Masukkan nominal pembayaran
5. Klik "Simpan" untuk menyimpan transaksi
6. Klik "Cetak" untuk print struk

### Manajemen Produk
1. Buka menu "Data Produk"
2. Klik "Tambah Produk" untuk produk baru
3. Isi form: kode, nama, kategori, harga modal, harga jual, stok
4. Klik simpan

### Lihat Laporan
1. Buka menu "Data Laporan"
2. Lihat statistik: total transaksi, produk terjual, penjualan, keuntungan
3. Klik "Detail" untuk melihat item per transaksi
4. Klik "Cetak" untuk print ulang struk

## 🖨️ Pengaturan Printer

Aplikasi mendukung printer thermal 58mm. Pastikan:
- Printer sudah terinstall di komputer
- Set sebagai default printer untuk hasil optimal

## ⌨️ Shortcut Keyboard

| Tombol | Fungsi |
|--------|--------|
| `Tab` | Fokus ke input kode produk |
| `Escape` | Tutup dropdown pencarian |

## 🔧 Troubleshooting

### Database tidak terkoneksi
- Pastikan MySQL sudah running di XAMPP
- Cek konfigurasi di `helper/connection.php`
- Pastikan database `db_kasir` sudah dibuat

### Halaman blank/error
- Cek Apache sudah running
- Periksa error log di `C:\xampp\apache\logs\error.log`

### Font tidak tampil
- Pastikan folder `assets/fonts/` berisi file font Inter
- Atau aplikasi akan fallback ke font sistem

## 📝 Lisensi

© <?php echo date('Y'); ?> LazyPeople. All rights reserved.

---

<p align="center">
  Made with ❤️ by <strong>LazyPeople</strong>
</p>
