<?= $this->include('user-panel/includes/header') ?>

<?php
$isIndianProfile = false;
if (!empty($profile['location'])) {
    $isIndianProfile = preg_match('/\bIndia\b/i', (string) $profile['location']) === 1;
}

$packageDetails = [
    'name' => 'Premium',
    'amount' => '3000',
    'label' => 'INR 3,000',
];

$upiId = $paymentSettings['upi']['id'] ?? '';
$upiName = $paymentSettings['upi']['name'] ?? '';
$upiUri = 'upi://pay?pa=' . rawurlencode($upiId) . '&pn=' . rawurlencode($upiName) . '&am=' . rawurlencode($packageDetails['amount']) . '&cu=INR&tn=' . rawurlencode($packageDetails['name'] . ' Upgrade');
?>

<main class="flex-1 pt-20 pb-28 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600 mb-4 shadow-sm">
                <i class="bi bi-qr-code-scan text-3xl"></i>
            </div>
            <h2 class="font-bold text-2xl text-slate-800">UPI Payment</h2>
            <p class="text-sm text-slate-500 mt-2">Only for Indian users. Pay <?= esc($packageDetails['label']) ?> for the <?= esc($packageDetails['name']) ?> package.</p>
        </div>

        <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100">
            <?php if ($upiId === '') : ?>
                <p class="rounded-2xl bg-amber-50 border border-amber-100 p-4 text-sm text-amber-800">UPI payments are not configured yet. Please contact support.</p>
            <?php else : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col items-center justify-center rounded-3xl bg-slate-50 p-5 border border-slate-100">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=<?= urlencode($upiUri) ?>" alt="UPI payment QR code" class="h-60 w-60 rounded-2xl bg-white p-3 shadow-sm">
                    <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-green-50 text-green-700 px-4 py-2 text-sm font-bold">
                        <i class="bi bi-clock-fill"></i>
                        <span id="upiTimer">15:00</span>
                    </div>
                </div>

                <div>
                    <div class="rounded-3xl border border-slate-100 p-5">
                        <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Pay To UPI ID</p>
                        <h3 class="mt-1 font-bold text-xl text-slate-800"><?= esc($upiName) ?></h3>
                        <div class="mt-4 flex items-stretch gap-2">
                            <input id="upiId" type="text" readonly value="<?= esc($upiId) ?>" class="min-w-0 flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            <button type="button" id="copyUpi" class="w-12 rounded-2xl bg-violet-600 text-white flex items-center justify-center" title="Copy UPI ID">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                        <p id="copyStatus" class="mt-2 hidden text-xs font-semibold text-green-600">UPI ID copied.</p>
                    </div>

                    <div class="mt-4 rounded-2xl bg-amber-50 border border-amber-100 p-4 text-sm text-amber-800">
                        <i class="bi bi-info-circle-fill mr-1"></i>
                        This QR is valid for 15 minutes. If the timer expires, reload the page before paying.
                    </div>
                </div>
            </div>

            <form id="upiForm" action="#" method="post" class="mt-6 border-t border-slate-100 pt-6">
                <label for="upiTxnId" class="block text-sm font-semibold text-slate-700 mb-2">Transaction ID</label>
                <input id="upiTxnId" name="transaction_id" type="text" placeholder="Enter UPI transaction ID" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-violet-400 focus:outline-none">

                <button type="submit" class="mt-5 w-full bg-violet-600 hover:bg-violet-700 text-white py-4 rounded-3xl font-semibold transition inline-flex items-center justify-center gap-2">
                    <i class="bi bi-send-check-fill"></i>
                    Submit Transaction ID
                </button>
                <p id="upiSubmitStatus" class="hidden mt-3 text-center text-sm font-semibold text-green-600">Transaction ID captured for UI preview.</p>
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
    const timer = document.getElementById('upiTimer');
    const copyButton = document.getElementById('copyUpi');
    const upiId = document.getElementById('upiId');
    const copyStatus = document.getElementById('copyStatus');
    const form = document.getElementById('upiForm');
    const submitStatus = document.getElementById('upiSubmitStatus');
    let secondsLeft = 15 * 60;

    if (!timer) return;

    const renderTimer = () => {
        const minutes = Math.floor(secondsLeft / 60).toString().padStart(2, '0');
        const seconds = (secondsLeft % 60).toString().padStart(2, '0');
        timer.textContent = minutes + ':' + seconds;
        if (secondsLeft <= 0) {
            timer.textContent = 'Expired';
            timer.parentElement.classList.remove('bg-green-50', 'text-green-700');
            timer.parentElement.classList.add('bg-red-50', 'text-red-700');
            return;
        }
        secondsLeft -= 1;
        window.setTimeout(renderTimer, 1000);
    };

    renderTimer();

    copyButton?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(upiId.value);
            copyStatus.classList.remove('hidden');
        } catch (error) {
            upiId.select();
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
