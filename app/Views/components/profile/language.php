<?php
$profile = is_array($profile ?? null) ? $profile : [];
$languages = is_array($profile['languages'] ?? null) ? $profile['languages'] : [];
?>

<?php if ($languages !== []): ?>
    <section class="bg-white rounded-3xl p-6 shadow-sm">
        <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-slate-800">
            <i class="bi bi-translate text-violet-500"></i> <?= esc(lang('Site.languages')) ?>
        </h2>

        <div class="flex flex-wrap gap-3">
            <?php foreach ($languages as $language): ?>
                <span class="bg-gradient-to-r from-violet-50 to-fuchsia-50 border border-violet-100 text-violet-800 px-5 py-2 rounded-xl text-sm font-semibold shadow-sm flex items-center gap-2">
                    <i class="bi bi-chat-dots text-violet-400"></i> <?= esc($language) ?>
                </span>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
