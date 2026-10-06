<?php
// Keep every membership type supplied by the API, including free profiles,
// while preventing an oversized response from rendering more than 60 cards.
$displayProfiles = array_slice(is_array($profiles ?? null) ? $profiles : [], 0, 60);
?>
<?= $this->include('includes/head') ?>
<?= $this->include('includes/header') ?>
<main class="pt-16 sm:pt-24 pb-20 bg-gradient-to-br from-slate-50 to-violet-50/50 min-h-screen">
<?= $this->include('includes/intro-panel') ?>
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 sm:gap-5 max-w-screen-2xl mx-auto px-3 sm:px-6 lg:px-8">
    <?php if (!empty($displayProfiles)): ?>
        <?php foreach ($displayProfiles as $profile): ?>
            <?= $this->setData(['profile' => $profile])->include('components/profile_card') ?>
        <?php endforeach; ?>
<?php else: ?>
    <div class="col-span-full py-16 sm:py-24 px-4">
        <div class="relative max-w-lg mx-auto overflow-hidden
                    rounded-3xl border border-gray-100
                    bg-white p-8 sm:p-12
                    text-center shadow-xl shadow-gray-200/40">

            <!-- Decorative background -->
            <div class="absolute -top-20 -right-20 w-40 h-40
                        rounded-full bg-blue-100/60 blur-3xl"></div>

            <div class="absolute -bottom-20 -left-20 w-40 h-40
                        rounded-full bg-indigo-100/60 blur-3xl"></div>

            <div class="relative">

                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center
                            w-20 h-20 rounded-3xl
                            bg-gradient-to-br from-blue-500 to-indigo-600
                            text-white shadow-lg shadow-blue-500/25">

                    <i class="bi bi-search text-3xl"></i>
                </div>

                <!-- Content -->
                <h3 class="mt-7 text-2xl sm:text-3xl font-bold text-gray-900">
                    <?= esc(lang('Site.noProfilesFound')) ?>
                </h3>

                <p class="mt-3 text-sm sm:text-base text-gray-500
                          leading-relaxed max-w-sm mx-auto">
                    <?= esc(lang('Site.noProfilesMessage')) ?>
                </p>

                <!-- Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row
                            items-center justify-center gap-3">

                    <a href="<?= site_url() ?>"
                       class="w-full sm:w-auto inline-flex items-center
                              justify-center gap-2 px-6 py-3
                              rounded-xl bg-gray-900 text-white
                              text-sm font-semibold
                              hover:bg-gray-800
                              transition">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        <?= esc(lang('Site.exploreProfiles')) ?>
                    </a>

                    <button type="button"
                            onclick="window.location.reload()"
                            class="w-full sm:w-auto inline-flex items-center
                                   justify-center gap-2 px-6 py-3
                                   rounded-xl border border-gray-200
                                   bg-white text-gray-700
                                   text-sm font-semibold
                                   hover:bg-gray-50
                                   transition">
                        <i class="bi bi-arrow-clockwise"></i>
                        <?= esc(lang('Site.tryAgain')) ?>
                    </button>

                </div>

            </div>
        </div>
    </div>
<?php endif; ?>
</div>
<?= $this->include('includes/seo-panel') ?>
<?= $this->include('includes/footer-content') ?>
</main>
<?= $this->include('includes/footer') ?>
