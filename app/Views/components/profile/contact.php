<?php
$profile = is_array($profile ?? null) ? $profile : [];
$contacts = [];

$cleanUrl = static function (string $value): string {
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    return preg_match('#^https?://#i', $value) === 1 ? $value : 'https://' . ltrim($value, '/');
};

$addContact = static function (string $label, string $value, string $href, string $icon, string $border, string $bg, string $iconBg, string $iconColor, bool $external = true, bool $clickable = true) use (&$contacts): void {
    $value = trim($value);
    $href = trim($href);
    if ($value === '' || $href === '') {
        return;
    }

    $contacts[] = compact('label', 'value', 'href', 'icon', 'border', 'bg', 'iconBg', 'iconColor', 'external', 'clickable');
};

$phone = trim((string) ($profile['phone'] ?? ''));
$whatsapp = trim((string) ($profile['whatsapp'] ?? ''));
$telegram = trim((string) ($profile['telegram'] ?? ''));
$facebook = trim((string) ($profile['facebook'] ?? ''));
$instagram = trim((string) ($profile['instagram'] ?? ''));
$discord = trim((string) ($profile['discord'] ?? ''));
$website = trim((string) ($profile['website'] ?? ''));

$addContact(lang('Site.phone'), $phone, $phone !== '' ? 'tel:' . preg_replace('/\s+/', '', $phone) : '', 'bi-telephone-fill', 'border-blue-100', 'from-blue-50 to-indigo-50', 'bg-blue-100', 'text-blue-600', false);
$addContact('WhatsApp', $whatsapp, $whatsapp !== '' ? 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp) : '', 'bi-whatsapp', 'border-emerald-100', 'from-emerald-50 to-green-50', 'bg-emerald-100', 'text-emerald-600');
$addContact('Telegram', $telegram, preg_match('#^https?://#i', $telegram) === 1 ? $telegram : 'https://t.me/' . ltrim($telegram, '@'), 'bi-telegram', 'border-cyan-100', 'from-cyan-50 to-sky-50', 'bg-cyan-100', 'text-cyan-600');
$addContact('Instagram', $instagram, preg_match('#^https?://#i', $instagram) === 1 ? $instagram : 'https://instagram.com/' . ltrim($instagram, '@'), 'bi-instagram', 'border-pink-100', 'from-pink-50 to-rose-50', 'bg-pink-100', 'text-pink-600');
$addContact('Facebook', $facebook, $cleanUrl($facebook), 'bi-facebook', 'border-blue-100', 'from-blue-50 to-indigo-50', 'bg-blue-100', 'text-blue-600');
$addContact('Discord', $discord, $cleanUrl($discord), 'bi-discord', 'border-indigo-100', 'from-indigo-50 to-violet-50', 'bg-indigo-100', 'text-indigo-600');
$addContact(lang('Site.website'), $website, $cleanUrl($website), 'bi-globe2', 'border-violet-100', 'from-violet-50 to-fuchsia-50', 'bg-violet-100', 'text-violet-600');

$otherPages = is_array($profile['other_pages'] ?? null) ? $profile['other_pages'] : [];
foreach ($otherPages as $index => $page) {
    $label = lang('Site.otherPage');
    $url = '';

    if (is_array($page)) {
        $label = trim((string) ($page['label'] ?? $page['name'] ?? $page['title'] ?? $label));
        $url = trim((string) ($page['url'] ?? $page['link'] ?? ''));
    } else {
        $url = trim((string) $page);
    }

    $addContact($label !== '' ? $label : lang('Site.otherPage'), $url, $cleanUrl($url), 'bi-link-45deg', 'border-slate-200', 'from-slate-50 to-gray-100', 'bg-slate-900', 'text-white');
}
?>

<section class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
        <i class="bi bi-share text-violet-500"></i>
        <?= esc(lang('Site.contactAndSocials')) ?>
    </h2>

    <?php if ($contacts !== []): ?>
        <div class="space-y-3">
            <?php foreach ($contacts as $contact): ?>
                <?php $tag = $contact['clickable'] ? 'a' : 'div'; ?>
                <<?= $tag ?><?= $contact['clickable'] ? ' href="' . esc($contact['href']) . '"' : '' ?><?= $contact['clickable'] && $contact['external'] ? ' target="_blank" rel="noopener noreferrer"' : '' ?> class="flex items-center gap-4 rounded-2xl border <?= esc($contact['border']) ?> bg-gradient-to-r <?= esc($contact['bg']) ?> p-4<?= $contact['clickable'] ? ' hover:shadow-md transition' : '' ?>">
                    <div class="w-14 h-14 rounded-xl <?= esc($contact['iconBg']) ?> <?= esc($contact['iconColor']) ?> flex items-center justify-center shrink-0">
                        <i class="bi <?= esc($contact['icon']) ?> text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-900"><?= esc($contact['label']) ?></h3>
                        <p class="text-sm text-slate-500 truncate"><?= esc($contact['value']) ?></p>
                    </div>
                    <i class="bi bi-chevron-right text-slate-400 text-xl"></i>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-10 rounded-2xl border border-dashed border-slate-200">
            <i class="bi bi-share text-4xl text-slate-300"></i>
            <p class="mt-3 text-slate-500">
                <?= esc(lang('Site.noContactDetails')) ?>
            </p>
        </div>
    <?php endif; ?>
</section>
