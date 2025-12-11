<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Tailwind dipakai utama, ini cuma kalau butuh custom kecil */
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="space-y-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
            Dashboard Owner
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Ringkasan performa harian Toko Z&amp;Z.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="text-[11px] tracking-[0.15em] uppercase text-slate-500 mb-1">
                Omzet Hari Ini
            </div>
            <div class="text-xl font-semibold text-slate-900">
                Rp <?= number_format($totalPenjualan ?? 0, 0, ',', '.'); ?>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Total penjualan semua kasir.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="text-[11px] tracking-[0.15em] uppercase text-slate-500 mb-1">
                Transaksi Hari Ini
            </div>
            <div class="text-xl font-semibold text-slate-900">
                <?= number_format($totalTransaksi ?? 0); ?>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Nota yang tercatat di sistem.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="text-[11px] tracking-[0.15em] uppercase text-slate-500 mb-1">
                Produk Aktif
            </div>
            <div class="text-xl font-semibold text-slate-900">
                <?= number_format($totalProduk ?? 0); ?>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Item yang siap dijual.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="text-[11px] tracking-[0.15em] uppercase text-slate-500 mb-1">
            Ringkasan
        </div>
        <h2 class="text-sm font-semibold text-slate-800 mb-2">
            Aktivitas terakhir
        </h2>
        <p class="text-xs text-slate-500">
            Di sini nantinya bisa diisi grafik penjualan, performa per kasir, atau daftar transaksi terbaru.
            Untuk sekarang, gunakan menu <span class="font-semibold text-slate-700">Produk</span>
            dan <span class="font-semibold text-slate-700">Laporan</span> di sidebar untuk mengelola data.
        </p>
    </div>
</div>

<?= $this->endSection(); ?>
