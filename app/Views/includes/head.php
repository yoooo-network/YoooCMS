<?php
helper('url');

$siteName = trim((string) ($siteName ?? env('SITE_NAME', 'Yooo.App')));
$metaTitle = trim((string) ($metaTitle ?? $title ?? $siteName));
$metaDescription = trim((string) ($metaDescription ?? lang('Site.browseDirectoryDescription')));
$metaKeywords = trim((string) ($metaKeywords ?? lang('Site.independentEscortDirectory')));
$metaRobots = trim((string) ($metaRobots ?? 'index, follow'));
$ogType = trim((string) ($ogType ?? 'website'));
$ogImage = trim((string) ($ogImage ?? ''));
$twitterCard = trim((string) ($twitterCard ?? ($ogImage !== '' ? 'summary_large_image' : 'summary')));
$categoryUrl = trim((string) ($categoryUrl ?? current_url()));
$canonicalUrl = trim((string) ($canonicalUrl ?? $categoryUrl));
$breadcrumbEnabled = (bool) ($breadcrumbEnabled ?? false);
$collectionPageEnabled = (bool) ($collectionPageEnabled ?? false);

$request = service('request');
$uri = $request->getUri();
$path = '/' . trim($uri->getPath(), '/');
$query = $uri->getQuery();

$segments = array_values(array_filter(explode('/', trim($path, '/')), 'strlen'));
$knownLocales = supported_languages();
$currentLocale = $segments[0] ?? '';

if (in_array($currentLocale, $knownLocales, true)) {
    array_shift($segments);
}

$pathWithoutLocale = $segments ? '/' . implode('/', $segments) : '';

$alternateUrls = [];
foreach ($knownLocales as $locale) {
    $localePath = '/' . $locale . $pathWithoutLocale;
    $alternateUrls[$locale] = site_url(ltrim($localePath, '/')) . ($query !== '' ? '?' . $query : '');
}

$categoryLabels = [
    'male' => lang('Site.maleEscorts'),
    'female' => lang('Site.femaleEscorts'),
    'gay' => lang('Site.gayEscorts'),
    'trans' => lang('Site.transEscorts'),
];

$categorySlug = strtolower(trim((string) ($categorySlug ?? ($segments[0] ?? ''))));
$isProfileRoute = strtolower((string) ($segments[0] ?? '')) === 'profile';
$countrySlug = strtolower(trim((string) ($countrySlug ?? ($isProfileRoute ? '' : ($segments[1] ?? '')))));
$citySlug = strtolower(trim((string) ($citySlug ?? ($isProfileRoute ? '' : ($segments[2] ?? '')))));

$categoryLabel = $categoryLabels[$categorySlug] ?? '';
$countryLabel = trim((string) ($countryLabel ?? ''));
$cityLabel = trim((string) ($cityLabel ?? ''));

$slugToTitle = static function (string $value): string {
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    $value = str_replace('-', ' ', $value);
    $value = preg_replace('/\s+/', ' ', $value) ?? $value;

    return ucwords($value);
};

if ($countryLabel === '' && $countrySlug !== '') {
    $countryLabel = $slugToTitle($countrySlug);
}
if ($cityLabel === '' && $citySlug !== '') {
    $cityLabel = $slugToTitle($citySlug);
}

$localePrefix = $currentLocale !== '' ? $currentLocale . '/' : '';
$categoryPath = $categorySlug !== '' ? trim($localePrefix . $categorySlug, '/') : '';
$countryPath = $countrySlug !== '' && $categoryPath !== '' ? trim($categoryPath . '/' . $countrySlug, '/') : '';
$cityPath = $citySlug !== '' && $countryPath !== '' ? trim($countryPath . '/' . $citySlug, '/') : '';

$breadcrumbItems = [];
$breadcrumbItems[] = [
    '@type' => 'ListItem',
    'position' => 1,
    'name' => lang('Site.home'),
    'item' => rtrim(site_url(), '/') . '/',
];

$isProfilePage = $isProfileRoute;
$position = 2;

if ($isProfilePage) {
    $profileId = trim((string) ($profileId ?? ($segments[1] ?? '')));
    $profileSlug = trim((string) ($profileSlug ?? ($segments[2] ?? '')));
    $profileLabel = trim((string) ($profileName ?? ''));
    if ($profileLabel === '' && $profileSlug !== '') {
        $profileLabel = $slugToTitle($profileSlug);
    }
    if ($profileLabel === '') {
        $profileLabel = lang('Site.profileFallback');
    }

    $profilePathParts = array_values(array_filter([
        $currentLocale !== '' ? $currentLocale : null,
        'profile',
        $profileId !== '' ? $profileId : null,
        $profileSlug !== '' ? $profileSlug : null,
    ]));
    $profilePath = implode('/', $profilePathParts);

    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => $profileLabel,
        'item' => site_url($profilePath),
    ];
} else {
    if ($categoryLabel !== '' && $categoryPath !== '') {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $categoryLabel,
            'item' => site_url($categoryPath),
        ];
    }

    if ($countryLabel !== '' && $countryPath !== '') {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $countryLabel,
            'item' => site_url($countryPath),
        ];
    }

    if ($cityLabel !== '' && $cityPath !== '') {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $cityLabel,
            'item' => site_url($cityPath),
        ];
    }
}

if ($collectionPageEnabled) {
    $collectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        '@id' => $canonicalUrl . '#collectionpage',
        'name' => $metaTitle,
        'description' => $metaDescription,
        'url' => $canonicalUrl,
    ];
}

$breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $breadcrumbItems,
];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= esc($metaTitle) ?></title>
    <meta name="description" content="<?= esc($metaDescription) ?>">
    <meta name="keywords" content="<?= esc($metaKeywords) ?>">
    <meta name="robots" content="<?= esc($metaRobots) ?>">
    <link rel="canonical" href="<?= esc($canonicalUrl) ?>">
    <meta property="og:type" content="<?= esc($ogType) ?>">
    <meta property="og:site_name" content="<?= esc($siteName) ?>">
    <meta property="og:title" content="<?= esc($metaTitle) ?>">
    <meta property="og:description" content="<?= esc($metaDescription) ?>">
    <meta property="og:url" content="<?= esc($canonicalUrl) ?>">
    <?php if ($ogImage !== '') : ?>
    <meta property="og:image" content="<?= esc($ogImage) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="<?= esc($twitterCard) ?>">
    <meta name="twitter:title" content="<?= esc($metaTitle) ?>">
    <meta name="twitter:description" content="<?= esc($metaDescription) ?>">
    <?php if ($ogImage !== '') : ?>
    <meta name="twitter:image" content="<?= esc($ogImage) ?>">
    <?php endif; ?>
    <?php foreach ($alternateUrls as $locale => $alternateUrl) : ?>
    <link rel="alternate" hreflang="<?= esc($locale) ?>" href="<?= esc($alternateUrl) ?>">
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?= esc($alternateUrls['en']) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><g transform='translate(4, 4) scale(1)'><path fill='%237C3AED' d='M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z'/></g></svg>">
    <?php if ($collectionPageEnabled) : ?>
    <script type="application/ld+json">
        <?= json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>
    </script>
    <?php endif; ?>
    <?php if ($breadcrumbEnabled) : ?>
    <script type="application/ld+json">
        <?= json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>
    </script>
    <?php endif; ?>
</head>
