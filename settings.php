<?php
require_once 'helper/connection.php';
require_once 'helper/auth.php';

userOnly();

// Handle update akun
if (isset($_GET['q']) && $_GET['q'] === 'update') {
    header('Content-Type: application/json');
    
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    
    if (!is_array($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }
    
    $nama_toko = mysqli_real_escape_string($conn, $data['nama_toko'] ?? '');
    $username = mysqli_real_escape_string($conn, $data['username'] ?? '');
    $alamat = mysqli_real_escape_string($conn, $data['alamat'] ?? '');
    $telepon = mysqli_real_escape_string($conn, $data['telepon'] ?? '');
    $password_lama = $data['password_lama'] ?? '';
    $password_baru = $data['password_baru'] ?? '';
    
    // Get current user
    $current_username = $_SESSION['username'];
    
    // Validate required fields
    if (empty($nama_toko) || empty($username) || empty($alamat) || empty($telepon)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi']);
        exit;
    }
    
    // Check if username changed and already exists
    if ($username !== $current_username) {
        $check = mysqli_query($conn, "SELECT id_login FROM login WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan']);
            exit;
        }
    }
    
    // Build update query
    $update_query = "UPDATE login SET nama_toko='$nama_toko', username='$username', alamat='$alamat', telepon='$telepon'";
    
    // If password change requested
    if (!empty($password_baru)) {
        if (empty($password_lama)) {
            echo json_encode(['status' => 'error', 'message' => 'Password lama harus diisi untuk mengubah password']);
            exit;
        }
        
        // Verify old password
        $verify = mysqli_query($conn, "SELECT password FROM login WHERE username='$current_username'");
        $row = mysqli_fetch_assoc($verify);
        
        if (!password_verify($password_lama, $row['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Password lama tidak sesuai']);
            exit;
        }
        
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
        $update_query .= ", password='$hashed_password'";
    }
    
    $update_query .= " WHERE username='$current_username'";
    
    if (mysqli_query($conn, $update_query)) {
        // Update session
        $_SESSION['username'] = $username;
        $_SESSION['nama_toko'] = $nama_toko;
        $_SESSION['alamat'] = $alamat;
        $_SESSION['telepon'] = $telepon;
        
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

// Handle hapus data penjualan
if (isset($_GET['q']) && $_GET['q'] === 'hapus_penjualan') {
    header('Content-Type: application/json');
    
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    
    $password = $data['password'] ?? '';
    $current_username = $_SESSION['username'];
    
    // Verify password
    $verify = mysqli_query($conn, "SELECT password FROM login WHERE username='$current_username'");
    $row = mysqli_fetch_assoc($verify);
    
    if (!password_verify($password, $row['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Password tidak sesuai']);
        exit;
    }
    
    // Delete sales data
    mysqli_query($conn, "DELETE FROM tb_nota");
    mysqli_query($conn, "DELETE FROM laporan");
    mysqli_query($conn, "DELETE FROM keranjang");
    
    echo json_encode(['status' => 'success', 'message' => 'Data penjualan berhasil dihapus']);
    exit;
}

// Handle hapus data produk
if (isset($_GET['q']) && $_GET['q'] === 'hapus_produk') {
    header('Content-Type: application/json');
    
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    
    $password = $data['password'] ?? '';
    $current_username = $_SESSION['username'];
    
    // Verify password
    $verify = mysqli_query($conn, "SELECT password FROM login WHERE username='$current_username'");
    $row = mysqli_fetch_assoc($verify);
    
    if (!password_verify($password, $row['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Password tidak sesuai']);
        exit;
    }
    
    // Delete products and related data
    mysqli_query($conn, "DELETE FROM tb_nota");
    mysqli_query($conn, "DELETE FROM laporan");
    mysqli_query($conn, "DELETE FROM keranjang");
    mysqli_query($conn, "DELETE FROM produk");
    mysqli_query($conn, "DELETE FROM kategori");
    
    echo json_encode(['status' => 'success', 'message' => 'Data produk dan kategori berhasil dihapus']);
    exit;
}

$title = "Pengaturan - KasirApp";
$content = 'pages/settings.php';
include 'template/layout.php';