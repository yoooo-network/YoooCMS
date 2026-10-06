<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-5xl mx-auto">
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

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Change Password -->
            <a href="<?= localized_url('user-panel/settings/password') ?>" class="flex items-center p-4 bg-white rounded-3xl shadow-sm border border-slate-100 hover:border-amber-200 hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mr-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <i class="bi bi-key-fill text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-slate-800 text-base">Change Password</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Update your account password</p>
                </div>
                <i class="bi bi-chevron-right text-slate-400 group-hover:text-amber-600 group-hover:translate-x-1 transition-all"></i>
            </a>

            <!-- Delete Profile -->
            <a href="<?= localized_url('user-panel/settings/delete-account') ?>" class="flex items-center p-4 bg-white rounded-3xl shadow-sm border border-slate-100 hover:border-red-200 hover:shadow-md transition-all group cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mr-4 group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <i class="bi bi-trash3-fill text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-slate-800 text-base">Delete Profile</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Permanently remove your profile</p>
                </div>
                <i class="bi bi-chevron-right text-slate-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all"></i>
            </a>

        </div>
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
