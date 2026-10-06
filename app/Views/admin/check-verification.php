<?= view('admin/includes/header') ?>
    <main class="min-h-screen bg-slate-50 p-4 sm:p-6">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div><p class="text-sm font-semibold text-violet-700">Manage Profiles</p><h1 class="mt-1 text-2xl font-bold text-slate-900">Verification files</h1></div>
            <a href="<?= site_url('ci-admin/profiles/verifications') ?>" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="bi bi-arrow-left mr-2"></i>Back to queue</a>
        </div>
        <div class="mx-auto grid max-w-6xl gap-5 md:grid-cols-2">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-3 font-semibold text-slate-900">Live Selfie · <?= esc($profile['name'] ?? 'Profile') ?></h2>
                <?php if (!empty($liveSelfieUrl)): ?>
                    <a href="<?= esc($liveSelfieUrl) ?>" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline text-sm">Open full image</a>
                    <img src="<?= esc($liveSelfieUrl) ?>" alt="Live Selfie" class="mt-3 w-full rounded border border-gray-200 object-contain max-h-[70vh]">
                <?php else: ?>
                    <p class="text-sm text-red-600">No live selfie uploaded.</p>
                <?php endif; ?>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-3 font-semibold text-slate-900">ID Document</h2>
                <?php if (!empty($idDocumentUrl)): ?>
                    <a href="<?= esc($idDocumentUrl) ?>" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline text-sm">Open full image</a>
                    <img src="<?= esc($idDocumentUrl) ?>" alt="ID Document" class="mt-3 w-full rounded border border-gray-200 object-contain max-h-[70vh]">
                <?php else: ?>
                    <p class="text-sm text-red-600">No ID document uploaded.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>
<?= view('admin/includes/footer') ?>
