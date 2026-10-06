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

    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-xl font-semibold text-slate-800 mb-1">Contact Settings</h1>
        <p class="text-sm text-slate-500 mb-6">Enable/disable each sticky contact button and set its value.</p>

        <form action="/ci-admin/settings/contact" method="post" class="space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" name="telegram_enabled" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                        <?= !empty($settings['telegram']['enabled']) ? 'checked' : '' ?>>
                    Enable Telegram
                </label>
                <input type="text"
                    name="telegram_value"
                    value="<?= esc($settings['telegram']['value'] ?? '') ?>"
                    placeholder="https://t.me/your_channel or @username"
                    class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" name="whatsapp_enabled" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                        <?= !empty($settings['whatsapp']['enabled']) ? 'checked' : '' ?>>
                    Enable WhatsApp
                </label>
                <input type="text"
                    name="whatsapp_value"
                    value="<?= esc($settings['whatsapp']['value'] ?? '') ?>"
                    placeholder="+91xxxxxxxxxx"
                    class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" name="phone_enabled" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                        <?= !empty($settings['phone']['enabled']) ? 'checked' : '' ?>>
                    Enable Phone
                </label>
                <input type="text"
                    name="phone_value"
                    value="<?= esc($settings['phone']['value'] ?? '') ?>"
                    placeholder="+91xxxxxxxxxx"
                    class="w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
            </div>

            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Settings</button>
            </div>
        </form>
    </div>
</main>
<?= view('admin/includes/footer') ?>
