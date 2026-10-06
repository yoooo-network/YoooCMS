<?php
$profile = is_array($profile ?? null) ? $profile : [];
$description = trim((string) ($profile['description'] ?? ''));
?>

<?php if ($description !== ''): ?>
    <section class="bg-gradient-to-br from-violet-600 to-fuchsia-600 rounded-3xl p-6 sm:p-8 shadow-lg shadow-violet-500/20 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none text-8xl">
            <i class="bi bi-quote"></i>
        </div>
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <i class="bi bi-person-lines-fill text-violet-200"></i> <?= esc(lang('Site.about')) ?>
        </h2>
        <p class="text-white/90 leading-relaxed sm:text-lg relative z-10">
            <?= esc($description) ?>
        </p>
    </section>
<?php endif; ?>
