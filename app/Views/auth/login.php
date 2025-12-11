<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Toko Z&amp;Z</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#ecfdf5',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                    },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center px-4">

    <div class="max-w-md w-full">
        <div
            class="bg-slate-900/80 border border-slate-700/70 rounded-2xl shadow-2xl shadow-black/40 p-6 sm:p-8 backdrop-blur">
            
            <div class="mb-6 text-center">
                <div class="text-xs tracking-[0.35em] uppercase text-slate-400 mb-1">
                    Sistem Kasir
                </div>
                <h1 class="text-2xl font-bold tracking-wide">
                    Toko <span class="text-brand-500">Z&amp;Z</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Login untuk masuk ke panel Owner / Kasir.
                </p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 text-sm text-red-300 bg-red-900/40 border border-red-700/60 rounded-lg px-3 py-2">
                    <?= esc(session()->getFlashdata('error')); ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login'); ?>" method="post" class="space-y-4">
                <?= csrf_field(); ?>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Username</label>
                    <input
                        type="text"
                        name="username"
                        required
                        autocomplete="username"
                        class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm
                               text-slate-100 placeholder:text-slate-500
                               focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="Masukkan username">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-3 py-2 text-sm
                               text-slate-100 placeholder:text-slate-500
                               focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="••••••••">
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center rounded-xl bg-brand-600 hover:bg-brand-700
                           transition-colors px-3 py-2.5 text-sm font-semibold text-white">
                    Masuk
                </button>
            </form>

            <p class="mt-5 text-[11px] text-center text-slate-500">
                &copy; <?= date('Y'); ?> Toko Z&amp;Z &mdash; Sistem kasir dan laporan penjualan.
            </p>
        </div>
    </div>

</body>
</html>
