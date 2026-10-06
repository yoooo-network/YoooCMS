<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-white via-slate-50 to-indigo-50 text-slate-900">
    <main class="mx-auto flex min-h-screen max-w-xl items-center px-4 py-10">
        <section class="w-full rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm backdrop-blur">
            <div class="flex items-start gap-4">
                <?php if (isset($success) && $success): ?>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                        <i class="bi bi-check-circle-fill text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Email Verified</h1>
                        <p class="mt-2 text-slate-600"><?= esc($message ?? 'Your email has been verified successfully.') ?></p>
                    </div>
                <?php else: ?>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                        <i class="bi bi-x-circle-fill text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Verification Failed</h1>
                        <p class="mt-2 text-slate-600"><?= esc($message ?? 'Verification failed. Please try again.') ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-6 rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
                <div class="flex items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-slate-400"></i>
                    <span>For security, verification links expire and resends may be throttled.</span>
                </div>
            </div>
        </section>
    </main>
</body>
</html>