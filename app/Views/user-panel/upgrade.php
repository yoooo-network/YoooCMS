<?= $this->include('user-panel/includes/header') ?>

<?php
$isIndianProfile = false;
if (!empty($profile['location'])) {
    $isIndianProfile = preg_match('/\bIndia\b/i', (string) $profile['location']) === 1;
}

$packages = [
    [
        'key' => 'premium',
        'name' => 'Premium',
        'displayPrice' => $isIndianProfile ? 'INR 3,000' : '$50',
        'icon' => 'bi-stars',
        'accent' => 'violet',
        'description' => 'A verified premium listing with agent-assisted booking support.',
        'features' => [
            'Get Listed as Premium and Verified',
            'Direct and agent-assisted bookings',
            'Dedicated personal agent',
            'Agent handles booking discussions',
            'High-profile clients',
            '6-8 meetings per month',
            'Verified bookings',
        ],
    ],
];
?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-violet-100 text-violet-600 mb-4 shadow-sm">
                <i class="bi bi-rocket-takeoff-fill text-3xl"></i>
            </div>
            <h2 class="font-bold text-2xl text-slate-800">Upgrade to Premium</h2>
            <p class="text-sm text-slate-500 mt-2">Unlock verified listing benefits and agent-assisted booking support.</p>
        </div>

        <div class="max-w-xl mx-auto grid grid-cols-1 gap-4">
            <?php foreach ($packages as $item): ?>
                <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-violet-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center">
                                <i class="bi <?= esc($item['icon']) ?> text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-slate-800"><?= esc($item['name']) ?></h3>
                                <p class="text-xs text-slate-500 mt-1"><?= esc($item['description']) ?></p>
                            </div>
                        </div>
                        <span class="shrink-0 rounded-full bg-violet-100 text-violet-700 px-3 py-1 text-xs font-semibold">Premium</span>
                    </div>

                    <div class="mt-6 flex items-end gap-3">
                        <p class="text-4xl font-extrabold text-slate-900"><?= esc($item['displayPrice']) ?></p>
                    </div>

                    <div class="mt-6 space-y-3">
                        <?php foreach ($item['features'] as $feature): ?>
                            <div class="flex items-start gap-3 text-sm text-slate-600">
                                <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                                    <i class="bi bi-check text-sm"></i>
                                </span>
                                <span><?= esc($feature) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <a href="<?= localized_url('user-panel/payments/payment') ?>"
                       class="mt-7 flex items-center justify-center gap-2 w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition">
                        <i class="bi bi-credit-card-fill"></i>
                        Upgrade to <?= esc($item['name']) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">What happens next?</h3>
                    <p class="text-sm text-slate-500 mt-1">Choose Crypto or UPI payment, submit your transaction ID, and our team will review your Premium upgrade manually.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
