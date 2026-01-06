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

    $cart_items = $_SESSION['cart'] ?? [];
    if (empty($cart_items)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Keranjang kosong'
        ]);
        exit;
    }

    // Simpan ke tb_nota dan kurangi stock
    $no_nota = $data['no_nota'] ?? '';
    foreach ($cart_items as $item) {
        $idproduk = (int)$item['idproduk'];
        $qty      = (int)$item['quantity'];
        $harga_jual = (float)$item['harga_jual'];

        // Insert ke tb_nota dengan harga saat transaksi
        $insert = mysqli_query($conn, "INSERT INTO tb_nota (no_nota, idproduk, quantity, harga_jual) VALUES ('$no_nota', '$idproduk', '$qty', '$harga_jual')");
        if (!$insert) {
            echo json_encode([
                'status' => 'error',
                'message' => mysqli_error($conn)
            ]);
            exit;
        }

        // Kurangi stock
        $update_stock = mysqli_query($conn, "UPDATE produk SET stock = stock - $qty WHERE idproduk = '$idproduk'");
        if (!$update_stock) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal update stock: ' . mysqli_error($conn)
            ]);
            exit;
        }
    }

    // Clear session cart
    $_SESSION['cart'] = [];

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

    // Clear session cart (stock tidak perlu dikembalikan karena belum dikurangi)
    $_SESSION['cart'] = [];

    echo json_encode([
        'status' => 'success'
    ]);
    exit;
}

// Endpoint untuk get cart data
if (isset($_GET['q']) && $_GET['q'] === 'get_cart') {
    header('Content-Type: application/json');

    $cart_items = $_SESSION['cart'] ?? [];
    $items = [];
    $total = 0;

    foreach ($cart_items as $item) {
        $items[] = $item;
        $total += $item['harga_jual'] * $item['quantity'];
    }

    echo json_encode([
        'status' => 'success',
        'items' => $items,
        'total' => $total
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
    $kode_produk = $data['kode_produk'] ?? '';
    $nama_produk = $data['nama_produk'] ?? '';
    $harga = (float)($data['harga'] ?? 0);
    $qty = (int)($data['qty'] ?? 0);

    if ($idproduk <= 0 || $qty <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data tidak valid'
        ]);
        exit;
    }

    // Cek stock produk
    $stockcheck = mysqli_query($conn, "SELECT stock FROM produk WHERE idproduk = '$idproduk'");
    $stockdata = mysqli_fetch_assoc($stockcheck);

    // Hitung total qty yang sudah ada di cart
    $cart_qty = 0;
    if (isset($_SESSION['cart'][$idproduk])) {
        $cart_qty = $_SESSION['cart'][$idproduk]['quantity'];
    }

    if (!$stockdata || $stockdata['stock'] < ($cart_qty + $qty)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Stok tidak mencukupi'
        ]);
        exit;
    }

    // Tambah atau update cart di session
    if (isset($_SESSION['cart'][$idproduk])) {
        $_SESSION['cart'][$idproduk]['quantity'] += $qty;
    } else {
        $_SESSION['cart'][$idproduk] = [
            'idproduk' => $idproduk,
            'kode_produk' => $kode_produk,
            'nama_produk' => $nama_produk,
            'harga_jual' => $harga,
            'quantity' => $qty
        ];
    }

    // Calculate total
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['harga_jual'] * $item['quantity'];
    }

    $cart_count = count($_SESSION['cart']);

    echo json_encode([
        'status'   => 'success',
        'idproduk' => $idproduk,
        'qty'      => $qty,
        'cart_item' => $_SESSION['cart'][$idproduk],
        'cart_count' => $cart_count,
        'total' => $total
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

    $idproduk = (int)($data['idproduk'] ?? 0);

    if ($idproduk <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID produk tidak valid'
        ]);
        exit;
    }

    // Hapus dari session
    if (isset($_SESSION['cart'][$idproduk])) {
        unset($_SESSION['cart'][$idproduk]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Item berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Item tidak ditemukan'
        ]);
    }
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
         WHERE kode_produk LIKE ? OR nama_produk LIKE ?
         LIMIT 10"
    );

    $search = "%$q%";
    mysqli_stmt_bind_param($stmt, "ss", $search, $search);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'idproduk' => $row['idproduk'],
            'kode_produk' => $row['kode_produk'],
            'nama' => $row['nama'],
            'harga_jual' => (float)$row['harga_jual'], // Kirim sebagai number, bukan string
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
