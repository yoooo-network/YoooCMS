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

            <form action="<?= localized_url('user-panel/profile/edit-basic') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="space-y-5">
                    
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="name"
                            value="<?= esc($profile['name'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter your full name"
                            required>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date of Birth</label>
                        <input 
                            type="date" 
                            name="dob"
                            value="<?= esc($profile['dob'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            required>
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Country</label>
                        <select name="country_id" id="countrySelect"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            required>
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $c): ?>
                              <option value="<?= $c['id'] ?>"
                                <?= (!empty($profile['location']) && strpos($profile['location'], $c['name']) !== false) ? 'selected' : '' ?>>
                                <?= esc($c['name']) ?>
                              </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">City</label>
                        <select name="city_id" id="citySelect"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            required>
                            <option value="">Select City</option>
                            <?php if (!empty($selectedCities)): ?>
                              <?php foreach ($selectedCities as $city): ?>
                                <option value="<?= $city['id'] ?>"
                                  <?= (!empty($profile['location']) && strpos($profile['location'], $city['name']) !== false) ? 'selected' : '' ?>>
                                  <?= esc($city['name']) ?>
                                </option>
                              <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">About Me</label>
                        <textarea 
                            name="description"
                            rows="4"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Write a short description about yourself..."
                            required
                        ><?= esc($profile['description'] ?? '') ?></textarea>
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
        const countrySelect = document.getElementById('countrySelect');
        const citySelect = document.getElementById('citySelect');

        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            citySelect.innerHTML = '<option value="">Select City</option>';

            if (countryId) {
                fetch(`<?= localized_url('user-panel/profile/cities/') ?>${countryId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching cities:', error));
            }
        });
    });
</script>

    <?= $this->include('user-panel/includes/footer') ?>
