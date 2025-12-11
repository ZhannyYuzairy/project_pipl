<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-[11px] font-semibold tracking-[0.24em] text-slate-500 uppercase">
                Manajemen Kasir
            </p>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Edit Kasir
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Perbarui informasi akun kasir tanpa mengubah password dan hak akses.
            </p>
        </div>
        <a href="<?= site_url('owner/cashiers'); ?>"
           class="inline-flex items-center rounded-full border border-slate-300
                  text-slate-700 text-xs sm:text-sm font-semibold px-3 py-1.5 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <!-- Card form -->
    <form action="<?= site_url('owner/cashiers/update/' . $kasir['id']); ?>" method="post"
          class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-6">
        <?= csrf_field(); ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Username
                </label>
                <input type="text" name="username" required
                       value="<?= old('username', $kasir['username']); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <p class="mt-1 text-[11px] text-slate-500">
                    Username harus unik. Digunakan saat kasir login.
                </p>
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Nama Lengkap
                </label>
                <input type="text" name="nama" required
                       value="<?= old('nama', $kasir['nama']); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <p class="mt-1 text-[11px] text-slate-500">
                    Nama kasir yang akan tampil di dashboard dan laporan.
                </p>
            </div>
        </div>

        <!-- Info status & password -->
        <div class="grid grid-cols-1 lg:grid-cols-[1.4fr,1.2fr] gap-4">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-[11px] sm:text-xs text-slate-600 space-y-2">
                <p class="font-semibold text-slate-800">
                    Status akun
                </p>
                <?php $active = isset($kasir['is_active']) ? (bool) $kasir['is_active'] : true; ?>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]
                                 <?= $active
                                     ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
                                     : 'bg-slate-100 text-slate-600 border border-slate-200'; ?>">
                        <span class="h-1.5 w-1.5 rounded-full <?= $active ? 'bg-emerald-500' : 'bg-slate-400'; ?>"></span>
                        <span><?= $active ? 'Aktif' : 'Nonaktif'; ?></span>
                    </span>
                    <span class="text-[11px] text-slate-500">
                        Pengaturan aktif/nonaktif dilakukan dari halaman daftar kasir.
                    </span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-[11px] text-slate-600 space-y-2">
                <p class="font-semibold text-slate-800">
                    Password & hak akses
                </p>
                <ul class="space-y-1">
                    <li>Password saat ini tidak diubah dari halaman ini.</li>
                    <li>Gunakan tombol <span class="font-semibold">Reset Pass</span> di halaman daftar kasir jika ingin mereset ke <span class="font-mono">password</span>.</li>
                    <li>Role tetap: <span class="font-semibold text-slate-900">kasir</span>.</li>
                </ul>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-2 pt-2">
            <a href="<?= site_url('owner/cashiers'); ?>"
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
