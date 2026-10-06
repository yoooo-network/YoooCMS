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

            <form action="<?= localized_url('user-panel/profile/edit-gender') ?>" method="post">
                <?= csrf_field() ?>
                
                <?php
                $sexualityData = ['types' => [], 'roles' => []];

                if (!empty($profile['sexuality'])) {
                    $decoded = is_string($profile['sexuality'])
                        ? json_decode($profile['sexuality'], true)
                        : (is_array($profile['sexuality']) ? $profile['sexuality'] : []);

                    if (is_array($decoded)) {
                        if (isset($decoded[0]) || empty($decoded)) {
                            // It's a flat array like ["Straight", "Bisexual"] or empty
                            $sexualityData['types'] = $decoded;
                        } else {
                            // It's an associative array like {"types": [...], "roles": [...]}
                            $sexualityData['types'] = $decoded['types'] ?? [];
                            $sexualityData['roles'] = $decoded['roles'] ?? [];
                        }
                    }
                }
                $preRolesJson = json_encode($sexualityData['roles']);
                $currentGender = strtolower(trim((string)($profile['gender'] ?? '')));
                ?>

                <div class="space-y-6">
                    
                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gender Identity</label>
                        <select name="gender" id="genderSelect" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Select Gender</option>
                            <option value="Male"   <?= $currentGender === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $currentGender === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="Trans"  <?= $currentGender === 'trans' ? 'selected' : '' ?>>Trans</option>
                            <option value="Other"  <?= $currentGender === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <!-- Sexuality -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Sexuality Type</label>

                        <div class="flex flex-wrap gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="straight" name="sexuality[]" value="Straight"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?= in_array('Straight', $sexualityData['types']) ? 'checked' : '' ?>>
                                <span class="text-sm text-slate-700">Straight</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="homo" name="sexuality[]" value="Homo"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?= in_array('Homo', $sexualityData['types']) ? 'checked' : '' ?>>
                                <span class="text-sm text-slate-700">Homo</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="bi" name="sexuality[]" value="Bisexual"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?= in_array('Bisexual', $sexualityData['types']) ? 'checked' : '' ?>>
                                <span class="text-sm text-slate-700">Bisexual</span>
                            </label>
                        </div>
                    </div>

                    <!-- Orientation Role -->
                    <?php $showRoles = in_array('Homo', $sexualityData['types']) || in_array('Bisexual', $sexualityData['types']); ?>
                    <div id="roleOptions" class="<?= $showRoles ? '' : 'hidden' ?>">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Orientation Role</label>
                        <div id="roleCheckboxes" class="flex flex-wrap gap-6"></div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {

    const genderSelect = document.getElementById('genderSelect');
    const roleDiv = document.getElementById('roleOptions');
    const roleContainer = document.getElementById('roleCheckboxes');

    let preRoles = <?= $preRolesJson ?>;
    if (!Array.isArray(preRoles)) preRoles = [];

    const roleSets = {
        'Male':   ['Top', 'Bottom', 'Verse'],
        'Female': ['Butch', 'Femme', 'Futch'],
        'Trans':  ['Top', 'Bottom', 'Verse', 'Switch'],
        'Other':  []
    };

    function isChecked(value) {
        const el = document.querySelector(`input[name="sexuality[]"][value="${value}"]`);
        return el ? el.checked : false;
    }

    function updateRoleOptions() {
        const gender = genderSelect.value;

        const isHomo = isChecked('Homo');
        const isBi   = isChecked('Bisexual');
        const show = isHomo || isBi;

        roleDiv.classList.add('hidden');
        roleContainer.innerHTML = '';

        if (!show) return;

        const options = roleSets[gender] || [];
        if (options.length === 0) return;

        options.forEach(opt => {
            const id = 'role_' + opt.toLowerCase().replace(/\s+/g, '_');
            const checked = preRoles.includes(opt) ? 'checked' : '';

            roleContainer.insertAdjacentHTML('beforeend', `
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" id="${id}" name="roles[]" value="${opt}" ${checked} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                  <span class="text-sm text-slate-700">${opt}</span>
                </label>
            `);
        });

        roleDiv.classList.remove('hidden');

        // Apply HTML5 validation to dynamically created role checkboxes
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
        function updateRoleValidation() {
            const isChecked = Array.from(roleCheckboxes).some(cb => cb.checked);
            roleCheckboxes.forEach(cb => cb.required = !isChecked);
        }
        roleCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateRoleValidation);
        });
        updateRoleValidation();
    }

    function updateSexualityValidation() {
        const checkboxes = document.querySelectorAll('input[name="sexuality[]"]');
        const isChecked = Array.from(checkboxes).some(cb => cb.checked);
        checkboxes.forEach(cb => cb.required = !isChecked);
    }

    genderSelect.addEventListener('change', updateRoleOptions);
    document.querySelectorAll('input[name="sexuality[]"]').forEach(cb => {
        cb.addEventListener('change', updateRoleOptions);
        cb.addEventListener('change', updateSexualityValidation);
    });

    updateRoleOptions();
    updateSexualityValidation();
});
</script>

<?= $this->include('user-panel/includes/footer') ?>
