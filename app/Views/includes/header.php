<?php
$requestedLanguage = $language ?? 'en';
$requestedCategory = $category ?? default_site_category();
$navigationLanguage = in_array($requestedLanguage, supported_languages(), true) ? $requestedLanguage : 'en';
$categoryOptions = [
    'male' => [
        'label' => 'Male', 'color' => 'text-slate-900',
        'icon' => '<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="14" r="6"/><path d="M14.5 9.5 21 3m-5 0h5v5"/></svg>',
    ],
    'female' => [
        'label' => 'Female', 'color' => 'text-pink-500',
        'icon' => '<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="9" r="6"/><path d="M12 15v8m-4-4h8"/></svg>',
    ],
    'gay' => [
        'label' => 'Gay', 'color' => 'text-violet-600',
        'icon' => '<svg viewBox="0 0 100 100" class="size-6" aria-hidden="true" focusable="false"><g fill="#0071bc"><path d="M66.596 11.052a3.552 3.552 0 0 1 0 5.023L50.428 32.243a3.552 3.552 0 0 1-5.023-5.023l16.168-16.168a3.552 3.552 0 0 1 5.023 0z"/><path d="M70.148 11.052v22.222a3.552 3.552 0 1 1-7.103 0V18.156a3.552 3.552 0 0 0-3.552-3.552H44.376a3.551 3.551 0 1 1 0-7.103h22.221a3.55 3.55 0 0 1 3.551 3.551z"/><path d="M55.869 44.205a23.989 23.989 0 0 0-.196-1.731c-.71-5.01-2.969-9.84-6.814-13.685-9.434-9.434-24.785-9.434-34.219 0-9.434 9.434-9.434 24.785 0 34.219 3.81 3.81 8.586 6.063 13.546 6.795-.028-.494-.046-.99-.046-1.488 0-1.913.212-3.791.612-5.615a16.95 16.95 0 0 1-9.09-4.714c-6.664-6.664-6.664-17.509 0-24.173 6.664-6.664 17.509-6.664 24.173 0a16.943 16.943 0 0 1 4.729 9.186c.097.568.173 1.139.213 1.712.04.58.046 1.162.028 1.742a17.123 17.123 0 0 1-.601 4.005 16.812 16.812 0 0 1-.71 2.071 17.227 17.227 0 0 1-1.394 2.63c-.643 1-1.39 1.952-2.264 2.827a16.925 16.925 0 0 1-2.912 2.319 15.375 15.375 0 0 0-2.226 8.759 24.028 24.028 0 0 0 10.16-6.054 24.028 24.028 0 0 0 6.015-10.028c.17-.559.322-1.122.451-1.689a24.409 24.409 0 0 0 .601-5.367 24.069 24.069 0 0 0-.056-1.721z"/><path d="M71.147 51.205c-3.81-3.81-8.586-6.063-13.546-6.795.028.494.046.99.046 1.488 0 1.913-.212 3.792-.612 5.615a16.946 16.946 0 0 1 9.089 4.714c6.664 6.664 6.664 17.509 0 24.173-6.664 6.664-17.509 6.664-24.173 0a16.943 16.943 0 0 1-4.729-9.186 17.36 17.36 0 0 1-.213-1.712c-.04-.58-.046-1.162-.028-1.742.042-1.35.241-2.695.601-4.005.192-.702.426-1.394.71-2.071a17.17 17.17 0 0 1 1.394-2.629c.643-1 1.39-1.952 2.264-2.827a16.925 16.925 0 0 1 2.912-2.319 15.372 15.372 0 0 0 2.225-8.758 24.028 24.028 0 0 0-10.16 6.054 24.028 24.028 0 0 0-6.015 10.028c-.17.559-.322 1.122-.451 1.69a24.409 24.409 0 0 0-.544 7.086c.04.579.114 1.156.196 1.731.71 5.01 2.969 9.84 6.814 13.685 9.434 9.434 24.785 9.434 34.219 0 9.435-9.435 9.435-24.786.001-34.22z"/><path d="M88.884 33.467a3.552 3.552 0 0 1 0 5.023L72.716 54.658a3.552 3.552 0 0 1-5.023-5.023l16.168-16.168a3.552 3.552 0 0 1 5.023 0z"/><path d="M92.436 33.468V55.69a3.552 3.552 0 1 1-7.103 0V40.572a3.552 3.552 0 0 0-3.552-3.552H66.664a3.551 3.551 0 1 1 0-7.103h22.221a3.55 3.55 0 0 1 3.551 3.551z"/></g></svg>',
    ],
    'trans' => [
        'label' => 'Trans', 'color' => 'text-sky-600',
        'icon' => '<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="14" r="6"/><path d="M14.5 9.5 21 3m-5 0h5v5M16 14h7m-3.5-3.5v7"/></svg>',
    ],
];
$configuredCategories = array_values(array_intersect(array_keys($categoryOptions), site_categories()));
$navigationCategory = in_array($requestedCategory, $configuredCategories, true) ? $requestedCategory : default_site_category();
?>
<body>
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b">
    <div class="max-w-screen-2xl mx-auto">
        <div class="h-16 lg:h-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-violet-600 flex items-center justify-center text-white"> <i class="bi bi-stars text-xl"></i> </div>
                <a href="<?= esc(site_url($navigationLanguage)) ?>">
                    <h1 class="font-extrabold text-lg lg:text-2xl text-violet-600"><?= esc($siteName ?? env('SITE_NAME', 'Yooo.App')) ?></h1>
                </a>
            </div>
            <div class="flex items-center bg-slate-100 rounded-2xl p-1">
                <?php foreach ($configuredCategories as $categorySlug) : $option = $categoryOptions[$categorySlug]; ?>
                    <button id="category-<?= esc($categorySlug) ?>" data-category="<?= esc($categorySlug) ?>" aria-label="<?= esc($option['label']) ?>" class="category-toggle w-10 h-10 lg:w-12 lg:h-12 rounded-xl <?= $navigationCategory === $categorySlug ? 'bg-white shadow-sm' : '' ?> flex items-center justify-center">
                        <span class="<?= esc($option['color']) ?>"><?= $option['icon'] ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
</header>
