<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-4">
    <!-- Header + toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Produk
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Kelola daftar produk yang dijual di Toko Z&amp;Z.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <input
                    type="text"
                    id="product-search"
                    placeholder="Cari nama / barcode..."
                    class="w-full sm:w-56 rounded-full border border-slate-300 bg-white px-3 py-1.5 pr-8 text-xs sm:text-sm
                           placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <span class="absolute right-2 top-1.5 text-slate-400 text-xs">🔍</span>
            </div>
            <a href="<?= site_url('owner/products/create'); ?>"
               class="inline-flex items-center justify-center rounded-full bg-brand-600 hover:bg-brand-700
                      text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 shadow-sm">
                + Tambah Produk
            </a>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
        <div>
            Total produk:
            <span class="font-semibold text-slate-700">
                <?= isset($products) ? count($products) : 0; ?>
            </span>
        </div>
        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-600">
            Klik tombol aksi di kanan untuk mengedit atau menghapus produk.
        </span>
    </div>

    <!-- Tabel produk -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm" id="product-table">
                <thead class="bg-slate-50">
                <tr class="text-xs uppercase tracking-[0.12em] text-slate-500">
                    <th class="px-4 py-2 text-left">Barcode</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-right">Stok</th>
                    <th class="px-4 py-2 text-right">Harga Beli</th>
                    <th class="px-4 py-2 text-right">Harga Jual</th>
                    <th class="px-4 py-2 text-left hidden sm:table-cell">Satuan</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <?php if (! empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <?php $lowStock = isset($p['stok']) && $p['stok'] !== null && $p['stok'] <= 5; ?>
                        <tr class="product-row hover:bg-slate-50 transition-colors"
                            data-name="<?= strtolower(esc($p['nama'], 'attr')); ?>"
                            data-barcode="<?= strtolower(esc($p['barcode'], 'attr')); ?>">
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-700">
                                    <?= esc($p['barcode']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 font-medium text-slate-800">
                                <?= esc($p['nama']); ?>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <span class="<?= $lowStock ? 'text-red-600 font-semibold' : 'text-slate-800'; ?>">
                                    <?= (int) $p['stok']; ?>
                                </span>
                                <?php if ($lowStock): ?>
                                    <span class="ml-1 text-[10px] text-red-500">
                                        • Stok menipis
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 text-right text-slate-700">
                                Rp <?= number_format($p['harga_beli'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-4 py-2 text-right text-slate-800">
                                Rp <?= number_format($p['harga_jual'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-4 py-2 text-left hidden sm:table-cell text-slate-700">
                                <?= esc($p['satuan']); ?>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center gap-2">
                                    <a href="<?= site_url('owner/products/edit/' . $p['id']); ?>"
                                       class="inline-flex items-center rounded-full border border-slate-300
                                              px-3 py-1 text-xs text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </a>
                                    <!-- Hapus: langsung ke UI konfirmasi hapus -->
                                    <a href="<?= site_url('owner/products/delete/' . $p['id']); ?>"
                                       class="inline-flex items-center rounded-full border border-red-300
                                              px-3 py-1 text-xs text-red-600 hover:bg-red-50">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500 text-xs">
                            Belum ada produk terdaftar.  
                            Klik <span class="font-semibold text-slate-700">Tambah Produk</span> untuk mulai menambah.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // pencarian client-side sederhana
    const searchInput = document.getElementById('product-search');
    const productRows = document.querySelectorAll('#product-table .product-row');

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.trim().toLowerCase();

            productRows.forEach(row => {
                const name    = row.getAttribute('data-name') || '';
                const barcode = row.getAttribute('data-barcode') || '';

                if (!q || name.includes(q) || barcode.includes(q)) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        });
    }
</script>
<?= $this->endSection(); ?>
