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

            <form action="<?= localized_url('user-panel/profile/edit-services') ?>" method="post">
                <?= csrf_field() ?>
                
                <?php
                $selected = [];
                $other = '';

                if (!empty($profile['services'])) {
                    $decoded = is_string($profile['services'])
                        ? json_decode($profile['services'], true)
                        : (is_array($profile['services']) ? $profile['services'] : []);

                    if (is_array($decoded)) {
                        // Check if it's a flat array or associative
                        if (isset($decoded[0]) || empty($decoded)) {
                            // Flat array
                            $selected = $decoded;
                        } else {
                            // Associative array
                            $selected = $decoded['selected'] ?? [];
                            $other = $decoded['other'] ?? '';
                        }
                    }
                }

                $options = [
                    'Giving Oral Sex', 'Receiving Oral Sex', 'Foreplay', 'Roleplay', 'Cuddling',
                    'All Sex Positions', 'DFK (Deep French Kissing)', 'A-Level (Anal Sex)',
                    'Anal Rimming (Licking Anus)', '69 (69 Sex Position)', 'Striptease / Lapdance',
                    'Fingering / Handjob', 'Massage', 'GFE / BFE (Girlfriend / Boyfriend Experience)',
                    'Threesome', 'BDSM', 'Sex Toys', 'Extraball (Multiple Sessions)',
                    'Domination', 'LT (Long Time / Overnight)'
                ];
                ?>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-4">Select Services</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($options as $opt): ?>
                            <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors group">
                                <input type="checkbox" name="services[]" value="<?= esc($opt) ?>"
                                       class="h-5 w-5 text-violet-600 border-gray-300 rounded focus:ring-violet-500"
                                       <?= in_array($opt, $selected) ? 'checked' : '' ?>>
                                <span class="text-sm text-slate-700 group-hover:text-slate-900 font-medium"><?= esc($opt) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Other Services</label>
                    <input 
                        type="text" 
                        name="other_services" 
                        value="<?= esc($other) ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-violet-500 outline-none transition-all"
                        placeholder="e.g. Custom requests, unique experiences">
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="services[]"]');
    const otherInput = document.querySelector('input[name="other_services"]');

    function updateValidation() {
        const isChecked = Array.from(checkboxes).some(cb => cb.checked);
        const hasOtherText = otherInput.value.trim().length > 0;
        
        const isSatisfied = isChecked || hasOtherText;
        checkboxes.forEach(cb => cb.required = !isSatisfied);
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateValidation));
    otherInput.addEventListener('input', updateValidation);
    updateValidation();
});
</script>

<?= $this->include('user-panel/includes/footer') ?>
