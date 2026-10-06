<?php
$seoEntry = is_array($seoEntry ?? null) ? $seoEntry : [];
$heading = trim((string) ($seoEntry['h1'] ?? ''));
$intro = trim((string) ($seoEntry['intro_content'] ?? ''));
?>

<?php if ($heading !== '' || $intro !== '') : ?>
    <section class="mb-6 rounded-2xl bg-white/90 shadow-sm ring-1 ring-rose-100 px-6 py-6">
        <?php if ($heading !== '') : ?>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3">
                <?= esc($heading) ?>
            </h1>
        <?php endif; ?>
        <?php if ($intro !== '') : ?>
            <div class="text-base leading-relaxed text-slate-700">
                <?= nl2br(esc($intro)) ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
