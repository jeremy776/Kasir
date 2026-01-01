<?php
require_once 'helper/connection.php';

if (isset($_GET['q']) && $_GET['q'] === 'hapus') {
    header('Content-Type: application/json');

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    
    if (!is_array($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }

    $no_nota = mysqli_real_escape_string($conn, $data['no_nota'] ?? '');
    
    if (empty($no_nota)) {
        echo json_encode(['status' => 'error', 'message' => 'No nota tidak valid']);
        exit;
    }

    $hapus_laporan = mysqli_query($conn, "DELETE FROM laporan WHERE no_nota='$no_nota'");
    $hapus_nota = mysqli_query($conn, "DELETE FROM tb_nota WHERE no_nota='$no_nota'");

    if ($hapus_laporan) {
        echo json_encode(['status' => 'success', 'message' => 'Laporan berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

// Handler untuk Detail Nota
if (isset($_GET['q']) && $_GET['q'] === 'detail') {
    header('Content-Type: application/json');
    
    $no_nota = mysqli_real_escape_string($conn, $_GET['nota'] ?? '');
    
    if (empty($no_nota)) {
        echo json_encode(['status' => 'error', 'message' => 'No nota tidak valid']);
        exit;
    }
    
    // Ambil data laporan
    $query_laporan = mysqli_query($conn, "SELECT * FROM laporan WHERE no_nota='$no_nota'");
    $laporan = mysqli_fetch_assoc($query_laporan);
    
    if (!$laporan) {
        echo json_encode(['status' => 'error', 'message' => 'Data laporan tidak ditemukan']);
        exit;
    }
    
    // Ambil data item dari tb_nota dengan join ke produk
    $query_items = mysqli_query($conn, "
        SELECT n.*, p.nama_produk, p.kode_produk, p.harga_jual 
        FROM tb_nota n 
        LEFT JOIN produk p ON n.idproduk = p.idproduk 
        WHERE n.no_nota='$no_nota'
    ");
    
    $items = [];
    while ($row = mysqli_fetch_assoc($query_items)) {
        $items[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'laporan' => $laporan,
            'items' => $items
        ]
    ]);
    exit;
}

$title = "Laporan - KasirApp";
$content = 'pages/laporan.php';
include 'template/layout.php';