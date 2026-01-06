<?php
session_start();

$dbhost = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'db_kasir';

$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
