<?php
require_once 'helper/connection.php';
require_once 'helper/utils.php';

if (isset($_GET['q']) && $_GET['q'] === 'save_transaction') {
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

    $list_keranjang = mysqli_query($conn, "SELECT idproduk, quantity FROM keranjang");
    if (mysqli_num_rows($list_keranjang) === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Keranjang kosong'
        ]);
        exit;
    }

    // masukin semua data keranjang ke tabel 'tb_nota' dengan format no_nota, idproduk, quantity
    $no_nota = $data['no_nota'] ?? '';
    while ($item = mysqli_fetch_assoc($list_keranjang)) {
        $idproduk = (int)$item['idproduk'];
        $qty      = (int)$item['quantity'];

        $insert = mysqli_query($conn, "INSERT INTO tb_nota (no_nota, idproduk, quantity) VALUES ('$no_nota', '$idproduk', '$qty')");
        if (!$insert) {
            echo json_encode([
                'status' => 'error',
                'message' => mysqli_error($conn)
            ]);
            exit;
        }
    }
    // kosongin keranjang
    $delete = mysqli_query($conn, "DELETE FROM keranjang");
    if (!$delete) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    // tambahkan data ke table 'laporan'
    $catatan = $data['catatan'] ?? '';
    $id_pelanggan = (int)($data['id_pelanggan'] ?? 0);
    $total_bayar = (int)($data['total_bayar'] ?? 0);
    $kembalian = (int)($data['kembalian'] ?? 0);
    $totalbeli = (int)($data['totalbeli'] ?? 0);
    $tgl = date('Y-m-d H:i:s');
    $insert_laporan = mysqli_query($conn, "INSERT INTO laporan (no_nota, idpelanggan, catatan, totalbeli, pembayaran, kembalian, tgl_sub) VALUES ('$no_nota', '$id_pelanggan', '$catatan', '$totalbeli', '$total_bayar', '$kembalian', '$tgl')");
    if (!$insert_laporan) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }
    echo json_encode([
        'status' => 'success'
    ]);
    exit;
}

if (isset($_GET['q']) && $_GET['q'] === 'reset_cart') {
    header('Content-Type: application/json');

    // cek stock produk di keranjang dan balikin ke tabel produk
    $cartcheck = mysqli_query($conn, "SELECT idproduk, quantity FROM keranjang");
    while ($cartitem = mysqli_fetch_assoc($cartcheck)) {
        $idproduk = (int)$cartitem['idproduk'];
        $qty      = (int)$cartitem['quantity'];

        $update = mysqli_query($conn, "UPDATE produk SET stock = stock + $qty WHERE idproduk = '$idproduk'");
    }

    $delete = mysqli_query($conn, "DELETE FROM keranjang");

    if (!$delete) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success'
    ]);
    exit;
}

if (isset($_GET['q']) && $_GET['q'] === 'add_to_cart') {

    
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
    $qty      = (int)($data['qty'] ?? 0);
    
    if ($idproduk <= 0 || $qty <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak valid'
        ]);
        exit;
    }

    // cek stock produk
    $stockcheck = mysqli_query($conn, "SELECT stock FROM produk WHERE idproduk = '$idproduk'");
    $stockdata = mysqli_fetch_assoc($stockcheck);
    if (!$stockdata || $stockdata['stock'] < $qty) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Stok tidak mencukupi'
        ]);
        exit;
    }
    
    $insert = mysqli_query($conn, "INSERT INTO keranjang (idproduk,quantity) VALUES ('$idproduk','$qty')");

    if (!$insert) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }

    // update dan kurangin quantity di tabel produk
    $update = mysqli_query($conn, "UPDATE produk SET stock = stock - $qty WHERE idproduk = '$idproduk'");

    echo json_encode([
        'status'   => 'success',
        'idproduk' => $idproduk,
        'qty'      => $qty
    ]);
    exit;
}

// Handler untuk hapus item dari keranjang
if (isset($_GET['q']) && $_GET['q'] === 'delete_cart_item') {
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
    
    $idcart = (int)($data['idcart'] ?? 0);
    
    if ($idcart <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID cart tidak valid'
        ]);
        exit;
    }
    
    // Ambil data item sebelum dihapus untuk kembalikan stock
    $cartItem = mysqli_query($conn, "SELECT idproduk, quantity FROM keranjang WHERE idcart = '$idcart'");
    $item = mysqli_fetch_assoc($cartItem);
    
    if (!$item) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Item tidak ditemukan'
        ]);
        exit;
    }
    
    $idproduk = (int)$item['idproduk'];
    $qty = (int)$item['quantity'];
    
    // Hapus item dari keranjang
    $delete = mysqli_query($conn, "DELETE FROM keranjang WHERE idcart = '$idcart'");
    
    if (!$delete) {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conn)
        ]);
        exit;
    }
    
    // Kembalikan stock ke produk
    $update = mysqli_query($conn, "UPDATE produk SET stock = stock + $qty WHERE idproduk = '$idproduk'");
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Item berhasil dihapus'
    ]);
    exit;
}


if (isset($_GET['q'])) {
    $q = trim($_GET['q']);

    if (strlen($q) < 2) {
        echo json_encode([]);
        exit;
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT idproduk, kode_produk, nama_produk AS nama, harga_jual, stock
         FROM produk
         WHERE kode_produk LIKE ?
         LIMIT 10"
    );

    $search = "%$q%";
    mysqli_stmt_bind_param($stmt, "s", $search);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // $data[] = $row;
        $data[] = [
            'idproduk' => $row['idproduk'],
            'kode_produk' => $row['kode_produk'],
            'nama' => $row['nama'],
            'harga_jual' => formatRupiah($row['harga_jual'], false),
            'stock' => (int)$row['stock'],
        ];
    }


    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

$title = "Transaksi - KasirApp";
$content = 'pages/index.php';
include 'template/layout.php';
