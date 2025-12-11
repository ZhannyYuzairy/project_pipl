<?= $this->extend('layout/kasir_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                Dashboard Kasir
            </p>
            <h1 class="mt-1 text-2xl md:text-3xl font-bold text-slate-900">
                Halo, <?= esc($namaKasir); ?> 👋
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan transaksi hari ini untuk kasir yang sedang login.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 rounded-full bg-slate-900 text-slate-50 px-3 py-1.5 text-[11px]">
            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Shift aktif</span>
            <span class="w-px h-3 bg-slate-700"></span>
            <span><?= date('d/m/Y'); ?></span>
        </div>
    </div>

    <!-- Kartu statistik utama -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <!-- Total penjualan hari ini -->
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                Total penjualan hari ini
            </p>
            <p class="mt-2 text-2xl font-bold text-slate-900">
                Rp <?= number_format($totalHariIni, 0, ',', '.'); ?>
            </p>
            <p class="mt-1 text-[11px] text-slate-500">
                Akumulasi dari semua transaksi yang kamu proses hari ini.
            </p>
        </div>

        <!-- Jumlah transaksi hari ini -->
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                Jumlah transaksi hari ini
            </p>
            <p class="mt-2 text-2xl font-bold text-slate-900">
                <?= (int) $trxHariIni; ?>
            </p>
            <p class="mt-1 text-[11px] text-slate-500">
                Banyaknya invoice yang kamu buat hari ini.
            </p>
        </div>

        <!-- Average ticket size -->
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                Rata-rata nilai transaksi
            </p>
            <p class="mt-2 text-2xl font-bold text-slate-900">
                Rp <?= number_format($avgTicket, 0, ',', '.'); ?>
            </p>
            <p class="mt-1 text-[11px] text-slate-500">
                Total penjualan ÷ jumlah transaksi hari ini.
            </p>
        </div>
    </div>

    <!-- Seksi info tambahan / shortcut -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5">
        <!-- Info singkat & tips -->
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm font-semibold text-slate-900">
                Aktivitas kasir hari ini
            </h2>
            <p class="mt-1 text-[11px] text-slate-500">
                Data ini hanya menampilkan transaksi yang dibuat oleh akun kasir yang sedang login.
            </p>

            <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                <div>
                    <dt class="text-[11px] text-slate-500">Tanggal</dt>
                    <dd class="font-medium text-slate-900"><?= date('d/m/Y'); ?></dd>
                </div>
                <div>
                    <dt class="text-[11px] text-slate-500">Kasir</dt>
                    <dd class="font-medium text-slate-900"><?= esc($namaKasir); ?></dd>
                </div>
                <div>
                    <dt class="text-[11px] text-slate-500">Total transaksi</dt>
                    <dd class="font-medium text-slate-900"><?= (int) $trxHariIni; ?> trx</dd>
                </div>
                <div>
                    <dt class="text-[11px] text-slate-500">Avg. ticket</dt>
                    <dd class="font-medium text-slate-900">
                        Rp <?= number_format($avgTicket, 0, ',', '.'); ?>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Shortcut ke POS & Riwayat -->
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">
                    Aksi cepat
                </h2>
                <p class="mt-1 text-[11px] text-slate-500">
                    Mulai transaksi baru atau cek riwayat transaksi kasir.
                </p>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <a href="<?= site_url('kasir/pos'); ?>"
                   class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                    🧾 Buka POS
                </a>
                <a href="<?= site_url('kasir/transaksi'); ?>"
                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                    📚 Riwayat transaksi
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
