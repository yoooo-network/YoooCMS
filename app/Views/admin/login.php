<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { accent: '#0ea5e9' }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen antialiased">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-semibold">Admin Login</h1>
                <p class="text-sm text-slate-500 mt-1">Sign in to access admin panel.</p>
            </div>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                    <?= esc(session()->getFlashdata('message')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="mb-4 text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form action="/ci-admin/login" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" required value="<?= old('username') ?>"
                        class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="admin">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="********">
                </div>
                <button type="submit"
                    class="w-full rounded-lg bg-accent text-white py-2.5 font-semibold hover:bg-sky-600 transition">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>
