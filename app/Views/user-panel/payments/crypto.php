<?= $this->include('user-panel/includes/header') ?>

<?php
$isIndianProfile = false;
if (!empty($profile['location'])) {
    $isIndianProfile = preg_match('/\bIndia\b/i', (string) $profile['location']) === 1;
}

$packageDetails = [
    'name' => 'Premium',
    'amount' => $isIndianProfile ? 'INR 3,000' : '$50',
];

$wallets = $paymentSettings['wallets'] ?? [];
$firstWallet = reset($wallets);
?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 text-orange-600 mb-4 shadow-sm">
                <i class="bi bi-currency-bitcoin text-3xl"></i>
            </div>
            <h2 class="font-bold text-2xl text-slate-800">Crypto Payment</h2>
            <p class="text-sm text-slate-500 mt-2"><?= esc($packageDetails['name']) ?> package payment of <?= esc($packageDetails['amount']) ?>.</p>
        </div>

        <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100">
            <?php if (empty($wallets)) : ?>
                <p class="rounded-2xl bg-amber-50 border border-amber-100 p-4 text-sm text-amber-800">Crypto payments are not configured yet. Please contact support.</p>
            <?php else : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="cryptoSelect" class="block text-sm font-semibold text-slate-700 mb-2">Select Crypto</label>
                    <select id="cryptoSelect" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-violet-400 focus:outline-none">
                        <?php foreach ($wallets as $key => $wallet): ?>
                            <option value="<?= esc($key) ?>"><?= esc($wallet['name']) ?> - <?= esc($wallet['network']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="mt-5">
                        <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Wallet ID</p>
                        <div class="mt-2 flex items-stretch gap-2">
                            <input id="walletAddress" type="text" readonly value="<?= esc($firstWallet['address']) ?>" class="min-w-0 flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            <button type="button" id="copyWallet" class="w-12 rounded-2xl bg-violet-600 text-white flex items-center justify-center" title="Copy wallet ID">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                        <p id="copyStatus" class="mt-2 hidden text-xs font-semibold text-green-600">Wallet ID copied.</p>
                    </div>

                    <div class="mt-5 rounded-2xl bg-amber-50 border border-amber-100 p-4 text-sm text-amber-800">
                        <i class="bi bi-info-circle-fill mr-1"></i>
                        Send only the selected crypto on the selected network. Wrong network transfers cannot be verified.
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center rounded-3xl bg-slate-50 p-5 border border-slate-100">
                    <img id="cryptoQr" src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= urlencode($firstWallet['address']) ?>" alt="Crypto wallet QR code" class="h-56 w-56 rounded-2xl bg-white p-3 shadow-sm">
                    <p id="networkLabel" class="mt-4 text-sm font-semibold text-slate-700"><?= esc($firstWallet['network']) ?></p>
                    <p class="mt-1 text-xs text-slate-500">Scan to copy wallet details</p>
                </div>
            </div>

            <form id="cryptoForm" action="#" method="post" class="mt-6 border-t border-slate-100 pt-6">
                <label for="cryptoTxnId" class="block text-sm font-semibold text-slate-700 mb-2">Transaction ID</label>
                <input id="cryptoTxnId" name="transaction_id" type="text" placeholder="Enter crypto transaction ID" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-violet-400 focus:outline-none">

                <button type="submit" class="mt-5 w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition inline-flex items-center justify-center gap-2">
                    <i class="bi bi-send-check-fill"></i>
                    Submit Transaction ID
                </button>
                <p id="cryptoSubmitStatus" class="hidden mt-3 text-center text-sm font-semibold text-green-600">Transaction ID captured for UI preview.</p>
            </form>
            <?php endif; ?>
        </div>

        <a href="<?= localized_url('user-panel/payments/payment') ?>" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-violet-600">
            <i class="bi bi-arrow-left"></i>
            Back to payment options
        </a>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wallets = <?= json_encode($wallets, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const select = document.getElementById('cryptoSelect');
    const address = document.getElementById('walletAddress');
    const qr = document.getElementById('cryptoQr');
    const network = document.getElementById('networkLabel');
    const copyButton = document.getElementById('copyWallet');
    const copyStatus = document.getElementById('copyStatus');
    const form = document.getElementById('cryptoForm');
    const submitStatus = document.getElementById('cryptoSubmitStatus');

    select?.addEventListener('change', () => {
        const wallet = wallets[select.value];
        if (!wallet) return;
        address.value = wallet.address;
        network.textContent = wallet.network;
        qr.src = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encodeURIComponent(wallet.address);
        copyStatus.classList.add('hidden');
    });

    copyButton?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(address.value);
            copyStatus.classList.remove('hidden');
        } catch (error) {
            address.select();
            document.execCommand('copy');
            copyStatus.classList.remove('hidden');
        }
    });

    form?.addEventListener('submit', (event) => {
        event.preventDefault();
        submitStatus.classList.remove('hidden');
    });
});
</script>

<?= $this->include('user-panel/includes/footer') ?>
