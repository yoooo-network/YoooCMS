<?= $this->include('user/partials/head') ?>
    <div class="flex min-h-screen">
        <?= $this->include('user/partials/sidebar') ?>
        <div class="flex-1 flex flex-col min-w-0">
            <?= $this->include('user/partials/header') ?>
<main class="flex-grow p-4 md:p-6">
<div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border-t-4 border-blue-500">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
        <!-- Name & Location -->
        <div>
            <h2 class="text-4xl font-bold text-gray-900 mb-2"><?= esc($profile['name'] ?? 'Unnamed') ?></h2>
            <p class="text-gray-500 text-lg flex items-center gap-2">
                <i class="bi bi-geo-alt-fill"></i> <?= esc($profile['location'] ?? 'Location not specified') ?>
            </p>
        </div>
        
        <!-- Physical Info -->
        <div class="flex flex-wrap gap-6 text-sm">
            <?php
            // Calculate age
            $age = '';
            if (!empty($profile['dob'])) {
                $dob = new DateTime($profile['dob']);
                $today = new DateTime('today');
                $age = $dob->diff($today)->y;
            }
            ?>
            <div class="flex items-center gap-2 text-gray-700">
                <i class="bi bi-person-fill text-blue-500 text-lg"></i>
                <span><strong>Age:</strong> <?= esc($age ?: 'N/A') ?></span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
                <i class="bi bi-rulers text-blue-500 text-lg"></i>
                <span><strong>Height:</strong> <?= esc($profile['height'] ?? 'N/A') ?></span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
                <i class="bi bi-activity text-blue-500 text-lg"></i>
                <span><strong>Weight:</strong> <?= esc($profile['weight'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>
</div>
<section class="profile-gallery mb-4">
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <?php if (!empty($profile['images'])): ?>
            <?php 
                $images = is_array($profile['images']) ? $profile['images'] : json_decode($profile['images'], true);
            ?>
            <?php if (!empty($images)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($images as $index => $img): 
                        $baseName = pathinfo($img, PATHINFO_FILENAME);
                        $thumb400  = '/images/users/' . $baseName . '_400.webp';
                        $thumb800  = '/images/users/' . $baseName . '_800.webp';
                        $fullImage = '/images/users/' . $baseName . '.webp';
                    ?>
                        <div class="rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow cursor-pointer">
                            <img 
                                src="<?= esc($thumb400) ?>" 
                                srcset="<?= esc($thumb400) ?> 400w, <?= esc($thumb800) ?> 800w"
                                sizes="(max-width: 767px) 50vw, (min-width: 768px) 33vw"
                                alt="<?= esc($profile['name']) ?>" 
                                class="w-full h-48 object-cover open-modal"
                                data-full="<?= esc($fullImage) ?>"
                                data-index="<?= $index ?>"
                            >
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Modal -->
                <div id="image-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
                    <div class="relative max-w-3xl w-full mx-4 flex items-center">
                        <!-- Left arrow -->
                        <button id="modal-prev" class="absolute left-2 md:left-4 text-white text-3xl font-bold z-50 select-none">&larr;</button>
                        <!-- Image -->
                        <img id="modal-img" class="w-full h-auto rounded-xl shadow-2xl mx-auto">
                        <!-- Right arrow -->
                        <button id="modal-next" class="absolute right-2 md:right-4 text-white text-3xl font-bold z-50 select-none">&rarr;</button>
                        <!-- Close -->
                        <button id="modal-close" class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                    </div>
                </div>

            <?php else: ?>
                <p class="text-gray-500 text-center">No images available.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-gray-500 text-center">No images uploaded.</p>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-img');
    const closeBtn = document.getElementById('modal-close');
    const prevBtn = document.getElementById('modal-prev');
    const nextBtn = document.getElementById('modal-next');
    const thumbs = Array.from(document.querySelectorAll('.open-modal'));

    let currentIndex = 0;

    function openModal(index) {
        currentIndex = index;
        modalImg.src = thumbs[currentIndex].dataset.full;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // prevent background scroll
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // restore scroll
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % thumbs.length;
        modalImg.src = thumbs[currentIndex].dataset.full;
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + thumbs.length) % thumbs.length;
        modalImg.src = thumbs[currentIndex].dataset.full;
    }

    thumbs.forEach((img, index) => {
        img.addEventListener('click', () => openModal(index));
    });

    closeBtn.addEventListener('click', closeModal);
    nextBtn.addEventListener('click', showNext);
    prevBtn.addEventListener('click', showPrev);

    modal.addEventListener('click', e => {
        if(e.target === modal) closeModal();
    });

    document.addEventListener('keydown', e => {
        if(modal.classList.contains('hidden')) return;

        switch(e.key) {
            case 'ArrowRight':
            case 'ArrowDown':
                e.preventDefault();
                showNext();
                break;
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                showPrev();
                break;
            case 'Escape':
                e.preventDefault();
                closeModal();
                break;
        }
    });
});
</script>
<!-- About Section -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">✨</span> About Me
    </h3>
    <p class="text-gray-700 leading-relaxed">
        <?= esc($profile['description'] ?? 'No description provided.') ?>
    </p>
</div>

<!-- Gender, Sexuality & Role -->
<?php
$sex = ['types' => [], 'roles' => []];

if (!empty($profile['sexuality'])) {
    $decoded = is_string($profile['sexuality'])
        ? json_decode($profile['sexuality'], true)
        : (is_array($profile['sexuality']) ? $profile['sexuality'] : []);

    if (is_array($decoded)) {
        $sex['types'] = $decoded['types'] ?? [];
        $sex['roles'] = $decoded['roles'] ?? [];
    }
}

$sexualityText = !empty($sex['types']) ? implode(', ', $sex['types']) : 'Not specified';
$rolesText     = !empty($sex['roles']) ? implode(', ', $sex['roles']) : '—';
?>

<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">🌈</span> Gender, Sexuality & Role
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-700">
        <div>
            <p class="text-sm font-semibold text-gray-600 mb-1">Gender</p>
            <p class="text-lg text-gray-900"><?= esc($profile['gender'] ?? 'Not specified') ?></p>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-600 mb-1">Sexuality</p>
            <p class="text-lg text-gray-900"><?= esc($sexualityText) ?></p>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-600 mb-1">Role</p>
            <p class="text-lg text-gray-900"><?= esc($rolesText) ?></p>
        </div>
    </div>
</div>
<!-- Physical Details -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">💪</span> Physical Details
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Age</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['age'] ?? 'N/A') ?></p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Height</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['height'] ?? 'N/A') ?></p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Weight</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['weight'] ?? 'N/A') ?></p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Eye Color</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['eye_color'] ?? 'Brown') ?></p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Hair Type</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['hair_type'] ?? 'Short Black') ?></p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Skin Color</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['skin_color'] ?? 'Fair') ?></p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Body Structure</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['body_structure'] ?? 'Athletic') ?></p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-1">Ethnicity</p>
            <p class="text-gray-900 font-bold"><?= esc($profile['ethnicity'] ?? 'Asian') ?></p>
        </div>
    </div>
</div>
<!-- Services -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">🔥</span> Services Offered
    </h3>

    <?php if (!empty($profile['services'])): ?>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($profile['services_selected'] as $service): ?>
                <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-900 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="bi bi-check-circle"></i> <?= esc($service) ?>
                </span>
            <?php endforeach; ?>

            <?php if (!empty($profile['services_other'])): ?>
                <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-900 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="bi bi-plus-circle"></i> <?= esc($profile['services_other']) ?>
                </span>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No services listed.</p>
    <?php endif; ?>
</div>

<!-- Languages -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">🌍</span> Languages Spoken
    </h3>

    <?php
    $languages = [];
    if (!empty($profile['languages'])) {
        $decoded = is_array($profile['languages'])
            ? $profile['languages']
            : json_decode($profile['languages'], true);

        if (!empty($decoded['selected'])) {
            $languages = $decoded['selected'];
        }

        if (!empty($decoded['other'])) {
            $languages[] = $decoded['other'];
        }
    }
    ?>

    <?php if (!empty($languages)): ?>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($languages as $lang): ?>
                <span class="bg-green-100 text-green-900 px-4 py-2 rounded-full text-sm font-semibold">
                    <?= esc($lang) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No languages listed.</p>
    <?php endif; ?>
</div>
<!-- Pricing -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">💰</span> Pricing
    </h3>

    <?php 
        $pricingData = $profile['pricing'] ?? [];
        if (is_string($pricingData)) {
            $pricingData = json_decode($pricingData, true);
        }

        $pricingLabels = [
            '1hr'   => '1 Hour',
            '3hr'   => '3 Hours',
            'night' => 'Full Night',
            'week'  => 'Full Week',
            'month' => 'Full Month'
        ];
    ?>

    <?php if (!empty($pricingData) && is_array($pricingData)): ?>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Duration</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pricingLabels as $key => $label): ?>
                        <?php if (!empty($pricingData[$key])): ?>

                            <?php
                                // Extract amount and currency code (e.g. 5000INR)
                                preg_match('/^(\d+(?:\.\d+)?)([A-Z]{3})$/', $pricingData[$key], $matches);
                                $amount   = $matches[1] ?? 0;
                                $currency = $matches[2] ?? '';
                            ?>

                            <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                                <td class="py-3 px-4 text-gray-900"><?= esc($label) ?></td>
                                <td class="py-3 px-4 text-right font-bold text-green-600">
                                    <?= esc(number_format((float)$amount)) ?>
                                    <span class="text-sm text-gray-500 ml-1"><?= esc($currency) ?></span>
                                </td>
                            </tr>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No pricing information available.</p>
    <?php endif; ?>
</div>

<?php if (!empty($profile['contact_limit_reached'])): ?>

    <div class="text-center text-yellow-600 font-semibold">
        🔒 You have reached your daily limit for viewing contact details.<br>
        Try again tomorrow!
    </div>

<?php else: ?>

<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <span class="text-2xl">📞</span> Contact Information
    </h3>

    <?php 
        $otherPages = $profile['other_pages'] ?? [];
        if (is_string($otherPages)) {
            $decoded = json_decode($otherPages, true);
            $otherPages = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [];
        }
    ?>

    <?php if (
        !empty($profile['phone']) || !empty($profile['whatsapp']) || 
        !empty($profile['telegram']) || !empty($profile['facebook']) || 
        !empty($profile['instagram']) || !empty($profile['discord']) || 
        !empty($profile['website']) || !empty($otherPages)
    ): ?>



            <div class="space-y-3">
                <?php if (!empty($profile['phone'])): ?>
                    <a href="tel:<?= esc($profile['phone']) ?>" class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-telephone-fill text-blue-600 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">Phone</p>
                            <p class="text-gray-900 font-bold"><?= esc($profile['phone']) ?></p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profile['whatsapp'])): ?>
                    <a href="https://wa.me/<?= esc($profile['whatsapp']) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-whatsapp text-green-600 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">WhatsApp</p>
                            <p class="text-gray-900 font-bold">Chat Now</p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profile['telegram'])): ?>
                    <a href="https://t.me/<?= esc(ltrim($profile['telegram'], '@')) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-telegram text-cyan-600 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">Telegram</p>
                            <p class="text-gray-900 font-bold">Message</p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profile['instagram'])): ?>
                    <a href="<?= esc($profile['instagram']) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-rose-50 to-rose-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-instagram text-rose-600 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">Instagram</p>
                            <p class="text-gray-900 font-bold">View Profile</p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profile['facebook'])): ?>
                    <a href="<?= esc($profile['facebook']) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-facebook text-blue-600 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">Facebook</p>
                            <p class="text-gray-900 font-bold">Visit Profile</p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profile['discord'])): ?>
                    <a href="<?= esc($profile['discord']) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-discord text-gray-800 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">Discord</p>
                            <p class="text-gray-900 font-bold">Join Server</p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($profile['website'])): ?>
                    <a href="<?= esc($profile['website']) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:shadow-md transition-shadow group">
                        <i class="bi bi-globe text-gray-600 text-xl group-hover:scale-110 transition-transform"></i>
                        <div>
                            <p class="text-xs font-semibold text-gray-600">Website</p>
                            <p class="text-gray-900 font-bold"><?= esc($profile['website']) ?></p>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($otherPages)): ?>
                    <?php foreach ($otherPages as $label => $url): ?>
                        <?php if (!empty($url)): ?>
                            <a href="<?= esc($url) ?>" target="_blank" class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:shadow-md transition-shadow group">
                                <i class="bi bi-link-45deg text-gray-600 text-xl group-hover:scale-110 transition-transform"></i>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600"><?= esc(ucfirst($label)) ?></p>
                                    <p class="text-gray-900 font-bold">Visit Link</p>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

    <?php else: ?>
        <p class="text-gray-500">No contact information available.</p>
    <?php endif; ?>
</div>

<?php endif; ?>

</main>
<?= view('user/partials/footer') ?>
