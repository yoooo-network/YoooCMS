<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="mb-6">
        <p class="text-sm font-semibold text-violet-700">Settings</p>
        <h1 class="mt-1 text-2xl font-bold text-slate-900"><?= esc($title) ?></h1>
        <p class="mt-1 text-sm text-slate-500"><?= esc($description) ?></p>
    </div>
    <section class="max-w-4xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-5 flex items-center justify-between gap-4">
            <h2 class="font-semibold text-slate-900">Current configuration</h2>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"><?= esc($state) ?></span>
        </div>

        <?php if ($section === 'site'): ?>
            <dl class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Site name</dt><dd class="mt-1 font-semibold text-slate-900"><?= esc($siteName) ?></dd></div>
                <div class="rounded-xl bg-slate-50 p-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Base URL</dt><dd class="mt-1 break-all font-semibold text-slate-900"><?= esc($siteUrl) ?></dd></div>
            </dl>
            <p class="mt-4 text-sm text-slate-500">Edit <code class="rounded bg-slate-100 px-1.5 py-0.5">SITE_NAME</code> and <code class="rounded bg-slate-100 px-1.5 py-0.5">app.baseURL</code> in <code class="rounded bg-slate-100 px-1.5 py-0.5">.env</code> to change these values.</p>
        <?php elseif ($section === 'sitemap'): ?>
            <ul class="space-y-3">
                <?php foreach ($sitemaps as $label => $url): ?>
                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-slate-50 p-4"><span class="font-medium text-slate-800"><?= esc($label) ?></span><a href="<?= esc($url) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-violet-700 hover:text-violet-900"><span><?= esc($url) ?></span><i class="bi bi-box-arrow-up-right"></i></a></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($section === 'languages'): ?>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($languages as $language): ?><span class="rounded-lg bg-violet-50 px-3 py-2 text-sm font-semibold uppercase text-violet-700"><?= esc($language) ?></span><?php endforeach; ?>
            </div>
            <p class="mt-4 text-sm text-slate-500">Language support is maintained in the CI4 app configuration and localized routes.</p>
        <?php else: ?>
            <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900"><i class="bi bi-info-circle mt-0.5"></i><p>This section is listed here for navigation, but its management screen has not been implemented yet.</p></div>
        <?php endif; ?>
    </section>
</main>
<?= view('admin/includes/footer') ?>
