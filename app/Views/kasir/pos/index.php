<?= $this->extend('layout/kasir_main'); ?>

<?php
$cart  = session()->get('cart') ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['qty'] * $item['harga_jual'];
}
?>

<?= $this->section('styles'); ?>
<!-- Tailwind CDN khusus halaman POS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        50: '#ecfdf3',
                        100: '#d1fadf',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',
                    }
                },
                borderRadius: {
                    '2xl': '1.25rem',
                }
            }
        }
    }
</script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="space-y-5">
    <!-- Header POS -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div>
            <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                POS Kasir
            </p>
            <h1 class="mt-1 text-2xl md:text-3xl font-bold text-slate-900">
                Toko Z&amp;Z &mdash; Point of Sale
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Scan / input barang, cek ringkasan cart, lalu selesaikan pembayaran dengan cepat.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 rounded-full bg-slate-900 text-slate-50 px-3 py-1.5 text-[11px]">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Mode kasir aktif</span>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-[11px] text-slate-700">
                <span><?= count($cart); ?> item</span>
                <span class="w-px h-3 bg-slate-300"></span>
                <span>Total: <strong>Rp <?= number_format($total, 0, ',', '.'); ?></strong></span>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGE UMUM (error dari checkout, stok kurang, dll) -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <?= esc(session()->getFlashdata('error')); ?>
        </div>
    <?php endif; ?>

    <?php $stockErrors = session()->getFlashdata('stock_errors'); ?>
    <?php if (! empty($stockErrors)): ?>
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs sm:text-sm text-amber-900 space-y-2">
            <div class="flex items-start gap-2">
                <div class="mt-0.5 text-lg">⚠️</div>
                <div>
                    <p class="font-semibold">
                        Stok beberapa item tidak mencukupi. Sesuaikan qty di keranjang sebelum melanjutkan.
                    </p>
                    <p class="text-[11px] sm:text-xs text-amber-800/90">
                        Turunkan qty sesuai stok sistem atau hapus item yang stoknya sudah habis, lalu coba checkout lagi.
                    </p>
                </div>
            </div>

            <div class="mt-2 rounded-xl border border-amber-200 bg-white/80 overflow-hidden">
                <!-- Desktop: tabel -->
                <div class="hidden md:block">
                    <table class="min-w-full text-xs text-slate-900">
                        <thead class="bg-amber-50">
                        <tr class="text-[11px] uppercase tracking-[0.16em] text-amber-700">
                            <th class="px-3 py-2 text-left">Barcode</th>
                            <th class="px-3 py-2 text-left">Nama</th>
                            <th class="px-3 py-2 text-center">Qty di cart</th>
                            <th class="px-3 py-2 text-center">Stok sistem</th>
                            <th class="px-3 py-2 text-center">Kurang</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-100">
                        <?php foreach ($stockErrors as $err): ?>
                            <tr>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] text-amber-800">
                                        <?= esc($err['barcode']); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="font-medium text-amber-900"><?= esc($err['nama']); ?></span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <?= (int) $err['qty_cart']; ?>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <?= (int) $err['stok_sistem']; ?>
                                </td>
                                <td class="px-3 py-2 text-center text-red-700 font-semibold">
                                    -<?= (int) $err['selisih']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile: card per item -->
                <div class="grid gap-2 p-3 md:hidden">
                    <?php foreach ($stockErrors as $err): ?>
                        <div class="rounded-lg border border-amber-100 bg-amber-50/70 px-3 py-2 text-slate-900">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-xs font-semibold text-amber-900">
                                        <?= esc($err['nama']); ?>
                                    </div>
                                    <div class="text-[11px] text-amber-800">
                                        Barcode: <span class="font-mono"><?= esc($err['barcode']); ?></span>
                                    </div>
                                </div>
                                <div class="text-right text-[11px] text-amber-900">
                                    <div>Cart: <span class="font-semibold"><?= (int) $err['qty_cart']; ?></span></div>
                                    <div>Stok: <span class="font-semibold"><?= (int) $err['stok_sistem']; ?></span></div>
                                </div>
                            </div>
                            <div class="mt-1 text-[11px] text-red-700">
                                Kurang <span class="font-semibold">-<?= (int) $err['selisih']; ?></span> pcs
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Layout 2 kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5">
        <!-- KIRI: Scan & Pembayaran -->
        <div class="space-y-4">
            <!-- Scan / Input Barang -->
            <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div>
                        <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                            Scan / Input Barang
                        </p>
                        <h2 class="mt-1 text-base sm:text-lg font-semibold text-slate-900">
                            Tambahkan item ke keranjang
                        </h2>
                    </div>
                    <span class="hidden sm:inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] text-slate-600">
                        Gunakan barcode / ketik manual
                    </span>
                </div>

                <?php $cartSuccess = session()->getFlashdata('cart_success'); ?>
                <?php if (! empty($cartSuccess)): ?>
                    <div class="mb-3 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-[11px] sm:text-xs text-emerald-800">
                        ✅ <?= esc($cartSuccess); ?>
                    </div>
                <?php endif; ?>

                <div class="space-y-3">
                    <!-- Tombol scan kamera -->
                    <button type="button"
                            id="btn-open-scanner"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300
                                   bg-slate-50 px-3 py-2 text-xs sm:text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                        <span>📷</span>
                        <span>Scan barcode dengan kamera</span>
                        <span class="hidden sm:inline text-[10px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                            aktif
                        </span>
                    </button>

                    <!-- Area scanner -->
                    <div id="scanner-wrapper" class="mt-2 hidden rounded-xl border border-dashed border-slate-300 bg-slate-50 p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                                Mode kamera
                            </span>
                            <button type="button"
                                    id="btn-close-scanner"
                                    class="text-[11px] text-slate-500 hover:text-slate-700">
                                Tutup
                            </button>
                        </div>
                        <div id="reader"
                             class="w-full aspect-video rounded-lg bg-slate-900/90 flex items-center justify-center text-[11px] text-slate-200">
                            Menginisialisasi kamera...
                        </div>
                        <p class="mt-2 text-[11px] text-slate-500">
                            Arahkan barcode ke kotak kamera. Setelah terbaca, kode akan otomatis terisi dan barang langsung ditambahkan ke keranjang.
                        </p>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Catatan: Fitur ini membutuhkan akses kamera dan sebaiknya dijalankan melalui HTTPS atau localhost.
                        </p>
                        <p id="scanner-error" class="mt-1 text-[11px] text-red-600 hidden"></p>
                    </div>

                    <!-- Form tambah item -->
                    <form id="form-add-item" action="<?= site_url('kasir/pos/addItem'); ?>" method="post" class="space-y-3">
                        <?= csrf_field() ?>

                        <div class="space-y-1 relative">
                            <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                                Barcode / Nama barang
                            </label>
                            <input type="text"
                                   name="barcode"
                                   id="barcode-input"
                                   autocomplete="off"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                                          placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                   placeholder="Scan barcode atau ketik nama barang">
                            <p class="mt-1 text-[10px] text-slate-400">
                                Ketik minimal 2 huruf, daftar barang yang cocok akan muncul.  
                                Tips: tekan <span class="font-semibold text-slate-600">F2</span> untuk fokus ke kolom ini.
                            </p>

                            <!-- Dropdown suggestion produk -->
                            <div id="product-suggestions"
                                 class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg
                                        max-h-64 overflow-auto text-sm text-slate-900 hidden">
                                <!-- diisi via JS -->
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                                Qty
                            </label>
                            <input type="number"
                                   name="qty"
                                   min="1"
                                   value="1"
                                   required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                                          focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center rounded-full bg-brand-600 hover:bg-brand-700
                                       px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                            + Tambah ke cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pembayaran -->
            <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div>
                        <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                            Pembayaran
                        </p>
                        <h2 class="mt-1 text-base sm:text-lg font-semibold text-slate-900">
                            Ringkasan & proses pembayaran
                        </h2>
                    </div>
                    <?php if (! empty($cart)): ?>
                        <div class="text-right">
                            <p class="text-[11px] text-slate-500 uppercase tracking-[0.14em]">Total</p>
                            <p class="text-lg font-bold text-emerald-600">
                                Rp <?= number_format($total, 0, ',', '.'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <form id="form-checkout" action="<?= site_url('kasir/pos/checkout'); ?>" method="post" class="space-y-3">
                    <?= csrf_field() ?>

                    <div class="space-y-1">
                        <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                            Total yang harus dibayar
                        </label>
                        <div class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 flex items-center justify-between">
                            <span class="text-[11px] text-slate-500">Grand total</span>
                            <span class="font-semibold">
                                Rp <?= number_format($total, 0, ',', '.'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                            Metode pembayaran
                        </label>
                        <select name="payment_method"
                                id="payment_method"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                                       focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                required>
                            <option value="cash">Tunai</option>
                            <option value="qris">QRIS</option>
                        </select>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Tips: tekan <span class="font-semibold text-slate-600">F4</span> untuk fokus ke nominal bayar, <span class="font-semibold text-slate-600">F9</span> untuk submit pembayaran.
                        </p>
                    </div>

                    <!-- Tunai -->
                    <div class="space-y-1" id="amountPaidGroup">
                        <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                            Nominal diterima (Tunai)
                        </label>
                        <div class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2">
                            <span class="text-xs text-slate-500">Rp</span>
                            <!-- INPUT DISPLAY (format rupiah) -->
                            <input
                                type="text"
                                id="amount_paid_display"
                                class="w-full bg-transparent text-sm text-slate-900 focus:outline-none"
                                inputmode="numeric"
                                autocomplete="off">
                            <!-- INPUT HIDDEN (nilai asli utk backend) -->
                            <input type="hidden" name="amount_paid" id="amount_paid">
                        </div>
                        <p class="text-[11px] text-slate-500">
                            Masukkan nominal uang yang diterima dari pelanggan.
                        </p>

                        <!-- Info kembalian -->
                        <div id="change-info" class="mt-1 text-[11px] text-emerald-700 hidden">
                            Kembalian: <span id="change-amount" class="font-semibold">Rp 0</span>
                        </div>
                        <div id="change-warning" class="mt-1 text-[11px] text-red-600 hidden">
                            Nominal diterima kurang dari total (Rp <?= number_format($total, 0, ',', '.'); ?>).
                        </div>
                    </div>

                    <!-- QRIS (info saja, QR di popup) -->
                    <div id="qris-section" class="hidden space-y-2">
                        <div class="rounded-xl border border-dashed border-emerald-300 bg-emerald-50/50 p-3">
                            <p class="text-xs font-medium text-emerald-800 mb-1">
                                QRIS
                            </p>
                            <div class="text-[11px] text-slate-600 space-y-1">
                                <p>
                                    Metode pembayaran <span class="font-semibold">QRIS</span> dipilih.
                                    QR akan tampil di popup otomatis.
                                </p>
                                <p>
                                    ID Transaksi:
                                    <span id="qris-txid" class="font-mono text-xs font-semibold text-slate-900">
                                        -
                                    </span>
                                </p>
                                <p class="text-[10px] text-slate-500">
                                    Pembayaran QRIS pada aplikasi kasir ini belum terhubung ke payment gateway,
                                    status dibayar ditentukan oleh kasir saat menekan tombol
                                    <span class="font-semibold">Sudah dibayar</span> di popup QR.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button id="btn-submit-checkout"
                            type="submit"
                            class="w-full inline-flex items-center justify-center rounded-full
                                   bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-xs sm:text-sm
                                   font-semibold text-white shadow-sm transition disabled:opacity-50"
                            <?= empty($cart) ? 'disabled' : ''; ?>>
                        Selesaikan transaksi &amp; cetak struk
                    </button>
                    <p class="text-[11px] text-slate-500">
                        Setelah transaksi tersimpan, sistem akan mengarahkan ke halaman struk.
                    </p>
                </form>
            </div>
        </div>

        <!-- KANAN: Cart -->
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm flex flex-col h-full">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div>
                    <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                        Cart
                    </p>
                    <h2 class="mt-1 text-base sm:text-lg font-semibold text-slate-900">
                        Daftar item di keranjang
                    </h2>
                </div>

                <div class="flex items-center gap-2">
                    <?php if (! empty($cart)): ?>
                        <span class="hidden sm:inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] text-slate-600">
                            <?= count($cart); ?> item • Rp <?= number_format($total, 0, ',', '.'); ?>
                        </span>

                        <!-- Tombol kosongkan cart -->
                        <form action="<?= site_url('kasir/pos/clearCart'); ?>" method="get"
                              onsubmit="return confirm('Kosongkan seluruh keranjang?');">
                            <button type="submit"
                                    class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-[11px] font-medium text-red-600 hover:bg-red-100">
                                Kosongkan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex-1 overflow-hidden">
                <div class="h-full overflow-auto rounded-xl border border-slate-100">
                    <table class="min-w-full text-xs sm:text-sm text-slate-900">
                        <thead class="bg-slate-50">
                        <tr class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                            <th class="px-3 py-2 text-left">Barcode</th>
                            <th class="px-3 py-2 text-left">Nama</th>
                            <th class="px-3 py-2 text-center">Qty</th>
                            <th class="px-3 py-2 text-right">Harga</th>
                            <th class="px-3 py-2 text-right">Subtotal</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        <?php if (! empty($cart)): ?>
                            <?php foreach ($cart as $item): ?>
                                <?php $subtotal = $item['qty'] * $item['harga_jual']; ?>
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-3 py-2 align-middle">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-700">
                                            <?= esc($item['barcode']); ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-slate-800">
                                        <?= esc($item['nama']); ?>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-center">
                                        <form action="<?= site_url('kasir/pos/updateQty'); ?>" method="get" class="inline-flex items-center gap-1">
                                            <input type="hidden" name="product_id" value="<?= esc($item['product_id']); ?>">
                                            <input
                                                type="number"
                                                name="qty"
                                                min="0"
                                                value="<?= (int) $item['qty']; ?>"
                                                class="w-14 rounded-lg border border-slate-300 bg-white px-2 py-1 text-center text-xs text-slate-900
                                                       focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500">
                                            <button type="submit"
                                                    class="hidden sm:inline-flex rounded-lg bg-slate-900 px-2.5 py-1 text-[11px] font-medium text-white hover:bg-slate-800">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-right text-slate-700 whitespace-nowrap">
                                        Rp <?= number_format($item['harga_jual'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-right font-semibold text-slate-900 whitespace-nowrap">
                                        Rp <?= number_format($subtotal, 0, ',', '.'); ?>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <form action="<?= site_url('kasir/pos/updateQty'); ?>" method="get" class="sm:hidden">
                                                <input type="hidden" name="product_id" value="<?= esc($item['product_id']); ?>">
                                                <input type="hidden" name="qty" value="<?= (int) $item['qty']; ?>">
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-lg bg-slate-900 px-2 py-1 text-[11px] font-medium text-white hover:bg-slate-800">
                                                    ✔
                                                </button>
                                            </form>

                                            <form action="<?= site_url('kasir/pos/removeItem'); ?>" method="get"
                                                  onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                                <input type="hidden" name="product_id" value="<?= esc($item['product_id']); ?>">
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-2 py-1 text-[11px] font-medium text-red-600 hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-3 py-6 text-center text-[11px] sm:text-sm text-slate-500">
                                    Keranjang masih kosong.  
                                    Tambahkan barang dari sisi kiri terlebih dahulu.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                        <?php if (! empty($cart)): ?>
                            <tfoot class="border-t border-slate-100 bg-slate-50/60">
                            <tr>
                                <th colspan="4" class="px-3 py-2 text-right text-[11px] uppercase tracking-[0.16em] text-slate-500">
                                    Total
                                </th>
                                <th class="px-3 py-2 text-right text-sm font-bold text-emerald-600">
                                    Rp <?= number_format($total, 0, ',', '.'); ?>
                                </th>
                                <th class="px-3 py-2"></th>
                            </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL QRIS -->
<div id="qrisModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-5 relative">
        <button type="button"
                id="qrisModalClose"
                class="absolute right-3 top-3 text-slate-400 hover:text-slate-700 text-xl leading-none">
            &times;
        </button>

        <div class="text-center mb-4">
            <p class="text-[11px] font-semibold tracking-[0.16em] text-slate-500 uppercase">
                Pembayaran QRIS
            </p>
            <h2 class="mt-1 text-base font-semibold text-slate-900">
                Scan QR untuk membayar
            </h2>
        </div>

        <div class="mb-3 text-center">
            <p class="text-[11px] text-slate-500">Total yang harus dibayar:</p>
            <p class="text-lg font-semibold text-emerald-700">
                Rp <?= number_format($total, 0, ',', '.'); ?>
            </p>
        </div>

        <div class="flex items-center justify-center mb-3">
            <div id="qris-qr-modal"
                 class="p-3 border border-slate-200 rounded-xl bg-white">
                <img id="qris-qr-img"
                     src=""
                     alt="Kode QRIS"
                     class="w-40 h-40 object-contain">
            </div>
        </div>

        <div class="text-center text-[11px] text-slate-500">
            <p>ID Transaksi: <span id="qris-modal-txid">-</span></p>
            <p class="mt-1">Pastikan nominal sudah sesuai sebelum konfirmasi lunas.</p>
        </div>

        <div class="mt-4 flex justify-center">
            <button type="button"
                    id="qrisModalDone"
                    class="inline-flex items-center justify-center rounded-xl border border-emerald-600 px-4 py-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-emerald-700 hover:bg-emerald-50">
                Sudah dibayar
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI TUNAI -->
<div id="cashConfirmModal"
     class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-5">
        <div class="text-center mb-3">
            <p class="text-[11px] font-semibold tracking-[0.16em] text-slate-500 uppercase">
                Konfirmasi Pembayaran Tunai
            </p>
            <h2 class="mt-1 text-base font-semibold text-slate-900">
                Cek kembali sebelum proses
            </h2>
        </div>

        <div class="space-y-1 text-sm text-slate-800 mb-3">
            <div class="flex justify-between">
                <span>Total belanja</span>
                <span id="cashConfirmTotal" class="font-semibold">Rp 0</span>
            </div>
            <div class="flex justify-between">
                <span>Nominal diterima</span>
                <span id="cashConfirmPaid" class="font-semibold">Rp 0</span>
            </div>
            <div class="flex justify-between">
                <span>Kembalian</span>
                <span id="cashConfirmChange" class="font-semibold">Rp 0</span>
            </div>
            <p id="cashConfirmStatus" class="text-[11px] mt-2"></p>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button"
                    id="cashConfirmCancel"
                    class="px-3 py-1.5 rounded-full border border-slate-200 text-[11px] font-medium text-slate-600 hover:bg-slate-100">
                Batal
            </button>
            <button type="button"
                    id="cashConfirmOk"
                    class="px-4 py-1.5 rounded-full bg-emerald-600 text-[11px] font-semibold text-white hover:bg-emerald-700">
                Proses transaksi
            </button>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    // =============== Scanner ===============
    const btnOpenScanner  = document.getElementById('btn-open-scanner');
    const btnCloseScanner = document.getElementById('btn-close-scanner');
    const scannerWrapper  = document.getElementById('scanner-wrapper');
    const scannerErrorEl  = document.getElementById('scanner-error');
    const barcodeInput    = document.getElementById('barcode-input');
    const formAddItem     = document.getElementById('form-add-item');

    let html5QrInstance   = null;
    let scannerRunning    = false;

    function showScannerError(msg) {
        if (!scannerErrorEl) return;
        scannerErrorEl.textContent = msg;
        scannerErrorEl.classList.remove('hidden');
    }
    function clearScannerError() {
        if (!scannerErrorEl) return;
        scannerErrorEl.textContent = '';
        scannerErrorEl.classList.add('hidden');
    }

    async function startScanner() {
        if (!scannerWrapper) return;
        clearScannerError();

        if (typeof Html5Qrcode === 'undefined') {
            showScannerError('Library scanner belum termuat. Pastikan koneksi internet stabil.');
            return;
        }

        scannerWrapper.classList.remove('hidden');

        try {
            if (!html5QrInstance) {
                html5QrInstance = new Html5Qrcode("reader");
            }

            const devices = await Html5Qrcode.getCameras();
            if (!devices || devices.length === 0) {
                showScannerError('Kamera tidak ditemukan pada perangkat ini.');
                return;
            }

            let cameraId = devices[0].id;
            const backCam = devices.find(d =>
                (d.label || '').toLowerCase().includes('back') ||
                (d.label || '').toLowerCase().includes('belakang')
            );
            if (backCam) {
                cameraId = backCam.id;
            }

            const config = {
                fps: 10,
                qrbox: (w,h) => {
                    const size = Math.min(280, Math.floor(w * 0.7));
                    return { width: size, height: size };
                }
            };

            await html5QrInstance.start(
                cameraId,
                config,
                (decodedText) => {
                    const text = (decodedText || '').trim();
                    if (!text) return;

                    if (barcodeInput) barcodeInput.value = text;
                    if (formAddItem) formAddItem.submit();
                    stopScanner();
                },
                () => {}
            );

            scannerRunning = true;
        } catch (err) {
            console.error(err);
            showScannerError('Tidak dapat mengakses kamera. Pastikan izin kamera aktif dan gunakan HTTPS / localhost.');
            scannerRunning = false;
        }
    }

    function stopScanner() {
        if (!html5QrInstance) {
            if (scannerWrapper) scannerWrapper.classList.add('hidden');
            return;
        }

        if (scannerRunning) {
            html5QrInstance.stop()
                .then(() => html5QrInstance.clear())
                .catch(err => console.error(err))
                .finally(() => {
                    scannerRunning = false;
                    if (scannerWrapper) scannerWrapper.classList.add('hidden');
                });
        } else {
            if (scannerWrapper) scannerWrapper.classList.add('hidden');
        }
    }

    if (btnOpenScanner)  btnOpenScanner.addEventListener('click', startScanner);
    if (btnCloseScanner) btnCloseScanner.addEventListener('click', stopScanner);

    // =============== Pembayaran & format rupiah ===============
    const paymentSelect      = document.getElementById('payment_method');
    const amountPaidGroup    = document.getElementById('amountPaidGroup');
    const amountPaidHidden   = document.getElementById('amount_paid');
    const amountPaidDisplay  = document.getElementById('amount_paid_display');
    const qrisSection        = document.getElementById('qris-section');
    const qrisTxIdSpan       = document.getElementById('qris-txid');
    const totalAmount        = <?= (float) $total; ?>;
    const checkoutForm       = document.getElementById('form-checkout');
    const btnSubmitCheckout  = document.getElementById('btn-submit-checkout');

    const changeInfoEl    = document.getElementById('change-info');
    const changeAmountEl  = document.getElementById('change-amount');
    const changeWarningEl = document.getElementById('change-warning');

    function formatRupiah(num) {
        if (isNaN(num)) return '0';
        return new Intl.NumberFormat('id-ID').format(Math.round(num));
    }

    function getAmountPaidNumeric() {
        if (!amountPaidHidden) return 0;
        const val = parseInt(amountPaidHidden.value || '0', 10);
        return isNaN(val) ? 0 : val;
    }

    function syncAmountFromDisplay() {
        if (!amountPaidDisplay || !amountPaidHidden) return;

        const raw     = (amountPaidDisplay.value || '').replace(/\D/g, '');
        const numeric = raw === '' ? 0 : parseInt(raw, 10);

        amountPaidHidden.value  = numeric;
        amountPaidDisplay.value = numeric ? formatRupiah(numeric) : '';

        updateChangeDisplay();
    }

    function updateChangeDisplay() {
        if (!changeInfoEl || !changeAmountEl || !changeWarningEl) return;

        if (paymentSelect && paymentSelect.value === 'qris') {
            changeInfoEl.classList.add('hidden');
            changeWarningEl.classList.add('hidden');
            return;
        }

        const paid   = getAmountPaidNumeric();
        const change = paid - totalAmount;

        if (!paid) {
            changeInfoEl.classList.add('hidden');
            changeWarningEl.classList.add('hidden');
            return;
        }

        if (change < 0) {
            changeInfoEl.classList.add('hidden');
            changeWarningEl.classList.remove('hidden');
        } else {
            changeWarningEl.classList.add('hidden');
            changeInfoEl.classList.remove('hidden');
            changeAmountEl.textContent = 'Rp ' + formatRupiah(change);
        }
    }

    function generateTxId() {
        const now = new Date();
        const pad = n => n.toString().padStart(2, '0');
        return 'TX' +
            now.getFullYear().toString() +
            pad(now.getMonth() + 1) +
            pad(now.getDate()) +
            pad(now.getHours()) +
            pad(now.getMinutes()) +
            pad(now.getSeconds());
    }

    // =============== MODAL QRIS (online QR) ===============
    const qrisModal      = document.getElementById('qrisModal');
    const qrisModalClose = document.getElementById('qrisModalClose');
    const qrisModalDone  = document.getElementById('qrisModalDone');
    const qrisModalTxid  = document.getElementById('qris-modal-txid');
    const qrisQrImg      = document.getElementById('qris-qr-img');

    function buildQrisPayload() {
        const txid = qrisTxIdSpan ? qrisTxIdSpan.textContent : '';
        return 'TOKO=Toko Z&Z|TOTAL=' + totalAmount + '|TXID=' + txid;
    }

    function updateQrisImage() {
        if (!qrisQrImg) return;
        const payload = buildQrisPayload();
        const encoded = encodeURIComponent(payload);
        qrisQrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encoded;
    }

    function openQrisModal() {
        if (!qrisModal) return;
        if (qrisModalTxid && qrisTxIdSpan) {
            qrisModalTxid.textContent = qrisTxIdSpan.textContent;
        }
        updateQrisImage();
        qrisModal.classList.remove('hidden');
        qrisModal.classList.add('flex');
    }
    function closeQrisModal() {
        if (!qrisModal) return;
        qrisModal.classList.add('hidden');
        qrisModal.classList.remove('flex');
    }

    if (qrisModalClose) qrisModalClose.addEventListener('click', closeQrisModal);
    if (qrisModalDone) {
        qrisModalDone.addEventListener('click', () => {
            closeQrisModal();
            if (checkoutForm && btnSubmitCheckout && !btnSubmitCheckout.disabled) {
                btnSubmitCheckout.disabled = true;
                checkoutForm.submit();
            }
        });
    }
    if (qrisModal) {
        qrisModal.addEventListener('click', e => {
            if (e.target === qrisModal) closeQrisModal();
        });
    }

    // =============== MODAL KONFIRMASI TUNAI ===============
    const cashConfirmModal   = document.getElementById('cashConfirmModal');
    const cashConfirmTotal   = document.getElementById('cashConfirmTotal');
    const cashConfirmPaid    = document.getElementById('cashConfirmPaid');
    const cashConfirmChange  = document.getElementById('cashConfirmChange');
    const cashConfirmStatus  = document.getElementById('cashConfirmStatus');
    const cashConfirmCancel  = document.getElementById('cashConfirmCancel');
    const cashConfirmOk      = document.getElementById('cashConfirmOk');

    let cashConfirmed = false; // flag supaya submit kedua kali tidak buka modal lagi

    function openCashConfirmModal(total, paid) {
        if (!cashConfirmModal) return;

        const change = paid - total;

        if (cashConfirmTotal)  cashConfirmTotal.textContent  = 'Rp ' + formatRupiah(total);
        if (cashConfirmPaid)   cashConfirmPaid.textContent   = 'Rp ' + formatRupiah(paid);
        if (cashConfirmChange) cashConfirmChange.textContent = 'Rp ' + formatRupiah(change >= 0 ? change : 0);

        if (cashConfirmStatus) {
            if (!paid) {
                cashConfirmStatus.textContent = 'Nominal diterima belum diisi.';
                cashConfirmStatus.className = 'text-[11px] text-red-600';
            } else if (change < 0) {
                cashConfirmStatus.textContent = 'Nominal diterima kurang Rp ' + formatRupiah(-change) + '.';
                cashConfirmStatus.className = 'text-[11px] text-red-600';
            } else {
                cashConfirmStatus.textContent = 'Pastikan kembalian sudah benar sebelum melanjutkan.';
                cashConfirmStatus.className = 'text-[11px] text-slate-500';
            }
        }

        cashConfirmModal.classList.remove('hidden');
        cashConfirmModal.classList.add('flex');
    }

    function closeCashConfirmModal() {
        if (!cashConfirmModal) return;
        cashConfirmModal.classList.add('hidden');
        cashConfirmModal.classList.remove('flex');
    }

    if (cashConfirmCancel) {
        cashConfirmCancel.addEventListener('click', () => {
            closeCashConfirmModal();
            cashConfirmed = false;
        });
    }

    if (cashConfirmOk) {
        cashConfirmOk.addEventListener('click', () => {
            closeCashConfirmModal();
            cashConfirmed = true;
            if (checkoutForm) checkoutForm.submit();
        });
    }

    // Klik di luar modal = batal
    if (cashConfirmModal) {
        cashConfirmModal.addEventListener('click', (e) => {
            if (e.target === cashConfirmModal) {
                closeCashConfirmModal();
                cashConfirmed = false;
            }
        });
    }

    // =============== Update UI pembayaran ===============
    function updatePaymentUI() {
        if (!paymentSelect) return;
        const method = paymentSelect.value;

        if (method === 'qris') {
            if (amountPaidGroup) amountPaidGroup.classList.add('hidden');
            if (amountPaidHidden) amountPaidHidden.value = totalAmount;
            if (amountPaidDisplay) amountPaidDisplay.value = totalAmount ? formatRupiah(totalAmount) : '';
            if (qrisSection) qrisSection.classList.remove('hidden');
            if (qrisTxIdSpan) qrisTxIdSpan.textContent = generateTxId();
            openQrisModal();
        } else {
            if (amountPaidGroup) amountPaidGroup.classList.remove('hidden');
            if (qrisSection) qrisSection.classList.add('hidden');
            if (qrisTxIdSpan) qrisTxIdSpan.textContent = '-';
            closeQrisModal();
        }
        updateChangeDisplay();
    }

    if (paymentSelect) paymentSelect.addEventListener('change', updatePaymentUI);

    // =============== Submit checkout (konfirmasi tunai) ===============
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            // Kalau QRIS → biarkan flow normal (konfirmasi di popup QRIS)
            if (paymentSelect && paymentSelect.value === 'qris') {
                return;
            }

            // Kalau sudah dikonfirmasi sekali → izinkan submit
            if (cashConfirmed) {
                cashConfirmed = false; // reset untuk transaksi berikutnya
                return;
            }

            // TUNAI: intercept dan buka modal
            e.preventDefault();
            syncAmountFromDisplay();
            const paid = getAmountPaidNumeric();
            openCashConfirmModal(totalAmount, paid);
        });
    }

    // =============== Input nominal (format otomatis) ===============
    if (amountPaidDisplay) {
        amountPaidDisplay.addEventListener('input', syncAmountFromDisplay);
        amountPaidDisplay.addEventListener('change', syncAmountFromDisplay);

        amountPaidDisplay.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                syncAmountFromDisplay();
                if (checkoutForm && btnSubmitCheckout && !btnSubmitCheckout.disabled) {
                    checkoutForm.submit();
                }
            }
        });
    }

    // =============== UX tambahan ===============
    window.addEventListener('DOMContentLoaded', () => {
        if (amountPaidHidden) amountPaidHidden.value = totalAmount;
        if (amountPaidDisplay) amountPaidDisplay.value = totalAmount ? formatRupiah(totalAmount) : '';
        updatePaymentUI();

        if (barcodeInput) {
            barcodeInput.focus();
            barcodeInput.select();
        }
    });

    if (barcodeInput && formAddItem) {
        barcodeInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                formAddItem.submit();
            }
        });
    }

    window.addEventListener('keydown', (e) => {
        const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
        const isTyping = tag === 'input' || tag === 'textarea' || e.target.isContentEditable;

        if (e.key === 'F2') {
            e.preventDefault();
            if (barcodeInput) {
                barcodeInput.focus();
                barcodeInput.select();
            }
        }

        if (e.key === 'F4') {
            e.preventDefault();
            if (amountPaidDisplay && !amountPaidGroup.classList.contains('hidden')) {
                amountPaidDisplay.focus();
                amountPaidDisplay.select();
            }
        }

        if (e.key === 'F9') {
            e.preventDefault();
            if (checkoutForm && btnSubmitCheckout && !btnSubmitCheckout.disabled) {
                checkoutForm.submit();
            }
        }
    });

    // =============== Autocomplete produk ===============
    const suggestionBox = document.getElementById('product-suggestions');
    let suggestionTimeout = null;

    function hideSuggestions() {
        if (suggestionBox) {
            suggestionBox.classList.add('hidden');
            suggestionBox.innerHTML = '';
        }
    }

    function renderSuggestions(items) {
        if (!suggestionBox) return;
        if (!items || items.length === 0) {
            hideSuggestions();
            return;
        }

        const html = items.map(item => {
            const nama    = item.nama ?? '';
            const barcode = item.barcode ?? '';
            const harga   = item.harga_jual ? Number(item.harga_jual) : 0;
            const stok    = Number(item.stok ?? 0);
            const satuan  = item.satuan ?? '';

            const hargaFormatted = new Intl.NumberFormat('id-ID').format(harga);

            const stokClass =
                stok <= 0 ? 'text-red-600 font-semibold' :
                stok <= 5 ? 'text-amber-600 font-medium' :
                            'text-slate-500';

            const stokLabel =
                stok <= 0 ? 'Habis' :
                stok <= 5 ? `Stok menipis: ${stok}` :
                            `Stok: ${stok}`;

            return `
    <button type="button"
            class="w-full text-left px-3 py-2 hover:bg-slate-100 flex flex-col gap-0.5"
            data-barcode="${barcode.replace(/"/g, '&quot;')}"
            data-name="${nama.replace(/"/g, '&quot;')}">
        <div class="flex items-center justify-between gap-2">
            <span class="text-xs font-semibold text-slate-900">${nama}</span>
            <span class="text-[11px] font-mono text-slate-500">${barcode}</span>
        </div>
        <div class="text-[11px] flex items-center justify-between text-slate-500">
            <span>Rp ${hargaFormatted}${satuan ? ' / ' + satuan : ''}</span>
            <span class="${stokClass}">${stokLabel}</span>
        </div>
    </button>
`;
        }).join('');

        suggestionBox.innerHTML = html;
        suggestionBox.classList.remove('hidden');
    }

    async function fetchSuggestions(query) {
        if (!suggestionBox) return;

        const trimmed = query.trim();
        if (trimmed.length < 2) {
            hideSuggestions();
            return;
        }

        try {
            const url = "<?= site_url('kasir/pos/search-product'); ?>" + "?q=" + encodeURIComponent(trimmed);
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) {
                hideSuggestions();
                return;
            }
            const data = await res.json();
            if (data.status === 'ok') {
                renderSuggestions(data.data || []);
            } else {
                hideSuggestions();
            }
        } catch (err) {
            console.error('Gagal fetch suggestions:', err);
            hideSuggestions();
        }
    }

    if (barcodeInput) {
        barcodeInput.addEventListener('input', (e) => {
            const val = e.target.value;
            clearTimeout(suggestionTimeout);
            suggestionTimeout = setTimeout(() => {
                fetchSuggestions(val);
            }, 250);
        });

        barcodeInput.addEventListener('focus', (e) => {
            const val = e.target.value;
            if (val && val.trim().length >= 2) fetchSuggestions(val);
        });
    }

    if (suggestionBox) {
        suggestionBox.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-barcode]');
            if (!btn) return;

            const barcode = btn.getAttribute('data-barcode') || '';
            if (barcodeInput) {
                barcodeInput.value = barcode;
                barcodeInput.focus();
                barcodeInput.select();
            }

            hideSuggestions();
            if (formAddItem) formAddItem.submit();
        });
    }

    document.addEventListener('click', (e) => {
        if (!suggestionBox || !barcodeInput) return;
        if (suggestionBox.contains(e.target) || barcodeInput.contains(e.target)) return;
        hideSuggestions();
    });

    if (barcodeInput) {
        barcodeInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') hideSuggestions();
        });
    }
</script>
<?= $this->endSection(); ?>
