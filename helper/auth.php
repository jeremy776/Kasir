<?php
function guestOnly() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!(!isset($_SESSION['login']) || $_SESSION['login'] !== true)) {
        header("Location: ../index.php");
        exit;
    }
}

function getUserLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
        return [
            'username' => $_SESSION['username'],
            'nama_toko' => $_SESSION['nama_toko'],
            'alamat' => $_SESSION['alamat'],
            'telepon' => $_SESSION['telepon'],
        ];
    }

    return null;
}

function userOnly() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
        header("Location: index.php");
        exit;
    }
}