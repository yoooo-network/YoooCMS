<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="mb-6">
        <p class="text-sm font-semibold text-violet-700">Overview</p>
        <h1 class="mt-1 text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Today’s activity across users, profiles, and email.</p>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <?php foreach ([
            ['Users today', $usersToday ?? 0, 'bi-people', 'bg-blue-50 text-blue-700'],
            ['Profiles today', $profilesToday ?? 0, 'bi-person-vcard', 'bg-violet-50 text-violet-700'],
            ['Approved today', $approvedProfilesToday ?? 0, 'bi-patch-check', 'bg-emerald-50 text-emerald-700'],
            ['Emails sent today', $emailsToday ?? 0, 'bi-envelope-check', 'bg-amber-50 text-amber-700'],
        ] as [$label, $value, $icon, $color]): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between"><div><p class="text-sm font-medium text-slate-500"><?= esc($label) ?></p><p class="mt-3 text-3xl font-bold text-slate-900"><?= esc($value) ?></p></div><span class="grid h-11 w-11 place-items-center rounded-xl <?= esc($color) ?>"><i class="bi <?= esc($icon) ?> text-xl"></i></span></div>
            </section>
        <?php endforeach; ?>
    </div>
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-bold text-slate-900">Quick links</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="<?= site_url('ci-admin/users') ?>" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"><i class="bi bi-people mr-2"></i>Manage users</a>
            <a href="<?= site_url('ci-admin/profiles') ?>" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"><i class="bi bi-person-vcard mr-2"></i>Manage profiles</a>
            <a href="<?= site_url('ci-admin/profiles/verifications') ?>" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"><i class="bi bi-patch-check mr-2"></i>Review verifications</a>
            <a href="<?= site_url('ci-admin/sent-emails') ?>" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"><i class="bi bi-envelope-paper mr-2"></i>Email logs</a>
        </div>
    </section>
</main>
<?= view('admin/includes/footer') ?>
