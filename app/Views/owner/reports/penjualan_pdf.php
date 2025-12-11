<?php
// Data dari controller
$sales   = $sales   ?? [];
$filters = $filters ?? [];

// Periode
$start = $filters['start_date'] ?? date('Y-m-01');
$end   = $filters['end_date']   ?? date('Y-m-d');

// Hitung ringkasan
$totalTransaksi = count($sales);
$totalOmzet     = 0;
$totalCash      = 0;
$totalQris      = 0;

foreach ($sales as $row) {
    $totalOmzet += (float) ($row['total_amount'] ?? 0);

    $pm = strtolower((string) ($row['payment_method'] ?? ''));
    if ($pm === 'cash' || $pm === 'tunai') {
        $totalCash += (float) ($row['total_amount'] ?? 0);
    } elseif ($pm === 'qris' || $pm === 'qris_ewallet') {
        $totalQris += (float) ($row['total_amount'] ?? 0);
    }
}

function pm_label_pdf($pm) {
    $pm = strtolower((string) $pm);
    if ($pm === 'cash' || $pm === 'tunai') return 'Cash';
    if ($pm === 'qris' || $pm === 'qris_ewallet') return 'QRIS / e-Wallet';
    return $pm ?: '-';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; box-sizing: border-box; }
        body { font-size: 10px; margin: 20px; color: #111827; }
        h1   { font-size: 16px; margin: 0 0 4px 0; }
        .subtitle { font-size: 11px; color: #6b7280; margin-bottom: 6px; }

        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d1d5db; padding: 4px 6px; }
        th {
            background: #f3f4f6;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #4b5563;
        }
        td { font-size: 9.5px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .no-border td { border: none; padding: 2px 0; }
    </style>
</head>
<body>

<!-- Header -->
<table class="no-border" style="width:100%; margin-bottom: 8px;">
    <tr class="no-border">
        <td class="no-border">
            <h1>Laporan Penjualan</h1>
            <div class="subtitle">Toko Z&amp;Z • Sistem Kasir</div>
        </td>
        <td class="no-border" style="text-align:right; font-size:9px; color:#4b5563;">
            Periode:
            <strong><?= date('d M Y', strtotime($start)); ?></strong>
            s/d
            <strong><?= date('d M Y', strtotime($end)); ?></strong><br>
            Dicetak: <?= date('d M Y H:i'); ?>
        </td>
    </tr>
</table>

<!-- Tabel daftar transaksi -->
<table>
    <thead>
    <tr>
        <th style="width:16%;">Tanggal</th>
        <th style="width:14%;">Invoice</th>
        <th style="width:18%;">Kasir</th>
        <th style="width:16%;">Metode</th>
        <th style="width:12%;" class="text-right">Total</th>
        <th style="width:12%;" class="text-right">Dibayar</th>
        <th style="width:12%;" class="text-right">Kembali</th>
    </tr>
    </thead>
    <tbody>
    <?php if (! empty($sales)): ?>
        <?php foreach ($sales as $row): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($row['sale_date'])); ?></td>
                <td><?= htmlspecialchars($row['invoice_no']); ?></td>
                <td><?= htmlspecialchars($row['kasir_nama'] ?? '-'); ?></td>
                <td><?= pm_label_pdf($row['payment_method'] ?? '-'); ?></td>
                <td class="text-right">Rp <?= number_format($row['total_amount'] ?? 0, 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?= number_format($row['amount_paid'] ?? 0, 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?= number_format($row['change_amount'] ?? 0, 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Tabel ringkasan total (dipisah) -->
<table style="width:55%; margin-top:10px;">
    <thead>
    <tr>
        <th colspan="2">Ringkasan</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Total Transaksi</td>
        <td class="text-right"><?= number_format($totalTransaksi, 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Total Omzet</td>
        <td class="text-right">Rp <?= number_format($totalOmzet, 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Total Cash</td>
        <td class="text-right">Rp <?= number_format($totalCash, 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Total QRIS</td>
        <td class="text-right">Rp <?= number_format($totalQris, 0, ',', '.'); ?></td>
    </tr>
    </tbody>
</table>

<!-- Footer halaman -->
<script type="text/php">
if (isset($pdf)) {
    $font = $fontMetrics->get_font("DejaVu Sans", "normal");
    $size = 8;
    $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
    $width  = $pdf->get_width();
    $y      = $pdf->get_height() - 24;
    $x      = $width - 150;
    $pdf->page_text($x, $y, $text, $font, $size, [0.4, 0.4, 0.4]);
}
</script>

</body>
</html>
