<?php

use BcMath\Number;

require_once 'helper/connection.php';
require_once 'helper/utils.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query dengan search
$where_clause = '';
if (!empty($search)) {
    $where_clause = "WHERE p.kode_produk LIKE '%$search%' 
        OR p.nama_produk LIKE '%$search%' 
        OR k.nama_kategori LIKE '%$search%'
        OR p.stock LIKE '%$search%'
        OR p.harga_modal LIKE '%$search%'
        OR p.harga_jual LIKE '%$search%'";
}

$list_produk = mysqli_query($conn, "SELECT p.* FROM produk p LEFT JOIN kategori k ON p.idkategori = k.idkategori $where_clause ORDER BY p.idproduk ASC LIMIT $limit OFFSET $offset");
$total_produk_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk p LEFT JOIN kategori k ON p.idkategori = k.idkategori $where_clause");
$total_produk_row = mysqli_fetch_assoc($total_produk_result);
$total_produk = (int)$total_produk_row['total'];
$total_pages = ceil($total_produk / $limit);

$last_kode_result = mysqli_query($conn, "SELECT kode_produk FROM produk ORDER BY idproduk DESC LIMIT 1");
$last_kode_row = mysqli_fetch_assoc($last_kode_result);
if ($last_kode_row) {
    // Ambil angka dari kode produk (setelah prefix 'PROD')
    preg_match('/\d+$/', $last_kode_row['kode_produk'], $matches);
    $last_number = isset($matches[0]) ? (int)$matches[0] : 0;
    $new_number = $last_number + 1;
} else {
    $new_number = 1;
}
$kode_produk_baru = 'PROD' . str_pad($new_number, 4, '0', STR_PAD_LEFT);

// Ambil list kategori untuk dropdown
$list_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

?>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Manajemen Produk</h1>
        <p class="text-xs md:text-sm text-gray-500 mt-1">Kelola data produk dan stok toko Anda</p>
    </div>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="border border-gray-200 rounded-lg divide-gray-200 bg-white">
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-3 p-3 sm:p-4 border-b border-gray-200">
                        <form method="GET" class="relative flex items-center gap-2 w-full sm:w-auto">
                            <div class="relative flex-1 sm:flex-initial">
                                <input type="text" name="search" id="hs-table-search" value="<?php echo htmlspecialchars($search); ?>" class="py-2 px-3 ps-9 block w-full sm:w-auto border-gray-300 border shadow-2xs rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="Cari produk...">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                    <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-3 sm:px-4 py-2 rounded-lg">Cari</button>
                            <?php if (!empty($search)) : ?>
                                <a href="produk.php" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-4 py-2 rounded-lg">Reset</a>
                            <?php endif; ?>
                        </form>
                        <button onclick="openModal()" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full sm:w-auto">
                            + Tambah Produk
                        </button>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 ">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">No.</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Kode Produk</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Nama Produk</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Stok</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Harga Modal</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Harga Jual</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase ">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 ">
                                <?php $no = 1;
                                while ($produk = mysqli_fetch_assoc($list_produk)) : ?>
                                    <tr>
                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-800">
                                            <!-- perbaiki numbering sesuai page -->
                                            <?php echo $no++ + $offset; ?>
                                        </td>
                                        <td class="px-6 whitespace-nowrap text-sm text-gray-800"><?php echo $produk['kode_produk']; ?></td>
                                        <td class="px-6 whitespace-nowrap text-sm text-gray-800"><?php echo $produk['nama_produk']; ?></td>
                                        <td class="px-6 whitespace-nowrap text-sm text-gray-800 "><?php echo getKategoryById($conn, $produk['idkategori'])['nama_kategori'] ?? '' ?></td>
                                        <td class="px-6 whitespace-nowrap text-sm text-gray-800 "><?php echo $produk['stock'] ?? 0 ?></td>
                                        <td class="px-6 whitespace-nowrap text-sm text-gray-800 "><?php echo formatRupiah($produk['harga_modal'] ?? 0) ?></td>
                                        <td class="px-6 whitespace-nowrap text-sm text-gray-800 "><?php echo formatRupiah($produk['harga_jual'] ?? 0) ?></td>
                                        <td class="px-6 whitespace-nowrap space-x-4 text-end text-sm font-medium">
                                            <button type="button" onclick='editProduk(<?php echo json_encode($produk); ?>)' class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">Edit</button>
                                            <button type="button" onclick="hapus(this)" id="<?php echo $produk['idproduk']; ?>" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card List -->
                    <div class="md:hidden divide-y divide-gray-200">
                        <?php 
                        mysqli_data_seek($list_produk, 0);
                        $mobileNo = 1;
                        while ($produk = mysqli_fetch_assoc($list_produk)) : ?>
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800"><?php echo $produk['nama_produk']; ?></p>
                                        <p class="text-xs text-gray-500 font-mono"><?php echo $produk['kode_produk']; ?></p>
                                    </div>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                        <?php echo getKategoryById($conn, $produk['idkategori'])['nama_kategori'] ?? '-' ?>
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <p class="text-gray-400">Stok</p>
                                        <p class="font-medium text-gray-800"><?php echo $produk['stock'] ?? 0 ?></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Modal</p>
                                        <p class="font-medium text-gray-800"><?php echo formatRupiah($produk['harga_modal'] ?? 0) ?></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Jual</p>
                                        <p class="font-medium text-green-600"><?php echo formatRupiah($produk['harga_jual'] ?? 0) ?></p>
                                    </div>
                                </div>
                                <div class="flex gap-3 pt-2 border-t border-gray-100">
                                    <button type="button" onclick='editProduk(<?php echo json_encode($produk); ?>)' class="text-xs font-semibold text-blue-600 hover:text-blue-800">Edit</button>
                                    <button type="button" onclick="hapus(this)" id="<?php echo $produk['idproduk']; ?>" class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="p-3 sm:p-0 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <h4 class="px-2 sm:px-5 py-2 text-xs sm:text-sm text-gray-500">Page <?php echo $page; ?> of <?php echo $total_pages ?: 1; ?> (<?php echo $total_produk; ?> data)</h4>
                        <div class="px-2 sm:px-5 py-2 flex flex-wrap items-center gap-1">
                            <?php 
                            $search_param = !empty($search) ? '&search=' . urlencode($search) : '';
                            if ($page > 1) : ?>
                                <a href="produk.php?page=<?php echo $page - 1; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">«</a>
                            <?php endif; ?>
                            
                            <?php 
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            if ($start_page > 1) : ?>
                                <a href="produk.php?page=1<?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">1</a>
                                <?php if ($start_page > 2) : ?><span class="px-2 text-gray-500">...</span><?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start_page; $i <= $end_page; $i++) : ?>
                                <?php if ($i === $page) : ?>
                                    <span class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg"><?php echo $i; ?></span>
                                <?php else : ?>
                                    <a href="produk.php?page=<?php echo $i; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($end_page < $total_pages) : ?>
                                <?php if ($end_page < $total_pages - 1) : ?><span class="px-2 text-gray-500">...</span><?php endif; ?>
                                <a href="produk.php?page=<?php echo $total_pages; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"><?php echo $total_pages; ?></a>
                            <?php endif; ?>
                            
                            <?php if ($page < $total_pages) : ?>
                                <a href="produk.php?page=<?php echo $page + 1; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">»</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    function hapus(btn) {
        const idproduk = btn.id;

        // konfirmasi hapus
        if (!confirm('Yakin ingin menghapus Produk ini?')) {
            return;
        }
        fetch('produk.php?q=hapus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idproduk: idproduk
                })
            }).then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Produk berhasil dihapus');
                    // reload page
                    location.reload();
                } else {
                    alert('Gagal menghapus produk: ' + data.message);
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus produk');
            });
    }

    function openModal() {
        document.getElementById('modalTambahProduk').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalTambahProduk').classList.add('hidden');
    }

    function tambahProduk() {
        const kode_produk = document.getElementById('kode_produk').value;
        const nama_produk = document.getElementById('nama_produk').value;
        const idkategori = document.getElementById('idkategori').value;
        const stock = document.getElementById('stock').value;
        const harga_modal = document.getElementById('harga_modal').value;
        const harga_jual = document.getElementById('harga_jual').value;

        if (!nama_produk || !idkategori || !harga_jual) {
            alert('Mohon lengkapi data produk!');
            return;
        }

        fetch('produk.php?q=tambah', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                kode_produk,
                nama_produk,
                idkategori,
                stock: stock || 0,
                harga_modal: harga_modal || 0,
                harga_jual
            })
        }).then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Produk berhasil ditambahkan');
                location.reload();
            } else {
                alert('Gagal menambah produk: ' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambah produk');
        });
    }

    // Edit functions
    function editProduk(produk) {
        document.getElementById('edit_idproduk').value = produk.idproduk;
        document.getElementById('edit_kode_produk').value = produk.kode_produk;
        document.getElementById('edit_nama_produk').value = produk.nama_produk;
        document.getElementById('edit_idkategori').value = produk.idkategori;
        document.getElementById('edit_stock').value = produk.stock || 0;
        document.getElementById('edit_harga_modal').value = produk.harga_modal || 0;
        document.getElementById('edit_harga_jual').value = produk.harga_jual || 0;
        document.getElementById('modalEditProduk').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditProduk').classList.add('hidden');
    }

    function updateProduk() {
        const idproduk = document.getElementById('edit_idproduk').value;
        const kode_produk = document.getElementById('edit_kode_produk').value;
        const nama_produk = document.getElementById('edit_nama_produk').value;
        const idkategori = document.getElementById('edit_idkategori').value;
        const stock = document.getElementById('edit_stock').value;
        const harga_modal = document.getElementById('edit_harga_modal').value;
        const harga_jual = document.getElementById('edit_harga_jual').value;

        if (!nama_produk || !idkategori || !harga_jual) {
            alert('Mohon lengkapi data produk!');
            return;
        }

        fetch('produk.php?q=edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                idproduk,
                kode_produk,
                nama_produk,
                idkategori,
                stock: stock || 0,
                harga_modal: harga_modal || 0,
                harga_jual
            })
        }).then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Produk berhasil diupdate');
                location.reload();
            } else {
                alert('Gagal update produk: ' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat update produk');
        });
    }
</script>

<!-- Modal Tambah Produk -->
<div id="modalTambahProduk" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Produk Baru</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                    <input type="text" id="kode_produk" value="<?php echo $kode_produk_baru; ?>" readonly class="py-2 px-3 block w-full border border-gray-300 bg-gray-100 rounded-lg text-sm focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_produk" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan nama produk">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="idkategori" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($kat = mysqli_fetch_assoc($list_kategori)) : ?>
                            <option value="<?php echo $kat['idkategori']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" id="stock" value="0" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="0">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Modal</label>
                        <input type="number" id="harga_modal" value="0" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-red-500">*</span></label>
                        <input type="number" id="harga_jual" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="0">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    Batal
                </button>
                <button onclick="tambahProduk()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
<div id="modalEditProduk" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Produk</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <input type="hidden" id="edit_idproduk">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                    <input type="text" id="edit_kode_produk" readonly class="py-2 px-3 block w-full border border-gray-300 bg-gray-100 rounded-lg text-sm focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_nama_produk" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan nama produk">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="edit_idkategori" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        <?php 
                        // Reset pointer kategori untuk edit modal
                        $list_kategori_edit = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while ($kat_edit = mysqli_fetch_assoc($list_kategori_edit)) : ?>
                            <option value="<?php echo $kat_edit['idkategori']; ?>"><?php echo $kat_edit['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" id="edit_stock" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="0">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Modal</label>
                        <input type="number" id="edit_harga_modal" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_harga_jual" class="py-2 px-3 block w-full border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="0">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    Batal
                </button>
                <button onclick="updateProduk()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>