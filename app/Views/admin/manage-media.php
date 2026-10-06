<?= view('admin/includes/header') ?>
    <main class="p-6 bg-gray-100 min-h-screen">

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="mb-4 text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Preview</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Filename</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Size</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Modified</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white">
                    <?php if (!empty($images)) : ?>
                        <?php foreach ($images as $image) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <img src="<?= esc($image['url']) ?>" alt="Image" class="h-16 w-16 rounded object-cover">
                                </td>
                                <td class="px-4 py-3"><?= esc($image['name']) ?></td>
                                <td class="px-4 py-3"><?= esc($image['size_kb']) ?> KB</td>
                                <td class="px-4 py-3"><?= date('Y-m-d H:i', $image['modified']) ?></td>
                                <td class="px-4 py-3 text-right">
                                    <form action="/ci-admin/media/delete/<?= esc($image['name']) ?>" method="post" onsubmit="return confirm('Delete this image and its related sizes?')">
                                        <?= csrf_field() ?>
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No media found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
        $currentPage = (int) ($page ?? 1);
        $perPage = 100;
        $totalPages = isset($totalImages) ? (int) ceil($totalImages / $perPage) : 1;
        $maxPagesToShow = 3;
        $startPage = max(min($currentPage - 1, $totalPages - $maxPagesToShow + 1), 1);
        $endPage = min($startPage + $maxPagesToShow - 1, $totalPages);
        ?>

        <div class="mt-6 flex justify-end">
            <div class="flex items-center gap-2">
                <a href="<?= '/ci-admin/media?page=' . max($currentPage - 1, 1) ?>"
                   class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    Prev
                </a>

                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="px-3 py-1 border rounded bg-blue-600 text-white"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= '/ci-admin/media?page=' . $i ?>"
                           class="px-3 py-1 border rounded hover:bg-gray-200"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <a href="<?= '/ci-admin/media?page=' . min($currentPage + 1, $totalPages) ?>"
                   class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == $totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    Next
                </a>
            </div>
        </div>

    </main>
<?= view('admin/includes/footer') ?>
