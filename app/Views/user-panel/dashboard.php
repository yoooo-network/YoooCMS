    <?= $this->include('user-panel/includes/header') ?>
    <main class="flex-1 pt-20 pb-28 px-4">

        <div class="max-w-5xl mx-auto">
            <!-- Profile Card -->

            <div class="bg-white rounded-3xl p-5 shadow-sm mb-5">

                <div class="flex items-center gap-4">

                    <?php
                        $profileImage = '';
                        if (!empty($profile['images'])) {
                            $images = is_string($profile['images']) ? json_decode($profile['images'], true) : $profile['images'];
                            if (is_array($images) && !empty($images)) {
                                $cdnUrl = rtrim((string) env('app.cdnURL'), '/');
                                $profileImage = $cdnUrl . '/images/users/' . $images[0];
                            }
                        }
                    ?>
                    
                    <?php if ($profileImage): ?>
                        <img src="<?= esc($profileImage) ?>" class="w-20 h-20 rounded-2xl object-cover">
                    <?php else: ?>
                        <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <i class="bi bi-person-fill text-4xl text-slate-400"></i>
                        </div>
                    <?php endif; ?>

                    <div class="flex-1">

                        <div class="flex items-center gap-2">

                            <h2 class="font-bold text-xl">
                                <?= esc($profile['name'] ?? $user['name'] ?? 'User Name') ?>
                            </h2>

                            <?php if (!empty($profile['is_verified'])): ?>
                                <i class="bi bi-patch-check-fill text-green-500"></i>
                            <?php endif; ?>

                        </div>

                        <p class="text-sm text-slate-500">
                            <?= esc($profile['gender'] ?? 'Not set') ?> • <?= esc($profile['location'] ?? 'Location not set') ?>
                        </p>

                        <div class="mt-3">

                            <div class="flex justify-between text-xs mb-1">

                                <span>Profile Completion</span>

                                <?php
                                    $completion = 10;
                                    if (!empty($profile['name'])) $completion += 15;
                                    if (!empty($profile['location'])) $completion += 15;
                                    if (!empty($profile['gender'])) $completion += 15;
                                    if (!empty($profile['images'])) $completion += 20;
                                    if (!empty($profile['services'])) $completion += 25;
                                ?>
                                <span><?= $completion ?>%</span>

                            </div>

                            <div class="h-2 bg-slate-100 rounded-full">

                                <div class="h-2 w-[<?= $completion ?>%] bg-violet-600 rounded-full"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Stats -->
<?php
    $views = rand(10, 20);
    $favorites = rand(10, 15);
    $photos = rand(5, 10);
    $bookings = rand(2, 3);
?>
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <!-- Views -->

    <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-100">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-slate-500 text-xs uppercase tracking-wide">
                    Views
                </p>

                <h3 class="text-3xl font-bold mt-1">
                    <?= $views ?>
                </h3>

            </div>

            <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center">

                <i class="bi bi-eye text-xl"></i>

            </div>

        </div>

        <div class="mt-4 text-xs text-green-600 font-medium">

            <i class="bi bi-arrow-up"></i>
            +<?= rand(10, 15) ?> today

        </div>

    </div>

    <!-- Favorites -->

    <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-100">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-slate-500 text-xs uppercase tracking-wide">
                    Favorites
                </p>

                <h3 class="text-3xl font-bold mt-1">
                    <?= $favorites ?>
                </h3>

            </div>

            <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center">

                <i class="bi bi-heart-fill text-xl"></i>

            </div>

        </div>

        <div class="mt-4 text-xs text-green-600 font-medium">

            <i class="bi bi-arrow-up"></i>
            +<?= rand(5, 10) ?> new today

        </div>

    </div>

    <!-- Portfolio -->

    <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-100">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-slate-500 text-xs uppercase tracking-wide">
                    Photos
                </p>

                <h3 class="text-3xl font-bold mt-1">
                    <?= $photos ?>
                </h3>

            </div>

            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">

                <i class="bi bi-images text-xl"></i>

            </div>

        </div>

        <div class="mt-4 text-xs text-slate-500">

            Portfolio Complete

        </div>

    </div>

    <!-- booking -->

    <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-100">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-slate-500 text-xs uppercase tracking-wide">
                    Bookings
                </p>

                <h3 class="text-3xl font-bold mt-1">
                    <?= $bookings ?>
                </h3>

            </div>

            <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">

                <i class="bi bi-chat-dots-fill text-xl"></i>

            </div>

        </div>

        <div class="mt-4 text-xs text-orange-500 font-medium">

            <?= rand(1, 2) ?> unread

        </div>

    </div>

</div>

<?php 
$status = strtolower($profile['status'] ?? '');
$isVerified = !empty($profile['is_verified']);
$membership = strtolower($profile['membership'] ?? 'free');
?>
<div class="flex flex-col gap-4">
    <?php if ($status !== 'approved'): ?>
        <a href="<?= localized_url('user-panel/edit-profile') ?>"
           class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-3xl font-semibold transition">
            <i class="bi bi-person-fill-gear"></i>
            Complete Your Profile
        </a>
    <?php endif; ?>
    
    <?php if (!$isVerified): ?>
        <a href="<?= localized_url('user-panel/apply-verification') ?>"
           class="flex items-center justify-center gap-2 w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition">
            <i class="bi bi-patch-check-fill"></i>
            Verify Your Profile
        </a>
    <?php endif; ?>
    
    <?php if ($membership === 'free'): ?>
        <a href="<?= localized_url('user-panel/upgrade') ?>"
           class="flex items-center justify-center gap-2 w-full bg-blue-500 hover:bg-blue-600 text-white py-4 rounded-3xl font-semibold transition">
            <i class="bi bi-star-fill"></i>
            Upgrade Your Profile
        </a>
    <?php endif; ?>
</div>

        </div>

    </main>
    <?= $this->include('user-panel/includes/footer') ?>
