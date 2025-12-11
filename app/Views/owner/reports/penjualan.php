<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<?php
// Filter values
$filters = $filters ?? [];

$start = $filters['start_date'] ?? date('Y-m-01');
$end   = $filters['end_date'] ?? date('Y-m-d');
$cashierId = $filters['cashier_id'] ?? '';
$paymentMethod = $filters['payment_method'] ?? 'all';

$cashiers = $cashiers ?? [];
$sales = $sales ?? [];

$summary = $summary ?? [
    'total_transaksi' => 0,
    'total_omzet'     => 0,
    'total_cash'      => 0,
    'total_qris'      => 0,
];

// Query untuk export
$queryExport = http_build_query([
    'start_date'     => $start,
    'end_date'       => $end,
    'cashier_id'     => $cashierId,
    'payment_method' => $paymentMethod,
]);
?>

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="space-y-1">
            <p class="text-[11px] font-semibold tracking-[0.24em] text-slate-500 uppercase">
                Laporan
            </p>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Laporan Penjualan
            </h1>
            <p class="text-sm text-slate-500">
                Monitoring transaksi, omzet, dan metode pembayaran.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="<?= site_url('owner/reports/penjualan/export') . '?' . $queryExport; ?>"
               class="inline-flex items-center rounded-full bg-slate-900 hover:bg-slate-800
                      px-4 py-1.5 text-xs sm:text-sm font-semibold text-white shadow-sm">
                📄 Export CSV
            </a>

            <a href="<?= site_url('owner/reports/penjualan/pdf') . '?' . $queryExport; ?>"
               class="inline-flex items-center rounded-full bg-brand-600 hover:bg-brand-700
                      px-4 py-1.5 text-xs sm:text-sm font-semibold text-white shadow-sm">
                🖨 Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <form action="<?= site_url('owner/reports/penjualan'); ?>" method="get"
          class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-4">

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">

            <!-- Start Date -->
            <div>
                <label class="text-[11px] uppercase tracking-[0.14em] font-semibold text-slate-600">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="<?= esc($start); ?>"
                       class="w-full mt-1 rounded-lg border border-slate-300 text-slate-900 px-3 py-2 text-sm focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- End Date -->
            <div>
                <label class="text-[11px] uppercase tracking-[0.14em] font-semibold text-slate-600">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="<?= esc($end); ?>"
                       class="w-full mt-1 rounded-lg border border-slate-300 text-slate-900 px-3 py-2 text-sm focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Cashier -->
            <div>
                <label class="text-[11px] uppercase tracking-[0.14em] font-semibold text-slate-600">
                    Kasir
                </label>
                <select name="cashier_id"
                        class="w-full mt-1 rounded-lg border border-slate-300 bg-white text-slate-900 px-3 py-2 text-sm">
                    <option value="">Semua Kasir</option>
                    <?php foreach ($cashiers as $c): ?>
                        <option value="<?= $c['id']; ?>"
                            <?= $cashierId == $c['id'] ? 'selected' : ''; ?>>
                            <?= esc($c['nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="text-[11px] uppercase tracking-[0.14em] font-semibold text-slate-600">
                    Metode Pembayaran
                </label>
                <select name="payment_method"
                        class="w-full mt-1 rounded-lg border border-slate-300 bg-white text-slate-900 px-3 py-2 text-sm">
                    <option value="all" <?= $paymentMethod === 'all' ? 'selected' : ''; ?>>Semua</option>
                    <option value="cash" <?= $paymentMethod === 'cash' ? 'selected' : ''; ?>>Cash</option>
                    <option value="qris" <?= $paymentMethod === 'qris' ? 'selected' : ''; ?>>QRIS</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="<?= site_url('owner/reports/penjualan'); ?>"
               class="text-xs px-4 py-1.5 rounded-full border border-slate-300 text-slate-600 hover:bg-slate-100">
                Reset
            </a>
            <button type="submit"
                    class="text-xs px-4 py-1.5 rounded-full bg-brand-600 hover:bg-brand-700 text-white shadow">
                Terapkan Filter
            </button>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">

        <!-- Total transaksi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">Total Transaksi</p>
            <p class="text-xl font-bold text-slate-900"><?= $summary['total_transaksi']; ?></p>
        </div>

        <!-- Omzet -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">Total Omzet</p>
            <p class="text-xl font-bold text-slate-900">
                Rp <?= number_format($summary['total_omzet'], 0, ',', '.'); ?>
            </p>
        </div>

        <!-- Cash -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">Cash</p>
            <p class="text-xl font-bold text-slate-900">
                Rp <?= number_format($summary['total_cash'], 0, ',', '.'); ?>
            </p>
        </div>

        <!-- QRIS -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">QRIS</p>
            <p class="text-xl font-bold text-slate-900">
                Rp <?= number_format($summary['total_qris'], 0, ',', '.'); ?>
            </p>
        </div>

    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                <tr class="text-[11px] uppercase tracking-[0.12em] text-slate-500">
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Invoice</th>
                    <th class="px-4 py-2 text-left">Kasir</th>
                    <th class="px-4 py-2 text-left">Metode</th>
                    <th class="px-4 py-2 text-right">Total</th>
                </tr>
                </thead>

<tbody class="divide-y divide-slate-100 text-slate-800">
<?php if (! empty($sales)): ?>
    <?php foreach ($sales as $s): ?>
        <tr class="hover:bg-slate-50">
            
            <td class="px-4 py-2 text-slate-800">
                <?= date('d M Y H:i', strtotime($s['sale_date'])); ?>
            </td>

            <td class="px-4 py-2 font-medium text-slate-900">
                <?= esc($s['invoice_no']); ?>
            </td>

            <td class="px-4 py-2 text-slate-800">
                <?= esc($s['kasir_nama']); ?>
            </td>

            <td class="px-4 py-2 text-slate-800">
                <?= strtoupper(esc($s['payment_method'])); ?>
            </td>

            <td class="px-4 py-2 text-right text-slate-900">
                Rp <?= number_format($s['total_amount'], 0, ',', '.'); ?>
            </td>

        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="5" class="text-center py-6 text-sm text-slate-500">
            Tidak ada transaksi pada periode ini.
        </td>
    </tr>
<?php endif; ?>
</tbody>

            </table>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>
