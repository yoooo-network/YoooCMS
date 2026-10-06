<?php
$profile = is_array($profile ?? null) ? $profile : [];
$images = is_array($profile['images'] ?? null) ? array_values($profile['images']) : [];
$fallbackImage = 'https://www.yooo.app/images/yoooo-male.webp';
$displayImages = $images !== [] ? $images : [$fallbackImage];
$mainImage = $displayImages[0] ?? $fallbackImage;
$tileImages = array_slice($displayImages, 1, 4);
while (count($tileImages) < 4) {
    $tileImages[] = $mainImage;
}

$name = trim((string) ($profile['name'] ?? lang('Site.profileFallback')));
$location = trim((string) ($profile['location'] ?? ''));
$gender = strtolower(trim((string) ($profile['gender'] ?? '')));
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

$keyword = $gender === 'male' && array_intersect(['homo', 'bisexual'], $sexualityTypes)
    ? 'gay escort'
    : match ($gender) {
        'male' => 'gigolo',
        'female' => 'female escort',
        'trans' => 'trans escort',
        default => 'escort',
    };
$profileAlt = sprintf(
    '%s - %s in %s - Yooo.App',
    $name,
    $keyword,
    $city !== '' ? $city : 'your city'
);

$buildImageSources = static function (string $image) use ($fallbackImage): array {
    $image = trim($image);
    $buildResponsive = $image !== ''
        && $image !== $fallbackImage
        && preg_match('/\.\w+(\?.*)?$/', $image) === 1;

    return [
        'image' => $image !== '' ? $image : $fallbackImage,
        'image400' => $buildResponsive ? preg_replace('#(\.\w+)(\?.*)?$#', '_400$1$2', $image) : '',
        'image800' => $buildResponsive ? preg_replace('#(\.\w+)(\?.*)?$#', '_800$1$2', $image) : '',
        'responsive' => $buildResponsive,
    ];
};

$age = $profile['age'] ?? null;
$height = trim((string) ($profile['height'] ?? ''));
$summary = array_values(array_filter([
    $age ? $age . ' ' . lang('Site.yearsShort') : '',
    $height !== '' ? $height : '',
    $location,
], static fn(string $item): bool => $item !== ''));
$services = is_array($profile['services'] ?? null) ? array_slice($profile['services'], 0, 3) : [];
$mainSources = $buildImageSources((string) $mainImage);
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
    <section class="relative h-[70vh] sm:h-[80vh] overflow-hidden rounded-3xl shadow-sm">
        <!-- Responsive Image Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 sm:grid-rows-4 h-full sm:gap-1">
            <div class="sm:col-span-2 sm:row-span-4 cursor-pointer relative group overflow-hidden" onclick="openLightbox(0)">
                <img
                    src="<?= esc($mainSources['image']) ?>"
                    <?php if ($mainSources['responsive']): ?>
                        srcset="<?= esc($mainSources['image400']) ?> 400w, <?= esc($mainSources['image800']) ?> 800w"
                        sizes="(max-width: 640px) 100vw, 66vw"
                    <?php endif; ?>
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105 group-hover:brightness-75"
                    alt="<?= esc($profileAlt) ?>"
                >
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none">
                    <i class="bi bi-arrows-fullscreen text-white text-3xl drop-shadow-md"></i>
                </div>
            </div>

            <div class="hidden sm:grid sm:col-span-1 sm:row-span-4 sm:grid-rows-4 sm:gap-1">
                <?php foreach ($tileImages as $index => $image): ?>
                    <?php $tileSources = $buildImageSources((string) $image); ?>
                    <div class="cursor-pointer relative group overflow-hidden" onclick="openLightbox(<?= $index + 1 ?>)">
                        <img
                            src="<?= esc($tileSources['image']) ?>"
                            <?php if ($tileSources['responsive']): ?>
                                srcset="<?= esc($tileSources['image400']) ?> 400w, <?= esc($tileSources['image800']) ?> 800w"
                                sizes="(max-width: 640px) 100vw, 33vw"
                            <?php endif; ?>
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-105 group-hover:brightness-75"
                            alt="<?= esc($profileAlt) ?>"
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent pointer-events-none"></div>

        <button type="button" onclick="history.back()" class="absolute top-4 left-4 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 transition backdrop-blur text-white flex items-center justify-center">
            <i class="bi bi-arrow-left"></i>
        </button>

        <button type="button" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 transition backdrop-blur text-white flex items-center justify-center group cursor-pointer">
            <i class="bi bi-heart group-hover:text-pink-500 group-hover:scale-110 transition"></i>
        </button>

        <div class="absolute top-4 right-16">
            <span class="bg-black/30 backdrop-blur text-white text-xs px-3 py-2 rounded-full">
                <?= count($displayImages) ?> <?= esc(lang('Site.photos')) ?>
            </span>
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-6 text-white pointer-events-none">
            <div class="flex items-center gap-2 mb-2">
                <h1 class="text-2xl sm:text-4xl font-bold">
                    <?= esc($name) ?>
                </h1>
                <?php if (!empty($profile['is_verified'])): ?>
                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">
                        <?= esc(lang('Site.verified')) ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($summary !== []): ?>
                <p class="text-white/80 text-lg">
                    <?= esc(implode(' - ', $summary)) ?>
                </p>
            <?php endif; ?>

            <?php if ($services !== []): ?>
                <div class="flex gap-2 mt-4 flex-wrap">
                    <?php foreach ($services as $service): ?>
                        <span class="bg-gradient-to-r from-white/30 to-white/10 border border-white/20 backdrop-blur px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider shadow-sm">
                            <?= esc($service) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- Full-screen Lightbox -->
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center transition-opacity duration-300 opacity-0">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl z-50 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 transition flex items-center justify-center">
        <i class="bi bi-x"></i>
    </button>

    <div id="lightbox-slider" class="w-full h-full overflow-x-auto overflow-y-hidden snap-x snap-mandatory scroll-smooth flex">
        <?php foreach ($displayImages as $image): ?>
            <?php $lightboxSources = $buildImageSources((string) $image); ?>
            <div class="w-full h-full flex-shrink-0 snap-center flex items-center justify-center">
                <img
                    src="<?= esc($lightboxSources['image']) ?>"
                    <?php if ($lightboxSources['responsive']): ?>
                        srcset="<?= esc($lightboxSources['image400']) ?> 400w, <?= esc($lightboxSources['image800']) ?> 800w"
                        sizes="100vw"
                    <?php endif; ?>
                    class="max-w-full max-h-full object-contain"
                    alt="<?= esc($profileAlt) ?>"
                >
            </div>
        <?php endforeach; ?>
    </div>

    <button onclick="slideLightbox(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 transition flex items-center justify-center">
        <i class="bi bi-chevron-left"></i>
    </button>
    <button onclick="slideLightbox(1)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 transition flex items-center justify-center">
        <i class="bi bi-chevron-right"></i>
    </button>

    <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white bg-black/50 px-3 py-1 rounded-full text-sm">
        <!-- Counter will be updated by JS -->
    </div>
</div>


<script>
    function openLightbox(index) {
        const lightbox = document.getElementById('lightbox');
        const slider = document.getElementById('lightbox-slider');
        if (!lightbox || !slider) {
            return;
        }

        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');

        setTimeout(() => {
            lightbox.classList.remove('opacity-0');
            slider.scrollTo({ left: slider.clientWidth * index, behavior: 'auto' });
            updateLightboxCounter();
        }, 10);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox) {
            return;
        }

        lightbox.classList.add('opacity-0');

        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
        }, 300);
    }

    function slideLightbox(direction) {
        const slider = document.getElementById('lightbox-slider');
        if (!slider) {
            return;
        }
        const scrollAmount = slider.clientWidth * direction;
        slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }

    function updateLightboxCounter() {
        const slider = document.getElementById('lightbox-slider');
        const counter = document.getElementById('lightbox-counter');
        if (!slider || !counter) {
            return;
        }

        const totalImages = slider.children.length;
        const currentIndex = Math.round(slider.scrollLeft / slider.clientWidth) + 1;

        counter.textContent = `${currentIndex} / ${totalImages}`;
    }

    // Add event listener for slider scroll
    const slider = document.getElementById('lightbox-slider');
    if (slider) {
        // Use a timeout to prevent the counter from updating too frequently during scroll
        let scrollTimeout;
        slider.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(updateLightboxCounter, 100);
        });
    }

    // Add keyboard navigation
    document.addEventListener('keydown', (e) => {
        const lightbox = document.getElementById('lightbox');
        if (lightbox.classList.contains('hidden')) {
            return;
        }

        if (e.key === 'ArrowRight') {
            slideLightbox(1);
        } else if (e.key === 'ArrowLeft') {
            slideLightbox(-1);
        } else if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>
