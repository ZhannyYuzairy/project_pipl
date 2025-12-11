<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? esc($title) . ' | ' : '' ?>Toko Z&Z — Owner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#ecfdf5',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        }
                    },
                    boxShadow: {
                        'soft': '0 18px 45px rgba(15,23,42,0.25)',
                    }
                }
            }
        };
    </script>

    <?= $this->renderSection('styles'); ?>
</head>

<body class="bg-slate-950 text-slate-100">
<div class="min-h-screen flex flex-col">

    <!-- ========== TOP NAV ========== -->
    <header class="border-b border-slate-800 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">

            <!-- BRAND -->
            <div class="flex items-center gap-3">
                <div class="h-7 w-7 rounded-xl bg-brand-600/20 border border-brand-600/40 flex items-center justify-center text-xs font-bold text-brand-500">
                    ZZ
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-semibold tracking-wide">
                        Toko <span class="text-brand-500">Z&Z</span>
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Panel Owner • Pengelolaan Sistem
                    </div>
                </div>
            </div>

            <!-- USER + MOBILE BUTTON -->
            <div class="flex items-center gap-3">
                <!-- Desktop info -->
                <div class="hidden sm:flex flex-col items-end leading-tight">
                    <span class="text-xs font-medium"><?= esc(session()->get('nama')); ?></span>
                    <span class="text-[11px] text-slate-400"><?= esc(session()->get('role')); ?></span>
                </div>

                <!-- Logout button -->
                <a href="<?= site_url('logout'); ?>"
                   class="hidden sm:inline-flex text-[11px] px-3 py-1.5 rounded-full border border-slate-600 hover:border-red-500 hover:text-red-300 transition">
                    Keluar
                </a>

                <!-- Mobile menu -->
                <button id="owner-sidebar-toggle"
                        class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-300 hover:bg-slate-800 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- ========== PAGE CONTENT WRAPPER ========== -->
    <div class="flex flex-1 relative">

        <?php
        $seg2 = service('uri')->getSegment(2);
        $seg3 = service('uri')->getSegment(3);
        ?>

        <!-- ========== SIDEBAR ========== -->
        <aside id="owner-sidebar"
               class="fixed inset-y-0 left-0 w-64 bg-slate-950/95 backdrop-blur border-r border-slate-800
                      transform -translate-x-full lg:translate-x-0 transition-all duration-200 z-40">

            <div class="h-full flex flex-col px-4 py-4">

                <!-- Mobile Profile -->
                <div class="flex lg:hidden justify-between mb-4">
                    <div class="leading-tight">
                        <div class="font-semibold text-xs"><?= esc(session()->get('nama')); ?></div>
                        <div class="text-[11px] text-slate-400"><?= esc(session()->get('role')); ?></div>
                    </div>
                    <a href="<?= site_url('logout'); ?>"
                       class="text-[11px] px-3 py-1.5 rounded-full border border-slate-600 hover:border-red-500 hover:text-red-300 transition">
                        Keluar
                    </a>
                </div>

                <!-- Sidebar Group Titles -->
                <div class="hidden lg:block mb-3">
                    <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500 mb-1">
                        Navigasi Utama
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <nav class="flex-1 space-y-1 text-sm">

                    <!-- Dashboard -->
                    <a href="<?= site_url('owner/dashboard'); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-xl
                              <?= $seg2 === 'dashboard'
                                  ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-black/30'
                                  : 'text-slate-200 hover:bg-slate-800'; ?>">
                        📊 <span>Dashboard</span>
                    </a>

                    <!-- PRODUK -->
                    <div class="pt-3 mt-3 border-t border-slate-800/80 text-[10px] uppercase tracking-[0.2em] text-slate-500">
                        Produk
                    </div>

                    <a href="<?= site_url('owner/products'); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-xl
                              <?= $seg2 === 'products'
                                  ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-black/30'
                                  : 'text-slate-200 hover:bg-slate-800'; ?>">
                        📦 <span>Kelola Produk</span>
                    </a>

                    <!-- KASIR -->
                    <div class="pt-3 mt-3 border-t border-slate-800/80 text-[10px] uppercase tracking-[0.2em] text-slate-500">
                        Kasir
                    </div>

                    <a href="<?= site_url('owner/cashiers'); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-xl
                              <?= $seg2 === 'cashiers'
                                  ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-black/30'
                                  : 'text-slate-200 hover:bg-slate-800'; ?>">
                        🧑‍💼 <span>Manajemen Kasir</span>
                    </a>

                    <!-- LAPORAN -->
                    <div class="pt-3 mt-3 border-t border-slate-800/80 text-[10px] uppercase tracking-[0.2em] text-slate-500">
                        Laporan
                    </div>

                    <a href="<?= site_url('owner/reports/penjualan'); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-xl
                              <?= $seg3 === 'penjualan'
                                  ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-black/30'
                                  : 'text-slate-200 hover:bg-slate-800'; ?>">
                        🧾 <span>Laporan Penjualan</span>
                    </a>

                    <a href="<?= site_url('owner/reports/stok'); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-xl
                              <?= $seg3 === 'stok'
                                  ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-black/30'
                                  : 'text-slate-200 hover:bg-slate-800'; ?>">
                        📊 <span>Laporan Stok</span>
                    </a>

                    <a href="<?= site_url('owner/reports/laba-rugi'); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-xl
                              <?= $seg3 === 'laba-rugi'
                                  ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-black/30'
                                  : 'text-slate-200 hover:bg-slate-800'; ?>">
                        💰 <span>Laba & Rugi</span>
                    </a>

                </nav>

                <div class="mt-5 text-[11px] text-slate-500">
                    © <?= date('Y'); ?> Toko Z&Z  
                    <span class="block text-slate-600">Sistem kasir & laporan canggih.</span>
                </div>
            </div>
        </aside>

        <!-- Backdrop Mobile -->
        <div id="owner-backdrop"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 opacity-0 pointer-events-none transition-opacity duration-200 lg:hidden">
        </div>

        <!-- ========== MAIN CONTENT AREA ========== -->
        <main class="flex-1 lg:ml-64">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 py-4 sm:py-6">

                <div class="bg-slate-50/95 rounded-3xl shadow-soft shadow-black/40 border border-slate-200 p-4 sm:p-6">

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-2 text-xs sm:text-sm">
                            <?= esc(session()->getFlashdata('success')); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-xs sm:text-sm">
                            <?= esc(session()->getFlashdata('error')); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Halaman konten -->
                    <?= $this->renderSection('content'); ?>

                    <footer class="mt-6 pt-4 border-t border-slate-200 text-[11px] text-slate-500 flex justify-between">
                        <span>© <?= date('Y'); ?> Toko Z&Z</span>
                        <span>Sistem kasir modern & laporan otomatis</span>
                    </footer>

                </div>
            </div>
        </main>

    </div>
</div>

<script>
    // Sidebar toggle
    const btnToggle = document.getElementById('owner-sidebar-toggle');
    const sidebar = document.getElementById('owner-sidebar');
    const backdrop = document.getElementById('owner-backdrop');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
    }
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
    }

    btnToggle?.addEventListener('click', () => {
        if (sidebar.classList.contains('-translate-x-full')) openSidebar();
        else closeSidebar();
    });

    backdrop?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
        } else {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>

<?= $this->renderSection('scripts'); ?>
</body>
</html>
