<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<?php
$products = $products ?? [];
?>

<div class="space-y-6">

    <!-- Header -->
    <div class="space-y-1">
        <p class="text-[11px] uppercase tracking-[0.24em] text-slate-500 font-semibold">
            Laporan
        </p>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
            Laporan Stok Barang
        </h1>
        <p class="text-sm text-slate-500">
            Monitoring ketersediaan barang di toko.
        </p>
    </div>

    <!-- Export PDF -->
    <div>
        <a href="<?= site_url('owner/reports/stok/pdf'); ?>"
           class="inline-flex items-center rounded-full bg-slate-900 hover:bg-slate-800
                  px-4 py-1.5 text-xs sm:text-sm text-white font-semibold shadow-sm">
            🖨 Export PDF
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                <tr class="text-[11px] uppercase tracking-[0.12em] text-slate-500">
                    <th class="px-4 py-2 text-left">Barcode</th>
                    <th class="px-4 py-2 text-left">Nama Produk</th>
                    <th class="px-4 py-2 text-right">Stok</th>
                    <th class="px-4 py-2 text-right">Harga Beli</th>
                    <th class="px-4 py-2 text-right">Harga Jual</th>
                    <th class="px-4 py-2 text-left">Satuan</th>
                </tr>
                </thead>
<tbody class="divide-y divide-slate-100 text-slate-800">
<?php foreach ($products as $p): ?>
    <?php $low = $p['stok'] <= 5; ?>

    <tr class="hover:bg-slate-50">

        <td class="px-4 py-2 text-slate-800">
            <span class="inline-flex px-2 py-0.5 bg-slate-100 rounded-full text-[11px] text-slate-700">
                <?= esc($p['barcode']); ?>
            </span>
        </td>

        <td class="px-4 py-2 text-slate-900 font-medium">
            <?= esc($p['nama']); ?>
        </td>

        <td class="px-4 py-2 text-right <?= $low ? 'text-red-600 font-semibold' : 'text-slate-800'; ?>">
            <?= esc($p['stok']); ?>
        </td>

        <td class="px-4 py-2 text-right text-slate-800">
            Rp <?= number_format($p['harga_beli'], 0, ',', '.'); ?>
        </td>

        <td class="px-4 py-2 text-right text-slate-900">
            Rp <?= number_format($p['harga_jual'], 0, ',', '.'); ?>
        </td>

        <td class="px-4 py-2 text-slate-800">
            <?= esc($p['satuan']); ?>
        </td>

    </tr>
<?php endforeach; ?>
</tbody>

            </table>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>
