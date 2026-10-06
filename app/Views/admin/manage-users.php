<?= view('admin/includes/header') ?>
    <main class="p-6 bg-gray-100 min-h-screen">

    <div class="mb-4 flex gap-2 justify-end">
        <form action="/ci-admin/users" method="get" class="flex gap-2">
            <input type="text" name="id" value="<?= esc($id ?? '') ?>" placeholder="ID"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="name" value="<?= esc($name ?? '') ?>" placeholder="Name"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="email" value="<?= esc($email ?? '') ?>" placeholder="Email"
                   class="px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
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
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y bg-white">
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3"><?= esc($user['id']) ?></td>
                            <td class="px-4 py-3"><?= esc($user['name'] ?? '') ?></td>
                            <td class="px-4 py-3"><?= esc($user['email']) ?></td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <?php if (empty($user['email_verified_at'])) : ?>
                                        <form action="/ci-admin/users/verify-email/<?= esc($user['id']) ?>" method="post" onsubmit="return confirm('Mark this email as verified?')">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                               class="inline-flex items-center px-3 py-1 bg-emerald-600 text-white rounded text-xs hover:bg-emerald-700">
                                                Verify email
                                            </button>
                                        </form>
                                    <?php else : ?>
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 rounded text-xs border border-emerald-200">
                                            Verified
                                        </span>
                                    <?php endif; ?>

                                    <form action="/ci-admin/users/delete/<?= esc($user['id']) ?>" method="post" onsubmit="return confirm('Delete user, profile, and images?')">
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
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            No users found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php
$currentPage = (int) ($page ?? 1);
$perPage = 100;
$totalPages = ceil($totalUsers / $perPage);
$maxPagesToShow = 3;
$startPage = max(min($currentPage - 1, $totalPages - $maxPagesToShow + 1), 1);
$endPage = min($startPage + $maxPagesToShow - 1, $totalPages);
?>

<div class="mt-6 flex justify-end">
    <div class="flex items-center gap-2">
        <a href="<?= '/ci-admin/users?page=' . max($currentPage - 1, 1) . '&id=' . ($id ?? '') . '&name=' . ($name ?? '') . '&email=' . ($email ?? '') ?>"
           class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
            Prev
        </a>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="px-3 py-1 border rounded bg-blue-600 text-white"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= '/ci-admin/users?page=' . $i . '&id=' . ($id ?? '') . '&name=' . ($name ?? '') . '&email=' . ($email ?? '') ?>"
                   class="px-3 py-1 border rounded hover:bg-gray-200"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <a href="<?= '/ci-admin/users?page=' . min($currentPage + 1, $totalPages) . '&id=' . ($id ?? '') . '&name=' . ($name ?? '') . '&email=' . ($email ?? '') ?>"
           class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == $totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
            Next
        </a>
    </div>
</div>

    </main>
<?= view('admin/includes/footer') ?>
