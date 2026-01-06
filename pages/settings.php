<?php
require_once 'helper/connection.php';
require_once 'helper/auth.php';

$user = getUserLogin();
$username = $user['username'] ?? '';

// Get full user data from database
$query = mysqli_query($conn, "SELECT * FROM login WHERE username='$username'");
$data_user = mysqli_fetch_assoc($query);
?>

<div class="space-y-4 md:space-y-6 max-w-3xl">
    <!-- Header -->
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Pengaturan Akun</h1>
        <p class="text-xs md:text-sm text-gray-500 mt-1">Kelola informasi akun dan toko Anda</p>
    </div>

    <!-- Form Pengaturan -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <!-- Info Toko -->
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Informasi Toko
            </h2>
        </div>
        <div class="p-4 md:p-6 space-y-4">
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                    <input type="text" id="nama_toko" value="<?= htmlspecialchars($data_user['nama_toko'] ?? '') ?>"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        placeholder="Nama toko Anda">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" id="telepon" value="<?= htmlspecialchars($data_user['telepon'] ?? '') ?>"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        placeholder="08xxxxxxxxxx">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea id="alamat" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    placeholder="Alamat lengkap toko"><?= htmlspecialchars($data_user['alamat'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Info Akun -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Informasi Akun
            </h2>
        </div>
        <div class="p-4 md:p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" value="<?= htmlspecialchars($data_user['username'] ?? '') ?>"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    placeholder="Username">
            </div>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Ubah Password
                <span class="text-xs font-normal text-gray-400">(Opsional)</span>
            </h2>
        </div>
        <div class="p-4 md:p-6 space-y-4">
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                    <input type="password" id="password_lama"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        placeholder="Masukkan password lama">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="password_baru"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        placeholder="Masukkan password baru">
                </div>
            </div>
            <p class="text-xs text-gray-400">Kosongkan jika tidak ingin mengubah password</p>
        </div>
    </div>

    <!-- Button Simpan -->
    <div class="flex justify-end gap-3">
        <button onclick="window.location.reload()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
            Reset
        </button>
        <button onclick="simpanPengaturan()" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan Perubahan
        </button>
    </div>

</div>

<script>
    function simpanPengaturan() {
        const data = {
            nama_toko: document.getElementById('nama_toko').value,
            username: document.getElementById('username').value,
            alamat: document.getElementById('alamat').value,
            telepon: document.getElementById('telepon').value,
            password_lama: document.getElementById('password_lama').value,
            password_baru: document.getElementById('password_baru').value
        };

        if (data.nama_toko === "<?= addslashes($data_user['nama_toko'] ?? '') ?>" &&
            data.username === "<?= addslashes($data_user['username'] ?? '') ?>" &&
            data.alamat === "<?= addslashes($data_user['alamat'] ?? '') ?>" &&
            data.telepon === "<?= addslashes($data_user['telepon'] ?? '') ?>" &&
            !data.password_lama && !data.password_baru
        ) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Perubahan',
                text: 'Tidak ada perubahan data untuk disimpan.',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        if (!data.nama_toko || !data.username || !data.alamat || !data.telepon) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Lengkap',
                text: 'Semua field wajib diisi!',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        if (data.password_baru && !data.password_lama) {
            Swal.fire({
                icon: 'warning',
                title: 'Password Lama Required',
                text: 'Masukkan password lama untuk mengubah password!',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        fetch('settings.php?q=update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan!',
                        confirmButtonColor: '#2563eb',
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal: ' + result.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan',
                    confirmButtonColor: '#dc2626'
                });
            });
    }
</script>