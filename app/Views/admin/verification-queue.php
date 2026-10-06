<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="mb-6">
        <p class="text-sm font-semibold text-violet-700">Manage Profiles</p>
        <h1 class="mt-1 text-2xl font-bold text-slate-900">Verification queue</h1>
        <p class="mt-1 text-sm text-slate-500">Profiles with submitted identity or live selfie files.</p>
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-3">Profile</th><th class="px-5 py-3">User ID</th><th class="px-5 py-3">Files received</th><th class="px-5 py-3 text-right">Action</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <?php foreach ($profiles as $profile): ?>
                    <tr>
                        <td class="px-5 py-4"><span class="font-semibold text-slate-800"><?= esc($profile['name'] ?? 'Unnamed profile') ?></span><span class="mt-1 block text-xs text-slate-500">Profile #<?= esc($profile['id']) ?></span></td>
                        <td class="px-5 py-4 text-slate-600"><?= esc($profile['user_id']) ?></td>
                        <td class="px-5 py-4"><div class="flex flex-wrap gap-2">
                            <?php if ($profile['has_live_selfie']): ?><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Live selfie</span><?php endif; ?>
                            <?php if ($profile['has_id_document']): ?><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">ID document</span><?php endif; ?>
                        </div></td>
                        <td class="px-5 py-4 text-right"><a href="<?= site_url('ci-admin/profiles/check-verification/' . $profile['id']) ?>" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-xs font-bold text-white hover:bg-violet-700"><i class="bi bi-eye"></i> Review</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($profiles === []): ?><tr><td colspan="4" class="px-5 py-12 text-center text-slate-500"><i class="bi bi-shield-check mb-2 block text-3xl text-slate-300"></i>No verification uploads are waiting for review.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?= view('admin/includes/footer') ?>
