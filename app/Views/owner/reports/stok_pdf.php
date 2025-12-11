<?php
$products     = $products ?? [];
$generated_at = $generated_at ?? date('Y-m-d H:i');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Produk</title>
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
        .low-stock { color: #b91c1c; font-weight: 600; }
    </style>
</head>
<body>

<table class="no-border" style="width:100%; margin-bottom: 8px;">
    <tr class="no-border">
        <td class="no-border">
            <h1>Laporan Stok Produk</h1>
            <div class="subtitle">Toko Z&amp;Z • Sistem Kasir</div>
        </td>
        <td class="no-border" style="text-align:right; font-size:9px; color:#4b5563;">
            Dicetak: <?= htmlspecialchars($generated_at); ?><br>
            Total produk: <strong><?= count($products); ?></strong>
        </td>
    </tr>
</table>

<?php $totalModal = 0; ?>

<!-- Tabel daftar produk -->
<table>
    <thead>
    <tr>
        <th style="width:6%;">No</th>
        <th style="width:14%;">Barcode</th>
        <th style="width:32%;">Nama Produk</th>
        <th style="width:10%;">Satuan</th>
        <th style="width:8%;"  class="text-right">Stok</th>
        <th style="width:10%;" class="text-right">Harga Beli</th>
        <th style="width:10%;" class="text-right">Harga Jual</th>
        <th style="width:10%;" class="text-right">Nilai Modal</th>
    </tr>
    </thead>
    <tbody>
    <?php if (! empty($products)): ?>
        <?php $no = 1; ?>
        <?php foreach ($products as $p): ?>
            <?php
            $stok  = (int)   ($p['stok'] ?? 0);
            $beli  = (float) ($p['harga_beli'] ?? 0);
            $jual  = (float) ($p['harga_jual'] ?? 0);
            $modal = $stok * $beli;
            $totalModal += $modal;
            $low = $stok <= 5;
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td><?= htmlspecialchars($p['barcode']); ?></td>
                <td><?= htmlspecialchars($p['nama']); ?></td>
                <td><?= htmlspecialchars($p['satuan'] ?? 'pcs'); ?></td>
                <td class="text-right<?= $low ? ' low-stock' : ''; ?>">
                    <?= number_format($stok, 0, ',', '.'); ?>
                </td>
                <td class="text-right">Rp <?= number_format($beli, 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?= number_format($jual, 0, ',', '.'); ?></td>
                <td class="text-right">Rp <?= number_format($modal, 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" class="text-center">Belum ada data produk.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Tabel ringkasan total -->
<table style="width:55%; margin-top:10px;">
    <thead>
    <tr>
        <th colspan="2">Ringkasan</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Total Nilai Modal Stok</td>
        <td class="text-right">Rp <?= number_format($totalModal, 0, ',', '.'); ?></td>
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
