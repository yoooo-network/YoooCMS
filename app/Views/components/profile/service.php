<?php
$profile = is_array($profile ?? null) ? $profile : [];

$rawServices = $profile['services'] ?? [];
$services = [];

if (is_array($rawServices)) {
    $services = $rawServices;
} elseif (is_string($rawServices) && trim($rawServices) !== '') {
    $decoded = json_decode($rawServices, true);

    if (is_array($decoded)) {
        $services = $decoded;
    } else {
        $services = preg_split('/[\r\n,]+/', $rawServices) ?: [];
    }
}

$services = array_values(array_filter(array_map(static function ($item): string {
    if (is_array($item)) {
        return trim((string)($item['name'] ?? $item['title'] ?? ''));
    }

    return trim((string)$item);
}, $services), static fn($value) => $value !== ''));
?>

<section class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
        <i class="bi bi-stars text-violet-500"></i>
        <?= esc(lang('Site.services')) ?>
    </h2>

    <?php if (!empty($services)): ?>
        <div class="flex flex-wrap gap-3">
            <?php
            $colors = [
                'bg-violet-50 border-violet-100 text-violet-700',
                'bg-pink-50 border-pink-100 text-pink-700',
                'bg-blue-50 border-blue-100 text-blue-700',
                'bg-emerald-50 border-emerald-100 text-emerald-700',
                'bg-amber-50 border-amber-100 text-amber-700',
                'bg-cyan-50 border-cyan-100 text-cyan-700',
                'bg-rose-50 border-rose-100 text-rose-700',
                'bg-indigo-50 border-indigo-100 text-indigo-700',
            ];

            foreach ($services as $index => $service):
                $color = $colors[$index % count($colors)];
            ?>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border font-semibold <?= $color ?> hover:shadow-md hover:-translate-y-0.5 transition duration-300">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= esc($service) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-10 rounded-2xl border border-dashed border-slate-200">
            <i class="bi bi-stars text-4xl text-slate-300"></i>
            <p class="mt-3 text-slate-500">
                <?= esc(lang('Site.noServicesListed')) ?>
            </p>
        </div>
    <?php endif; ?>
</section>
