<?php
require_once 'helper/connection.php';

$data_user = getUserLogin();
$bikin_nota = mysqli_query($conn, "SELECT max(no_nota) as kodeTerbesar11 FROM laporan");
$datanya = mysqli_fetch_array($bikin_nota);
$kodenota = $datanya['kodeTerbesar11'];
$urutan = (int) substr($kodenota, 9, 3);
$urutan++;
$tgl = date("jnyGi");
$huruf = "APC";
$kodeCart = $huruf . $tgl . sprintf("%03s", $urutan);

// Get cart from session
$cart_items = $_SESSION['cart'] ?? [];
$cart_count = count($cart_items);

// Store info for print
$nama_toko = $data_user['nama_toko'] ?? 'Toko Saya';
$alamat_toko = $data_user['alamat'] ?? 'Alamat Toko';
$no_telp = $data_user['telepon'] ?? '-';
$kasir = $data_user['username'] ?? 'Admin';

?>

<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800">Transaksi Penjualan</h1>
        <p class="text-xs md:text-sm text-gray-500 mt-1">Proses transaksi dan pembayaran pelanggan</p>
    </div>

    <div class="flex flex-col lg:grid lg:grid-cols-3 gap-4 md:gap-6 items-start">
        <!-- Kolom Kiri: Informasi Nota + Form Input -->
        <div class="space-y-4">
            <!-- Informasi Nota -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Informasi Nota
                </h2>
                <div class="text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. Nota</span>
                        <span class="font-mono font-medium text-gray-800"><?php echo $kodeCart; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span id='jam' class="font-mono text-gray-800"><?php echo date("d-m-Y H:i:s"); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kasir</span>
                        <span class="font-mono text-gray-800"><?php echo $data_user['username']; ?></span>
                    </div>
                </div>
            </div>

            <!-- Form Input Produk -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Input Produk
                </h2>
                <form class='grid grid-cols-2 gap-3'>
                    <div class="col-span-2">
                        <div class="relative w-full" id="dropdown">
                            <label for="searchInput" class="block mb-1 text-xs text-gray-600">Kode Produk <span class="text-blue-500 font-medium">(F1)</span></label>
                            <input
                                type="text"
                                id="searchInput"
                                class="w-full rounded-lg border border-blue-400 hover:border-blue-500 focus:border-blue-600 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-100"
                                autocomplete="off"
                                placeholder="Ketik kode produk..."
                                autofocus>
                            <ul
                                id="list"
                                class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-lg border bg-white shadow-xl border-blue-400 text-sm hidden">
                            </ul>
                            <input type="hidden" name="produk_id" id="produkValue">
                        </div>
                    </div>
                    <div>
                        <label for='nama_produk' class='block mb-1 text-xs text-gray-600'>Nama Produk</label>
                        <input type='text' name='nama_produk' class='disabled:bg-gray-50 border border-gray-200 py-2 px-3 rounded-lg w-full text-sm' disabled />
                    </div>
                    <div>
                        <label for='harga' class='block mb-1 text-xs text-gray-600'>Harga</label>
                        <input type='text' name='harga' class='disabled:bg-gray-50 border border-gray-200 py-2 px-3 rounded-lg w-full text-sm' disabled />
                    </div>
                    <div>
                        <label for='stock' class='block mb-1 text-xs text-gray-600'>Stock</label>
                        <input type='text' name='stock' class='disabled:bg-gray-50 border border-gray-200 py-2 px-3 rounded-lg w-full text-sm' disabled />
                    </div>
                    <div>
                        <label for='qty' class='block mb-1 text-xs text-gray-600'>Qty</label>
                        <input type='text' name='qty' class='disabled:bg-gray-50 border border-gray-200 py-2 px-3 rounded-lg w-full text-sm' disabled />
                    </div>
                    <div class="col-span-2">
                        <label for='subtotal' class='block mb-1 text-xs text-gray-600'>Subtotal</label>
                        <input type='text' name='subtotal' class='bg-blue-50 border border-blue-200 py-2 px-3 rounded-lg w-full text-sm font-semibold text-blue-700' disabled />
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Tabel Keranjang + Pembayaran -->
        <div class="w-full lg:col-span-2 space-y-4">
            <!-- Tabel Keranjang -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Keranjang Belanja
                    </h2>
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <div class="max-h-[300px] overflow-y-auto overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 bg-gray-50 uppercase border-b sticky top-0 z-10">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-medium">#</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Kode</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Produk</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Harga</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Qty</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Subtotal</th>
                                    <th scope="col" class="px-4 py-3 font-medium">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($cart_items as $row): ?>
                                    <tr class="text-xs border-b border-gray-100 hover:bg-blue-50/50 transition-colors">
                                        <td class="px-4 py-2.5 font-medium text-gray-600">
                                            <?php echo $no++; ?>
                                        </td>
                                        <td class="px-4 py-2.5 font-mono text-gray-700">
                                            <?php echo $row['kode_produk']; ?>
                                        </td>
                                        <td class="px-4 py-2.5 text-gray-700">
                                            <?php echo $row['nama_produk']; ?>
                                        </td>
                                        <td class="px-4 py-2.5 text-gray-700">
                                            Rp. <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="px-4 py-2.5 text-gray-700">
                                            <?php echo $row['quantity']; ?>
                                        </td>
                                        <td class="px-4 py-2.5 font-medium text-gray-800">
                                            Rp. <?php echo number_format($row['harga_jual'] * $row['quantity'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <button onclick="hapusItem(<?php echo $row['idproduk']; ?>)" class="text-xs bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1 rounded-md transition-colors">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card List -->
                <div class="md:hidden divide-y divide-gray-100">
                    <?php
                    foreach ($cart_items as $row): ?>
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-800"><?php echo $row['nama_produk']; ?></p>
                                    <p class="text-xs text-gray-500 font-mono"><?php echo $row['kode_produk']; ?></p>
                                </div>
                                <button onclick="hapusItem(<?php echo $row['idproduk']; ?>)" class="text-xs bg-red-100 hover:bg-red-200 text-red-600 px-2 py-1 rounded-md">Hapus</button>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>Rp. <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?> x <?php echo $row['quantity']; ?></span>
                                <span class="font-semibold text-gray-800">Rp. <?php echo number_format($row['harga_jual'] * $row['quantity'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($cart_count == 0): ?>
                        <div class="p-8 text-center text-gray-400 text-sm">
                            Keranjang kosong
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Total -->
                <div class="px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600">
                    <div class="flex justify-end">
                        <h2 id="total" class="text-xl font-bold text-white">
                            Total: <?php
                                    $total = 0;
                                    foreach ($cart_items as $item) {
                                        $total += $item['harga_jual'] * $item['quantity'];
                                    }
                                    echo formatRupiah($total);
                                    ?>
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Catatan & Pembayaran -->
            <div class="grid md:grid-cols-2 gap-4">
                <!-- Catatan -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Transaksi</label>
                    <textarea class="text-sm border border-gray-200 rounded-lg px-3 py-2 w-full focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" rows='4' placeholder="Catatan transaksi (jika ada)"></textarea>
                </div>

                <!-- Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Pembayaran</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Bayar <span class="text-blue-500 font-medium">(F2)</span></label>
                            <input type="text" id="pembayaran" placeholder="0" class="border border-gray-200 px-3 py-2 rounded-lg w-full text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100" />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Kembalian</label>
                            <input type="text" placeholder="0" id='kembalian' disabled class="bg-green-50 border border-green-200 px-3 py-2 rounded-lg w-full text-sm font-semibold text-green-700" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 mt-4 pt-3 border-t border-gray-100">
                        <button id="btnCetak" class="bg-gray-100 hover:bg-gray-200 px-4 py-2.5 sm:py-2 text-sm font-medium text-gray-600 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" onclick="cetakStruk()" disabled>Cetak</button>
                        <button id="btnReset" class="bg-red-100 hover:bg-red-200 px-4 py-2.5 sm:py-2 text-sm font-medium text-red-600 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" onclick="resetbutton()" <?= $cart_count == 0 ? 'disabled' : '' ?>>Reset</button>
                        <button id="btnSimpan" class="bg-green-500 hover:bg-green-600 px-4 py-2.5 sm:py-2 text-sm font-medium text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" onclick="simpan()" disabled>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let cartCount = <?= (int)$cart_count ?>; // Ubah dari const ke let agar bisa diupdate

    function checkCartEmpty() {
        // Cek cart count terkini
        if (cartCount === 0 || cartCount <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Silakan tambahkan produk terlebih dahulu.',
                confirmButtonColor: '#2563eb'
            });
            return true;
        }
        return false;
    }

    function checkPembayaran() {
        const pembayaranStr = document.getElementById('pembayaran').value.replace(/\./g, '');
        const pembayaranNum = parseFloat(pembayaranStr) || 0;

        if (!pembayaranStr || pembayaranNum <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Pembayaran Belum Diisi',
                text: 'Silakan masukkan nominal pembayaran terlebih dahulu!',
                confirmButtonColor: '#2563eb'
            });
            return false;
        }

        const totalText = document.getElementById('total').textContent;
        const totalAmount = parseFloat(totalText.replace(/[^0-9]+/g, '')) || 0;

        if (pembayaranNum < totalAmount) {
            const kurang = totalAmount - pembayaranNum;
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Tidak Cukup!',
                html: `<div style="text-align: left;">
                    <p><strong>Total:</strong> Rp ${formatRupiah(totalAmount)}</p>
                    <p><strong>Bayar:</strong> Rp ${formatRupiah(pembayaranNum)}</p>
                    <p style="color: #dc2626;"><strong>Kurang:</strong> Rp ${formatRupiah(kurang)}</p>
                </div>`,
                confirmButtonColor: '#dc2626'
            });
            return false;
        }

        return true;
    }

    function updateButtonState() {
        const pembayaranInput = document.getElementById('pembayaran').value.replace(/\./g, '');
        const pembayaranNum = parseFloat(pembayaranInput) || 0;

        const totalText = document.getElementById('total').textContent;
        const totalAmount = parseFloat(totalText.replace(/[^0-9]+/g, '')) || 0;

        const hasPembayaran = pembayaranNum > 0 && pembayaranNum >= totalAmount;
        const hasCart = cartCount > 0;

        document.getElementById('btnCetak').disabled = !(hasCart && hasPembayaran);
        document.getElementById('btnSimpan').disabled = !(hasCart && hasPembayaran);

        document.getElementById('btnReset').disabled = !hasCart;
    }

    function simpan() {
        if (checkCartEmpty()) return;
        if (!checkPembayaran()) return;

        const totalText = document.getElementById('total').textContent.replace('Total: ', '');
        const pembayaran = document.getElementById('pembayaran').value;
        const kembalian = document.getElementById('kembalian').value;

        Swal.fire({
            title: 'Konfirmasi Transaksi',
            html: `<div style="text-align: left;">
                <p><strong>Total:</strong> ${totalText}</p>
                <p><strong>Pembayaran:</strong> Rp ${pembayaran}</p>
                <p><strong>Kembalian:</strong> Rp ${kembalian}</p>
            </div>
            <p style="margin-top: 15px;">Simpan transaksi ini?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                prosesTransaksi();
            }
        });
    }

    function prosesTransaksi() {
        // ambil no nota
        const no_nota = '<?php echo $kodeCart; ?>';
        fetch('index.php?q=save_transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    no_nota: no_nota,
                    catatan: document.querySelector('textarea').value,
                    total_bayar: document.getElementById('pembayaran').value.replace(/\./g, ''),
                    kembalian: document.getElementById('kembalian').value.replace(/\./g, ''),
                    totalbeli: <?php echo (int)$total_row['total']; ?>,
                    id_pelanggan: 0
                })
            }).then(res => res.text())
            .then(text => {
                console.log('RAW RESPONSE:', text);
                return JSON.parse(text);
            })
            .then(data => {
                if (data.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message,
                        confirmButtonColor: '#dc2626'
                    }).then(() => {
                        window.location.reload();
                    });
                    return;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Transaksi Berhasil!',
                    text: 'Apakah Anda ingin mencetak struk?',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-print"></i> Cetak Struk',
                    cancelButtonText: 'Tidak, Terima Kasih',
                    allowOutsideClick: false,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        cetakStrukSebelumReload();
                    } else {
                        window.location.reload();
                    }
                });
            })
            .catch(err => {
                console.error('ERROR:', err);
            });
    }

    function resetbutton() {
        if (checkCartEmpty()) return;

        Swal.fire({
            title: 'Reset Keranjang?',
            text: 'Semua item di keranjang akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch('index.php?q=reset_cart', {
                    method: 'POST'
                })
                .then(res => res.text())
                .then(text => {
                    console.log('RAW RESPONSE:', text);
                    return JSON.parse(text);
                })
                .then(data => {
                    console.log('JSON:', data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Keranjang telah direset',
                        confirmButtonColor: '#2563eb',
                        timer: 1000
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(err => {
                    console.error('ERROR:', err);
                });
        });
    }

    const dropdown = document.getElementById("dropdown")
    const input = document.getElementById("searchInput")
    const list = document.getElementById("list")
    const hiddenInput = document.getElementById("produkValue")
    const inputPembayaran = document.getElementById("pembayaran")

    window.addEventListener('DOMContentLoaded', function() {
        input.focus();
    });

    let debounceTimer = null

    input.addEventListener("keyup", function() {
        const keyword = this.value.trim()

        clearTimeout(debounceTimer)

        if (keyword.length < 2) {
            list.innerHTML = ""
            list.classList.add("hidden")
            return
        }

        debounceTimer = setTimeout(() => {
            fetchProducts(keyword)
        }, 400)
    })

    function fetchProducts(keyword) {
        fetch(`index.php?q=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => renderList(data))
            .catch((R) => {
                console.log(R)
                list.innerHTML = `<li class="px-4 py-2 text-red-500">Gagal memuat data</li>`
                list.classList.remove("hidden")
            })
    }

    function renderList(products) {
        if (products.length === 0) {
            list.innerHTML = `<li class="px-4 py-2 text-gray-500">Produk tidak ditemukan</li>`
        } else {
            list.innerHTML = products.map(p => `
      <li
        class="cursor-pointer text-sm flex justify-between items-center px-4 py-2 hover:bg-gray-100"
        onclick="selectProduct('${p.idproduk}', '${p.kode_produk}', '${p.nama}', ${p.harga_jual}, '${p.stock}')"
      >
        ${p.nama}
        <span class="text-xs text-gray-500 font-mono">
        ${p.kode_produk}
        </span>
      </li>
    `).join("")
        }
        list.classList.remove("hidden")
    }

    function selectProduct(id_produk, id, nama, harga, stock) {
        input.value = id
        hiddenInput.value = id

        const hargaNum = parseFloat(harga);

        const form = input.closest("form")
        form.elements['nama_produk'].value = nama
        form.elements['harga'].value = hargaNum
        form.elements['stock'].value = stock
        form.elements['qty'].value = 1
        form.elements['subtotal'].value = hargaNum

        tambah_keranjang(id_produk, id, nama, hargaNum, 1)

        list.classList.add("hidden")
    }

    function tambah_keranjang(id_produk, id, nama, harga, qty) {
        const hargaNum = parseFloat(harga);

        fetch('index.php?q=add_to_cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idproduk: id_produk,
                    kode_produk: id,
                    nama_produk: nama,
                    harga: hargaNum,
                    qty: qty
                })
            }).then(res => res.text())
            .then(text => {
                console.log('RAW RESPONSE:', text);
                return JSON.parse(text);
            })
            .then(data => {
                if (data.status === 'success') {
                    updateCartDisplay();

                    const form = input.closest("form");
                    form.reset();
                    input.value = '';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(err => {
                console.error('ERROR:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menambahkan produk',
                    confirmButtonColor: '#dc2626'
                });
            });
    }

    function updateCartDisplay() {
        fetch('index.php?q=get_cart')
            .then(res => res.json())
            .then(cartData => {
                cartCount = cartData.items.length;

                updateCartTable(cartData);

                document.getElementById('total').textContent = 'Total: Rp. ' + new Intl.NumberFormat('id-ID').format(cartData.total);

                updateButtonState();
            })
            .catch(err => {
                console.error('Error updating cart:', err);
            });
    }

    function updateCartTable(cartData) {
        const desktopTable = document.querySelector('.hidden.md\\:block tbody');
        if (desktopTable) {
            if (cartData.items.length === 0) {
                desktopTable.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Keranjang kosong</td></tr>';
            } else {
                desktopTable.innerHTML = cartData.items.map((item, index) => `
                    <tr class="text-xs border-b border-gray-100 hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-2.5 font-medium text-gray-600">${index + 1}</td>
                        <td class="px-4 py-2.5 font-mono text-gray-700">${item.kode_produk}</td>
                        <td class="px-4 py-2.5 text-gray-700">${item.nama_produk}</td>
                        <td class="px-4 py-2.5 text-gray-700">Rp. ${new Intl.NumberFormat('id-ID').format(item.harga_jual)}</td>
                        <td class="px-4 py-2.5 text-gray-700">${item.quantity}</td>
                        <td class="px-4 py-2.5 font-medium text-gray-800">Rp. ${new Intl.NumberFormat('id-ID').format(item.harga_jual * item.quantity)}</td>
                        <td class="px-4 py-2.5">
                            <button onclick="hapusItem(${item.idproduk})" class="text-xs bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1 rounded-md transition-colors">Hapus</button>
                        </td>
                    </tr>
                `).join('');
            }
        }

        const mobileList = document.querySelector('.md\\:hidden.divide-y');
        if (mobileList) {
            if (cartData.items.length === 0) {
                mobileList.innerHTML = '<div class="p-8 text-center text-gray-400 text-sm">Keranjang kosong</div>';
            } else {
                mobileList.innerHTML = cartData.items.map(item => `
                    <div class="p-4 space-y-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-800">${item.nama_produk}</p>
                                <p class="text-xs text-gray-500 font-mono">${item.kode_produk}</p>
                            </div>
                            <button onclick="hapusItem(${item.idproduk})" class="text-xs bg-red-100 hover:bg-red-200 text-red-600 px-2 py-1 rounded-md">Hapus</button>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Rp. ${new Intl.NumberFormat('id-ID').format(item.harga_jual)} x ${item.quantity}</span>
                            <span class="font-semibold text-gray-800">Rp. ${new Intl.NumberFormat('id-ID').format(item.harga_jual * item.quantity)}</span>
                        </div>
                    </div>
                `).join('');
            }
        }

        updateButtonState();
    }

    document.addEventListener("click", function(e) {
        if (!dropdown.contains(e.target)) {
            list.classList.add("hidden")
        }
    })

    input.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();

            const keyword = input.value.trim();
            if (!keyword) return;

            fetch(`index.php?q=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 1) {
                        const p = data[0];
                        selectProduct(p.idproduk, p.kode_produk, p.nama, p.harga_jual, p.stock);
                        input.value = '';
                    } else if (data.length > 1) {
                        renderList(data);
                    } else {
                        list.innerHTML = `<li class="px-4 py-2 text-red-500">Produk tidak ditemukan</li>`;
                        list.classList.remove("hidden");
                        setTimeout(() => {
                            list.classList.add("hidden");
                            input.value = '';
                        }, 1500);
                    }
                })
                .catch(err => {
                    console.error('ERROR:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat produk',
                        confirmButtonColor: '#dc2626'
                    });
                });
        }
    });

    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            list.classList.add("hidden")
        }

        if (e.key === "Tab") {
            e.preventDefault();
            input.focus();
            input.select();
        }

        if (e.key === "F1") {
            e.preventDefault();
            input.focus();
            input.select();
        }

        if (e.key === "F2") {
            e.preventDefault();
            document.getElementById('pembayaran').focus();
            document.getElementById('pembayaran').select();
        }

        if (e.key === "F3") {
            e.preventDefault();
            cetakStruk();
        }
    });

    document.getElementById('pembayaran').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            simpan();
        }
    });

    // Hapus item dari keranjang
    function hapusItem(idproduk) {
        Swal.fire({
            title: 'Hapus Item?',
            text: 'Item akan dihapus dari keranjang',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                hapusItemProses(idproduk);
            }
        });
    }

    function hapusItemProses(idproduk) {
        fetch('index.php?q=delete_cart_item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idproduk: idproduk
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    fetch('index.php?q=get_cart')
                        .then(res => res.json())
                        .then(cartData => {
                            cartCount = cartData.items.length;

                            updateCartTable(cartData);

                            document.getElementById('total').textContent = 'Total: Rp. ' + new Intl.NumberFormat('id-ID').format(cartData.total);

                            updateButtonState();
                        });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal menghapus item',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(err => {
                console.error('ERROR:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan',
                    confirmButtonColor: '#dc2626'
                });
            });
    }

    function updateJam() {
        const now = new Date();

        const pad = n => n.toString().padStart(2, '0');

        const tanggal = pad(now.getDate()) + '-' +
            pad(now.getMonth() + 1) + '-' +
            now.getFullYear();;
        list.addEventListener("click", e => e.stopPropagation());
        const waktu = pad(now.getHours()) + ':' +
            pad(now.getMinutes()) + ':' +
            pad(now.getSeconds());

        document.getElementById('jam').textContent = `${tanggal} ${waktu}`;
    }

    updateJam();
    setInterval(updateJam, 1000);

    input.addEventListener("click", e => e.stopPropagation());
    list.addEventListener("click", e => e.stopPropagation());

    document.getElementById('pembayaran').addEventListener('input', e => {
        const pembayaranStr = e.target.value.replace(/\./g, '');
        const pembayaranNum = parseFloat(pembayaranStr) || 0;

        const totalText = document.getElementById('total').textContent;
        const totalAmount = parseFloat(totalText.replace(/[^0-9]+/g, '')) || 0;

        const kembalian = pembayaranNum - totalAmount;

        if (kembalian >= 0) {
            document.getElementById('kembalian').value = formatRupiah(kembalian);
            document.getElementById('kembalian').style.color = '';
            document.getElementById('kembalian').style.background = '';
        } else {
            document.getElementById('kembalian').value = '-' + formatRupiah(Math.abs(kembalian));
            document.getElementById('kembalian').style.color = '#dc2626';
            document.getElementById('kembalian').style.background = '#fee2e2';
        }

        updateButtonState();
    });

    const pembayarann = document.getElementById('pembayaran');

    pembayarann.addEventListener('input', function() {
        // Ambil angka saja
        let value = this.value.replace(/\D/g, '');

        if (!value) {
            this.value = '';
            return;
        }

        // Format ke Rupiah
        this.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function cetakStruk() {
        if (checkCartEmpty()) return;
        if (!checkPembayaran()) return;

        const noNota = '<?= $kodeCart ?>';
        const tanggal = document.getElementById('jam').textContent;
        const kasir = '<?= htmlspecialchars($kasir) ?>';
        const namaToko = '<?= strtoupper(htmlspecialchars($nama_toko)) ?>';
        const alamatToko = '<?= htmlspecialchars($alamat_toko) ?>';
        const noTelp = '<?= htmlspecialchars($no_telp) ?>';
        const catatan = document.querySelector('textarea').value || '-';
        const pembayaran = document.getElementById('pembayaran').value || '0';
        const kembalian = document.getElementById('kembalian').value || '0';
        const totalText = document.getElementById('total').textContent.replace('Total: ', '');

        // Get cart items from table
        let itemsHtml = '';
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                const nama = cells[2].textContent.trim();
                const qty = cells[4].textContent.trim();
                const subtotal = cells[5].textContent.trim();
                itemsHtml += `
                    <tr>
                        <td style="padding: 3px 0; font-size: 9px;">${nama}</td>
                        <td style="padding: 3px 0; font-size: 9px; text-align: center;">${qty}</td>
                        <td style="padding: 3px 0; font-size: 9px; text-align: right;">${subtotal}</td>
                    </tr>
                `;
            }
        });

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
                    table { width: 100%; border-collapse: collapse; }
                    th { text-align: left; border-bottom: 1px solid #000; padding: 3px 0; font-size: 8px; }
                    .summary { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
                    .summary-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; }
                    .summary-row.total { font-weight: bold; border-top: 1px solid #000; padding-top: 3px; margin-top: 3px; }
                    .footer { text-align: center; border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; font-size: 9px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>${namaToko}</h2>
                    <p>${alamatToko}</p>
                    <p>Telp: ${noTelp}</p>
                </div>
                
                <div class="info">
                    <div class="info-row"><span>No. Nota</span><span>${noNota}</span></div>
                    <div class="info-row"><span>Kasir</span><span>${kasir}</span></div>
                    <div class="info-row"><span>Tanggal</span><span>${tanggal}</span></div>
                    <div class="info-row"><span>Catatan</span><span>${catatan}</span></div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>
                
                <div class="summary">
                    <div class="summary-row"><span>Total</span><span>${totalText}</span></div>
                    <div class="summary-row"><span>Pembayaran</span><span>Rp. ${pembayaran}</span></div>
                    <div class="summary-row total"><span>Kembalian</span><span>Rp. ${kembalian}</span></div>
                </div>
                
                <div class="footer">
                    <p>Terima kasih!</p>
                    <p style="font-size: 8px; margin-top: 2px;">Barang yang dibeli tidak dapat dikembalikan</p>
                </div>
                
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

    function cetakStrukSebelumReload() {
        const noNota = '<?= $kodeCart ?>';
        const tanggal = document.getElementById('jam').textContent;
        const kasir = '<?= htmlspecialchars($kasir) ?>';
        const namaToko = '<?= strtoupper(htmlspecialchars($nama_toko)) ?>';
        const alamatToko = '<?= htmlspecialchars($alamat_toko) ?>';
        const noTelp = '<?= htmlspecialchars($no_telp) ?>';
        const catatan = document.querySelector('textarea').value || '-';
        const pembayaran = document.getElementById('pembayaran').value || '0';
        const kembalian = document.getElementById('kembalian').value || '0';
        const totalText = document.getElementById('total').textContent.replace('Total: ', '');

        let itemsHtml = '';
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                const nama = cells[2].textContent.trim();
                const qty = cells[4].textContent.trim();
                const subtotal = cells[5].textContent.trim();
                itemsHtml += `
                    <tr>
                        <td style="padding: 3px 0; font-size: 9px;">${nama}</td>
                        <td style="padding: 3px 0; font-size: 9px; text-align: center;">${qty}</td>
                        <td style="padding: 3px 0; font-size: 9px; text-align: right;">${subtotal}</td>
                    </tr>
                `;
            }
        });

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Struk - ${noNota}</title>
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
                    table { width: 100%; border-collapse: collapse; }
                    th { text-align: left; border-bottom: 1px solid #000; padding: 3px 0; font-size: 8px; }
                    .summary { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
                    .summary-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; }
                    .summary-row.total { font-weight: bold; border-top: 1px solid #000; padding-top: 3px; margin-top: 3px; }
                    .footer { text-align: center; border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; font-size: 9px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>${namaToko}</h2>
                    <p>${alamatToko}</p>
                    <p>Telp: ${noTelp}</p>
                </div>
                
                <div class="info">
                    <div class="info-row"><span>No. Nota</span><span>${noNota}</span></div>
                    <div class="info-row"><span>Tanggal</span><span>${tanggal}</span></div>
                    <div class="info-row"><span>Kasir</span><span>${kasir}</span></div>
                    ${catatan !== '-' ? '<div class="info-row"><span>Catatan</span><span>' + catatan + '</span></div>' : ''}
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>
                
                <div class="summary">
                    <div class="summary-row"><span>Total</span><span>${totalText}</span></div>
                    <div class="summary-row"><span>Pembayaran</span><span>Rp. ${pembayaran}</span></div>
                    <div class="summary-row total"><span>Kembalian</span><span>Rp. ${kembalian}</span></div>
                </div>
                
                <div class="footer">
                    <p>Terima kasih atas kunjungan Anda!</p>
                    <p style="font-size: 8px; margin-top: 2px;">Barang yang dibeli tidak dapat dikembalikan</p>
                </div>
                
                <script>
                    window.onload = function() { 
                        window.print(); 
                        window.onafterprint = function() { 
                            window.close();
                            // Reload parent window setelah cetak
                            window.opener.location.reload();
                        };
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>