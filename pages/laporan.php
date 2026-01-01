<?php
require_once 'helper/connection.php';
require_once 'helper/utils.php';
require_once 'helper/auth.php';

$user = getUserLogin();
$nama_toko = $user['nama_toko'] ?? 'Toko Saya';
$alamat_toko = $user['alamat'] ?? 'Alamat Toko';
$no_telp = $user['no_telp'] ?? $user['telepon'] ?? '-';
$kasir = $user['username'] ?? 'Admin';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query dengan search
$where_clause = '';
if (!empty($search)) {
    $where_clause = "WHERE l.no_nota LIKE '%$search%' OR l.catatan LIKE '%$search%'";
}

// Data untuk statistik
$total_pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(idpelanggan) as total FROM pelanggan"))['total'] ?? 0;
$total_terjual = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM tb_nota"))['total'] ?? 0;
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(idlaporan) as total FROM laporan"))['total'] ?? 0;
$total_penjualan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(totalbeli) as total FROM laporan"))['total'] ?? 0;

// Hitung keuntungan (pendapatan - modal)
$data_keuntungan = mysqli_query($conn, "SELECT SUM(p.harga_jual * t.quantity) as pendapatan, SUM(p.harga_modal * t.quantity) as modal FROM tb_nota t JOIN produk p ON p.idproduk = t.idproduk");
$row_keuntungan = mysqli_fetch_assoc($data_keuntungan);
$pendapatan = $row_keuntungan['pendapatan'] ?? 0;
$modal = $row_keuntungan['modal'] ?? 0;
$keuntungan = $pendapatan - $modal;

// Data laporan dengan pagination
$list_laporan = mysqli_query($conn, "SELECT l.* FROM laporan l $where_clause ORDER BY l.idlaporan DESC LIMIT $limit OFFSET $offset");
$total_laporan_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan l $where_clause");
$total_laporan_row = mysqli_fetch_assoc($total_laporan_result);
$total_laporan = (int)$total_laporan_row['total'];
$total_pages = ceil($total_laporan / $limit);

?>

<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Laporan Penjualan</h1>
            <p class="text-xs md:text-sm text-gray-500 mt-1">Ringkasan dan detail transaksi penjualan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 md:gap-4">
        <!-- Total Transaksi -->
        <div class="bg-white rounded-xl border border-gray-200 p-3 md:p-5 shadow-sm">
            <div class="flex items-start md:items-center gap-2 md:gap-4">
                <div class="bg-blue-100 p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] md:text-xs text-gray-500 font-medium uppercase tracking-wide">Transaksi</p>
                    <p class="text-lg md:text-2xl font-bold text-gray-800 truncate"><?php echo number_format($total_transaksi); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Terjual -->
        <div class="bg-white rounded-xl border border-gray-200 p-3 md:p-5 shadow-sm">
            <div class="flex items-start md:items-center gap-2 md:gap-4">
                <div class="bg-green-100 p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] md:text-xs text-gray-500 font-medium uppercase tracking-wide">Terjual</p>
                    <p class="text-lg md:text-2xl font-bold text-gray-800 truncate"><?php echo number_format($total_terjual); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="bg-white rounded-xl border border-gray-200 p-3 md:p-5 shadow-sm">
            <div class="flex items-start md:items-center gap-2 md:gap-4">
                <div class="bg-purple-100 p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] md:text-xs text-gray-500 font-medium uppercase tracking-wide">Penjualan</p>
                    <p class="text-base md:text-xl font-bold text-gray-800 truncate"><?php echo formatRupiah($total_penjualan); ?></p>
                </div>
            </div>
        </div>

        <!-- Keuntungan -->
        <div class="bg-white rounded-xl border border-gray-200 p-3 md:p-5 shadow-sm">
            <div class="flex items-start md:items-center gap-2 md:gap-4">
                <div class="bg-emerald-100 p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] md:text-xs text-gray-500 font-medium uppercase tracking-wide">Keuntungan</p>
                    <p class="text-base md:text-xl font-bold text-emerald-600 truncate"><?php echo formatRupiah($keuntungan); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex flex-col gap-3 p-3 md:p-4 border-b border-gray-100">
            <h2 class="text-base md:text-lg font-semibold text-gray-800">Riwayat Transaksi</h2>
            <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full py-2.5 px-3 ps-9 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="Cari no nota...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-initial px-4 py-2.5 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Cari</button>
                    <?php if (!empty($search)) : ?>
                        <a href="laporan.php" class="flex-1 sm:flex-initial px-4 py-2.5 text-sm text-center bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Nota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kembalian</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php 
                    $no = 1;
                    while ($laporan = mysqli_fetch_assoc($list_laporan)): 
                        $nota = $laporan['no_nota'];
                        $qty_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as qty FROM tb_nota WHERE no_nota='$nota'"));
                        $qty = $qty_result['qty'] ?? 0;
                    ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo $no++ + $offset; ?></td>
                            <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800"><?php echo $laporan['no_nota']; ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo $laporan['tgl_sub'] ?? '-'; ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo $qty; ?> item</td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-[150px] truncate"><?php echo $laporan['catatan'] ?: '-'; ?></td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800"><?php echo formatRupiah($laporan['totalbeli']); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo formatRupiah($laporan['pembayaran']); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo formatRupiah($laporan['kembalian']); ?></td>
                            <td class="px-4 py-3 text-sm text-right space-x-2">
                                <button onclick="viewDetail('<?php echo $nota; ?>')" class="text-blue-600 hover:text-blue-800 font-medium">Detail</button>
                                <button onclick="hapusLaporan('<?php echo $nota; ?>')" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    
                    <?php if ($total_laporan === 0): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Belum ada data transaksi
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-gray-200">
            <?php 
            mysqli_data_seek($list_laporan, 0);
            while ($laporan = mysqli_fetch_assoc($list_laporan)): 
                $nota = $laporan['no_nota'];
                $qty_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as qty FROM tb_nota WHERE no_nota='$nota'"));
                $qty = $qty_result['qty'] ?? 0;
            ?>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-mono font-semibold text-gray-800"><?php echo $laporan['no_nota']; ?></p>
                            <p class="text-xs text-gray-500"><?php echo $laporan['tgl_sub'] ?? '-'; ?></p>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full"><?php echo $qty; ?> item</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div>
                            <p class="text-gray-400">Total</p>
                            <p class="font-semibold text-gray-800"><?php echo formatRupiah($laporan['totalbeli']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Bayar</p>
                            <p class="font-medium text-gray-700"><?php echo formatRupiah($laporan['pembayaran']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400">Kembali</p>
                            <p class="font-medium text-green-600"><?php echo formatRupiah($laporan['kembalian']); ?></p>
                        </div>
                    </div>
                    <?php if ($laporan['catatan']): ?>
                        <p class="text-xs text-gray-500 bg-gray-50 rounded p-2"><?php echo $laporan['catatan']; ?></p>
                    <?php endif; ?>
                    <div class="flex gap-3 pt-2 border-t border-gray-100">
                        <button onclick="viewDetail('<?php echo $nota; ?>')" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Detail</button>
                        <button onclick="hapusLaporan('<?php echo $nota; ?>')" class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if ($total_laporan === 0): ?>
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Belum ada data transaksi
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 md:p-4 border-t border-gray-100">
            <p class="text-xs sm:text-sm text-gray-500">Page <?php echo $page; ?> of <?php echo $total_pages ?: 1; ?> (<?php echo $total_laporan; ?> data)</p>
            <div class="flex flex-wrap items-center gap-1">
                <?php 
                $search_param = !empty($search) ? '&search=' . urlencode($search) : '';
                if ($page > 1) : ?>
                    <a href="laporan.php?page=<?php echo $page - 1; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">«</a>
                <?php endif; ?>
                
                <?php 
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++) : ?>
                    <?php if ($i === $page) : ?>
                        <span class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg"><?php echo $i; ?></span>
                    <?php else : ?>
                        <a href="laporan.php?page=<?php echo $i; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages) : ?>
                    <a href="laporan.php?page=<?php echo $page + 1; ?><?php echo $search_param; ?>" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">»</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function hapusLaporan(nota) {
        if (!confirm('Yakin ingin menghapus laporan ' + nota + '?')) {
            return;
        }
        fetch('laporan.php?q=hapus', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ no_nota: nota })
        }).then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Laporan berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        }).catch(err => {
            console.error(err);
            alert('Terjadi kesalahan');
        });
    }

    function viewDetail(nota) {
        fetch('laporan.php?q=detail&nota=' + encodeURIComponent(nota))
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showDetailModal(data.data);
            } else {
                alert('Gagal memuat detail: ' + data.message);
            }
        }).catch(err => {
            console.error(err);
            alert('Terjadi kesalahan');
        });
    }

    function showDetailModal(data) {
        const modal = document.getElementById('modalDetail');
        const laporan = data.laporan;
        const items = data.items;

        // Set header info
        document.getElementById('detail_no_nota').textContent = laporan.no_nota;
        document.getElementById('detail_tanggal').textContent = laporan.tgl_sub || '-';
        document.getElementById('detail_catatan').textContent = laporan.catatan || '-';

        // Build items table
        let itemsHtml = '';
        let totalQty = 0;
        items.forEach((item, index) => {
            const subtotal = item.harga_jual * item.quantity;
            totalQty += parseInt(item.quantity);
            itemsHtml += `
                <tr>
                    <td class="py-1 text-sm text-gray-800">${item.nama_produk}</td>
                    <td class="py-1 text-sm text-gray-600 text-center">${item.quantity}</td>
                    <td class="py-1 text-sm font-medium text-gray-800 text-right">${formatRupiah(subtotal)}</td>
                </tr>
            `;
        });
        document.getElementById('detail_items_body').innerHTML = itemsHtml;

        // Set summary
        document.getElementById('detail_total_qty').textContent = totalQty + ' item';
        document.getElementById('detail_subtotal').textContent = formatRupiah(laporan.totalbeli);
        document.getElementById('detail_pembayaran').textContent = formatRupiah(laporan.pembayaran);
        document.getElementById('detail_kembalian').textContent = formatRupiah(laporan.kembalian);

        // Show modal
        modal.classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

    function formatRupiah(angka) {
        if (!angka) return 'Rp. 0';
        return 'Rp. ' + parseInt(angka).toLocaleString('id-ID');
    }

    function printNota() {
        const printContent = document.getElementById('printArea').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Struk</title>
                <style>
                    @page {
                        size: 58mm auto;
                        margin: 0;
                    }
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { 
                        font-family: 'Courier New', monospace; 
                        font-size: 10px; 
                        padding: 5px;
                        width: 58mm;
                        margin: 0 auto;
                    }
                    .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }
                    .header h2 { font-size: 12px; font-weight: bold; margin-bottom: 2px; }
                    .header p { font-size: 9px; margin: 1px 0; }
                    .info { border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }
                    .info-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; }
                    .info-row span:first-child { color: #666; }
                    table { width: 100%; border-collapse: collapse; font-size: 9px; }
                    th { text-align: left; border-bottom: 1px solid #000; padding: 3px 0; font-size: 8px; }
                    td { padding: 3px 0; vertical-align: top; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    .summary { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
                    .summary-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; }
                    .summary-row.total { font-weight: bold; border-top: 1px solid #000; padding-top: 3px; margin-top: 3px; }
                    .footer { text-align: center; border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; font-size: 9px; }
                    .hidden { display: none; }
                    .px-6, .py-4, .px-2, .py-2 { padding: 0; }
                    .border-b, .border-t, .border-gray-100, .border-gray-200, .border-gray-300 { border: none; }
                    .space-y-2 > * + * { margin-top: 2px; }
                </style>
            </head>
            <body>
                ${printContent}
                <script>
                    window.onload = function() { 
                        window.print(); 
                        window.onafterprint = function() { window.close(); };
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>

<!-- Modal Detail Nota -->
<div id="modalDetail" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDetailModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <!-- Print Area -->
            <div id="printArea">
                <!-- Header Struk -->
                <div class="header text-center py-2 border-b border-gray-200 mb-1">
                    <h2 class="text-xl font-bold text-gray-800"><?= strtoupper(htmlspecialchars($nama_toko)) ?></h2>
                    <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($alamat_toko) ?></p>
                    <p class="text-xs text-gray-400">Telp: <?= htmlspecialchars($no_telp) ?></p>
                </div>

                <!-- Info Nota -->
                <div class="info px-6 py-4 border-b border-gray-100">
                    <div class="info-row">
                        <span class="text-gray-500">No. Nota</span>
                        <span id="detail_no_nota" class="font-mono font-bold text-gray-800"></span>
                    </div>
                    <div class="info-row">
                        <span class="text-gray-500">Kasir</span>
                        <span class="text-gray-800"><?= htmlspecialchars($kasir) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="text-gray-500">Tanggal</span>
                        <span id="detail_tanggal" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="info-row">
                        <span class="text-gray-500">Catatan</span>
                        <span id="detail_catatan" class="text-gray-800"></span>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="px-6 py-4">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-2 py-2 text-left text-xs font-medium text-gray-500">Produk</th>
                                <th class="px-2 py-2 text-center text-xs font-medium text-gray-500">Qty</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail_items_body">
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="summary px-6 py-4 border-t border-gray-200">
                    <div class="space-y-2 text-sm">
                        <div class="summary-row flex justify-between">
                            <span class="text-gray-500">Total Item</span>
                            <span id="detail_total_qty" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="summary-row flex justify-between">
                            <span class="text-gray-500">Subtotal</span>
                            <span id="detail_subtotal" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="summary-row flex justify-between">
                            <span class="text-gray-500">Pembayaran</span>
                            <span id="detail_pembayaran" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="summary-row total flex justify-between pt-2 border-t border-gray-300">
                            <span class="text-gray-700 font-bold">Kembalian</span>
                            <span id="detail_kembalian" class="font-bold text-gray-800"></span>
                        </div>
                    </div>
                </div>

                <!-- Footer Struk -->
                <div class="footer text-center py-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Terima kasih!</p>
                    <p class="text-xs text-gray-400 mt-1">Barang yang dibeli tidak dapat dikembalikan</p>
                </div>
            </div>

            <!-- Modal Buttons (Hidden when print) -->
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 no-print">
                <button onclick="closeDetailModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Tutup
                </button>
                <button onclick="printNota()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>
</div>