<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk PDF - <?= esc($sale['invoice_no']); ?></title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
            color: #0f172a;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px dashed #cbd5f5;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .store-name {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .store-info {
            font-size: 10px;
            color: #475569;
        }
        .section-title {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 9px;
            color: #64748b;
            margin-bottom: 2px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 2px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .table th,
        .table td {
            padding: 4px 3px;
            font-size: 10px;
        }
        .table thead th {
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 9px;
            color: #64748b;
        }
        .table tbody tr + tr td {
            border-top: 1px dashed #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            border-top: 1px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 4px;
            font-size: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .note {
            border-top: 1px dashed #cbd5f5;
            margin-top: 8px;
            padding-top: 6px;
            font-size: 9px;
            color: #64748b;
            text-align: center;
        }
        .note strong {
            color: #0f172a;
        }
    </style>
</head>
<body>

<?php
$tgl       = date('d/m/Y', strtotime($sale['sale_date']));
$jam       = date('H:i:s', strtotime($sale['sale_date']));
$namaKasir = $kasir['nama'] ?? ($kasir['username'] ?? 'Kasir');
?>

<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div>
            <div class="store-name">Toko Z&amp;Z</div>
            <div class="store-info">
                Jl. Kuantan No. 69<br>
                Telp: 081364554097
            </div>
        </div>
        <div style="text-align: right; font-size: 10px;">
            <div class="section-title">Struk Penjualan</div>
            <div>Invoice: <strong><?= esc($sale['invoice_no']); ?></strong></div>
            <div>Tanggal: <strong><?= $tgl; ?></strong></div>
            <div>Jam: <strong><?= $jam; ?></strong></div>
            <div>Kasir: <strong><?= esc($namaKasir); ?></strong></div>
        </div>
    </div>

    <!-- Info transaksi -->
    <div style="display: flex; justify-content: space-between; gap: 12px; margin-bottom: 6px;">
        <div style="flex: 1;">
            <div class="section-title">Informasi transaksi</div>
            <div style="font-size: 10px;">
                Metode pembayaran:
                <strong><?= strtoupper($sale['payment_method']); ?></strong>
            </div>
        </div>
        <div style="flex: 1; text-align: right;">
            <div class="section-title">Ringkasan nominal</div>
            <div style="font-size: 10px;">
                Total:
                <strong>Rp <?= number_format($sale['total_amount'], 0, ',', '.'); ?></strong>
            </div>
            <div style="font-size: 10px;">
                Dibayar:
                <strong>Rp <?= number_format($sale['amount_paid'], 0, ',', '.'); ?></strong>
            </div>
            <div style="font-size: 10px;">
                Kembali:
                <strong>Rp <?= number_format($sale['change_amount'], 0, ',', '.'); ?></strong>
            </div>
        </div>
    </div>

    <!-- Tabel item -->
    <table class="table">
        <thead>
        <tr>
            <th style="text-align: left;">Barang</th>
            <th style="text-align: left;">Barcode</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <div style="font-weight: 500;"><?= esc($item['nama']); ?></div>
                    <?php if (! empty($item['satuan'])): ?>
                        <div style="font-size: 9px; color: #64748b;">Satuan: <?= esc($item['satuan']); ?></div>
                    <?php endif; ?>
                </td>
                <td>
                    <span style="display: inline-block; padding: 1px 4px; border-radius: 999px; background: #e2e8f0; font-size: 9px;">
                        <?= esc($item['barcode']); ?>
                    </span>
                </td>
                <td class="text-center">
                    <?= (int) $item['qty']; ?>
                </td>
                <td class="text-right">
                    Rp <?= number_format($item['price'], 0, ',', '.'); ?>
                </td>
                <td class="text-right">
                    Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span>Total</span>
            <span><strong>Rp <?= number_format($sale['total_amount'], 0, ',', '.'); ?></strong></span>
        </div>
        <div class="summary-row">
            <span>Dibayar</span>
            <span><strong>Rp <?= number_format($sale['amount_paid'], 0, ',', '.'); ?></strong></span>
        </div>
        <div class="summary-row">
            <span>Kembali</span>
            <span><strong>Rp <?= number_format($sale['change_amount'], 0, ',', '.'); ?></strong></span>
        </div>
    </div>

    <!-- Note -->
    <div class="note">
        Barang yang sudah dibeli tidak dapat dikembalikan<br>
        kecuali ada perjanjian sebelumnya.<br><br>
        <strong>Terima kasih telah berbelanja di Toko Z&amp;Z 🙏</strong>
    </div>
</div>

</body>
</html>
