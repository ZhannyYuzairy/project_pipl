<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-3">Laporan Penjualan</h3>

<form class="row g-2 mb-3" method="get">
    <div class="col-md-3">
        <label class="form-label">Dari</label>
        <input type="date" name="start" class="form-control" value="<?= $start; ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Sampai</label>
        <input type="date" name="end" class="form-control" value="<?= $end; ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary me-2" type="submit">Tampilkan</button>
        <button class="btn btn-outline-secondary" type="button" onclick="window.print()">Cetak</button>
    </div>
</form>

<div class="card p-3">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Metode</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($sales)): ?>
            <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?= $s['sale_date']; ?></td>
                    <td><?= $s['invoice_no']; ?></td>
                    <td class="text-uppercase"><?= $s['payment_method']; ?></td>
                    <td>Rp <?= number_format($s['total_amount'],0,',','.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center text-muted">Tidak ada data</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>
