<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<?php
// Ambil data dari controller
$filters = $filters ?? [];
$summary = $summary ?? [
    'total_penjualan' => 0,
    'total_modal'     => 0,
    'total_laba'      => 0,
];
$rows = $rows ?? [];

// Periode
$start = $filters['start_date'] ?? date('Y-m-01');
$end   = $filters['end_date'] ?? date('Y-m-d');

// Angka summary
$totalPenjualan = (float) ($summary['total_penjualan'] ?? 0);
$totalHpp       = (float) ($summary['total_modal'] ?? 0);
$labaBersih     = (float) ($summary['total_laba'] ?? 0);

// Query string untuk export PDF
$queryExport = http_build_query([
    'start_date' => $start,
    'end_date'   => $end,
]);
?>

<div class="space-y-5">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="space-y-1">
            <p class="text-[11px] font-semibold tracking-[0.24em] text-slate-500 uppercase">
                Laporan
            </p>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Laporan Laba &amp; Rugi
            </h1>
            <p class="text-sm text-slate-500">
                Ringkasan penjualan, modal, dan laba bersih dalam rentang tanggal tertentu.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= site_url('owner/reports/laba-rugi/pdf') . '?' . $queryExport; ?>"
               class="inline-flex items-center rounded-full bg-slate-900 hover:bg-slate-800
                      px-3 sm:px-4 py-1.5 text-xs sm:text-sm text-white font-semibold shadow-sm">
                🖨 Export PDF
            </a>
        </div>
    </div>

    <!-- Filter bar -->
    <form action="<?= site_url('owner/reports/laba-rugi'); ?>" method="get"
          class="bg-white rounded-2xl shadow-sm border border-slate-200 p-3 sm:p-4 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <!-- Tanggal mulai -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="<?= esc($start); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs sm:text-sm
                              text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Tanggal akhir -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="<?= esc($end); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs sm:text-sm
                              text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <!-- Tombol -->
            <div class="flex flex-wrap justify-end gap-2">
                <a href="<?= site_url('owner/reports/laba-rugi'); ?>"
                   class="inline-flex items-center rounded-full border border-slate-300
                          px-4 py-1.5 text-xs sm:text-sm text-slate-700 hover:bg-slate-50 mt-4 sm:mt-0">
                    Reset
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-full bg-brand-600 hover:bg-brand-700
                               px-4 py-1.5 text-xs sm:text-sm text-white font-semibold shadow-sm mt-4 sm:mt-0">
                    Terapkan Filter
                </button>
            </div>
        </div>

        <p class="text-[11px] text-slate-500 mt-1">
            Periode:
            <span class="font-semibold text-slate-700">
                <?= date('d M Y', strtotime($start)); ?> s/d <?= date('d M Y', strtotime($end)); ?>
            </span>
        </p>
    </form>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <!-- Total Penjualan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-4 flex flex-col">
            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">
                Total Penjualan
            </span>
            <span class="text-xl font-bold text-slate-900">
                Rp <?= number_format($totalPenjualan, 0, ',', '.'); ?>
            </span>
            <span class="mt-1 text-xs text-slate-500">
                Nilai penjualan kotor dalam periode ini.
            </span>
        </div>

        <!-- Total Modal -->
        <div class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-4 flex flex-col">
            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">
                Total Modal (HPP)
            </span>
            <span class="text-xl font-bold text-slate-900">
                Rp <?= number_format($totalHpp, 0, ',', '.'); ?>
            </span>
            <span class="mt-1 text-xs text-slate-500">
                Perkiraan modal barang yang terjual.
            </span>
        </div>

        <!-- Laba Bersih -->
        <?php $labaPositif = $labaBersih >= 0; ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-4 flex flex-col">
            <span class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-1">
                Laba / Rugi Bersih
            </span>
            <span class="text-xl font-bold <?= $labaPositif ? 'text-emerald-700' : 'text-red-600'; ?>">
                Rp <?= number_format($labaBersih, 0, ',', '.'); ?>
            </span>
            <span class="mt-1 text-xs text-slate-500">
                Laba = Penjualan - Modal. <?= $labaPositif ? 'Usaha dalam kondisi profit.' : 'Perlu evaluasi karena rugi.'; ?>
            </span>
        </div>
    </div>

    <!-- Visual perbandingan -->
    <div class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-4">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
            <div class="text-[11px] tracking-[0.15em] uppercase text-slate-500">
                Visual perbandingan
            </div>
            <div class="text-[11px] text-slate-500">
                Periode:
                <span class="font-semibold text-slate-700">
                    <?= esc($start); ?> s/d <?= esc($end); ?>
                </span>
            </div>
        </div>

        <?php
        $max = max($totalPenjualan, $totalHpp, abs($labaBersih), 1);
        $pPenjualan = ($totalPenjualan / $max) * 100;
        $pHpp       = ($totalHpp / $max) * 100;
        $pLaba      = (abs($labaBersih) / $max) * 100;
        ?>

        <div class="space-y-3">
            <!-- Penjualan -->
            <div>
                <div class="flex justify-between text-[11px] text-slate-600 mb-1">
                    <span>Penjualan</span>
                    <span>Rp <?= number_format($totalPenjualan, 0, ',', '.'); ?></span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-2 rounded-full bg-brand-500" style="width: <?= $pPenjualan; ?>%;"></div>
                </div>
            </div>

            <!-- HPP -->
            <div>
                <div class="flex justify-between text-[11px] text-slate-600 mb-1">
                    <span>Modal (HPP)</span>
                    <span>Rp <?= number_format($totalHpp, 0, ',', '.'); ?></span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-2 rounded-full bg-slate-500" style="width: <?= $pHpp; ?>%;"></div>
                </div>
            </div>

            <!-- Laba -->
            <div>
                <div class="flex justify-between text-[11px] text-slate-600 mb-1">
                    <span><?= $labaPositif ? 'Laba Bersih' : 'Rugi Bersih'; ?></span>
                    <span class="<?= $labaPositif ? 'text-emerald-700' : 'text-red-600'; ?>">
                        Rp <?= number_format($labaBersih, 0, ',', '.'); ?>
                    </span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-2 rounded-full <?= $labaPositif ? 'bg-emerald-500' : 'bg-red-500'; ?>"
                         style="width: <?= $pLaba; ?>%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel detail per invoice -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm">
                <thead class="bg-slate-50">
                <tr class="text-[11px] uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Invoice</th>
                    <th class="px-4 py-2 text-left hidden md:table-cell">Kasir</th>
                    <th class="px-4 py-2 text-right">Penjualan</th>
                    <th class="px-4 py-2 text-right">Modal</th>
                    <th class="px-4 py-2 text-right">Laba / Rugi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <?php if (! empty($rows)): ?>
                    <?php foreach ($rows as $r): ?>
                        <?php
                        $lp = (float) $r['total_laba'];
                        $rowClass = $lp >= 0 ? 'text-emerald-700' : 'text-red-600';
                        ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2 align-middle text-slate-700 whitespace-nowrap">
                                <?= date('d M Y H:i', strtotime($r['sale_date'])); ?>
                            </td>
                            <td class="px-4 py-2 align-middle">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-800">
                                    <?= esc($r['invoice_no']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 align-middle hidden md:table-cell text-slate-800">
                                <?= esc($r['kasir_nama'] ?? '-'); ?>
                            </td>
                            <td class="px-4 py-2 align-middle text-right text-slate-900">
                                Rp <?= number_format($r['total_penjualan'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-4 py-2 align-middle text-right text-slate-900">
                                Rp <?= number_format($r['total_modal'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-4 py-2 align-middle text-right font-semibold <?= $rowClass; ?>">
                                Rp <?= number_format($lp, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-[11px] sm:text-sm text-slate-500">
                            Tidak ada data laba rugi untuk periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>
