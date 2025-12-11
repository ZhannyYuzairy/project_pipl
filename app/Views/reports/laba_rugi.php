<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-3">Laporan Laba Rugi</h3>

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

<div class="card p-4">
    <div class="row mb-2">
        <div class="col-md-4">
            <h6 class="text-muted">Total Penjualan</h6>
            <h4>Rp <?= number_format($totalPenjualan,0,',','.'); ?></h4>
        </div>
        <div class="col-md-4">
            <h6 class="text-muted">Total HPP (Modal)</h6>
            <h4>Rp <?= number_format($totalHpp,0,',','.'); ?></h4>
        </div>
        <div class="col-md-4">
            <h6 class="text-muted">Laba Bersih</h6>
            <h4 class="<?= $labaBersih >= 0 ? 'text-success' : 'text-danger'; ?>">
                Rp <?= number_format($labaBersih,0,',','.'); ?>
            </h4>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
