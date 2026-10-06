<?php
$profile = is_array($profile ?? null) ? $profile : [];

$sex = ['types' => [], 'roles' => []];

if (!empty($profile['sexuality'])) {
    $decoded = is_string($profile['sexuality'])
        ? json_decode($profile['sexuality'], true)
        : (is_array($profile['sexuality']) ? $profile['sexuality'] : []);

    if (is_array($decoded)) {
        $sex['types'] = $decoded['types'] ?? (array_is_list($decoded) ? $decoded : []);
        $sex['roles'] = $decoded['roles'] ?? [];
    }
}

$sexualityText = !empty($sex['types']) ? implode(', ', $sex['types']) : lang('Site.notSpecified');
$rolesText = !empty($sex['roles']) ? implode(', ', $sex['roles']) : lang('Site.notSpecified');
?>

<section class="bg-white rounded-3xl p-6 shadow-sm">
    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
        <i class="bi bi-gender-ambiguous text-violet-500"></i>
        <?= esc(lang('Site.identity')) ?>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-violet-50/50 border border-violet-100 rounded-2xl p-5 hover:shadow-md hover:-translate-y-1 transition duration-300">
            <div class="text-[11px] text-violet-600/80 font-bold uppercase tracking-wider mb-1">
                <?= esc(lang('Site.gender')) ?>
            </div>
            <div class="text-xl font-bold text-slate-900">
                <?= esc($profile['gender'] ?? lang('Site.notSpecified')) ?>
            </div>
        </div>

        <div class="bg-pink-50/50 border border-pink-100 rounded-2xl p-5 hover:shadow-md hover:-translate-y-1 transition duration-300">
            <div class="text-[11px] text-pink-600/80 font-bold uppercase tracking-wider mb-1">
                <?= esc(lang('Site.sexuality')) ?>
            </div>
            <div class="text-xl font-bold text-slate-900">
                <?= esc($sexualityText) ?>
            </div>
        </div>

        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 hover:shadow-md hover:-translate-y-1 transition duration-300">
            <div class="text-[11px] text-blue-600/80 font-bold uppercase tracking-wider mb-1">
                <?= esc(lang('Site.role')) ?>
            </div>
            <div class="text-xl font-bold text-slate-900">
                <?= esc($rolesText) ?>
            </div>
        </div>
    </div>
</section>
