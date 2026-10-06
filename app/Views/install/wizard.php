<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Install your website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body{font-family:'DM Sans',sans-serif}.heading{font-family:'Manrope',sans-serif}[hidden]{display:none!important}</style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
<div class="min-h-screen px-4 py-10 sm:py-14">
    <div class="mx-auto max-w-3xl">
        <header class="mb-8 flex items-center gap-3">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-violet-600 text-xl text-white shadow-lg shadow-violet-200"><i class="bi bi-stars"></i></div>
            <div>
                <p class="heading text-lg font-extrabold text-slate-900">YoooCMS setup</p>
                <p class="text-sm text-slate-500">A few details, then you’re ready to go.</p>
            </div>
        </header>

        <main class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
            <div class="bg-gradient-to-r from-violet-700 to-indigo-600 px-6 py-7 text-white sm:px-9">
                <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-violet-200">YoooCMS installer</p>
                <h1 class="heading text-2xl font-extrabold sm:text-3xl">Let’s set up your website</h1>
                <p class="mt-2 max-w-xl text-sm leading-6 text-violet-100">Connect your database, choose your site address, and create your administrator account.</p>
            </div>

            <div class="px-6 py-7 sm:px-9 sm:py-9">
                <?php if (!empty($errors)): ?>
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800" role="alert">
                        <p class="mb-2 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> Setup needs your attention</p>
                        <ul class="list-inside list-disc space-y-1">
                            <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="mb-8 grid grid-cols-3 gap-2" aria-label="Installer steps">
                    <?php foreach (['Database', 'Site details', 'Administrator'] as $i => $step): ?>
                        <div class="step-indicator rounded-xl bg-slate-100 px-2 py-3 text-center text-xs font-semibold text-slate-500 sm:text-sm" data-step-indicator="<?= $i + 1 ?>">
                            <span class="mr-1 inline-grid h-5 w-5 place-items-center rounded-full bg-white text-[11px]"><?= $i + 1 ?></span><?= esc($step) ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form id="installer-form" method="post" action="/install" class="space-y-7" autocomplete="off">
                    <?= csrf_field() ?>
                    <section class="installer-step space-y-5" data-step="1">
                        <div>
                            <h2 class="heading text-lg font-bold text-slate-900">Database connection</h2>
                            <p class="mt-1 text-sm text-slate-500">Use a MySQL database and a user that can create tables.</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block text-sm font-semibold">Database host
                                <input name="db_host" required value="<?= esc($old['db_host'] ?? 'localhost') ?>" placeholder="localhost" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            </label>
                            <label class="block text-sm font-semibold">Database port
                                <input name="db_port" type="number" min="1" max="65535" required value="<?= esc($old['db_port'] ?? '3306') ?>" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            </label>
                            <label class="block text-sm font-semibold">Database name
                                <input name="db_name" required value="<?= esc($old['db_name'] ?? '') ?>" placeholder="your_database" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            </label>
                            <label class="block text-sm font-semibold">Database username
                                <input name="db_username" required value="<?= esc($old['db_username'] ?? '') ?>" autocomplete="username" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            </label>
                            <label class="block text-sm font-semibold sm:col-span-2">Database password
                                <input name="db_password" type="password" autocomplete="new-password" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            </label>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
                            <button id="test-database" type="button" class="inline-flex h-11 items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 text-sm font-bold text-violet-700 transition hover:bg-violet-100">
                                <i class="bi bi-plug"></i> Test database connection
                            </button>
                            <span id="database-result" class="text-sm" role="status"></span>
                        </div>
                    </section>

                    <section class="installer-step hidden space-y-5" data-step="2" hidden>
                        <div>
                            <h2 class="heading text-lg font-bold text-slate-900">Site details</h2>
                            <p class="mt-1 text-sm text-slate-500">These values are saved as the website’s main identity and address.</p>
                        </div>
                        <label class="block text-sm font-semibold">Site name
                            <input name="site_name" required maxlength="100" value="<?= esc($old['site_name'] ?? 'Yooo.App') ?>" placeholder="My website" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                        </label>
                        <label class="block text-sm font-semibold">Site URL
                            <input name="site_url" type="url" required value="<?= esc($old['site_url'] ?? $siteUrl) ?>" placeholder="https://example.com" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            <span class="mt-2 block font-normal text-slate-500">Include the scheme, for example https://example.com</span>
                        </label>
                    </section>

                    <section class="installer-step hidden space-y-5" data-step="3" hidden>
                        <div>
                            <h2 class="heading text-lg font-bold text-slate-900">Administrator account</h2>
                            <p class="mt-1 text-sm text-slate-500">Use these credentials to sign in at <span class="font-semibold text-slate-700">/ci-admin</span>.</p>
                        </div>
                        <label class="block text-sm font-semibold">Admin username
                            <input name="admin_username" required minlength="3" maxlength="32" value="<?= esc($old['admin_username'] ?? '') ?>" autocomplete="username" placeholder="siteadmin" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                        </label>
                        <label class="block text-sm font-semibold">Admin email
                            <input name="admin_email" type="email" required value="<?= esc($old['admin_email'] ?? '') ?>" autocomplete="email" placeholder="you@example.com" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                        </label>
                        <label class="block text-sm font-semibold">Admin password
                            <input name="admin_password" type="password" required minlength="8" autocomplete="new-password" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                            <span class="mt-2 block font-normal text-slate-500">Use at least 8 characters.</span>
                        </label>
                    </section>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-5">
                        <button id="previous-step" type="button" class="hidden h-12 rounded-xl px-4 text-sm font-bold text-slate-600 hover:bg-slate-100" hidden><i class="bi bi-arrow-left mr-1"></i> Back</button>
                        <span id="step-spacer"></span>
                        <button id="next-step" type="button" class="h-12 rounded-xl bg-violet-600 px-6 text-sm font-bold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-700">Continue <i class="bi bi-arrow-right ml-1"></i></button>
                        <button id="install-submit" type="submit" class="hidden h-12 rounded-xl bg-violet-600 px-6 text-sm font-bold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-700" hidden><i class="bi bi-check2-circle mr-1"></i> Install website</button>
                    </div>
                    <p class="text-center text-xs leading-5 text-slate-400">Database settings are stored in the private <code>writable/</code> directory. The installer locks after successful setup.</p>
                </form>
            </div>
        </main>
        <p class="mt-5 text-center text-xs text-slate-400">YoooCMS · CodeIgniter 4</p>
    </div>
</div>
<script>
(() => {
    const form = document.getElementById('installer-form');
    const steps = [...document.querySelectorAll('.installer-step')];
    const indicators = [...document.querySelectorAll('[data-step-indicator]')];
    const next = document.getElementById('next-step');
    const previous = document.getElementById('previous-step');
    const submit = document.getElementById('install-submit');
    const testButton = document.getElementById('test-database');
    const result = document.getElementById('database-result');
    let active = 1;
    let databaseTested = false;

    ['db_host', 'db_name', 'db_username', 'db_password', 'db_port'].forEach((name) => {
        form.elements[name].addEventListener('input', () => {
            databaseTested = false;
            result.textContent = 'Database settings changed. Test the connection again.';
            result.className = 'text-sm text-amber-700';
        });
    });

    const showStep = (step) => {
        active = step;
        steps.forEach((section) => {
            const isHidden = Number(section.dataset.step) !== step;
            section.hidden = isHidden;
            section.classList.toggle('hidden', isHidden);
        });
        indicators.forEach((indicator) => {
            const selected = Number(indicator.dataset.stepIndicator) === step;
            indicator.className = `step-indicator rounded-xl px-2 py-3 text-center text-xs font-semibold sm:text-sm ${selected ? 'bg-violet-100 text-violet-800' : 'bg-slate-100 text-slate-500'}`;
        });
        previous.hidden = step === 1;
        previous.classList.toggle('hidden', step === 1);
        next.hidden = step === 3;
        next.classList.toggle('hidden', step === 3);
        submit.hidden = step !== 3;
        submit.classList.toggle('hidden', step !== 3);
    };

    const requiredFields = (section) => {
        for (const field of section.querySelectorAll('[required]')) {
            if (!field.reportValidity()) return false;
        }
        return true;
    };

    next.addEventListener('click', () => {
        if (active === 1 && !databaseTested) {
            result.textContent = 'Test the database connection to continue.';
            result.className = 'text-sm text-amber-700';
            return;
        }
        if (!requiredFields(steps[active - 1])) return;
        showStep(Math.min(3, active + 1));
    });
    previous.addEventListener('click', () => showStep(Math.max(1, active - 1)));

    testButton.addEventListener('click', async () => {
        if (!requiredFields(steps[0])) return;
        testButton.disabled = true;
        result.textContent = 'Testing connection…';
        result.className = 'text-sm text-slate-500';
        const data = new FormData();
        ['db_host', 'db_name', 'db_username', 'db_password', 'db_port'].forEach((name) => data.append(name, form.elements[name].value));
        try {
            const response = await fetch('/install/test', {method: 'POST', body: data, headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const payload = await response.json();
            databaseTested = response.ok && payload.ok === true;
            result.textContent = payload.message || (databaseTested ? 'Connection successful.' : 'Connection failed.');
            result.className = `text-sm ${databaseTested ? 'text-emerald-700' : 'text-rose-700'}`;
        } catch (error) {
            databaseTested = false;
            result.textContent = 'Could not complete the connection test. Try again.';
            result.className = 'text-sm text-rose-700';
        } finally {
            testButton.disabled = false;
        }
    });

    form.addEventListener('submit', (event) => {
        if (!databaseTested) {
            event.preventDefault();
            showStep(1);
            result.textContent = 'Test the database connection before installing.';
            result.className = 'text-sm text-amber-700';
        }
    });
    showStep(1);
})();
</script>
</body>
</html>
