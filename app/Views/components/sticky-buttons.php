<?php
$defaults = [
    'telegram' => ['enabled' => 0, 'value' => ''],
    'whatsapp' => ['enabled' => 0, 'value' => ''],
    'phone'    => ['enabled' => 0, 'value' => ''],
];

$settings = $defaults;
$settingsPath = WRITEPATH . 'settings/contact.json';

if (is_file($settingsPath)) {
    $raw = @file_get_contents($settingsPath);
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    if (is_array($decoded)) {
        $settings = [
            'telegram' => [
                'enabled' => !empty($decoded['telegram']['enabled']) ? 1 : 0,
                'value'   => trim((string) ($decoded['telegram']['value'] ?? '')),
            ],
            'whatsapp' => [
                'enabled' => !empty($decoded['whatsapp']['enabled']) ? 1 : 0,
                'value'   => trim((string) ($decoded['whatsapp']['value'] ?? '')),
            ],
            'phone' => [
                'enabled' => !empty($decoded['phone']['enabled']) ? 1 : 0,
                'value'   => trim((string) ($decoded['phone']['value'] ?? '')),
            ],
        ];
    }
}

$telegramUrl = '';
$telegramValue = (string) ($settings['telegram']['value'] ?? '');
if (!empty($settings['telegram']['enabled']) && $telegramValue !== '') {
    $telegramUrl = preg_match('#^https?://#i', $telegramValue)
        ? $telegramValue
        : 'https://t.me/' . ltrim($telegramValue, '@');
}

$whatsappUrl = '';
$whatsappDigits = preg_replace('/\D+/', '', (string) ($settings['whatsapp']['value'] ?? ''));
if (!empty($settings['whatsapp']['enabled']) && $whatsappDigits !== '') {
    $whatsappUrl = 'https://wa.me/' . $whatsappDigits;
}

$phoneUrl = '';
$phoneValue = trim((string) ($settings['phone']['value'] ?? ''));
if (!empty($settings['phone']['enabled']) && $phoneValue !== '') {
    $phoneUrl = 'tel:' . preg_replace('/[^\d+]/', '', $phoneValue);
}

if ($telegramUrl === '' && $whatsappUrl === '' && $phoneUrl === '') {
    return;
}
?>
<div class="fixed right-5 bottom-16 z-[9999] flex flex-col gap-3">
    <?php if ($whatsappUrl !== ''): ?>
        <a
            href="<?= esc($whatsappUrl) ?>"
            target="_blank"
            rel="noopener"
            class="w-14 h-14 rounded-full shadow-lg transition-transform hover:scale-110">
            <img
                src="https://www.yooo.app/icons/whatsapp-icon.png"
                alt="Chat with us on WhatsApp"
                class="w-full h-full rounded-full">
        </a>
    <?php endif; ?>

    <?php if ($telegramUrl !== ''): ?>
        <a
            href="<?= esc($telegramUrl) ?>"
            target="_blank"
            rel="noopener"
            class="w-14 h-14 rounded-full shadow-lg transition-transform hover:scale-110 self-end">
            <img
                src="https://www.yooo.app/icons/telegram-icon.png"
                alt="Join us on Telegram"
                class="w-full h-full rounded-full">
        </a>
    <?php endif; ?>

    <?php if ($phoneUrl !== ''): ?>
        <a
            href="<?= esc($phoneUrl) ?>"
            class="w-14 h-14 rounded-full shadow-lg transition-transform hover:scale-110 self-end">
            <img
                src="https://www.yooo.app/icons/call-icon.png"
                alt="Call us"
                class="w-full h-full rounded-full">
        </a>
    <?php endif; ?>
</div>
