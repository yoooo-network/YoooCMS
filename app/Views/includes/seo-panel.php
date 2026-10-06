<?php
$seoEntry = is_array($seoEntry ?? null) ? $seoEntry : [];
$seoContent = trim((string) ($seoEntry['seo_content'] ?? ''));
?>

<?php if ($seoContent !== '') : ?>
            <?= esc($seoContent, 'raw') ?>
<?php endif; ?>
