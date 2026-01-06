<!DOCTYPE html>
<html lang="id">

<head>
  <!-- Basic Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- SEO Meta Tags -->
  <title><?= $title ?? 'Aplikasi Kasir' ?></title>
  <meta name="description" content="Aplikasi Kasir - Sistem Point of Sale (POS) untuk mengelola transaksi, produk, dan laporan penjualan toko Anda dengan mudah dan efisien.">
  <meta name="keywords" content="aplikasi kasir, POS, point of sale, sistem kasir, manajemen toko, penjualan, inventory">
  <meta name="author" content="KasirApp">
  <meta name="robots" content="index, follow">

  <!-- App Meta -->
  <meta name="application-name" content="KasirApp">
  <meta name="theme-color" content="#2563eb">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="KasirApp">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/logo.png">
  <link rel="apple-touch-icon" href="assets/images/logo.png">

  <!-- Local Font - Inter -->
  <style>
    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 300;
      font-display: swap;
      src: url('assets/fonts/Inter-Light.woff2') format('woff2'),
        url('assets/fonts/Inter-Light.woff') format('woff');
    }

    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: url('assets/fonts/Inter-Regular.woff2') format('woff2'),
        url('assets/fonts/Inter-Regular.woff') format('woff');
    }

    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 500;
      font-display: swap;
      src: url('assets/fonts/Inter-Medium.woff2') format('woff2'),
        url('assets/fonts/Inter-Medium.woff') format('woff');
    }

    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 600;
      font-display: swap;
      src: url('assets/fonts/Inter-SemiBold.woff2') format('woff2'),
        url('assets/fonts/Inter-SemiBold.woff') format('woff');
    }

    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 700;
      font-display: swap;
      src: url('assets/fonts/Inter-Bold.woff2') format('woff2'),
        url('assets/fonts/Inter-Bold.woff') format('woff');
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }
  </style>

  <!-- Tailwind CSS -->
  <script src="assets/js/tailwind.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="antialiased">

  <div class='container'>