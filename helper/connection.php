<?php
$dbhost = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'db_kasir';

$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

date_default_timezone_set('Asia/Jakarta');
error_reporting(0);