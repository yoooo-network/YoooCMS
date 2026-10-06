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

            <form action="<?= localized_url('user-panel/profile/edit-pricing') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Currency</label>
                    <select name="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-violet-500 outline-none">
                        <?php
                        $currencies = [
                            'ARS' => 'Argentine Peso (ARS)', 'AUD' => 'Australian Dollar (AUD)', 'EUR' => 'Euro (EUR)',
                            'BDT' => 'Bangladeshi Taka (BDT)', 'BRL' => 'Brazilian Real (BRL)', 'CAD' => 'Canadian Dollar (CAD)',
                            'COP' => 'Colombian Peso (COP)', 'CDF' => 'Congolese Franc (CDF)', 'EGP' => 'Egyptian Pound (EGP)',
                            'GHS' => 'Ghanaian Cedi (GHS)', 'ISK' => 'Icelandic Króna (ISK)', 'INR' => 'Indian Rupee (INR)',
                            'IDR' => 'Indonesian Rupiah (IDR)', 'IRR' => 'Iranian Rial (IRR)', 'ILS' => 'Israeli New Shekel (ILS)',
                            'JPY' => 'Japanese Yen (JPY)', 'MYR' => 'Malaysian Ringgit (MYR)', 'MXN' => 'Mexican Peso (MXN)',
                            'MAD' => 'Moroccan Dirham (MAD)', 'NZD' => 'New Zealand Dollar (NZD)', 'NGN' => 'Nigerian Naira (NGN)',
                            'NOK' => 'Norwegian Krone (NOK)', 'OMR' => 'Omani Rial (OMR)', 'QAR' => 'Qatari Riyal (QAR)',
                            'SAR' => 'Saudi Riyal (SAR)', 'SGD' => 'Singapore Dollar (SGD)', 'ZAR' => 'South African Rand (ZAR)',
                            'KRW' => 'South Korean Won (KRW)', 'TWD' => 'New Taiwan Dollar (TWD)', 'THB' => 'Thai Baht (THB)',
                            'TRY' => 'Turkish Lira (TRY)', 'AED' => 'United Arab Emirates Dirham (AED)', 'GBP' => 'British Pound (GBP)',
                            'USD' => 'United States Dollar (USD)', 'VES' => 'Venezuelan Bolívar (VES)', 'VND' => 'Vietnamese Đồng (VND)',
                        ];
                        $selectedCurrency = $profile['currency'] ?? 'INR';
                        foreach ($currencies as $code => $label):
                        ?>
                            <option value="<?= esc($code) ?>" <?= $selectedCurrency === $code ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-slate-700 mb-4 border-b pb-2">Rates</h3>
                    
                    <?php 
                    $durations = [
                        '1 Hour' => '1hr', 
                        '3 Hours' => '3hr', 
                        'Full Night' => 'night', 
                        'Full Week' => 'week', 
                        'Full Month' => 'month'
                    ];
                    foreach ($durations as $label => $key): 
                    ?>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <span class="text-sm font-medium text-slate-700 w-1/3"><?= esc($label) ?></span>
                            <div class="w-2/3 flex justify-end">
                                <input 
                                    type="number" 
                                    name="rate_<?= $key ?>" 
                                    value="<?= esc($profile['pricing'][$key] ?? '') ?>" 
                                    class="w-32 text-right border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-violet-500 outline-none"
                                    placeholder="0.00">
                            </div>
                        </div>
                    <?php endforeach; ?>
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
    const rateInputs = document.querySelectorAll('input[name^="rate_"]');

    function updateValidation() {
        // Check if at least one rate input has a value.
        const isAnyFieldFilled = Array.from(rateInputs).some(input => input.value.trim() !== '');
        
        // If no fields are filled, make them all required. Otherwise, none are required.
        // The browser will prompt for the first required field that is empty.
        rateInputs.forEach(input => {
            input.required = !isAnyFieldFilled;
        });
    }

    // Add event listeners to check validation on each input change.
    rateInputs.forEach(input => {
        input.addEventListener('input', updateValidation);
    });

    // Run on page load to set initial state.
    updateValidation();
});
</script>

<?= $this->include('user-panel/includes/footer') ?>
