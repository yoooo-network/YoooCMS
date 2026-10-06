<?= view('admin/includes/header') ?>
    <main class="p-6 bg-gray-100 min-h-screen">

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">User Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Email ID</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Email Subject</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y bg-white">
                <?php if (!empty($logs)) : ?>
                    <?php foreach ($logs as $log) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3"><?= esc($log['name']) ?></td>
                            <td class="px-4 py-3"><?= esc($log['email']) ?></td>
                            <td class="px-4 py-3"><?= esc($log['subject']) ?></td>
                            <td class="px-4 py-3">
                                <?php if (($log['status'] ?? '') === 'success') : ?>
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-green-100 text-green-700">Success</span>
                                <?php else : ?>
                                    <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-red-100 text-red-700">Failed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            No sent emails found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    </main>
<?= view('admin/includes/footer') ?>
