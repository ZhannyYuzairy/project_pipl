<?= $this->extend('layout/kasir_main'); ?>

<?php
$tgl       = date('d/m/Y', strtotime($sale['sale_date']));
$jam       = date('H:i:s', strtotime($sale['sale_date']));
$namaKasir = $kasir['nama'] ?? ($kasir['username'] ?? 'Kasir');
?>

<?= $this->section('styles'); ?>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        50: '#ecfdf3',
                        100: '#d1fadf',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',
                    }
                },
                borderRadius: {
                    '2xl': '1.25rem',
                }
            }
        }
    }
</script>

<style>
    @media print {
        header,
        #kasir-sidebar,
        #kasir-backdrop {
            display: none !important;
        }

        body {
            background: #ffffff !important;
        }

        main > div > div {
            box-shadow: none !important;
            border: none !important;
            background: #ffffff !important;
            padding: 0 !important;
        }

        .print-area {
            box-shadow: none !important;
            border: none !important;
            background: #ffffff !important;
        }

        .no-print {
            display: none !important;
        }

        @page {
            margin: 10mm;
        }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex justify-center">
    <div class="print-area w-full max-w-3xl bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 text-slate-900">
        <!-- Header Struk -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 border-b border-dashed border-slate-300 pb-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold tracking-wide text-slate-900">
                    Toko Z&amp;Z
                </h1>
                <p class="text-xs text-slate-600">
                    Jl. Kuantan No. 69
                </p>
                <p class="text-xs text-slate-600">
                    Telp: 081364554097
                </p>
            </div>
            <div class="text-right text-xs space-y-0.5">
                <p class="uppercase tracking-[0.16em] text-[11px] text-slate-500">
                    Struk Penjualan
                </p>
                <p>
                    Invoice: <span class="font-semibold"><?= esc($sale['invoice_no']); ?></span>
                </p>
                <p>
                    Tanggal: <span class="font-medium"><?= $tgl; ?></span>
                </p>
                <p>
                    Jam: <span class="font-medium"><?= $jam; ?></span>
                </p>
                <p>
                    Kasir: <span class="font-medium"><?= esc($namaKasir); ?></span>
                </p>
            </div>
        </div>

        <!-- Info transaksi -->
        <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
            <div class="space-y-0.5">
                <p class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                    Informasi transaksi
                </p>
                <p class="text-slate-700">
                    Metode pembayaran:
                    <span class="font-semibold text-slate-900">
                        <?= strtoupper($sale['payment_method']); ?>
                    </span>
                </p>
            </div>
            <div class="space-y-0.5 text-sm text-right">
                <p class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                    Ringkasan nominal
                </p>
                <p class="text-xs text-slate-600">
                    Total: <span class="font-semibold text-slate-900">
                        Rp <?= number_format($sale['total_amount'], 0, ',', '.'); ?>
                    </span>
                </p>
                <p class="text-xs text-slate-600">
                    Dibayar: <span class="font-semibold text-slate-900">
                        Rp <?= number_format($sale['amount_paid'], 0, ',', '.'); ?>
                    </span>
                </p>
                <p class="text-xs text-slate-600">
                    Kembali: <span class="font-semibold text-slate-900">
                        Rp <?= number_format($sale['change_amount'], 0, ',', '.'); ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Tabel Item -->
        <div class="mt-4">
            <table class="w-full text-xs text-slate-900">
                <thead>
                <tr class="border-b border-slate-300 text-[11px] uppercase tracking-[0.14em] text-slate-500">
                    <th class="py-1 text-left">Barang</th>
                    <th class="py-1 text-left">Barcode</th>
                    <th class="py-1 text-center">Qty</th>
                    <th class="py-1 text-right">Harga</th>
                    <th class="py-1 text-right">Subtotal</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-dashed divide-slate-200">
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="py-1.5 pr-2 align-top">
                            <div class="font-medium text-xs text-slate-900">
                                <?= esc($item['nama']); ?>
                            </div>
                            <?php if (! empty($item['satuan'])): ?>
                                <div class="text-[10px] text-slate-500">
                                    Satuan: <?= esc($item['satuan']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="py-1.5 pr-2 align-top">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-700 font-mono">
                                <?= esc($item['barcode']); ?>
                            </span>
                        </td>
                        <td class="py-1.5 px-1 text-center align-top whitespace-nowrap">
                            <?= (int) $item['qty']; ?>
                        </td>
                        <td class="py-1.5 pl-1 pr-2 text-right align-top whitespace-nowrap">
                            Rp <?= number_format($item['price'], 0, ',', '.'); ?>
                        </td>
                        <td class="py-1.5 pl-1 text-right align-top whitespace-nowrap">
                            Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="mt-4 border-t border-slate-300 pt-3 flex flex-col gap-2 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-600">Total</span>
                <span class="font-semibold text-slate-900">
                    Rp <?= number_format($sale['total_amount'], 0, ',', '.'); ?>
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-600">Dibayar</span>
                <span class="font-semibold text-slate-900">
                    Rp <?= number_format($sale['amount_paid'], 0, ',', '.'); ?>
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-600">Kembali</span>
                <span class="font-semibold text-emerald-700">
                    Rp <?= number_format($sale['change_amount'], 0, ',', '.'); ?>
                </span>
            </div>
        </div>

        <!-- Catatan -->
        <div class="mt-4 border-t border-dashed border-slate-300 pt-3 text-center text-[11px] text-slate-500 space-y-1">
            <p>
                Barang yang sudah dibeli tidak dapat dikembalikan kecuali ada perjanjian sebelumnya.
            </p>
            <p class="font-semibold text-slate-700">
                Terima kasih telah berbelanja di Toko Z&amp;Z 🙏
            </p>
        </div>

        <!-- Tombol aksi -->
        <div class="mt-4 flex flex-wrap items-center justify-between gap-2 no-print">
            <div class="flex flex-wrap gap-2">
                <a href="<?= site_url('kasir/pos'); ?>"
                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    ⬅ Kembali ke POS
                </a>

                <a href="<?= site_url('kasir/pos/struk-thermal/' . $sale['id']); ?>"
                   target="_blank"
                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    🧾 Versi thermal
                </a>

                <a href="<?= site_url('kasir/pos/struk-pdf/' . $sale['id']); ?>"
                   target="_blank"
                   class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                    📄 Download PDF
                </a>
            </div>

            <button type="button"
                    onclick="window.print();"
                    class="inline-flex items-center rounded-full bg-slate-900 px-4 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                🖨 Cetak struk (normal)
            </button>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
