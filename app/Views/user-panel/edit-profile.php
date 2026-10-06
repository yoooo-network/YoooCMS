<?= $this->include('user-panel/includes/header') ?>

<?php
$profile = is_array($profile ?? null) ? $profile : [];

$isFilled = static function ($value) use (&$isFilled): bool {
    if (is_array($value)) {
        foreach ($value as $item) {
            if ($isFilled($item)) {
                return true;
            }
        }

        return false;
    }

    return trim((string) ($value ?? '')) !== '';
};

$decodeProfileValue = static function ($value): array {
    if (is_array($value)) {
        return $value;
    }

    if (!is_string($value) || trim($value) === '') {
        return [];
    }

    $decoded = json_decode($value, true);
    return is_array($decoded) ? $decoded : [];
};

$hasAnyValue = static function ($value) use ($isFilled, $decodeProfileValue): bool {
    $trimmedValue = is_string($value) ? trim($value) : '';
    if (is_array($value) || (is_string($value) && $trimmedValue !== '' && in_array($trimmedValue[0], ['[', '{'], true))) {
        $value = $decodeProfileValue($value);
    }

    return $isFilled($value);
};

$hasRequiredFields = static function (array $fields) use ($profile, $isFilled): bool {
    foreach ($fields as $field) {
        if (!$isFilled($profile[$field] ?? '')) {
            return false;
        }
    }

    return true;
};

$sexuality = $decodeProfileValue($profile['sexuality'] ?? []);
$sexualityTypes = isset($sexuality[0]) ? $sexuality : ($sexuality['types'] ?? []);
$sexualityRoles = $sexuality['roles'] ?? [];
$needsRole = (bool) array_intersect(['Homo', 'Bisexual'], is_array($sexualityTypes) ? $sexualityTypes : []);

$cards = [
    [
        'title' => 'Basic Info',
        'description' => 'Name, location, and bio',
        'url' => localized_url('user-panel/profile/edit-basic'),
        'icon' => 'bi-person-lines-fill',
        'accent' => 'violet',
        'complete' => $hasRequiredFields(['name', 'dob', 'location', 'description']),
    ],
    [
        'title' => 'Gender & Sexuality',
        'description' => 'Identity and orientation details',
        'url' => localized_url('user-panel/profile/edit-gender'),
        'icon' => 'bi-gender-ambiguous',
        'accent' => 'pink',
        'complete' => $isFilled($profile['gender'] ?? '') && $isFilled($sexualityTypes) && (!$needsRole || $isFilled($sexualityRoles)),
    ],
    [
        'title' => 'Physical Details',
        'description' => 'Height, measurements, hair & eyes',
        'url' => localized_url('user-panel/profile/edit-physical'),
        'icon' => 'bi-rulers',
        'accent' => 'blue',
        'complete' => $hasRequiredFields(['height', 'weight', 'eye_color', 'hair_type', 'skin_color', 'body_structure', 'ethnicity']),
    ],
    [
        'title' => 'Contact Details',
        'description' => 'Phone, email, and social links',
        'url' => localized_url('user-panel/profile/edit-contact'),
        'icon' => 'bi-telephone-fill',
        'accent' => 'emerald',
        'complete' => $isFilled($profile['phone'] ?? ''),
    ],
    [
        'title' => 'Language',
        'description' => 'Spoken and written languages',
        'url' => localized_url('user-panel/profile/edit-language'),
        'icon' => 'bi-translate',
        'accent' => 'orange',
        'complete' => $hasAnyValue($profile['languages'] ?? []),
    ],
    [
        'title' => 'Pricing',
        'description' => 'Rates for different assignments',
        'url' => localized_url('user-panel/profile/edit-pricing'),
        'icon' => 'bi-tag-fill',
        'accent' => 'amber',
        'complete' => $hasAnyValue($profile['pricing'] ?? []),
    ],
    [
        'title' => 'Services',
        'description' => 'Types of modeling work offered',
        'url' => localized_url('user-panel/profile/edit-services'),
        'icon' => 'bi-briefcase-fill',
        'accent' => 'cyan',
        'complete' => $hasAnyValue($profile['services'] ?? []),
    ],
    [
        'title' => 'Upload Photos',
        'description' => 'Manage portfolio and gallery',
        'url' => localized_url('user-panel/profile/edit-photos'),
        'icon' => 'bi-images',
        'accent' => 'fuchsia',
        'complete' => $hasAnyValue($profile['images'] ?? []),
    ],
];

$accentClasses = [
    'violet' => 'bg-violet-50 text-violet-600 group-hover:bg-violet-600 group-hover:text-white',
    'pink' => 'bg-pink-50 text-pink-600 group-hover:bg-pink-600 group-hover:text-white',
    'blue' => 'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
    'sky' => 'bg-sky-50 text-sky-600 group-hover:bg-sky-600 group-hover:text-white',
    'orange' => 'bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white',
    'amber' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white',
    'cyan' => 'bg-cyan-50 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white',
    'fuchsia' => 'bg-fuchsia-50 text-fuchsia-600 group-hover:bg-fuchsia-600 group-hover:text-white',
];
?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-5xl mx-auto">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($cards as $card): ?>
                <?php
                    $isComplete = (bool) $card['complete'];
                    $cardStateClasses = $isComplete
                        ? 'bg-green-50 border-green-200 hover:border-green-300'
                        : 'bg-red-50 border-red-200 hover:border-red-300';
                    $textStateClasses = $isComplete ? 'text-green-900' : 'text-red-900';
                    $descriptionStateClasses = $isComplete ? 'text-green-700' : 'text-red-700';
                    $statusClasses = $isComplete ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                    $statusIcon = $isComplete ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                    $statusLabel = $isComplete ? 'Completed' : 'Incomplete';
                    $accent = $accentClasses[$card['accent']] ?? $accentClasses['violet'];
                ?>
                <a href="<?= esc($card['url']) ?>" class="flex items-center p-4 <?= $cardStateClasses ?> rounded-3xl shadow-sm border hover:shadow-md transition-all group cursor-pointer">
                    <div class="w-12 h-12 rounded-2xl <?= $accent ?> flex items-center justify-center mr-4 transition-colors">
                        <i class="bi <?= esc($card['icon']) ?> text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold <?= $textStateClasses ?> text-base"><?= esc($card['title']) ?></h3>
                        <p class="text-xs <?= $descriptionStateClasses ?> mt-0.5"><?= esc($card['description']) ?></p>
                    </div>
                    <span class="w-9 h-9 rounded-full <?= $statusClasses ?> flex items-center justify-center ml-3" title="<?= esc($statusLabel) ?>" aria-label="<?= esc($statusLabel) ?>">
                        <i class="bi <?= $statusIcon ?> text-lg"></i>
                    </span>
                    <i class="bi bi-chevron-right text-slate-400 group-hover:translate-x-1 transition-all ml-3"></i>
                </a>
            <?php endforeach; ?>

        </div>
    </div>
</main>

<?= $this->include('user-panel/includes/footer') ?>
