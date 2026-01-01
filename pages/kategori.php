<?php
require_once 'helper/connection.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query dengan search
$where_clause = '';
if (!empty($search)) {
    $where_clause = "WHERE nama_kategori LIKE '%$search%'";
}

$list_kategori = mysqli_query($conn, "SELECT * FROM kategori $where_clause ORDER BY idkategori ASC LIMIT $limit OFFSET $offset");
$total_kategori_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori $where_clause");
$total_kategori_row = mysqli_fetch_assoc($total_kategori_result);
$total_kategori = (int)$total_kategori_row['total'];
$total_pages = ceil($total_kategori / $limit);

?>
<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Manajemen Kategori</h1>
        <p class="text-xs md:text-sm text-gray-500 mt-1">Kelola kategori produk toko Anda</p>
    </div>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="border border-gray-200 rounded-lg divide-gray-200 bg-white">
                    <div class="flex flex-col gap-3 p-3 sm:p-4 border-b border-gray-200">
                        <form method="GET" class="relative flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="text" name="search" id="hs-table-search" value="<?php echo htmlspecialchars($search); ?>" class="py-2 px-3 ps-9 block w-full border-gray-300 border shadow-2xs rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="Cari kategori...">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                    <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-3 sm:px-4 py-2 rounded-lg">Cari</button>
                            <?php if (!empty($search)) : ?>
                                <a href="kategori.php" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 sm:px-4 py-2 rounded-lg">Reset</a>
                            <?php endif; ?>
                        </form>
                        <div class="flex items-center gap-2">
                            <input type="text" name="hs-table-tambah" id="hs-table-tambah" class="py-2 px-3 block flex-1 border-blue-600 border shadow-xs rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 focus:outline-none disabled:pointer-events-none" placeholder="Nama kategori">
                            <button onclick="buatkategori()" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg whitespace-nowrap">
                                Tambah
                            </button>
                        </div>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 ">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">No.</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Nama Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase ">Qty</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase ">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 ">
                                <?php $no = 1;
                                while ($kategori = mysqli_fetch_assoc($list_kategori)) : ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            <!-- perbaiki numbering sesuai page -->
                                            <?php echo $no++ + $offset; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo $kategori['nama_kategori']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 "><?php
                                            $idkategori = (int)$kategori['idkategori'];
                                            $count_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE idkategori = $idkategori");
                                            $count_produk_row = mysqli_fetch_assoc($count_produk);
                                            echo (int)$count_produk_row['total'];

                                        ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap space-x-4 text-end text-sm font-medium">
                                            <button type="button" onclick="hapus(this)" id="<?php echo $kategori['idkategori']; ?>" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card List -->
                    <div class="md:hidden divide-y divide-gray-200">
                        <?php 
                        mysqli_data_seek($list_kategori, 0);
                        while ($kategori = mysqli_fetch_assoc($list_kategori)) : ?>
                            <div class="p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-800"><?php echo $kategori['nama_kategori']; ?></p>
                                    <p class="text-xs text-gray-500">
                                        <?php
                                            $idkategori = (int)$kategori['idkategori'];
                                            $count_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE idkategori = $idkategori");
                                            $count_produk_row = mysqli_fetch_assoc($count_produk);
                                            echo (int)$count_produk_row['total'];
                                        ?> produk
                                    </p>
                                </div>
                                <button type="button" onclick="hapus(this)" id="<?php echo $kategori['idkategori']; ?>" class="text-xs font-semibold text-red-600 hover:text-red-800 px-3 py-1.5 bg-red-50 rounded-lg">Hapus</button>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="p-3 sm:p-0 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <h4 class="px-2 sm:px-5 py-2 text-xs sm:text-sm text-gray-500">Page <?php echo $page; ?> of <?php echo $total_pages ?: 1; ?> (<?php echo $total_kategori; ?> data)</h4>
                        <div class="px-2 sm:px-5 py-2 flex flex-wrap items-center gap-1">
                            <?php 
                            $search_param = !empty($search) ? '&search=' . urlencode($search) : '';
                            if ($page > 1) : ?>
                                <a href="kategori.php?page=<?php echo $page - 1; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">«</a>
                            <?php endif; ?>
                            
                            <?php 
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            if ($start_page > 1) : ?>
                                <a href="kategori.php?page=1<?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">1</a>
                                <?php if ($start_page > 2) : ?><span class="px-2 text-gray-500">...</span><?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start_page; $i <= $end_page; $i++) : ?>
                                <?php if ($i === $page) : ?>
                                    <span class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg"><?php echo $i; ?></span>
                                <?php else : ?>
                                    <a href="kategori.php?page=<?php echo $i; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($end_page < $total_pages) : ?>
                                <?php if ($end_page < $total_pages - 1) : ?><span class="px-2 text-gray-500">...</span><?php endif; ?>
                                <a href="kategori.php?page=<?php echo $total_pages; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"><?php echo $total_pages; ?></a>
                            <?php endif; ?>
                            
                            <?php if ($page < $total_pages) : ?>
                                <a href="kategori.php?page=<?php echo $page + 1; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">»</a>
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
        const idkategori = btn.id;

        if (!confirm('Yakin hapus kategori ini?')) {
            return;
        }

        fetch('kategori.php?q=hapus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idkategori
                })
            }).then(res => res.text())
            .then(text => {
                console.log('RAW RESPONSE:', text);
                return JSON.parse(text);
            })
            .then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                    return;
                }
                alert('Kategori berhasil dihapus');
                window.location.reload();
            })
            .catch(err => {
                console.error('ERROR:', err);
            });
    }

    function buatkategori() {
        const nama_kategori = document.getElementById('hs-table-tambah').value;

        fetch('kategori.php?q=tambah', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nama_kategori
                })
            }).then(res => res.text())
            .then(text => {
                console.log('RAW RESPONSE:', text);
                return JSON.parse(text);
            })
            .then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                    return;
                }
                alert('Kategori berhasil ditambahkan');
                window.location.reload();
            })
            .catch(err => {
                console.error('ERROR:', err);
            });
    }
</script>