<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Thermal - <?= esc($sale['invoice_no']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: monospace;
            font-size: 11px;
            background: #f3f4f6;
        }
        .page {
            width: 58mm; /* lebar thermal 58mm */
            margin: 10px auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            padding: 6px 6px 10px;
        }
        .store-name {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .store-info {
            text-align: center;
            font-size: 10px;
            margin-bottom: 6px;
        }
        .line {
            border-top: 1px dashed #9ca3af;
            margin: 4px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .items th,
        .items td {
            padding: 2px 0;
        }
        .items th {
            border-bottom: 1px dashed #9ca3af;
            font-size: 9px;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .small {
            font-size: 9px;
        }
        .footer-note {
            text-align: center;
            font-size: 9px;
            margin-top: 6px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 10px 0;
        }
        .btn-print {
            padding: 6px 12px;
            font-size: 11px;
            border-radius: 999px;
            border: 1px solid #111827;
            background: #111827;
            color: #ffffff;
            cursor: pointer;
        }
        .btn-print:hover {
            background: #020617;
        }

        @media print {
            body {
                background: #ffffff;
            }
            .page {
                margin: 0;
                border: none;
            }
            .btn-wrapper {
                display: none;
            }
            @page {
                margin: 2mm;
                size: 58mm auto;
            }
        }
    </style>
</head>
<body>

<?php
$tgl       = date('d/m/Y', strtotime($sale['sale_date']));
$jam       = date('H:i:s', strtotime($sale['sale_date']));
$namaKasir = $kasir['nama'] ?? ($kasir['username'] ?? 'Kasir');
?>

<div class="page">
    <div class="store-name">Toko Z&amp;Z</div>
    <div class="store-info">
        Jl. Kuantan No. 69<br>
        Telp: 081364554097
    </div>

    <div class="line"></div>

    <div class="row">
        <span class="small">Invoice</span>
        <span class="small"><?= esc($sale['invoice_no']); ?></span>
    </div>
    <div class="row">
        <span class="small">Tanggal</span>
        <span class="small"><?= $tgl . ' ' . $jam; ?></span>
    </div>
    <div class="row">
        <span class="small">Kasir</span>
        <span class="small"><?= esc($namaKasir); ?></span>
    </div>
    <div class="row">
        <span class="small">Metode</span>
        <span class="small"><?= strtoupper($sale['payment_method']); ?></span>
    </div>

    <div class="line"></div>

    <table class="items">
        <thead>
        <tr>
            <th>Item</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <?= esc($item['nama']); ?><br>
                    <span class="small"><?= esc($item['barcode']); ?></span>
                </td>
                <td class="text-center">
                    <?= (int) $item['qty']; ?>
                </td>
                <td class="text-right">
                    Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="line"></div>

    <div class="row">
        <span class="small">Total</span>
        <span class="small">
            Rp <?= number_format($sale['total_amount'], 0, ',', '.'); ?>
        </span>
    </div>
    <div class="row">
        <span class="small">Dibayar</span>
        <span class="small">
            Rp <?= number_format($sale['amount_paid'], 0, ',', '.'); ?>
        </span>
    </div>
    <div class="row">
        <span class="small">Kembali</span>
        <span class="small">
            Rp <?= number_format($sale['change_amount'], 0, ',', '.'); ?>
        </span>
    </div>

    <div class="line"></div>

    <div class="footer-note">
        Barang yang sudah dibeli<br>
        tidak dapat dikembalikan.<br>
        <br>
        Terima kasih 🙏
    </div>
</div>

<div class="btn-wrapper">
    <button class="btn-print" onclick="window.print();">
        🖨 Cetak ke thermal
    </button>
</div>

</body>
</html>
