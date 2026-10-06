<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-md mx-auto">
        <div class="mb-6">
            <a href="<?= localized_url('user-panel/settings') ?>" class="inline-flex items-center text-slate-500 hover:text-red-600 transition-colors mb-4">
                <i class="bi bi-arrow-left mr-2"></i> Back to Settings
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Delete Profile</h1>
            <p class="text-sm text-slate-500 mt-1">Permanently remove your public profile.</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl shadow-sm border border-red-100 p-6">
            <div class="flex items-start gap-3 mb-6">
                <div class="w-10 h-10 shrink-0 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">This cannot be undone</h2>
                    <p class="text-sm text-slate-500 mt-1">Your profile and its profile data will be removed. Your user account will remain available.</p>
                </div>
            </div>

            <form action="<?= localized_url('user-panel/settings/delete-account') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" value="<?= esc((string) ($user['id'] ?? '')) ?>">
                <button type="submit" class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all" onclick="return confirm('Delete your profile permanently? Your user account will not be deleted.');">
                    Delete My Profile
                </button>
            </form>
        </div>
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
