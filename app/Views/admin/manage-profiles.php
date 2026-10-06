<?= view('admin/includes/header') ?>
    <main class="p-6 bg-gray-100 min-h-screen">

    <div class="mb-4 flex gap-2 justify-end">
        <form action="/ci-admin/profiles" method="get" class="flex gap-2">
            <input type="text" name="id" value="<?= esc($id ?? '') ?>" placeholder="ID"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="name" value="<?= esc($name ?? '') ?>" placeholder="Name"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="email" value="<?= esc($email ?? '') ?>" placeholder="Email"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="city" value="<?= esc($city ?? '') ?>" placeholder="City"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <select name="gender" class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                <option value="">Gender</option>
                <option value="male" <?= (isset($gender) && $gender === 'male') ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= (isset($gender) && $gender === 'female') ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= (isset($gender) && $gender === 'other') ? 'selected' : '' ?>>Other</option>
            </select>
            <button type="submit"
                    class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">ID</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Gender</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">City</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y bg-white">
                <?php if (!empty($profiles)) : ?>
                    <?php foreach ($profiles as $profile) : ?>
                        <?php
                            $profileName = trim((string) ($profile['name'] ?? ''));
                            $profileSlug = url_title($profileName !== '' ? $profileName : 'profile-' . $profile['id'], '-', true);
                            $profileUrl = 'https://www.yooo.app/en/profile/' . $profile['id'] . '/' . $profileSlug;
                            $checkVerificationUrl = site_url('ci-admin/profiles/check-verification/' . $profile['id']);

                            $hasVerificationUploads = false;
                            if (!empty($profile['user_id'])) {
                                $verificationUploadPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'verifications' . DIRECTORY_SEPARATOR . 'user_' . (int) $profile['user_id'] . DIRECTORY_SEPARATOR;
                                if (is_dir($verificationUploadPath)) {
                                    $hasLiveSelfie = glob($verificationUploadPath . 'live_selfie_*') ?: [];
                                    $hasIdVerification = glob($verificationUploadPath . 'id_verification_*') ?: [];
                                    $hasVerificationUploads = ($hasLiveSelfie !== [] || $hasIdVerification !== []);
                                }
                            }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3"><?= esc($profile['id']) ?></td>
                            <td class="px-4 py-3">
                                <a href="<?= esc($profileUrl) ?>" class="text-blue-600 hover:underline">
                                    <?= esc($profile['name']) ?>
                                </a>
                                <?php if ($hasVerificationUploads): ?>
                                    <a href="<?= esc($checkVerificationUrl) ?>" target="_blank" rel="noopener noreferrer" class="ml-2 inline-flex items-center px-2 py-1 text-xs rounded bg-sky-600 text-white hover:bg-sky-700">
                                        Check verification
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?= esc($profile['gender']) ?></td>
                            <td class="px-4 py-3"><?= esc($profile['email']) ?></td>
							<?php
$city = explode(',', $profile['location'])[0] ?? '';
?>
<td class="px-4 py-3"><?= esc($city) ?></td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2 flex-wrap">
                                <form action="/ci-admin/profiles/toggle-status/<?= esc($profile['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="px-3 py-1 text-xs rounded <?= $profile['status'] == 'approved' ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' ?>">
                                        <?= $profile['status'] == 'approved' ? 'Approved' : 'Disapproved' ?>
                                    </button>
                                </form>
                                <form action="/ci-admin/profiles/toggle-membership/<?= esc($profile['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="px-3 py-1 text-xs rounded <?= $profile['membership'] == 'premium' ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-yellow-300 text-gray-700 hover:bg-yellow-400' ?>">
                                        <?= ucfirst($profile['membership']) ?>
                                    </button>
                                </form>
                                <form action="/ci-admin/profiles/toggle-verification/<?= esc($profile['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="px-3 py-1 text-xs rounded <?= ((int) ($profile['is_verified'] ?? 0) === 1) ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' ?>">
                                        <?= ((int) ($profile['is_verified'] ?? 0) === 1) ? 'Verified' : 'Unverified' ?>
                                    </button>
                                </form>
                                <form action="/ci-admin/profiles/delete/<?= esc($profile['id']) ?>" method="post" onsubmit="return confirm('Delete profile, linked user, and images?')">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                       class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No profiles found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php
$currentPage = (int) ($page ?? 1);
$perPage = 100;
$totalPages = ceil($totalProfiles / $perPage);
$maxPagesToShow = 3;
$startPage = max(min($currentPage - 1, $totalPages - $maxPagesToShow + 1), 1);
$endPage = min($startPage + $maxPagesToShow - 1, $totalPages);
?>

<div class="mt-6 flex justify-end">
    <div class="flex items-center gap-2">
        <a href="<?= '/ci-admin/profiles?page=' . max($currentPage - 1, 1) ?>"
           class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
            Prev
        </a>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="px-3 py-1 border rounded bg-blue-600 text-white"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= '/ci-admin/profiles?page=' . $i ?>"
                   class="px-3 py-1 border rounded hover:bg-gray-200"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <a href="<?= '/ci-admin/profiles?page=' . min($currentPage + 1, $totalPages) ?>"
           class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == $totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
            Next
        </a>
    </div>
</div>

    </main>
<?= view('admin/includes/footer') ?>
