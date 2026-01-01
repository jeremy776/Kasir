<?php
require_once 'helper/connection.php';

if(isset($_GET['q']) && $_GET['q'] === 'hapus') {
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

    $idkategori = (int)($data['idkategori'] ?? 0);
    if ($idkategori <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID kategori tidak valid'
        ]);
        exit;
    }

    // hapus semua produk dengan kategori ini
    $delete_produk = mysqli_query($conn, "DELETE FROM produk WHERE idkategori = '$idkategori'");
    if (!$delete_produk) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    $delete = mysqli_query($conn, "DELETE FROM kategori WHERE idkategori = '$idkategori'");
    if (!$delete) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Kategori berhasil dihapus'
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

    $nama_kategori = $data['nama_kategori'] ?? '';
    if (empty($nama_kategori)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Nama kategori tidak boleh kosong'
        ]);
        exit;
    }

    // cek apakah kategori sudah ada
    $check = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$nama_kategori'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Kategori sudah ada'
        ]);
        exit;
    }

    $insert = mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    if (!$insert) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Kategori berhasil ditambahkan'
    ]);
    exit;
}


$title = "Data Kategori - KasirApp";
$content = 'pages/kategori.php';
include 'template/layout.php';
