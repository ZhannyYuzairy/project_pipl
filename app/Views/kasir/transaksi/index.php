<?= $this->extend('layout/kasir_main'); ?>

<?= $this->section('styles'); ?>
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

<?php
$range     = $range ?? 'today';
$startDate = $startDate ?? null;
$endDate   = $endDate ?? null;
$q         = $q ?? '';
?>

<?= $this->section('content'); ?>

<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div>
            <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-500 uppercase">
                Riwayat Transaksi
            </p>
            <h1 class="mt-1 text-2xl md:text-3xl font-bold text-slate-900">
                Transaksi Kasir Saya
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Lihat transaksi yang pernah kamu proses dan cetak ulang struk jika diperlukan.
            </p>
        </div>

        <div class="flex flex-col items-end gap-2 text-right">
            <div class="inline-flex items-center gap-2 rounded-full bg-slate-900 text-slate-50 px-3 py-1.5 text-[11px]">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Kasir aktif</span>
            </div>
            <?php if (! empty($transaksi)): ?>
                <div class="text-[11px] text-slate-500">
                    <div>
                        Total transaksi: <span class="font-semibold text-slate-800"><?= count($transaksi); ?></span>
                    </div>
                    <div>
                        Total nominal: <span class="font-semibold text-emerald-600">
                            Rp <?= number_format($totalNominal, 0, ',', '.'); ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter + Search -->
    <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm space-y-4">
        <form action="<?= site_url('kasir/transaksi'); ?>" method="get" class="space-y-3">
            <div class="grid gap-3 md:grid-cols-[2fr,2fr,2fr,1.5fr]">
                <!-- Range cepat -->
                <div class="space-y-1">
                    <p class="text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                        Rentang cepat
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        <?php
                        $rangeOptions = [
                            'today'     => 'Hari ini',
                            'yesterday' => 'Kemarin',
                            '7days'     => '7 hari terakhir',
                            'all'       => 'Semua',
                        ];
                        ?>
                        <?php foreach ($rangeOptions as $key => $label): ?>
                            <button
                                type="submit"
                                name="range"
                                value="<?= esc($key); ?>"
                                class="inline-flex items-center rounded-full border px-3 py-1 text-[11px] font-medium
                                       <?= $range === $key ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100'; ?>">
                                <?= esc($label); ?>
                            </button>
                        <?php endforeach; ?>
                        <input type="hidden" name="start_date" value="<?= esc($startDate); ?>">
                        <input type="hidden" name="end_date" value="<?= esc($endDate); ?>">
                        <input type="hidden" name="q" value="<?= esc($q); ?>">
                    </div>
                </div>

                <!-- Tanggal mulai -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                        Tanggal mulai
                    </label>
                    <input
                        type="date"
                        name="start_date"
                        value="<?= esc($startDate); ?>"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    >
                </div>

                <!-- Tanggal selesai -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                        Tanggal selesai
                    </label>
                    <input
                        type="date"
                        name="end_date"
                        value="<?= esc($endDate); ?>"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                               focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    >
                </div>

                <!-- Search invoice -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                        Cari invoice
                    </label>
                    <div class="flex items-center gap-2">
                        <input
                            type="text"
                            name="q"
                            value="<?= esc($q); ?>"
                            placeholder="Contoh: INV202501010001"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                                   placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        >
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                <p class="text-[11px] text-slate-500">
                    Atur tanggal untuk menampilkan transaksi tertentu. Jika tanggal diisi manual, rentang cepat akan dianggap <strong>custom</strong>.
                </p>
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        name="range"
                        value="custom"
                        class="inline-flex items-center rounded-full bg-slate-900 px-4 py-1.5 text-[11px] font-semibold text-white hover:bg-slate-800"
                    >
                        Terapkan filter
                    </button>
                    <a href="<?= site_url('kasir/transaksi'); ?>"
                       class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-medium text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel transaksi -->
    <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
        <?php if (empty($transaksi)): ?>
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                Belum ada transaksi pada rentang tanggal ini.  
                Coba ubah filter tanggal atau proses transaksi terlebih dahulu.
            </div>
        <?php else: ?>
            <div class="flex items-center justify-between gap-2 mb-3">
                <div>
                    <p class="text-[11px] font-semibold tracking-[0.14em] text-slate-600 uppercase">
                        Hasil
                    </p>
                    <p class="text-xs text-slate-500">
                        Menampilkan <span class="font-semibold text-slate-800"><?= count($transaksi); ?></span> transaksi.
                    </p>
                </div>
                <div class="text-right text-[11px] text-slate-500">
                    <p>Total nominal:</p>
                    <p class="text-sm font-bold text-emerald-600">
                        Rp <?= number_format($totalNominal, 0, ',', '.'); ?>
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="min-w-full text-xs sm:text-sm text-slate-900">
                    <thead class="bg-slate-50">
                    <tr class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-left">Invoice</th>
                        <th class="px-3 py-2 text-right">Total</th>
                        <th class="px-3 py-2 text-center hidden md:table-cell">Metode</th>
                        <th class="px-3 py-2 text-center hidden md:table-cell">Dibayar</th>
                        <th class="px-3 py-2 text-center hidden md:table-cell">Kembali</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    <?php foreach ($transaksi as $trx): ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-3 py-2 align-middle whitespace-nowrap">
                                <div class="text-xs font-medium text-slate-800">
                                    <?= date('d/m/Y', strtotime($trx['sale_date'])); ?>
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    <?= date('H:i:s', strtotime($trx['sale_date'])); ?>
                                </div>
                            </td>
                            <td class="px-3 py-2 align-middle">
                                <div class="text-xs font-semibold text-slate-900">
                                    <?= esc($trx['invoice_no']); ?>
                                </div>
                                <div class="text-[11px] text-slate-500 md:hidden">
                                    Metode:
                                    <span class="font-medium text-slate-800">
                                        <?= strtoupper($trx['payment_method']); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-3 py-2 align-middle text-right whitespace-nowrap text-slate-900">
                                Rp <?= number_format($trx['total_amount'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-3 py-2 align-middle text-center hidden md:table-cell">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-700">
                                    <?= strtoupper($trx['payment_method']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 align-middle text-center hidden md:table-cell whitespace-nowrap text-slate-900">
                                Rp <?= number_format($trx['amount_paid'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-3 py-2 align-middle text-center hidden md:table-cell whitespace-nowrap text-slate-900">
                                Rp <?= number_format($trx['change_amount'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-3 py-2 align-middle text-center">
                                <a href="<?= site_url('kasir/pos/struk/' . $trx['id']); ?>"
                                   class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-white hover:bg-slate-800">
                                    Lihat / Cetak struk
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>
