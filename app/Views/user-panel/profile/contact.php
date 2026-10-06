<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
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

            <form action="<?= localized_url('user-panel/profile/edit-contact') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                        <input 
                            type="text" 
                            name="phone"
                            value="<?= esc($profile['phone'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="e.g. +91 98765 43210"
                            required>
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">WhatsApp</label>
                        <input 
                            type="text" 
                            name="whatsapp"
                            value="<?= esc($profile['whatsapp'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="e.g. +91 98765 43210">
                    </div>

                    <!-- Telegram -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Telegram</label>
                        <input 
                            type="text" 
                            name="telegram"
                            value="<?= esc($profile['telegram'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="@username">
                    </div>

                    <!-- Facebook -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Facebook</label>
                        <input 
                            type="text" 
                            name="facebook"
                            value="<?= esc($profile['facebook'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Profile URL">
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Instagram</label>
                        <input 
                            type="text" 
                            name="instagram"
                            value="<?= esc($profile['instagram'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="@username">
                    </div>

                    <!-- Discord -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Discord</label>
                        <input 
                            type="text" 
                            name="discord"
                            value="<?= esc($profile['discord'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="e.g. user#1234">
                    </div>

                    <!-- Website -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Website</label>
                        <input 
                            type="url" 
                            name="website"
                            value="<?= esc($profile['website'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="https://yourwebsite.com">
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition flex items-center justify-center gap-2">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
