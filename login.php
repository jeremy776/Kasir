<?php
include 'template/header.php';

require_once "helper/connection.php";
require_once "helper/auth.php";
session_start();
guestOnly();
$error = '';

// print ke konsole

if (isset($_POST['username'], $_POST['password'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = mysqli_prepare($conn, "SELECT * FROM login WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    $hash = password_hash('admin', PASSWORD_DEFAULT);
    var_dump($hash);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_toko'] = $user['nama_toko'];
        $_SESSION['alamat'] = $user['alamat'];
        $_SESSION['telepon'] = $user['telepon'];

        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
}
?>

<div id="id">
    <section class="flex min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
        <div class="flex flex-col w-full items-center justify-center px-4 sm:px-6 py-6 sm:py-8 mx-auto">

            <div class="w-full bg-white rounded-2xl shadow-xl border border-gray-100 sm:max-w-md overflow-hidden">

                <div class="p-5 sm:p-8">
                    <div class="text-center mb-5 sm:mb-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Selamat Datang</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Username atau password salah!
                            </p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-5" action="">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-colors" placeholder="Masukkan username" required>
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-colors" required>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Masuk
                        </button>
                    </form>
                </div>

                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">© <?php echo date('Y'); ?> LazyPeople. All rights reserved.</p>
                </div>
            </div>
        </div>
    </section>
</div>
</body>

</html>