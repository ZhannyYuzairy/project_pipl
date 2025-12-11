<?php
$filters = $filters ?? [];
$summary = $summary ?? [
    'total_penjualan' => 0,
    'total_modal'     => 0,
    'total_laba'      => 0,
];
$rows = $rows ?? [];

$start = $filters['start_date'] ?? date('Y-m-01');
$end   = $filters['end_date']   ?? date('Y-m-d');

$totalPenjualan = (float) $summary['total_penjualan'];
$totalModal     = (float) $summary['total_modal'];
$totalLaba      = (float) $summary['total_laba'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba &amp; Rugi</title>
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
        .laba-pos { color: #15803d; font-weight: 600; }
        .laba-neg { color: #b91c1c; font-weight: 600; }
    </style>
</head>
<body>

<table class="no-border" style="width:100%; margin-bottom: 8px;">
    <tr class="no-border">
        <td class="no-border">
            <h1>Laporan Laba &amp; Rugi</h1>
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

<!-- Tabel detail per transaksi -->
<table>
    <thead>
    <tr>
        <th style="width:16%;">Tanggal</th>
        <th style="width:14%;">Invoice</th>
        <th style="width:18%;">Kasir</th>
        <th style="width:16%;" class="text-right">Penjualan</th>
        <th style="width:16%;" class="text-right">Modal</th>
        <th style="width:20%;" class="text-right">Laba / Rugi</th>
    </tr>
    </thead>
    <tbody>
    <?php if (! empty($rows)): ?>
        <?php foreach ($rows as $r): ?>
            <?php
            $lpb = (float) $r['total_laba'];
            $cls = $lpb >= 0 ? 'laba-pos' : 'laba-neg';
            ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($r['sale_date'])); ?></td>
                <td><?= htmlspecialchars($r['invoice_no']); ?></td>
                <td><?= htmlspecialchars($r['kasir_nama'] ?? '-'); ?></td>
                <td class="text-right">Rp <?= number_format($r['total_penjualan'], 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?= number_format($r['total_modal'], 0, ',', '.'); ?></td>
                <td class="text-right <?= $cls; ?>">Rp <?= number_format($lpb, 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">Tidak ada data laba/rugi di periode ini.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Tabel ringkasan total -->
<table style="width:60%; margin-top:10px;">
    <thead>
    <tr>
        <th colspan="2">Ringkasan</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Total Penjualan</td>
        <td class="text-right">Rp <?= number_format($totalPenjualan, 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Total Modal</td>
        <td class="text-right">Rp <?= number_format($totalModal, 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <td>Total Laba (Rugi)</td>
        <td class="text-right <?= $totalLaba >= 0 ? 'laba-pos' : 'laba-neg'; ?>">
            Rp <?= number_format($totalLaba, 0, ',', '.'); ?>
        </td>
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
