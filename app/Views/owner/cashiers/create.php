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
                Tambah Kasir Baru
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Buat akun kasir yang dapat login ke sistem menggunakan username &amp; password default.
            </p>
        </div>
        <a href="<?= site_url('owner/cashiers'); ?>"
           class="inline-flex items-center rounded-full border border-slate-300
                  text-slate-700 text-xs sm:text-sm font-semibold px-3 py-1.5 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <!-- Card form -->
    <form action="<?= site_url('owner/cashiers/store'); ?>" method="post"
          class="w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-6">
        <?= csrf_field(); ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Username
                </label>
                <input type="text" name="username" required
                       value="<?= old('username'); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                       placeholder="contoh: kasir1">
                <p class="mt-1 text-[11px] text-slate-500">
                    Username harus unik. Digunakan saat kasir login.
                </p>
            </div>

            <div>
                <label class="block text-[11px] font-medium text-slate-600 mb-1 uppercase tracking-[0.14em]">
                    Nama Lengkap
                </label>
                <input type="text" name="nama" required
                       value="<?= old('nama'); ?>"
                       class="w-full rounded-lg border border-slate-300 bg-white text-slate-900
                              px-3 py-2 text-sm placeholder:text-slate-400
                              focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                       placeholder="contoh: Budi Santoso">
                <p class="mt-1 text-[11px] text-slate-500">
                    Nama kasir yang akan tampil di dashboard dan laporan.
                </p>
            </div>
        </div>

        <!-- Info password default & role -->
        <div class="grid grid-cols-1 lg:grid-cols-[2fr,1.2fr] gap-4">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-[11px] sm:text-xs text-slate-600 space-y-2">
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-slate-900 flex items-center justify-center text-[13px] text-slate-50">
                        🔐
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">
                            Password default kasir
                        </p>
                        <p>
                            Secara otomatis, password awal akun kasir adalah:
                            <span class="font-mono font-semibold bg-slate-900 text-slate-50 px-2 py-0.5 rounded-full text-[11px]">
                                password
                            </span>
                        </p>
                    </div>
                </div>
                <ul class="list-disc list-inside space-y-1 mt-2">
                    <li>Kasir login dengan username dan password di atas.</li>
                    <li>Disarankan kasir mengganti password setelah login pertama kali.</li>
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-[11px] text-slate-600 space-y-2">
                <p class="font-semibold text-slate-800">
                    Ringkasan hak akses
                </p>
                <ul class="space-y-1">
                    <li>Role akun: <span class="font-semibold text-slate-900">kasir</span></li>
                    <li>Akses: Dashboard kasir &amp; POS.</li>
                    <li>Tidak bisa mengakses menu owner (produk, laporan, manajemen kasir).</li>
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
                Simpan Kasir
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>
