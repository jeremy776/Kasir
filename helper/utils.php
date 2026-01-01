<?php

function formatRupiah($amount, $prefix = true) {
    return ($prefix ? 'Rp. ' : '') . number_format($amount, 0, ',', '.');
}

function getKategoryById($conn, $idkategori) {
    $query = "SELECT * FROM kategori WHERE idkategori = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $idkategori);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

?>