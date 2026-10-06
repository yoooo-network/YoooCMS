<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="<?= localized_url('user-panel/settings') ?>" class="inline-flex items-center text-slate-500 hover:text-amber-600 transition-colors mb-4">
                <i class="bi bi-arrow-left mr-2"></i> Back to Settings
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Change Password</h1>
            <p class="text-sm text-slate-500 mt-1">Update your account password securely.</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <form action="<?= localized_url('user-panel/settings/password') ?>" method="post" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Current Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-lock"></i>
                        </div>
                        <input type="password" name="current_password" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none"
                               placeholder="Enter current password">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <input type="password" name="new_password" required minlength="8"
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none"
                               placeholder="Enter new password (min. 8 characters)">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <input type="password" name="confirm_password" required minlength="8"
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none"
                               placeholder="Confirm new password">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
