<?php
$profile = is_array($profile ?? null) ? $profile : [];
$items = [
    ['label' => lang('Site.age'), 'value' => !empty($profile['age']) ? $profile['age'] : '', 'bg' => 'bg-blue-50/50', 'border' => 'border-blue-100', 'labelColor' => 'text-blue-600/80', 'valueColor' => 'text-blue-950'],
    ['label' => lang('Site.height'), 'value' => $profile['height'] ?? '', 'bg' => 'bg-pink-50/50', 'border' => 'border-pink-100', 'labelColor' => 'text-pink-600/80', 'valueColor' => 'text-pink-950'],
    ['label' => lang('Site.weight'), 'value' => $profile['weight'] ?? '', 'bg' => 'bg-purple-50/50', 'border' => 'border-purple-100', 'labelColor' => 'text-purple-600/80', 'valueColor' => 'text-purple-950'],
    ['label' => lang('Site.eyeColor'), 'value' => $profile['eye_color'] ?? '', 'bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-100', 'labelColor' => 'text-emerald-600/80', 'valueColor' => 'text-emerald-950'],
    ['label' => lang('Site.hairType'), 'value' => $profile['hair_type'] ?? '', 'bg' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'labelColor' => 'text-amber-600/80', 'valueColor' => 'text-amber-950'],
    ['label' => lang('Site.skinColor'), 'value' => $profile['skin_color'] ?? '', 'bg' => 'bg-cyan-50/50', 'border' => 'border-cyan-100', 'labelColor' => 'text-cyan-600/80', 'valueColor' => 'text-cyan-950'],
    ['label' => lang('Site.bodyStructure'), 'value' => $profile['body_structure'] ?? '', 'bg' => 'bg-rose-50/50', 'border' => 'border-rose-100', 'labelColor' => 'text-rose-600/80', 'valueColor' => 'text-rose-950'],
    ['label' => lang('Site.ethnicity'), 'value' => $profile['ethnicity'] ?? '', 'bg' => 'bg-indigo-50/50', 'border' => 'border-indigo-100', 'labelColor' => 'text-indigo-600/80', 'valueColor' => 'text-indigo-950'],
];
$items = array_values(array_filter($items, static fn(array $item): bool => trim((string) $item['value']) !== ''));
?>

<?php if ($items !== []): ?>
    <section class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
            <i class="bi bi-person-vcard text-violet-500"></i>
            <?= esc(lang('Site.physicalDetails')) ?>
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($items as $item): ?>
                <div class="<?= esc($item['bg']) ?> border <?= esc($item['border']) ?> rounded-2xl p-4 hover:shadow-md hover:-translate-y-1 transition duration-300">
                    <div class="text-[11px] <?= esc($item['labelColor']) ?> font-bold mb-1 uppercase tracking-wider"><?= esc($item['label']) ?></div>
                    <div class="font-bold text-xl <?= esc($item['valueColor']) ?>"><?= esc($item['value']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
