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

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">SEO Pages</h1>
            <button id="showFormBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Add new</button>
        </div>

        <div id="seoFormWrap" class="mb-6 bg-white shadow rounded p-6 <?= isset($seo) && $seo ? '' : 'hidden' ?>">
            <h2 class="text-lg font-semibold mb-4"><?= isset($seo) && $seo ? 'Edit Entry' : 'Add New Entry' ?></h2>
            <form action="/ci-admin/seo/save" method="post" class="space-y-4" id="seoEntryForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= esc($seo['id'] ?? '') ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">URL</label>
                        <input type="text" name="url" value="<?= esc($seo['url'] ?? '') ?>"
                            class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                        <input type="text" name="title" value="<?= esc($seo['title'] ?? '') ?>"
                            class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Meta Keyword</label>
                        <input type="text" name="meta_keywords" value="<?= esc($seo['meta_keywords'] ?? '') ?>"
                            class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">H1</label>
                        <input type="text" name="h1" value="<?= esc($seo['h1'] ?? '') ?>"
                            class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent"><?= esc($seo['description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Intro Content</label>
                        <textarea name="intro_content" rows="4"
                            class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent"><?= esc($seo['intro_content'] ?? '') ?></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">SEO Content</label>
                    <textarea name="seo_content" rows="5"
                        class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent"><?= esc($seo['seo_content'] ?? '') ?></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <?= isset($seo) && $seo ? 'Update' : 'Add' ?>
                    </button>
                    <button type="button" id="hideFormBtn"
                        class="px-4 py-2 bg-slate-200 text-slate-800 rounded hover:bg-slate-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">URL</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white">
                    <?php if (!empty($entries)) : ?>
                        <?php foreach ($entries as $row) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3"><?= esc($row['url']) ?></td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="/ci-admin/seo/edit/<?= esc($row['id']) ?>" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <form action="/ci-admin/seo/delete/<?= esc($row['id']) ?>" method="post" class="inline" onsubmit="return confirm('Delete this SEO entry?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2" class="px-4 py-6 text-center text-gray-500">
                                No SEO entries found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
        $currentPage = (int) ($page ?? 1);
        $perPage = 100;
        $totalPages = isset($totalEntries) ? (int) ceil($totalEntries / $perPage) : 1;
        $maxPagesToShow = 3;
        $startPage = max(min($currentPage - 1, $totalPages - $maxPagesToShow + 1), 1);
        $endPage = min($startPage + $maxPagesToShow - 1, $totalPages);
        ?>

        <div class="mt-6 flex justify-end">
            <div class="flex items-center gap-2">
                <a href="<?= '/ci-admin/seo?page=' . max($currentPage - 1, 1) ?>"
                   class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    Prev
                </a>

                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="px-3 py-1 border rounded bg-blue-600 text-white"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= '/ci-admin/seo?page=' . $i ?>"
                           class="px-3 py-1 border rounded hover:bg-gray-200"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <a href="<?= '/ci-admin/seo?page=' . min($currentPage + 1, $totalPages) ?>"
                   class="px-3 py-1 border rounded hover:bg-gray-200 <?= $currentPage == $totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    Next
                </a>
            </div>
        </div>


    </main>
    <script>
        (function () {
            const btn = document.getElementById('showFormBtn');
            const wrap = document.getElementById('seoFormWrap');
            const form = document.getElementById('seoEntryForm');
            const idInput = form ? form.querySelector('input[name="id"]') : null;
            const hideBtn = document.getElementById('hideFormBtn');
            if (btn && wrap && form) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    wrap.classList.remove('hidden');
                    form.reset();
                    if (idInput) { idInput.value = ''; }
                    wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
            if (hideBtn && wrap && form) {
                hideBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    wrap.classList.add('hidden');
                    form.reset();
                    if (idInput) { idInput.value = ''; }
                });
            }
        })();
    </script>
<?= view('admin/includes/footer') ?>
