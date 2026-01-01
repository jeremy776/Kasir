<?php
require_once 'helper/connection.php';

if (isset($_GET['q']) && $_GET['q'] === 'hapus') {
    header('Content-Type: application/json');

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON'
        ]);
        exit;
    }

    $idproduk = (int)($data['idproduk'] ?? 0);
    if ($idproduk <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID produk tidak valid'
        ]);
        exit;
    }

    $delete = mysqli_query($conn, "DELETE FROM produk WHERE idproduk = '$idproduk'");
    if (!$delete) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Produk berhasil dihapus'
    ]);
    exit;
}

if (isset($_GET['q']) && $_GET['q'] === 'tambah') {
    header('Content-Type: application/json');

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON'
        ]);
        exit;
    }

    $kode_produk = mysqli_real_escape_string($conn, $data['kode_produk'] ?? '');
    $nama_produk = mysqli_real_escape_string($conn, $data['nama_produk'] ?? '');
    $idkategori = (int)($data['idkategori'] ?? 0);
    $stock = (int)($data['stock'] ?? 0);
    $harga_modal = (int)($data['harga_modal'] ?? 0);
    $harga_jual = (int)($data['harga_jual'] ?? 0);

    if (empty($kode_produk) || empty($nama_produk) || $idkategori <= 0 || $harga_jual <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak lengkap'
        ]);
        exit;
    }

    $insert = mysqli_query($conn, "INSERT INTO produk (kode_produk, nama_produk, idkategori, stock, harga_modal, harga_jual) 
        VALUES ('$kode_produk', '$nama_produk', '$idkategori', '$stock', '$harga_modal', '$harga_jual')");
    
    if (!$insert) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Produk berhasil ditambahkan'
    ]);
    exit;
}

if (isset($_GET['q']) && $_GET['q'] === 'edit') {
    header('Content-Type: application/json');

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON'
        ]);
        exit;
    }

    $idproduk = (int)($data['idproduk'] ?? 0);
    $kode_produk = mysqli_real_escape_string($conn, $data['kode_produk'] ?? '');
    $nama_produk = mysqli_real_escape_string($conn, $data['nama_produk'] ?? '');
    $idkategori = (int)($data['idkategori'] ?? 0);
    $stock = (int)($data['stock'] ?? 0);
    $harga_modal = (int)($data['harga_modal'] ?? 0);
    $harga_jual = (int)($data['harga_jual'] ?? 0);

    if ($idproduk <= 0 || empty($nama_produk) || $idkategori <= 0 || $harga_jual <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak lengkap'
        ]);
        exit;
    }

    $update = mysqli_query($conn, "UPDATE produk SET 
        kode_produk = '$kode_produk',
        nama_produk = '$nama_produk', 
        idkategori = '$idkategori', 
        stock = '$stock', 
        harga_modal = '$harga_modal', 
        harga_jual = '$harga_jual' 
        WHERE idproduk = '$idproduk'");
    
    if (!$update) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Produk berhasil diupdate'
    ]);
    exit;
}

$title = "Data Produk - KasirApp";
$content = 'pages/produk.php';
include 'template/layout.php';