<?php
$profile = is_array($profile ?? null) ? $profile : [];

$pricingData = $profile['pricing'] ?? [];
if (is_string($pricingData)) {
    $pricingData = json_decode($pricingData, true);
}
$pricingData = is_array($pricingData) ? $pricingData : [];

$pricingLabels = [
    '1hr' => lang('Site.oneHour'),
    '3hr' => lang('Site.threeHours'),
    'night' => lang('Site.fullNight'),
    'week' => lang('Site.fullWeek'),
    'month' => lang('Site.fullMonth'),
];

$prices = [];
foreach ($pricingLabels as $key => $label) {
    if (empty($pricingData[$key])) {
        continue;
    }

    $rawValue = trim((string) $pricingData[$key]);
    $amount = $rawValue;
    $currency = '';

    if (preg_match('/^([0-9]+(?:\.[0-9]+)?)([A-Z]{3})$/', $rawValue, $matches) === 1) {
        $amount = $matches[1];
        $currency = $matches[2];
    } elseif (preg_match('/^([A-Z]{3})\s*([0-9]+(?:\.[0-9]+)?)$/', $rawValue, $matches) === 1) {
        $currency = $matches[1];
        $amount = $matches[2];
    }

    // Only add the price if we have a valid numeric amount greater than zero.
    if (!is_numeric($amount) || (float) $amount <= 0) {
        continue;
    }

    $prices[] = [
        'label' => $label,
        'amount' => is_numeric($amount) ? number_format((float) $amount) : $amount,
        'currency' => $currency,
    ];
}

$colors = [
    ['bg' => 'bg-violet-50/50', 'border' => 'border-violet-100', 'text' => 'text-violet-700'],
    ['bg' => 'bg-pink-50/50', 'border' => 'border-pink-100', 'text' => 'text-pink-700'],
    ['bg' => 'bg-blue-50/50', 'border' => 'border-blue-100', 'text' => 'text-blue-700'],
    ['bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-100', 'text' => 'text-emerald-700'],
    ['bg' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'text' => 'text-amber-700'],
];
?>

<section class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
        <i class="bi bi-cash-stack text-violet-500"></i>
        <?= esc(lang('Site.pricing')) ?>
    </h2>

    <?php if ($prices !== []): ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($prices as $index => $price): ?>
                <?php $color = $colors[$index % count($colors)]; ?>
                <div class="<?= esc($color['bg']) ?> border <?= esc($color['border']) ?> rounded-2xl p-5 hover:shadow-md hover:-translate-y-1 transition duration-300">
                    <div class="text-xs uppercase tracking-wider font-bold <?= esc($color['text']) ?>">
                        <?= esc($price['label']) ?>
                    </div>

                    <div class="mt-2 flex items-end gap-2">
                        <span class="text-3xl font-bold text-slate-900">
                            <?= esc($price['amount']) ?>
                        </span>

                        <?php if ($price['currency'] !== ''): ?>
                            <span class="text-sm font-semibold text-slate-500 mb-1">
                                <?= esc($price['currency']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-10 rounded-2xl border border-dashed border-slate-200">
            <i class="bi bi-cash-stack text-4xl text-slate-300"></i>
            <p class="mt-3 text-slate-500">
                <?= esc(lang('Site.noPricingInformation')) ?>
            </p>
        </div>
    <?php endif; ?>
</section>
