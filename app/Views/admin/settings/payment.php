<?= view('admin/includes/header') ?>
<main class="p-6 bg-gray-100 min-h-screen">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-4 text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-xl font-semibold text-slate-800 mb-1">Payment Settings</h1>
        <p class="text-sm text-slate-500 mb-6">Set the UPI account and cryptocurrency wallets shown to users during payment.</p>

        <form action="/ci-admin/settings/payment" method="post" class="space-y-8">
            <?= csrf_field() ?>

            <section>
                <h2 class="text-base font-semibold text-slate-800 mb-3">UPI</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="text-sm font-medium text-slate-700">Account name
                        <input type="text" name="upi_name" value="<?= esc($settings['upi']['name'] ?? '') ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
                    </label>
                    <label class="text-sm font-medium text-slate-700">UPI ID
                        <input type="text" name="upi_id" value="<?= esc($settings['upi']['id'] ?? '') ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5 focus:ring-2 focus:ring-accent focus:border-accent">
                    </label>
                </div>
            </section>

            <section class="border-t border-slate-200 pt-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4">Cryptocurrency wallets</h2>
                <div class="space-y-5">
                    <?php foreach ($settings['wallets'] as $key => $wallet) : ?>
                        <fieldset class="rounded-lg border border-slate-200 p-4">
                            <legend class="px-1 text-sm font-semibold text-slate-700"><?= esc(strtoupper($key)) ?></legend>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="text-sm font-medium text-slate-700">Currency name
                                    <input type="text" name="wallet_<?= esc($key) ?>_name" value="<?= esc($wallet['name']) ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5">
                                </label>
                                <label class="text-sm font-medium text-slate-700">Network
                                    <input type="text" name="wallet_<?= esc($key) ?>_network" value="<?= esc($wallet['network']) ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5">
                                </label>
                                <label class="text-sm font-medium text-slate-700">Wallet address
                                    <input type="text" name="wallet_<?= esc($key) ?>_address" value="<?= esc($wallet['address']) ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5">
                                </label>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>
                </div>
            </section>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Settings</button>
        </form>
    </div>
</main>
<?= view('admin/includes/footer') ?>
