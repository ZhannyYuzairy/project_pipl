<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Hapus Produk
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Konfirmasi penghapusan produk dari sistem Toko Z&amp;Z.
            </p>
        </div>
        <a href="<?= site_url('owner/products'); ?>"
           class="inline-flex items-center rounded-full border border-slate-300
                  text-slate-700 text-xs sm:text-sm font-semibold px-3 py-1.5 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <!-- Card utama: sama seperti create & edit -->
    <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-6">

        <!-- Warning -->
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold mb-1">Yakin ingin menghapus produk ini?</p>
            <p class="text-xs sm:text-sm">
                Setelah dihapus, produk tidak akan muncul lagi di daftar produk maupun POS.
                Data transaksi yang sudah terjadi tetap tersimpan di laporan.
            </p>
        </div>

        <!-- Info produk + gambar: grid 2 kolom seperti form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Info produk -->
            <div class="space-y-4">
                <div>
                    <div class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">
                        Nama Produk
                    </div>
                    <div class="text-base sm:text-lg font-semibold text-slate-900 mt-0.5">
                        <?= esc($product['nama']); ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">
                            Barcode
                        </div>
                        <div class="font-medium text-slate-800 mt-0.5">
                            <?= esc($product['barcode']); ?>
                        </div>
                    </div>

                    <div>
                        <div class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">
                            Satuan
                        </div>
                        <div class="font-medium text-slate-800 mt-0.5">
                            <?= esc(strtoupper($product['satuan'])); ?>
                        </div>
                    </div>

                    <div>
                        <div class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">
                            Stok
                        </div>
                        <div class="font-medium text-slate-800 mt-0.5">
                            <?= (int) $product['stok']; ?>
                        </div>
                    </div>

                    <div>
                        <div class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">
                            Harga Jual
                        </div>
                        <div class="font-medium text-slate-800 mt-0.5">
                            Rp <?= number_format($product['harga_jual'], 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gambar produk -->
            <div class="space-y-3">
                <div class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">
                    Gambar Produk
                </div>

                <?php if (! empty($product['gambar'])): ?>
                    <img src="<?= base_url('uploads/products/' . $product['gambar']); ?>"
                         alt="Gambar produk"
                         class="h-36 w-36 sm:h-40 sm:w-40 rounded-xl object-cover border border-slate-200 shadow-sm">
                <?php else: ?>
                    <div class="h-36 w-36 sm:h-40 sm:w-40 rounded-xl bg-slate-200 flex items-center justify-center
                                text-[11px] text-slate-500 border border-slate-200">
                        Tidak ada gambar
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tombol aksi: sama gaya dengan form -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
            <div class="text-[11px] sm:text-xs text-slate-500">
                Tindakan ini <span class="font-semibold text-red-600">tidak dapat dibatalkan</span>.
            </div>

            <div class="flex gap-2 justify-end">
                <a href="<?= site_url('owner/products'); ?>"
                   class="inline-flex items-center rounded-full border border-slate-300
                          text-slate-700 text-xs sm:text-sm font-semibold px-4 py-1.5 hover:bg-slate-50">
                    Batal
                </a>

                <form action="<?= site_url('owner/products/destroy/' . $product['id']); ?>"
                      method="post"
                      onsubmit="return confirm('Yakin ingin menghapus produk ini secara permanen?');">
                    <?= csrf_field(); ?>
                    <button type="submit"
                            class="inline-flex items-center rounded-full bg-red-600 hover:bg-red-700
                                   text-white text-xs sm:text-sm font-semibold px-4 py-1.5 shadow-sm">
                        Ya, Hapus Produk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
