<?= $this->include('user-panel/includes/header') ?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-5xl mx-auto">
        
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-bold text-2xl text-slate-800">Physical Details</h2>
            <p class="text-sm text-slate-500">Update your height, measurements, and other physical characteristics.</p>
        </div>

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

            <form action="<?= localized_url('user-panel/profile/edit-physical') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Height -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Height</label>
                        <input 
                            type="text" 
                            name="height"
                            value="<?= esc($profile['height'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="e.g. 180 cm"
                            required>
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Weight</label>
                        <input 
                            type="text" 
                            name="weight"
                            value="<?= esc($profile['weight'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="e.g. 75 kg"
                            required>
                    </div>

                    <!-- Eye Color -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Eye Color</label>
                        <select name="eye_color" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">Select Eye Color</option>
                            <?php 
                            $eyeColors = ['Brown','Black','Blue','Green','Hazel','Gray','Other'];
                            foreach($eyeColors as $color):
                            ?>
                              <option value="<?= $color ?>" <?= (isset($profile['eye_color']) && $profile['eye_color']==$color)?'selected':'' ?>><?= $color ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Hair Type -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Hair Type</label>
                        <select name="hair_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">Select Hair Type</option>
                            <?php 
                            $hairTypes = ['Straight','Wavy','Curly','Coily','Bald','Other'];
                            foreach($hairTypes as $type):
                            ?>
                              <option value="<?= $type ?>" <?= (isset($profile['hair_type']) && $profile['hair_type']==$type)?'selected':'' ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Skin Color -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Skin Color</label>
                        <select name="skin_color" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">Select Skin Color</option>
                            <?php 
                            $skinColors = ['Fair','Light','Medium','Tan','Brown','Dark'];
                            foreach($skinColors as $color):
                            ?>
                              <option value="<?= $color ?>" <?= (isset($profile['skin_color']) && $profile['skin_color']==$color)?'selected':'' ?>><?= $color ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Body Structure -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Body Structure</label>
                        <select name="body_structure" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">Select Body Type</option>
                            <?php 
                            $bodyTypes = ['Slim','Athletic','Average','Muscular','Curvy','Plus Size'];
                            foreach($bodyTypes as $type):
                            ?>
                              <option value="<?= $type ?>" <?= (isset($profile['body_structure']) && $profile['body_structure']==$type)?'selected':'' ?>><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Ethnicity -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Ethnicity</label>
                        <select name="ethnicity" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">Select Ethnicity</option>
                            <?php 
                            $ethnicities = ['Asian','Black','Caucasian','Hispanic','Middle Eastern','Mixed','Native American','Other'];
                            foreach($ethnicities as $ethnicity):
                            ?>
                              <option value="<?= $ethnicity ?>" <?= (isset($profile['ethnicity']) && $profile['ethnicity']==$ethnicity)?'selected':'' ?>><?= $ethnicity ?></option>
                            <?php endforeach; ?>
                        </select>
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
