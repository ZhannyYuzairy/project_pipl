<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? esc($title) . ' | ' : '' ?>Toko Z&amp;Z — Kasir</title>
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
                            100: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                    },
                    boxShadow: {
                        'soft': '0 18px 45px rgba(15,23,42,0.35)',
                    },
                    borderRadius: {
                        '3xl': '1.75rem',
                    }
                }
            }
        }
    </script>

    <?= $this->renderSection('styles'); ?>
</head>
<body class="bg-slate-950 text-slate-100">

<div class="min-h-screen flex flex-col">

    <!-- Top bar -->
    <header class="border-b border-slate-800 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-2xl bg-brand-600/15 border border-brand-600/50 flex items-center justify-center text-xs font-bold text-brand-500 shadow-sm shadow-brand-500/40">
                    ZZ
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-semibold tracking-wide">
                        Toko <span class="text-brand-500">Z&amp;Z</span>
                    </div>
                    <div class="text-[11px] text-slate-400">
                        Panel Kasir • Transaksi harian
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Info user (desktop) -->
                <div class="hidden sm:flex flex-col items-end leading-tight text-right">
                    <span class="text-xs font-medium">
                        <?= esc(session()->get('nama') ?? 'Kasir'); ?>
                    </span>
                    <span class="text-[11px] text-slate-400 capitalize">
                        <?= esc(session()->get('role') ?? 'kasir'); ?>
                    </span>
                </div>

                <a href="<?= site_url('logout'); ?>"
                   class="hidden sm:inline-flex text-[11px] px-3 py-1.5 rounded-full border border-slate-600
                          hover:border-red-500 hover:text-red-300 transition-colors">
                    Keluar
                </a>

                <!-- Mobile menu button -->
                <button id="kasir-sidebar-toggle"
                        class="inline-flex lg:hidden items-center justify-center rounded-md p-2
                               text-slate-300 hover:bg-slate-800 hover:text-white focus:outline-none">
                    <span class="sr-only">Toggle menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6.75h16M4 12h16M4 17.25h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <div class="flex flex-1 relative">

        <?php $seg2 = service('uri')->getSegment(2); ?>

        <!-- Sidebar -->
        <aside id="kasir-sidebar"
               class="fixed inset-y-0 left-0 w-64 bg-slate-950/95 backdrop-blur border-r border-slate-800
                      transform -translate-x-full lg:translate-x-0 transition-transform duration-200 z-40">
            <div class="h-full flex flex-col px-4 py-4">
                <!-- Profile (mobile) -->
                <div class="flex items-center justify-between mb-4 lg:hidden">
                    <div class="text-xs leading-tight">
                        <div class="font-semibold">
                            <?= esc(session()->get('nama') ?? 'Kasir'); ?>
                        </div>
                        <div class="text-[11px] text-slate-400 capitalize">
                            <?= esc(session()->get('role') ?? 'kasir'); ?>
                        </div>
                    </div>
                    <a href="<?= site_url('logout'); ?>"

                       class="text-[11px] px-3 py-1.5 rounded-full border border-slate-600
                              hover:border-red-500 hover:text-red-300 transition-colors">
                        Keluar
                    </a>
                </div>

                <!-- Intro sidebar -->
                <div class="mb-4 hidden lg:block">
                    <div class="text-[11px] uppercase tracking-[0.22em] text-slate-500 mb-1">
                        Menu Kasir
                    </div>
                    <p class="text-[11px] text-slate-500">
                        Akses cepat ke halaman utama, transaksi berjalan, dan riwayat penjualan kamu.
                    </p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 mt-1 space-y-4 text-sm">

                    <!-- Kategori: Utama -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500">
                                Utama
                            </span>
                            <span class="h-px flex-1 ml-2 bg-slate-800/70"></span>
                        </div>
                        <div class="space-y-1">
                            <a href="<?= site_url('kasir/dashboard'); ?>"
                               class="flex items-center gap-2 px-3 py-2 rounded-xl
                                      <?= $seg2 === 'dashboard'
                                          ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-slate-900/40'
                                          : 'text-slate-200 hover:bg-slate-800/80'; ?>">
                                <span class="text-[15px]">🏠</span>
                                <div class="flex flex-col leading-tight">
                                    <span>Dashboard</span>
                                    <span class="text-[11px] text-slate-400 hidden md:inline">
                                        Ringkasan singkat aktivitas kasir
                                    </span>
                                </div>
                            </a>

                            <a href="<?= site_url('kasir/pos'); ?>"
                               class="flex items-center gap-2 px-3 py-2 rounded-xl
                                      <?= $seg2 === 'pos'
                                          ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-slate-900/40'
                                          : 'text-slate-200 hover:bg-slate-800/80'; ?>">
                                <span class="text-[15px]">🧾</span>
                                <div class="flex flex-col leading-tight">
                                    <span>POS Kasir</span>
                                    <span class="text-[11px] text-slate-400 hidden md:inline">
                                        Input barang & proses pembayaran
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Kategori: Riwayat & Laporan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5 mt-1">
                            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500">
                                Riwayat &amp; Laporan
                            </span>
                            <span class="h-px flex-1 ml-2 bg-slate-800/70"></span>
                        </div>
                        <div class="space-y-1">
                            <a href="<?= site_url('kasir/transaksi'); ?>"
                               class="flex items-center gap-2 px-3 py-2 rounded-xl
                                      <?= $seg2 === 'transaksi'
                                          ? 'bg-slate-100 text-slate-900 font-semibold shadow-soft shadow-slate-900/40'
                                          : 'text-slate-200 hover:bg-slate-800/80'; ?>">
                                <span class="text-[15px]">📚</span>
                                <div class="flex flex-col leading-tight">
                                    <span>Riwayat transaksi</span>
                                    <span class="text-[11px] text-slate-400 hidden md:inline">
                                        Lihat & cetak ulang struk penjualanmu
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Kategori: Bantuan (opsional, placeholder) -->
                    <div class="hidden">
                        <div class="flex items-center justify-between mb-1.5 mt-1">
                            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500">
                                Bantuan
                            </span>
                            <span class="h-px flex-1 ml-2 bg-slate-800/70"></span>
                        </div>
                        <div class="space-y-1">
                            <button type="button"
                                    class="flex w-full items-center gap-2 px-3 py-2 rounded-xl text-slate-400 hover:bg-slate-800/80 text-left">
                                <span class="text-[15px]">❓</span>
                                <div class="flex flex-col leading-tight">
                                    <span>Panduan singkat</span>
                                    <span class="text-[11px] text-slate-500 hidden md:inline">
                                        Tips penggunaan kasir
                                    </span>
                                </div>
                            </button>
                        </div>
                    </div>
                </nav>

                <!-- Footer sidebar -->
                <div class="mt-4 pt-3 border-t border-slate-800 text-[11px] text-slate-500">
                    <p>&copy; <?= date('Y'); ?> Toko Z&amp;Z</p>
                    <p class="text-slate-600">
                        Sistem kasir harian • Shift: <span class="text-slate-300"><?= date('d/m/Y'); ?></span>
                    </p>
                </div>
            </div>
        </aside>

        <!-- Backdrop mobile -->
        <div id="kasir-backdrop"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 opacity-0 pointer-events-none lg:hidden transition-opacity duration-200"></div>

        <!-- Main content -->
        <main class="flex-1 lg:ml-64">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 py-4 sm:py-6">
                <div class="bg-slate-50/95 rounded-3xl shadow-soft shadow-black/40 border border-slate-200/80 p-3 sm:p-5">
                    <!-- Flash message global (kalau mau dipakai) -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="mb-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-2 text-xs sm:text-sm">
                            <?= esc(session()->getFlashdata('success')); ?>
                        </div>
                    <?php endif; ?>

                    <?= $this->renderSection('content'); ?>

                    <footer class="mt-6 pt-4 border-t border-slate-200 text-[11px] text-slate-500 flex flex-wrap justify-between gap-2">
                        <span>&copy; <?= date('Y'); ?> Toko Z&amp;Z</span>
                        <span>Sistem kasir harian • Versi kasir</span>
                    </footer>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const btnKasirToggle = document.getElementById('kasir-sidebar-toggle');
    const kasirSidebar   = document.getElementById('kasir-sidebar');
    const kasirBackdrop  = document.getElementById('kasir-backdrop');

    function closeKasirSidebar() {
        if (!kasirSidebar) return;
        kasirSidebar.classList.add('-translate-x-full');
        if (kasirBackdrop) {
            kasirBackdrop.classList.add('opacity-0');
            kasirBackdrop.classList.add('pointer-events-none');
        }
    }

    function openKasirSidebar() {
        if (!kasirSidebar) return;
        kasirSidebar.classList.remove('-translate-x-full');
        if (kasirBackdrop) {
            kasirBackdrop.classList.remove('opacity-0');
            kasirBackdrop.classList.remove('pointer-events-none');
        }
    }

    if (btnKasirToggle && kasirSidebar) {
        btnKasirToggle.addEventListener('click', () => {
            if (kasirSidebar.classList.contains('-translate-x-full')) {
                openKasirSidebar();
            } else {
                closeKasirSidebar();
            }
        });
    }

    if (kasirBackdrop) {
        kasirBackdrop.addEventListener('click', closeKasirSidebar);
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            if (kasirSidebar) kasirSidebar.classList.remove('-translate-x-full');
            if (kasirBackdrop) {
                kasirBackdrop.classList.add('opacity-0');
                kasirBackdrop.classList.add('pointer-events-none');
            }
        } else {
            if (kasirSidebar) kasirSidebar.classList.add('-translate-x-full');
        }
    });
</script>

<?= $this->renderSection('scripts'); ?>
</body>
</html>
