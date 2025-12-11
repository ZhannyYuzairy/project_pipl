<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Edit Produk
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Perbarui informasi untuk produk:
                <span class="font-semibold text-slate-800">
                    <?= esc($product['nama'] ?? ''); ?>
                </span>
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

    <form action="<?= site_url('owner/products/update/' . $product['id']); ?>" method="post" enctype="multipart/form-data"
          class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-6">
        <?= csrf_field(); ?>

        <!-- Baris 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Barcode
                </label>
                <input type="text" name="barcode" required
                       value="<?= old('barcode', $product['barcode'] ?? ''); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Nama Produk
                </label>
                <input type="text" name="nama" required
                       value="<?= old('nama', $product['nama'] ?? ''); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
        </div>

        <!-- Baris 2 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Stok
                </label>
                <input type="number" name="stok" required min="0"
                       value="<?= old('stok', $product['stok'] ?? 0); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Harga Beli
                </label>
                <input type="number" name="harga_beli" required min="0"
                       value="<?= old('harga_beli', $product['harga_beli'] ?? 0); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Harga Jual
                </label>
                <input type="number" name="harga_jual" required min="0"
                       value="<?= old('harga_jual', $product['harga_jual'] ?? 0); ?>"
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
                    $currentUnit = old('satuan', $product['satuan'] ?? 'pcs');
                    foreach ($allowedUnits as $unit):
                    ?>
                        <option value="<?= esc($unit); ?>" <?= $currentUnit === $unit ? 'selected' : ''; ?>>
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
                    Jika tidak diubah, gambar lama akan tetap digunakan.
                </p>
            </div>
        </div>

        <!-- Baris 4: gambar sekarang (info saja) -->
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700 max-w-sm">
            <div class="font-semibold mb-1">Gambar saat ini</div>
            <?php if (! empty($product['gambar'])): ?>
                <img src="<?= base_url('uploads/products/' . $product['gambar']); ?>"
                     alt="Gambar produk"
                     class="h-28 w-full max-w-[160px] rounded-xl object-cover border border-slate-200">
            <?php else: ?>
                <div class="h-28 w-full max-w-[160px] rounded-xl bg-slate-200 flex items-center justify-center text-[11px] text-slate-500">
                    Belum ada gambar
                </div>
            <?php endif; ?>
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
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>
