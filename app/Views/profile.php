<?= $this->include('includes/head') ?>
<?php
helper('url');

$profile = is_array($profile ?? null) ? $profile : [];
$profileLabel = trim((string) ($profile['name'] ?? ''));
if ($profileLabel === '') {
    $profileLabel = lang('Site.profileFallback');
}

$profileId = trim((string) ($profile['id'] ?? ''));
$profileSlug = url_title($profileLabel, '-', true);
$bookingFormErrors = session()->getFlashdata('booking_errors') ?? [];
$bookingFormError = session()->getFlashdata('error');
$bookingSuccess = session()->getFlashdata('success');
$bookingFormOpen = $profileId !== '' && (old('name') !== null || !empty($bookingFormErrors) || $bookingFormError !== null);
$profileUrl = $profileId !== ''
    ? site_url(trim((string) ($language ?? 'en'), '/') . '/profile/' . $profileId . '/' . $profileSlug)
    : current_url();

$description = trim((string) ($profile['description'] ?? ''));
$gender = strtolower(trim((string) ($profile['gender'] ?? '')));
$location = trim((string) ($profile['location'] ?? ''));
$locationParts = array_values(array_filter(array_map('trim', explode(',', $location))));
$city = $locationParts[0] ?? '';
$country = $locationParts !== [] ? end($locationParts) : '';

$normalizeSchemaDate = static function ($value): ?string {
    $value = trim((string) $value);
    if ($value === '') {
        return null;
    }

    try {
        return (new DateTime($value))->format(DateTime::ATOM);
    } catch (Throwable) {
        return null;
    }
};

$cleanSchemaUrl = static function ($value): string {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return preg_match('#^https?://#i', $value) === 1 ? $value : 'https://' . ltrim($value, '/');
};

$sexuality = $profile['sexuality'] ?? [];
if (is_string($sexuality)) {
    $sexuality = json_decode($sexuality, true) ?: [];
}
$sexualityTypes = is_array($sexuality) ? ($sexuality['types'] ?? (array_is_list($sexuality) ? $sexuality : [])) : [];
$sexualityTypes = array_map(static fn($type): string => strtolower(trim((string) $type)), (array) $sexualityTypes);
$profileKeyword = $gender === 'male' && array_intersect(['homo', 'bisexual'], $sexualityTypes)
    ? 'gay escort'
    : match ($gender) {
        'male' => 'gigolo',
        'female' => 'female escort',
        'trans' => 'trans escort',
        default => 'escort',
    };
$profileAlt = sprintf('%s - %s in %s - Yooo.App', $profileLabel, $profileKeyword, $city !== '' ? $city : 'your city');

$languages = is_array($profile['languages'] ?? null) ? $profile['languages'] : [];
$services = is_array($profile['services'] ?? null) ? $profile['services'] : [];
$imageMetadata = is_array($profile['image_metadata'] ?? null) ? $profile['image_metadata'] : [];

$images = is_array($profile['images'] ?? null) ? $profile['images'] : [];
$normalizedImages = array_values(array_filter(array_map(static function ($image): string {
    if (is_array($image)) {
        return trim((string) ($image['url'] ?? $image['src'] ?? $image['image'] ?? ''));
    }

    return trim((string) $image);
}, $images), static fn(string $image): bool => $image !== ''));

$imageObjects = [];
foreach ($normalizedImages as $index => $imageUrl) {
    $imageObject = [
        '@type' => 'ImageObject',
        '@id' => $profileUrl . '#image-' . ($index + 1),
        'contentUrl' => $imageUrl,
        'caption' => $profileAlt,
        'name' => $profileAlt,
        'description' => $profileAlt,
        'encodingFormat' => pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) !== ''
            ? 'image/' . strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION))
            : null,
        'representativeOfPage' => $index === 0,
    ];

    $metadata = is_array($imageMetadata[$index] ?? null) ? $imageMetadata[$index] : [];
    foreach (['width', 'height'] as $dimension) {
        if (isset($metadata[$dimension]) && is_numeric($metadata[$dimension])) {
            $imageObject[$dimension] = (int) $metadata[$dimension];
        }
    }
    if ($city !== '' || $country !== '') {
        $imageObject['contentLocation'] = array_filter([
            '@type' => 'Place',
            'name' => $location,
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'addressLocality' => $city,
                'addressCountry' => $country,
            ]),
        ]);
    }
    $imageObjects[] = array_filter($imageObject, static fn($value): bool => $value !== null && $value !== '');
}

$personSchema = [
    '@type' => 'Person',
    '@id' => $profileUrl . '#person',
    'name' => $profileLabel,
    'url' => $profileUrl,
    'description' => $description,
    'jobTitle' => $gender === 'male' ? 'Male escort' : ($gender !== '' ? ucfirst($gender) . ' escort' : 'Escort'),
    'gender' => $gender !== '' ? ucfirst($gender) : null,
    'birthDate' => $profile['dob'] ?? null,
    'nationality' => $country !== '' ? ['@type' => 'Country', 'name' => $country] : null,
    'address' => $location !== '' ? ['@type' => 'PostalAddress', 'addressLocality' => $city, 'addressCountry' => $country] : null,
    'homeLocation' => $location !== '' ? ['@type' => 'Place', 'name' => $location] : null,
    'knowsLanguage' => $languages !== [] ? $languages : null,
    'height' => !empty($profile['height']) ? ['@type' => 'QuantitativeValue', 'value' => $profile['height']] : null,
    'weight' => !empty($profile['weight']) ? ['@type' => 'QuantitativeValue', 'value' => $profile['weight']] : null,
    'memberOf' => ['@type' => 'Organization', 'name' => 'Yooo.App'],
    'worksFor' => ['@type' => 'Organization', 'name' => 'Yooo Network'],
];

$personSchema = array_filter($personSchema, static fn($value): bool => $value !== null && $value !== '' && $value !== []);

if ($imageObjects !== []) {
    $personSchema['image'] = array_map(static fn(array $image): array => ['@id' => $image['@id']], $imageObjects);
}

$profilePageSchema = [
    '@type' => 'ProfilePage',
    '@id' => $profileUrl . '#profilepage',
    'name' => $profileLabel,
    'url' => $profileUrl,
    'description' => $description,
    'dateCreated' => $normalizeSchemaDate($profile['created_at'] ?? null),
    'dateModified' => $normalizeSchemaDate($profile['updated_at'] ?? null),
    'inLanguage' => trim((string) ($language ?? 'en')),
    'breadcrumb' => ['@id' => $profileUrl . '#breadcrumb'],
    'primaryImageOfPage' => $imageObjects !== [] ? ['@id' => $imageObjects[0]['@id']] : null,
    'mainEntity' => ['@id' => $personSchema['@id']],
];
$profilePageSchema = array_filter($profilePageSchema, static fn($value): bool => $value !== null && $value !== '' && $value !== []);

$breadcrumbSchema = [
    '@type' => 'BreadcrumbList',
    '@id' => $profileUrl . '#breadcrumb',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => site_url(trim((string) ($language ?? 'en'), '/'))],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $profileLabel, 'item' => $profileUrl],
    ],
];

$profileGraphSchema = [
    '@context' => 'https://schema.org',
    '@graph' => array_merge([$profilePageSchema, $personSchema, $breadcrumbSchema], $imageObjects),
];
?>
<script type="application/ld+json"><?= json_encode($profileGraphSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?></script>
<?= $this->include('includes/header') ?>

<main class="pt-16 sm:pt-20 pb-24 bg-gradient-to-br from-slate-50 to-violet-50/50 min-h-screen">
<?= $this->include('includes/intro-panel') ?>
    <?= $this->include('components/profile/hero') ?>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <?= $this->include('components/profile/about') ?>
        <?= $this->include('components/profile/gender') ?>
        <?= $this->include('components/profile/physique') ?>
        <?= $this->include('components/profile/language') ?>
        <?= $this->include('components/profile/service') ?>
        <?= $this->include('components/profile/pricing') ?>
        <?= $this->include('components/profile/contact') ?>
        <section class="rounded-3xl border border-violet-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Interested in booking <?= esc($profileLabel) ?>?</h2>
                    <p class="mt-1 text-sm text-slate-500">Send a request with your preferred place, time, and requirements.</p>
                </div>
                <button type="button" onclick="openProfileBookingSheet()" aria-controls="profile-booking-sheet" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-violet-700">
                    <i class="bi bi-calendar-check-fill"></i> Book Now
                </button>
            </div>
        </section>
        <?php if ($bookingSuccess): ?>
            <p class="rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"><?= esc($bookingSuccess) ?></p>
        <?php endif; ?>
    </div>
<?= $this->include('includes/seo-panel') ?>
</main>
<div
    id="profile-booking-sheet"
    class="fixed inset-0 z-[60] bg-black/40 <?= $bookingFormOpen ? 'flex' : 'hidden' ?> items-end"
    aria-hidden="<?= $bookingFormOpen ? 'false' : 'true' ?>"
    onclick="if (event.target === this) closeProfileBookingSheet()"
>
    <div class="w-full max-h-[70vh] overflow-y-auto rounded-t-3xl bg-white">
        <div class="p-6">
            <div class="mx-auto max-w-2xl">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Book <?= esc($profileLabel) ?></h2>
                        <p class="mt-1 text-sm text-slate-500">Share your contact details and booking requirements.</p>
                    </div>
                    <button type="button" onclick="closeProfileBookingSheet()" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-slate-500 hover:bg-slate-100" aria-label="Close booking form"><i class="bi bi-x-lg"></i></button>
                </div>
                <?php if ($bookingFormError): ?>
                    <p class="mb-4 rounded-xl bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700"><?= esc($bookingFormError) ?></p>
                <?php endif; ?>
                <?php if ($bookingFormErrors): ?>
                    <div class="mb-4 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700"><ul class="list-inside list-disc"><?php foreach ($bookingFormErrors as $bookingError): ?><li><?= esc($bookingError) ?></li><?php endforeach; ?></ul></div>
                <?php endif; ?>
                <form method="post" action="<?= localized_url('profile/' . $profileId . '/book', (string) ($language ?? 'en')) ?>" class="space-y-4">
                    <?= csrf_field() ?>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="block text-sm font-semibold text-slate-700">Name
                            <input id="profile-booking-name" name="name" required maxlength="100" value="<?= esc(old('name') ?? '') ?>" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                        </label>
                        <label class="block text-sm font-semibold text-slate-700">Phone
                            <input name="phone" type="tel" required maxlength="40" value="<?= esc(old('phone') ?? '') ?>" class="mt-2 h-12 w-full rounded-xl border border-slate-200 px-4 font-normal outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-100">
                        </label>
                    </div>
                    <label class="block text-sm font-semibold text-slate-700">Message
                        <textarea name="message" required minlength="5" maxlength="2000" placeholder="Mention place, time and requirements" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 p-4 font-normal outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-100"><?= esc(old('message') ?? '') ?></textarea>
                    </label>
                    <div class="flex gap-3 pt-1">
                        <button type="submit" class="w-full rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-violet-700">Book Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openProfileBookingSheet() {
    const sheet = document.getElementById('profile-booking-sheet');
    sheet.classList.remove('hidden');
    sheet.classList.add('flex');
    sheet.setAttribute('aria-hidden', 'false');
    document.getElementById('profile-booking-name').focus();
}

function closeProfileBookingSheet() {
    const sheet = document.getElementById('profile-booking-sheet');
    sheet.classList.add('hidden');
    sheet.classList.remove('flex');
    sheet.setAttribute('aria-hidden', 'true');
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeProfileBookingSheet();
});
</script>
<?= $this->include('includes/footer') ?>
