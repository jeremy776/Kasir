<?php include 'template/header.php';
require_once 'helper/auth.php';
userOnly();

$user_data = getUserLogin();

$pages = [
    'index.php' => 'Transaksi',
    'produk.php' => 'Data Produk',
    'kategori.php' => 'Kategori',
    'laporan.php' => 'Data Laporan',
    'settings.php' => 'Pengaturan',
];


?>

<div class="flex min-h-screen bg-gray-50">

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out" data-collapsed="false">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h1 id="sidebar-title" class="text-lg font-semibold text-gray-800 tracking-tight truncate transition-all duration-200"><?php echo $user_data['nama_toko']; ?></h1>
            <!-- Mobile close button -->
            <button class="md:hidden text-gray-400 hover:text-gray-600 p-1" onclick="toggleSidebar()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Desktop collapse button -->
            <button id="collapse-btn" class="hidden md:flex text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg transition-colors" onclick="toggleCollapse()" title="Kecilkan sidebar">
                <svg id="collapse-icon" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 80px);">
            <?php foreach ($pages as $file => $label): ?>
                <?php $isActive = basename($_SERVER['PHP_SELF']) === $file; ?>
                <a href="<?php echo $file; ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors <?php echo $isActive ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'; ?>" title="<?php echo $label; ?>">
                    <?php
                    switch ($label) {
                        case 'Transaksi':
                            echo '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>';
                            break;
                        case 'Data Produk':
                            echo '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>';
                            break;
                        case 'Kategori':
                            echo '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>';
                            break;
                        case 'Data Laporan':
                            echo '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                            break;
                        case 'Pengaturan':
                            echo '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>';
                            break;
                        default:
                            echo '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    }
                    ?>
                    <span class="sidebar-text truncate transition-all duration-200"><?php echo $label; ?></span>
                </a>
            <?php endforeach; ?>
            
            <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="logout.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-600 hover:bg-red-50 transition-colors" title="Keluar">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="sidebar-text transition-all duration-200">Keluar</span>
                </a>
            </div>
        </nav>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>
    
    <main id="main-content" class="flex-1 md:ml-64 min-h-screen transition-all duration-300">
        <!-- Mobile Header -->
        <div class="sticky top-0 z-30 bg-white border-b border-gray-200 px-4 py-3 md:hidden">
            <div class="flex items-center justify-between">
                <button class="p-2 -ml-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg" onclick="toggleSidebar()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-sm font-semibold text-gray-800"><?php echo $user_data['nama_toko']; ?></h1>
                <div class="w-10"></div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
        <?php
        if (isset($content)) {
            include $content;
        }
        ?>
        </div>
        
        <!-- Footer -->
        <footer class="mt-auto border-t border-gray-200 bg-white px-4 py-4 text-center">
            <p class="text-xs text-gray-500">© <?php echo date('Y'); ?> LazyPeople. All rights reserved.</p>
        </footer>
    </main>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }
    
    // Desktop collapse/expand functionality
    function toggleCollapse() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarTitle = document.getElementById('sidebar-title');
        const collapseIcon = document.getElementById('collapse-icon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const isCollapsed = sidebar.dataset.collapsed === 'true';
        
        if (isCollapsed) {
            // Expand
            sidebar.style.width = '16rem'; // w-64
            mainContent.style.marginLeft = '16rem';
            sidebarTitle.style.opacity = '1';
            sidebarTitle.style.width = 'auto';
            collapseIcon.style.transform = 'rotate(0deg)';
            sidebarTexts.forEach(text => {
                text.style.opacity = '1';
                text.style.width = 'auto';
                text.style.display = 'inline';
            });
            sidebar.dataset.collapsed = 'false';
            localStorage.setItem('sidebarCollapsed', 'false');
        } else {
            // Collapse
            sidebar.style.width = '4.5rem'; // w-18
            mainContent.style.marginLeft = '4.5rem';
            sidebarTitle.style.opacity = '0';
            sidebarTitle.style.width = '0';
            collapseIcon.style.transform = 'rotate(180deg)';
            sidebarTexts.forEach(text => {
                text.style.opacity = '0';
                text.style.width = '0';
                text.style.display = 'none';
            });
            sidebar.dataset.collapsed = 'true';
            localStorage.setItem('sidebarCollapsed', 'true');
        }
    }
    
    // Restore sidebar state from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true' && window.innerWidth >= 768) {
            toggleCollapse();
        }
    });
    
    // Close sidebar on window resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('overlay').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        } else {
            // Reset to full width on mobile
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.style.width = '';
            mainContent.style.marginLeft = '';
        }
    });
</script>

</body>

</html>