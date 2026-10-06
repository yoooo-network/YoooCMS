<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installation complete</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body{font-family:'DM Sans',sans-serif}.heading{font-family:'Manrope',sans-serif}</style>
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 px-4 py-10 text-slate-800">
    <main class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-xl shadow-slate-200/70 sm:p-12">
        <div class="mx-auto mb-6 grid h-16 w-16 place-items-center rounded-2xl bg-emerald-100 text-3xl text-emerald-700"><i class="bi bi-check2-circle"></i></div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-violet-600">Setup complete</p>
        <h1 class="heading text-3xl font-extrabold text-slate-900">Your website is ready</h1>
        <p class="mt-3 leading-7 text-slate-500">Your database has been configured, the required tables are ready, and the installation lock is in place.</p>
        <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <a href="<?= esc($siteUrl) ?>" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 font-bold text-white transition hover:bg-violet-700"><i class="bi bi-globe2"></i> Open website</a>
            <a href="<?= esc(rtrim($siteUrl, '/') . '/ci-admin') ?>" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 font-bold text-slate-700 transition hover:bg-slate-50"><i class="bi bi-shield-lock"></i> Admin sign in</a>
        </div>
        <p class="mt-7 text-sm text-slate-500">Admin username: <strong class="text-slate-800"><?= esc($adminUsername) ?></strong></p>
    </main>
</body>
</html>
