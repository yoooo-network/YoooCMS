<?php
$profile = is_array($profile ?? null) ? $profile : [];
$profileId = (int) ($profile['id'] ?? 0);
$profileName = trim((string) ($profile['name'] ?? lang('Site.profileFallback')));
$profileSlug = url_title(
    $profileName !== '' ? $profileName : 'profile-' . $profileId,
    '-',
    true
);
$profileUrl = $profile['url'] ?? (
    $profileId > 0
        ? localized_url('profile/' . $profileId . '/' . $profileSlug)
        : '#'
);

$profileImage = trim((string) ($profile['image'] ?? ''));
$gender = strtolower(trim((string) ($profile['gender'] ?? '')));
$location = trim((string) ($profile['location'] ?? ''));
$city = trim((string) (explode(',', $location)[0] ?? ''));

$sexuality = $profile['sexuality'] ?? [];
if (is_string($sexuality)) {
    $sexuality = json_decode($sexuality, true) ?: [];
}

$sexualityTypes = is_array($sexuality)
    ? ($sexuality['types'] ?? (array_is_list($sexuality) ? $sexuality : []))
    : [];
$sexualityTypes = array_map(
    static fn ($type): string => strtolower(trim((string) $type)),
    is_array($sexualityTypes) ? $sexualityTypes : []
);

if ($gender === 'male' && array_intersect(['homo', 'bisexual'], $sexualityTypes)) {
    $keyword = 'gay escort';
} else {
    $keyword = match ($gender) {
        'male' => 'gigolo',
        'female' => 'female escort',
        'trans' => 'trans escort',
        default => 'escort',
    };
}

$profileAlt = sprintf(
    '%s - %s in %s - Yooo.App',
    $profileName,
    $keyword,
    $city !== '' ? $city : 'your city'
);

$defaultImage = 'https://www.yooo.app/images/yoooo-male.webp';
$buildResponsive = $profileImage !== ''
    && $profileImage !== $defaultImage
    && preg_match('/\.\w+(\?.*)?$/', $profileImage) === 1;

if ($buildResponsive) {
    $profileImage400 = preg_replace('#(\.\w+)(\?.*)?$#', '_400$1$2', $profileImage);
    $profileImage800 = preg_replace('#(\.\w+)(\?.*)?$#', '_800$1$2', $profileImage);
} else {
    $profileImage400 = '';
    $profileImage800 = '';
}

if ($profileImage === '') {
    $profileImage = $defaultImage;
}
?>

<a href="<?= esc($profileUrl) ?>" class="block" aria-label="<?= esc($profileAlt) ?>">
    <div class="relative overflow-hidden
                h-[280px] sm:h-[400px] lg:h-[450px]
                rounded-2xl sm:rounded-3xl
                cursor-pointer shadow-sm hover:shadow-lg
                transition group">

        <img
            src="<?= esc($profileImage) ?>"
            <?php if ($buildResponsive): ?>
                srcset="<?= esc($profileImage400) ?> 400w, <?= esc($profileImage800) ?> 800w"
                sizes="(max-width: 640px) 100vw, 500px"
            <?php endif; ?>
            class="absolute inset-0 w-full h-full object-cover
                   group-hover:scale-105 transition duration-500"
            alt="<?= esc($profileAlt) ?>"
        >

        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

        <!-- Badges -->
        <div class="absolute top-2 left-2 sm:top-4 sm:left-4 flex flex-col gap-1 sm:gap-2">

            <?php if (($profile['membership'] ?? 'free') !== 'free'): ?>
                <span class="bg-blue-500 text-white text-[10px] sm:text-xs
                             px-2 sm:px-3 py-1 rounded-full w-max">
                    <i class="bi bi-award text-[10px] sm:text-xs"></i>
                    <?= ucfirst(esc($profile['membership'])) ?>
                </span>
            <?php endif; ?>

            <?php if ($profile['is_verified'] ?? 0): ?>
                <span class="bg-green-500 text-white text-[10px] sm:text-xs
                             px-2 sm:px-3 py-1 rounded-full w-max">
                    <i class="bi bi-patch-check-fill text-[10px] sm:text-xs"></i>
                    <?= esc(lang('Site.verified')) ?>
                </span>
            <?php endif; ?>

        </div>

        <!-- Verified icon -->
        <?php if ($profile['is_verified'] ?? 0): ?>
            <div class="absolute top-2 right-2 sm:top-4 sm:right-4
                        w-8 h-8 sm:w-10 sm:h-10
                        rounded-full bg-white backdrop-blur
                        flex items-center justify-center shadow-sm">

                <i class="bi bi-shield-fill-check
                          text-green-500 text-xl sm:text-2xl"></i>
            </div>
        <?php endif; ?>

        <!-- Profile info -->
        <div class="absolute bottom-0 left-0 right-0
                    p-3 sm:p-5 text-white">

            <h3 class="text-base sm:text-2xl font-bold truncate">
                <?= esc($profile['name'] ?? lang('Site.noName')) ?>
            </h3>

            <p class="text-white/80 mt-1
                      text-xs sm:text-sm truncate">
                <?= esc($profile['location'] ?? lang('Site.unknownLocation')) ?>
            </p>

        </div>

    </div>
</a>
