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

    <!-- Danger Zone -->
    <div class="bg-white border border-red-200 rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-red-100 bg-red-50">
            <h2 class="text-sm font-semibold text-red-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Zona Berbahaya
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-gray-600">Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan. Harap berhati-hati.</p>
            
            <!-- Hapus Data Penjualan -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div>
                    <h3 class="text-sm font-medium text-gray-800">Hapus Data Penjualan</h3>
                    <p class="text-xs text-gray-500 mt-1">Menghapus semua data transaksi, laporan, dan keranjang</p>
                </div>
                <button onclick="showDeleteModal('penjualan')" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 rounded-lg transition-colors">
                    Hapus Data
                </button>
            </div>
            
            <!-- Hapus Data Produk -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div>
                    <h3 class="text-sm font-medium text-gray-800">Hapus Data Produk & Kategori</h3>
                    <p class="text-xs text-gray-500 mt-1">Menghapus semua produk, kategori, dan data penjualan terkait</p>
                </div>
                <button onclick="showDeleteModal('produk')" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-100 hover:bg-red-200 rounded-lg transition-colors">
                    Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modalDelete" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-red-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h3 class="text-lg font-semibold">Konfirmasi Penghapusan</h3>
                        <p id="modalDeleteTitle" class="text-red-100 text-sm"></p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p id="modalDeleteDesc" class="text-sm text-red-700"></p>
                </div>
                
                <!-- Step 1: Ketik HAPUS -->
                <div id="step1" class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Ketik <span class="font-bold text-red-600">HAPUS</span> untuk melanjutkan:</label>
                    <input type="text" id="confirmText" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100"
                        placeholder="Ketik HAPUS">
                    <button onclick="verifyStep1()" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                        Lanjutkan
                    </button>
                </div>
                
                <!-- Step 2: Masukkan Password -->
                <div id="step2" class="hidden space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Masukkan password Anda untuk konfirmasi:</label>
                    <input type="password" id="confirmPassword" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100"
                        placeholder="Password">
                    <button onclick="executeDelete()" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                        Hapus Permanen
                    </button>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </button>
            </div>
        </div>
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
    
    // Validasi
    if (!data.nama_toko || !data.username || !data.alamat || !data.telepon) {
        alert('Semua field wajib diisi!');
        return;
    }
    
    // Jika password baru diisi tapi password lama kosong
    if (data.password_baru && !data.password_lama) {
        alert('Masukkan password lama untuk mengubah password!');
        return;
    }
    
    fetch('pengaturan.php?q=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            alert('Data berhasil disimpan!');
            window.location.reload();
        } else {
            alert('Gagal: ' + result.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
}

let deleteType = '';

function showDeleteModal(type) {
    deleteType = type;
    const modal = document.getElementById('modalDelete');
    const title = document.getElementById('modalDeleteTitle');
    const desc = document.getElementById('modalDeleteDesc');
    
    // Reset steps
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('confirmText').value = '';
    document.getElementById('confirmPassword').value = '';
    
    if (type === 'penjualan') {
        title.textContent = 'Hapus Data Penjualan';
        desc.innerHTML = '<strong>PERINGATAN:</strong> Tindakan ini akan menghapus SEMUA data transaksi, laporan penjualan, dan keranjang belanja. Data yang dihapus TIDAK DAPAT dikembalikan!';
    } else if (type === 'produk') {
        title.textContent = 'Hapus Data Produk & Kategori';
        desc.innerHTML = '<strong>PERINGATAN:</strong> Tindakan ini akan menghapus SEMUA produk, kategori, serta data penjualan terkait. Data yang dihapus TIDAK DAPAT dikembalikan!';
    }
    
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modalDelete').classList.add('hidden');
    deleteType = '';
}

function verifyStep1() {
    const confirmText = document.getElementById('confirmText').value;
    
    if (confirmText !== 'HAPUS') {
        alert('Ketik HAPUS dengan benar untuk melanjutkan!');
        return;
    }
    
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
}

function executeDelete() {
    const password = document.getElementById('confirmPassword').value;
    
    if (!password) {
        alert('Masukkan password Anda!');
        return;
    }
    
    const endpoint = deleteType === 'penjualan' ? 'hapus_penjualan' : 'hapus_produk';
    
    fetch(`settings.php?q=${endpoint}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password: password })
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            alert(result.message);
            closeDeleteModal();
            window.location.reload();
        } else {
            alert('Gagal: ' + result.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan');
    });
}
</script>