<?= $this->include('user-panel/includes/header') ?>

<?php
$isIndianProfile = false;
if (!empty($profile['location'])) {
    $isIndianProfile = preg_match('/\bIndia\b/i', (string) $profile['location']) === 1;
}

$packageDetails = [
    'name' => 'Premium',
    'displayAmount' => $isIndianProfile ? 'INR 3,000' : '$50',
    'icon' => 'bi-stars',
];

$contactDefaults = [
    'telegram' => ['value' => ''],
    'whatsapp' => ['value' => ''],
    'phone'    => ['value' => ''],
];

$contactSettings = $contactDefaults;
$settingsPath = WRITEPATH . 'settings/contact.json';

if (is_file($settingsPath)) {
    $raw = @file_get_contents($settingsPath);
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    if (is_array($decoded)) {
        $contactSettings = [
            'telegram' => ['value' => trim((string) ($decoded['telegram']['value'] ?? ''))],
            'whatsapp' => ['value' => trim((string) ($decoded['whatsapp']['value'] ?? ''))],
            'phone'    => ['value' => trim((string) ($decoded['phone']['value'] ?? ''))],
        ];
    }
}

$supportPhone = $contactSettings['phone']['value'];
$supportWhatsapp = $contactSettings['whatsapp']['value'];
$supportTelegram = $contactSettings['telegram']['value'];

$supportPhoneDigits = preg_replace('/\D+/', '', $supportWhatsapp);
$supportTelegramHandle = ltrim($supportTelegram, '@');
?>

<main class="flex-1 px-4 pb-28 pt-20">
    <div class="mx-auto w-full max-w-md">
        <div class="mb-6 flex items-center gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-violet-600">Secure checkout</p>
                <h1 class="text-xl font-extrabold text-slate-900">Choose payment method</h1>
            </div>
        </div>

        <section class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-violet-600 via-violet-700 to-indigo-800 p-5 text-white shadow-lg shadow-violet-200" aria-label="Premium package summary">
            <div class="absolute -right-8 -top-10 h-32 w-32 rounded-full bg-white/10"></div>
            <div class="relative flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-2xl ring-1 ring-white/20">
                        <i class="bi <?= esc($packageDetails['icon']) ?>"></i>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-violet-200">Your plan</p>
                        <h2 class="text-xl font-bold"><?= esc($packageDetails['name']) ?></h2>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-violet-200">Total</p>
                    <p class="text-2xl font-extrabold"><?= esc($packageDetails['displayAmount']) ?></p>
                </div>
            </div>
            <div class="relative mt-5 flex items-center gap-2 border-t border-white/15 pt-4 text-xs text-violet-100">
                <i class="bi bi-shield-lock-fill"></i>
                Payment details are handled securely by your selected method.
            </div>
        </section>

        <section class="mt-7" aria-labelledby="payment-methods-title">
            <div class="mb-3 flex items-end justify-between">
                <div>
                    <h2 id="payment-methods-title" class="text-base font-bold text-slate-900">Payment methods</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Select one option to continue.</p>
                </div>
                <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">1 Year Plan</span>
            </div>

            <div class="space-y-3">
                <a href="<?= localized_url('user-panel/payments/crypto') ?>" class="group flex min-h-24 items-center gap-4 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition active:scale-[0.98] hover:ring-orange-300">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-3xl text-orange-500">
                        <i class="bi bi-currency-bitcoin"></i>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-center gap-2 font-bold text-slate-900">Crypto <span class="rounded-full bg-orange-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-orange-700">Global</span></span>
                        <span class="mt-1 block text-sm text-slate-500">Pay with your preferred crypto wallet.</span>
                    </span>
                    <i class="bi bi-chevron-right text-xl text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-orange-500"></i>
                </a>

                <?php if ($isIndianProfile): ?>
                    <a href="<?= localized_url('user-panel/payments/upi') ?>" class="group flex min-h-24 items-center gap-4 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition active:scale-[0.98] hover:ring-green-300">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-3xl text-green-600">
                            <i class="bi bi-qr-code-scan"></i>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center gap-2 font-bold text-slate-900">UPI <span class="rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-green-700">India</span></span>
                            <span class="mt-1 block text-sm text-slate-500">Scan a QR code or pay with your UPI app.</span>
                        </span>
                        <i class="bi bi-chevron-right text-xl text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-green-600"></i>
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($supportWhatsapp !== '' || $supportTelegram !== '' || $supportPhone !== ''): ?>
            <section class="mt-7 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-200" aria-labelledby="payment-help-title">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-50 text-sky-600"><i class="bi bi-headset"></i></span>
                    <div>
                        <h2 id="payment-help-title" class="font-bold text-slate-900">Need help?</h2>
                        <p class="text-xs text-slate-500">Contact customer care before paying.</p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-3">
                    <?php if ($supportWhatsapp !== ''): ?>
                        <a href="https://wa.me/<?= esc($supportPhoneDigits) ?>" target="_blank" rel="noopener" class="flex min-h-12 flex-col items-center justify-center gap-1 rounded-2xl bg-green-50 text-xs font-semibold text-green-700"><i class="bi bi-whatsapp text-base"></i>WhatsApp</a>
                    <?php endif; ?>
                    <?php if ($supportTelegram !== ''): ?>
                        <a href="https://t.me/<?= esc($supportTelegramHandle) ?>" target="_blank" rel="noopener" class="flex min-h-12 flex-col items-center justify-center gap-1 rounded-2xl bg-blue-50 text-xs font-semibold text-blue-700"><i class="bi bi-telegram text-base"></i>Telegram</a>
                    <?php endif; ?>
                    <?php if ($supportPhone !== ''): ?>
                        <a href="tel:<?= esc($supportPhone) ?>" class="flex min-h-12 flex-col items-center justify-center gap-1 rounded-2xl bg-slate-100 text-xs font-semibold text-slate-700"><i class="bi bi-telephone-fill text-sm"></i>Call</a>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
