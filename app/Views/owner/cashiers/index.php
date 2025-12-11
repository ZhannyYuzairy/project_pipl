<?= $this->extend('layout/owner_main'); ?>

<?= $this->section('content'); ?>

<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="space-y-1">
            <p class="text-[11px] font-semibold tracking-[0.24em] text-slate-500 uppercase">
                Manajemen Kasir
            </p>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                Data Kasir Toko Z&Z
            </h1>
            <p class="text-sm text-slate-500">
                Kelola akun kasir yang dapat login ke sistem kasir Toko Z&amp;Z.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] text-slate-600">
                <span class="h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                Role <span class="ml-1 font-semibold text-slate-800">kasir</span> hanya punya akses POS.
            </div>
            <a href="<?= site_url('owner/cashiers/create'); ?>"
               class="inline-flex items-center justify-center rounded-full bg-brand-600 hover:bg-brand-700
                      text-white text-xs sm:text-sm font-semibold px-4 py-1.5 shadow-sm">
                + Tambah Kasir
            </a>
        </div>
    </div>

    <!-- Summary + Search -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="inline-flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5">
                Total kasir:
                <span class="ml-1 font-semibold text-slate-800">
                    <?= isset($cashiers) ? count($cashiers) : 0; ?>
                </span>
            </span>
            <span class="hidden sm:inline text-slate-400">•</span>
            <span class="hidden sm:inline">
                Reset password dan pengaturan status akses kasir diatur dari sini.
            </span>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative">
                <input
                    type="text"
                    id="cashier-search"
                    placeholder="Cari username / nama kasir..."
                    class="w-full sm:w-64 rounded-full border border-slate-300 bg-white px-3 py-1.5 pr-8
                           text-xs sm:text-sm placeholder:text-slate-400
                           focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <span class="absolute right-2 top-1.5 text-slate-400 text-xs">🔍</span>
            </div>
        </div>
    </div>

    <!-- Tabel kasir -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm" id="cashier-table">
                <thead class="bg-slate-50">
                <tr class="text-[11px] uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-2 text-left">Username</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left hidden md:table-cell">Role</th>
                    <th class="px-4 py-2 text-center hidden sm:table-cell">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <?php if (! empty($cashiers)): ?>
                    <?php foreach ($cashiers as $c): ?>
                        <?php
                        $active = isset($c['is_active']) ? (bool) $c['is_active'] : true;
                        ?>
                        <tr class="cashier-row hover:bg-slate-50 transition-colors"
                            data-username="<?= strtolower(esc($c['username'], 'attr')); ?>"
                            data-nama="<?= strtolower(esc($c['nama'], 'attr')); ?>">
                            <td class="px-4 py-2 align-middle">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-800">
                                    <?= esc($c['username']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 align-middle text-slate-800">
                                <?= esc($c['nama']); ?>
                            </td>
                            <td class="px-4 py-2 align-middle hidden md:table-cell">
                                <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5
                                             text-[11px] text-sky-700 border border-sky-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-sky-500 mr-1"></span>
                                    Kasir
                                </span>
                            </td>
                            <td class="px-4 py-2 align-middle text-center hidden sm:table-cell">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px]
                                             <?= $active
                                                 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
                                                 : 'bg-slate-100 text-slate-600 border border-slate-200'; ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?= $active ? 'bg-emerald-500' : 'bg-slate-400'; ?>"></span>
                                    <span><?= $active ? 'Aktif' : 'Nonaktif'; ?></span>
                                </span>
                            </td>
                            <td class="px-4 py-2 align-middle text-center">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <!-- Edit -->
                                    <a href="<?= site_url('owner/cashiers/edit/' . $c['id']); ?>"
                                       class="inline-flex items-center rounded-full border border-slate-300
                                              px-3 py-1 text-[11px] text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </a>

                                    <!-- Reset Password -->
                                    <form action="<?= site_url('owner/cashiers/resetpass/' . $c['id']); ?>"
                                          method="post"
                                          onsubmit="return confirm('Reset password kasir ini menjadi \"password\"?');">
                                        <?= csrf_field(); ?>
                                        <button type="submit"
                                                class="inline-flex items-center rounded-full border border-amber-300
                                                       px-3 py-1 text-[11px] text-amber-700 hover:bg-amber-50">
                                            Reset Pass
                                        </button>
                                    </form>

                                    <!-- Aktif / Nonaktif pakai MODAL -->
                                    <?php if ($active): ?>
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-full border border-red-300
                                                   px-3 py-1 text-[11px] text-red-600 hover:bg-red-50"
                                            data-modal-type="deactivate"
                                            data-url="<?= site_url('owner/cashiers/deactivate/' . $c['id']); ?>"
                                            data-nama="<?= esc($c['nama'], 'attr'); ?>"
                                            data-username="<?= esc($c['username'], 'attr'); ?>"
                                            onclick="openKasirStatusModal(this)">
                                            Nonaktifkan
                                        </button>
                                    <?php else: ?>
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-full border border-emerald-300
                                                   px-3 py-1 text-[11px] text-emerald-700 hover:bg-emerald-50"
                                            data-modal-type="activate"
                                            data-url="<?= site_url('owner/cashiers/activate/' . $c['id']); ?>"
                                            data-nama="<?= esc($c['nama'], 'attr'); ?>"
                                            data-username="<?= esc($c['username'], 'attr'); ?>"
                                            onclick="openKasirStatusModal(this)">
                                            Aktifkan
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-[11px] sm:text-sm text-slate-500">
                            Belum ada kasir terdaftar.  
                            Klik <span class="font-semibold text-slate-700">Tambah Kasir</span> untuk membuat akun kasir baru.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL STATUS KASIR -->
<div id="kasir-status-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center">
    <!-- backdrop -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <!-- dialog -->
    <div class="relative z-10 w-full max-w-md px-3">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="px-4 sm:px-5 pt-4 pb-3 border-b border-slate-200 flex items-start justify-between gap-3">
                <div>
                    <p id="kasir-modal-subtitle" class="text-[11px] font-semibold tracking-[0.24em] text-slate-500 uppercase">
                        Konfirmasi
                    </p>
                    <h2 id="kasir-modal-title" class="mt-1 text-base sm:text-lg font-bold text-slate-900">
                        Ubah status kasir
                    </h2>
                </div>
                <button type="button"
                        onclick="closeKasirStatusModal()"
                        class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                    ✕
                </button>
            </div>

            <div class="px-4 sm:px-5 py-4 space-y-3 text-sm text-slate-700">
                <div class="flex items-center gap-3">
                    <div id="kasir-modal-icon"
                         class="h-9 w-9 rounded-full flex items-center justify-center text-sm">
                        <!-- warna di-set dari JS -->
                        ⚠
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-xs text-slate-500 uppercase tracking-[0.18em]">Kasir</p>
                        <p class="font-semibold text-slate-900" id="kasir-modal-name"></p>
                        <p class="text-[11px] text-slate-500">
                            Username: <span id="kasir-modal-username" class="font-mono text-xs"></span>
                        </p>
                    </div>
                </div>

                <p id="kasir-modal-message" class="text-xs sm:text-[13px] text-slate-600">
                    <!-- Diisi dinamis -->
                </p>
            </div>

            <div class="px-4 sm:px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-wrap justify-end gap-2">
                <button type="button"
                        onclick="closeKasirStatusModal()"
                        class="inline-flex items-center rounded-full border border-slate-300
                               px-4 py-1.5 text-xs sm:text-sm text-slate-700 hover:bg-white">
                    Batal
                </button>

                <form id="kasir-status-form" action="#" method="post">
                    <?= csrf_field(); ?>
                    <button id="kasir-modal-submit"
                            type="submit"
                            class="inline-flex items-center rounded-full px-4 py-1.5 text-xs sm:text-sm font-semibold shadow-sm">
                        <!-- warna & label di-set dari JS -->
                        Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Pencarian client-side untuk kasir (username & nama)
    const cashierSearch = document.getElementById('cashier-search');
    const cashierRows   = document.querySelectorAll('#cashier-table .cashier-row');

    if (cashierSearch && cashierRows.length) {
        cashierSearch.addEventListener('input', () => {
            const q = cashierSearch.value.trim().toLowerCase();

            cashierRows.forEach(row => {
                const u = row.getAttribute('data-username') || '';
                const n = row.getAttribute('data-nama') || '';

                if (!q || u.includes(q) || n.includes(q)) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        });
    }

    // Modal status kasir (aktif / nonaktif)
    const kasirModal        = document.getElementById('kasir-status-modal');
    const kasirModalTitle   = document.getElementById('kasir-modal-title');
    const kasirModalSubtitle= document.getElementById('kasir-modal-subtitle');
    const kasirModalIcon    = document.getElementById('kasir-modal-icon');
    const kasirModalName    = document.getElementById('kasir-modal-name');
    const kasirModalUser    = document.getElementById('kasir-modal-username');
    const kasirModalMsg     = document.getElementById('kasir-modal-message');
    const kasirModalSubmit  = document.getElementById('kasir-modal-submit');
    const kasirStatusForm   = document.getElementById('kasir-status-form');

    function openKasirStatusModal(button) {
        if (!kasirModal) return;

        const type     = button.getAttribute('data-modal-type'); // 'activate' / 'deactivate'
        const url      = button.getAttribute('data-url');
        const nama     = button.getAttribute('data-nama') || '';
        const username = button.getAttribute('data-username') || '';

        kasirStatusForm.setAttribute('action', url);

        if (type === 'deactivate') {
            kasirModalTitle.textContent    = 'Nonaktifkan kasir ini?';
            kasirModalSubtitle.textContent = 'Nonaktifkan Akses';
            kasirModalMsg.textContent      = 'Kasir yang dinonaktifkan tidak akan bisa login ke sistem kasir sampai diaktifkan kembali.';
            kasirModalIcon.className       = 'h-9 w-9 rounded-full flex items-center justify-center text-sm bg-red-100 text-red-700';
            kasirModalIcon.textContent     = '⛔';
            kasirModalSubmit.textContent   = 'Nonaktifkan';
            kasirModalSubmit.className     = 'inline-flex items-center rounded-full px-4 py-1.5 text-xs sm:text-sm font-semibold shadow-sm bg-red-600 text-white hover:bg-red-700';
        } else {
            kasirModalTitle.textContent    = 'Aktifkan kembali kasir ini?';
            kasirModalSubtitle.textContent = 'Aktifkan Akses';
            kasirModalMsg.textContent      = 'Kasir yang diaktifkan kembali akan bisa login dan menggunakan POS seperti biasa.';
            kasirModalIcon.className       = 'h-9 w-9 rounded-full flex items-center justify-center text-sm bg-emerald-100 text-emerald-700';
            kasirModalIcon.textContent     = '✅';
            kasirModalSubmit.textContent   = 'Aktifkan';
            kasirModalSubmit.className     = 'inline-flex items-center rounded-full px-4 py-1.5 text-xs sm:text-sm font-semibold shadow-sm bg-emerald-600 text-white hover:bg-emerald-700';
        }

        kasirModalName.textContent = nama;
        kasirModalUser.textContent = username;

        kasirModal.classList.remove('hidden');
        kasirModal.classList.add('flex');
    }

    function closeKasirStatusModal() {
        if (!kasirModal) return;
        kasirModal.classList.add('hidden');
        kasirModal.classList.remove('flex');
    }

    // Tutup modal kalau klik backdrop
    if (kasirModal) {
        kasirModal.addEventListener('click', (e) => {
            if (e.target === kasirModal) {
                closeKasirStatusModal();
            }
        });
    }

    // Esc untuk tutup
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeKasirStatusModal();
        }
    });
</script>
<?= $this->endSection(); ?>
