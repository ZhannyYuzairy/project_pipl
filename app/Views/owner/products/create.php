<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Tambah Produk
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Masukkan informasi produk baru untuk Toko Z&amp;Z.
            </p>
        </div>
        <a href="<?= site_url('owner/products'); ?>"
           class="inline-flex items-center rounded-full border border-slate-300
                  text-slate-700 text-xs sm:text-sm font-semibold px-3 py-1.5 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-xs sm:text-sm">
            <?= esc(session()->getFlashdata('error')); ?>
        </div>
    <?php endif; ?>

    <!-- Card full width -->
    <form action="<?= site_url('owner/products/store'); ?>" method="post" enctype="multipart/form-data"
          class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-6">
        <?= csrf_field(); ?>

        <!-- Baris 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Barcode
                </label>
                <input type="text" name="barcode" required
                       value="<?= old('barcode'); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                       placeholder="Contoh: 8991234567890">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Nama Produk
                </label>
                <input type="text" name="nama" required
                       value="<?= old('nama'); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                       placeholder="Contoh: Indomie Goreng 80gr">
            </div>
        </div>

        <!-- Baris 2 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Stok Awal
                </label>
                <input type="number" name="stok" required min="0"
                       value="<?= old('stok', 0); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Harga Beli
                </label>
                <input type="number" name="harga_beli" required min="0"
                       value="<?= old('harga_beli', 0); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Harga Jual
                </label>
                <input type="number" name="harga_jual" required min="0"
                       value="<?= old('harga_jual', 0); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
        </div>

        <!-- Baris 3 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
<label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
    Satuan
</label>
<select name="satuan"
        class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
               px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
    <?php
    $oldSatuan = old('satuan', 'pcs');
    foreach ($allowedUnits as $unit):
    ?>
        <option value="<?= esc($unit); ?>" <?= $oldSatuan === $unit ? 'selected' : ''; ?>>
            <?= strtoupper($unit); ?>
        </option>
    <?php endforeach; ?>
</select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Gambar (opsional)
                </label>
                <input type="file" name="gambar"
                       class="w-full text-sm text-slate-700 file:mr-3 file:py-1.5 file:px-3
                              file:rounded-full file:border-0 file:text-xs file:font-semibold
                              file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                <p class="mt-1 text-[11px] text-slate-500">
                    Format gambar JPG/PNG. Gunakan foto produk agar kasir lebih mudah mengenali.
                </p>
            </div>
        </div>

        <!-- Tips + preview satu baris full -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700">
                <div class="font-semibold mb-1">Tips pengisian</div>
                <ul class="list-disc list-inside space-y-1">
                    <li>Pastikan barcode sama dengan yang ada di kemasan.</li>
                    <li>Harga jual sudah termasuk margin keuntungan.</li>
                    <li>Gunakan satuan yang konsisten (pcs, dus, pack, dll).</li>
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700">
                <div class="font-semibold mb-2">Preview di POS</div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-slate-200 flex items-center justify-center text-[10px] text-slate-500">
                        IMG
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-slate-800">
                            <span id="preview-nama">Nama produk</span>
                        </div>
                        <div class="text-[11px] text-slate-500">
                            <span id="preview-barcode">Barcode</span> •
                            Rp <span id="preview-harga">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-2 pt-2">
            <a href="<?= site_url('owner/products'); ?>"
               class="inline-flex items-center rounded-full border border-slate-300
                      text-slate-700 text-xs sm:text-sm font-semibold px-4 py-1.5 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center rounded-full bg-brand-600 hover:bg-brand-700
                           text-white text-xs sm:text-sm font-semibold px-4 py-1.5 shadow-sm">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    const inputNama    = document.querySelector('input[name="nama"]');
    const inputBarcode = document.querySelector('input[name="barcode"]');
    const inputHarga   = document.querySelector('input[name="harga_jual"]');

    const prevNama    = document.getElementById('preview-nama');
    const prevBarcode = document.getElementById('preview-barcode');
    const prevHarga   = document.getElementById('preview-harga');

    const formatRupiah = (angka) => {
        const n = parseInt(angka || '0', 10);
        return n.toLocaleString('id-ID');
    };

    if (inputNama && prevNama) {
        inputNama.addEventListener('input', () => {
            prevNama.textContent = inputNama.value || 'Nama produk';
        });
    }
    if (inputBarcode && prevBarcode) {
        inputBarcode.addEventListener('input', () => {
            prevBarcode.textContent = inputBarcode.value || 'Barcode';
        });
    }
    if (inputHarga && prevHarga) {
        inputHarga.addEventListener('input', () => {
            prevHarga.textContent = formatRupiah(inputHarga.value);
        });
    }
</script>
<?= $this->endSection(); ?>
