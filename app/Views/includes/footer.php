<?php
$requestedLanguage = $language ?? 'en';
$navigationLanguage = in_array($requestedLanguage, supported_languages(), true)
    ? $requestedLanguage
    : 'en';

$countries = $countries ?? [];
$citiesByCountry = $citiesByCountry ?? [];

$currentPath = trim((string) uri_string(), '/');
$currentSegments = $currentPath === '' ? [] : explode('/', $currentPath);

$languagePattern = implode('|', array_map(static fn(string $code): string => preg_quote($code, '/'), supported_languages()));
$langRegex = '/^(?:' . $languagePattern . ')$/i';

$langIndex = -1;

foreach ($currentSegments as $index => $segment) {
    if (preg_match($langRegex, $segment)) {
        $langIndex = $index;
        break;
    }
}

$categorySlugs = site_categories();

$categoryIndex = -1;

foreach ($currentSegments as $index => $segment) {
    if (in_array(strtolower($segment), $categorySlugs, true)) {
        $categoryIndex = $index;
        break;
    }
}
$defaultCategory = default_site_category();

$isProfilePage = (
    $langIndex !== -1 &&
    isset($currentSegments[$langIndex + 1]) &&
    strtolower($currentSegments[$langIndex + 1]) === 'profile'
);

$currentLanguage = $langIndex !== -1
    ? strtolower($currentSegments[$langIndex])
    : strtolower($language ?? 'en');

$requestedCurrentCategory = $isProfilePage
    ? strtolower($category ?? $defaultCategory)
    : (
        $categoryIndex !== -1
            ? strtolower($currentSegments[$categoryIndex])
            : $defaultCategory
    );
$currentCategory = in_array($requestedCurrentCategory, $categorySlugs, true)
    ? $requestedCurrentCategory
    : $defaultCategory;

$currentCountrySlug = null;

if (
    $categoryIndex !== -1 &&
    isset($currentSegments[$categoryIndex + 1])
) {
    $currentCountrySlug = $currentSegments[$categoryIndex + 1];
}

$buildCountryUrl = function ($countrySlug) use (
    $currentSegments,
    $categoryIndex,
    $langIndex,
    $currentLanguage,
    $currentCategory
) {
    $newSegments = [];

    if ($categoryIndex !== -1) {
        $newSegments = array_slice(
            $currentSegments,
            0,
            $categoryIndex + 1
        );
    } elseif ($langIndex !== -1) {
        $newSegments = array_slice(
            $currentSegments,
            0,
            $langIndex + 1
        );

        $newSegments[] = $currentCategory;
    } else {
        $newSegments[] = $currentLanguage;
        $newSegments[] = $currentCategory;
    }

    $newSegments[] = $countrySlug;

    return site_url(implode('/', $newSegments));
};

$buildCityUrl = function ($citySlug) use (
    $currentSegments,
    $categoryIndex
) {
    if ($categoryIndex !== -1) {
        $newSegments = array_slice(
            $currentSegments,
            0,
            $categoryIndex + 2
        );

        $newSegments[] = $citySlug;

        return site_url(implode('/', $newSegments));
    }

    return '#';
};
?>

<footer class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t">
    <div class="max-w-screen-2xl mx-auto">
        <div class="h-16 lg:h-20 flex items-center justify-around">

            <button
                type="button"
                data-sheet-type="country"
                class="sheet-trigger flex flex-col items-center text-slate-500"
                aria-label="<?= esc(lang('Site.country')) ?>"
            >
                <i class="bi bi-globe text-xl lg:text-2xl"></i>
                <span class="text-[11px] lg:text-sm font-medium">
                    <?= esc(lang('Site.country')) ?>
                </span>
            </button>

            <button
                type="button"
                data-sheet-type="city"
                class="sheet-trigger flex flex-col items-center text-slate-500"
                aria-label="<?= esc(lang('Site.city')) ?>"
            >
                <i class="bi bi-geo-alt-fill text-xl lg:text-2xl"></i>
                <span class="text-[11px] lg:text-sm font-medium">
                    <?= esc(lang('Site.city')) ?>
                </span>
            </button>

            <a
                href="<?= esc(site_url($navigationLanguage . '/' . $currentCategory)) ?>"
                class="flex flex-col items-center text-violet-600"
            >
                <i class="bi bi-house-door-fill text-xl lg:text-2xl"></i>
                <span class="text-[11px] lg:text-sm font-medium">
                    <?= esc(lang('Site.home')) ?>
                </span>
            </a>

            <a
                href="<?= esc(localized_url('signin')) ?>"
                class="flex flex-col items-center text-slate-500"
            >
                <i class="bi bi-person-badge text-xl lg:text-2xl"></i>
                <span class="text-[11px] lg:text-sm font-medium">
                    <?= esc(lang('Site.profile')) ?>
                </span>
            </a>

            <button
                type="button"
                data-sheet-type="menu"
                class="sheet-trigger flex flex-col items-center text-slate-500"
                aria-label="<?= esc(lang('Site.menu')) ?>"
            >
                <i class="bi bi-grid-fill text-xl lg:text-2xl"></i>
                <span class="text-[11px] lg:text-sm font-medium">
                    <?= esc(lang('Site.menu')) ?>
                </span>
            </button>

        </div>
    </div>
</footer>

<div
    id="bottomSheet"
    class="fixed inset-0 bg-black/40 z-50 hidden items-end"
    aria-hidden="true"
>
    <div class="bg-white w-full rounded-t-3xl max-h-[70vh] overflow-y-auto">
        <div id="sheetContent" class="p-6">

            <div
                class="sheet-panel hidden"
                data-sheet-type="country"
                data-title="<?= esc(lang('Site.selectCountry')) ?>"
            >
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <?php foreach ($countries as $country): ?>

                        <?php
                        $countrySlug = $country['slug'] ?? '';
                        $countryName = $country['name'] ?? '';
                        $countryUrl = $buildCountryUrl($countrySlug);
                        ?>

                        <a
                            href="<?= esc($countryUrl) ?>"
                            class="country-item sheet-nav-item w-full block text-left px-4 py-3 rounded-xl border hover:bg-purple-50 hover:border-purple-300 transition"
                        ><?= esc($countryName) ?></a>

                    <?php endforeach; ?>

                </div>
            </div>

            <div
                class="sheet-panel hidden"
                data-sheet-type="city"
                data-title="<?= esc(lang('Site.selectCity')) ?>"
            >
                <div
                    id="city-list"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4"
                >

                    <?php
                    $hasCurrentCountry = false;

                    if ($currentCountrySlug) {
                        foreach ($countries as $country) {

                            $countrySlug = $country['slug'] ?? '';

                            if (
                                strtolower($countrySlug) ===
                                strtolower($currentCountrySlug)
                            ) {

                                $hasCurrentCountry = true;

                                $countryId = $country['id'] ?? null;
                                $countryName = $country['name'] ?? '';

                                $cities = $citiesByCountry[$countryId] ?? [];

                                if (!empty($cities)) {

                                    foreach ($cities as $city) {

                                        $citySlug = $city['slug'] ?? '';
                                        $cityName = $city['name'] ?? '';
                                        $cityUrl = $buildCityUrl($citySlug);
                                        ?>

                                        <a
                                            href="<?= esc($cityUrl) ?>"
                                            class="sheet-nav-item w-full block text-left px-4 py-3 rounded-xl border hover:bg-purple-50 hover:border-purple-300 transition"
                                        ><?= esc($cityName) ?></a>

                                        <?php
                                    }

                                } else {
                                    ?>

                                    <div class="sheet-city-message px-4 py-3 rounded-xl border border-dashed text-sm text-gray-500 col-span-full">
                                        <?= esc(
                                            lang(
                                                'Site.noCitiesFound',
                                                [$countryName]
                                            )
                                        ) ?>
                                    </div>

                                    <?php
                                }

                                break;
                            }
                        }
                    }

                    if (!$currentCountrySlug) {
                        ?>

                        <div class="sheet-city-message px-4 py-3 rounded-xl border border-dashed text-sm text-gray-500 col-span-full">
                            <?= esc(lang('Site.selectCountryFirst')) ?>
                        </div>

                        <?php
                    } elseif (!$hasCurrentCountry) {
                        ?>

                        <div class="sheet-city-message px-4 py-3 rounded-xl border border-dashed text-sm text-gray-500 col-span-full">
                            <?= esc(lang('Site.countryNotFound')) ?>
                        </div>

                        <?php
                    }
                    ?>

                </div>
            </div>

            <div
                class="sheet-panel hidden"
                data-sheet-type="menu"
                data-title="<?= esc(lang('Site.menu')) ?>"
            >
                <h2 class="text-xl font-bold text-slate-800 mb-5">
                    <?= esc(lang('Site.menu')) ?>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    <?php
                    $categoryMenuItems = [
                        'male' => ['icon' => 'bi-person-standing', 'label' => 'Site.maleEscorts'],
                        'female' => ['icon' => 'bi-person-standing-dress', 'label' => 'Site.femaleEscorts'],
                        'gay' => ['icon' => 'bi-person-hearts', 'label' => 'Site.gayEscort'],
                        'trans' => ['icon' => 'bi-people-fill', 'label' => 'Site.transEscort'],
                    ];
                    foreach ($categorySlugs as $categorySlug) :
                        $menuItem = $categoryMenuItems[$categorySlug];
                    ?>
                        <a href="<?= esc(site_url($navigationLanguage . '/' . $categorySlug)) ?>" class="block px-4 py-4 rounded-xl border hover:bg-purple-50 hover:border-purple-300 transition">
                            <i class="bi <?= esc($menuItem['icon']) ?> mr-2"></i><?= esc(lang($menuItem['label'])) ?>
                        </a>
                    <?php endforeach; ?>

                    <a
                        href="<?= esc(site_url('help')) ?>"
                        class="block px-4 py-4 rounded-xl border hover:bg-purple-50 hover:border-purple-300 transition"
                    >
                        <i class="bi bi-question-circle-fill mr-2"></i>
                        <?= esc(lang('Site.help')) ?>
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const baseUrl = <?= json_encode(site_url()) ?>;

    const currentPath = <?= json_encode(
        trim((string) uri_string(), '/')
    ) ?>;

    const currentSegments =
        currentPath === ''
            ? []
            : currentPath.split('/');

    const langRegex = new RegExp(
        <?= json_encode('^(?:' . $languagePattern . ')$') ?>,
        'i'
    );

    const categorySlugs = <?= json_encode($categorySlugs) ?>;

    const getLanguageIndex = function (segments) {
        for (let i = 0; i < segments.length; i++) {
            if (langRegex.test(segments[i])) {
                return i;
            }
        }

        return -1;
    };

    const getCategoryIndex = function (segments) {
        for (let i = 0; i < segments.length; i++) {
            if (
                categorySlugs.includes(
                    segments[i].toLowerCase()
                )
            ) {
                return i;
            }
        }

        return -1;
    };

    const langIndex =
        getLanguageIndex(currentSegments);

    const categoryIndex =
        getCategoryIndex(currentSegments);

    const isProfilePage =
        langIndex !== -1 &&
        currentSegments[langIndex + 1] === 'profile';

    const currentLanguage =
        langIndex !== -1
            ? currentSegments[langIndex].toLowerCase()
            : <?= json_encode(
                strtolower($language ?? 'en')
            ) ?>;

    const currentCategory = <?= json_encode($currentCategory) ?>;

    const categoryToggles =
        document.querySelectorAll('.category-toggle');

    categoryToggles.forEach(function (button) {

        button.addEventListener('click', function () {

            const newCategory =
                button.dataset.category;

            if (!newCategory) {
                return;
            }

            if (
                newCategory.toLowerCase() ===
                currentCategory.toLowerCase()
            ) {
                return;
            }

            if (isProfilePage) {

                window.location.href =
                    baseUrl +
                    currentLanguage +
                    '/' +
                    newCategory;

                return;
            }

            if (categoryIndex !== -1) {

                const segments =
                    [...currentSegments];

                segments[categoryIndex] =
                    newCategory;

                window.location.href =
                    baseUrl +
                    segments.join('/');

                return;
            }

            window.location.href =
                baseUrl +
                currentLanguage +
                '/' +
                newCategory;
        });
    });

    categoryToggles.forEach(function (button) {
        const isActive = button.dataset.category === currentCategory;
        button.classList.toggle('bg-white', isActive);
        button.classList.toggle('shadow-sm', isActive);
    });

    const sheet =
        document.getElementById('bottomSheet');

    const triggers =
        document.querySelectorAll('.sheet-trigger');

    const panels =
        document.querySelectorAll('.sheet-panel');

    if (
        !sheet ||
        triggers.length === 0 ||
        panels.length === 0
    ) {
        return;
    }

    const openBottomSheet =
        function (type) {

            let activePanel = null;

            panels.forEach(function (panel) {

                const isActive =
                    panel.dataset.sheetType === type;

                panel.classList.toggle(
                    'hidden',
                    !isActive
                );

                if (isActive) {
                    activePanel = panel;
                }
            });

            if (!activePanel) {
                return;
            }

            sheet.classList.remove('hidden');
            sheet.classList.add('flex');

            sheet.setAttribute(
                'aria-hidden',
                'false'
            );
        };

    const closeSheet =
        function () {

            sheet.classList.add('hidden');
            sheet.classList.remove('flex');

            sheet.setAttribute(
                'aria-hidden',
                'true'
            );
        };

    triggers.forEach(function (trigger) {

        trigger.addEventListener(
            'click',
            function () {

                const type =
                    trigger.dataset.sheetType || '';

                if (type) {
                    openBottomSheet(type);
                }
            }
        );
    });


    sheet.addEventListener(
        'click',
        function (event) {

            if (event.target === sheet) {
                closeSheet();
            }
        }
    );

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {
                closeSheet();
            }
        }
    );

});
</script>
<?= view('components/age-notice') ?>
<?= view('components/privacy-cookies-notice', ['navigationLanguage' => $navigationLanguage]) ?>
<?= view('components/sticky-buttons') ?>
</body>
</html>
