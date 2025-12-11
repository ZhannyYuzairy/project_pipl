<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-3">Laporan Sisa Stok Barang</h3>

<button class="btn btn-outline-secondary mb-3" onclick="window.print()">Cetak</button>

<div class="card p-3">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Barcode</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= esc($p['barcode']); ?></td>
                    <td><?= esc($p['nama']); ?></td>
                    <td><?= $p['stok']; ?></td>
                    <td><?= esc($p['satuan']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center text-muted">Tidak ada data</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>
