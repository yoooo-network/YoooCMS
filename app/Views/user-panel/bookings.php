<?= $this->include('user-panel/includes/header') ?>
<main class="flex-1 px-4 pb-28 pt-20">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900">Booking requests</h1>
            <p class="mt-1 text-sm text-slate-500">Requests sent by people interested in your profile.</p>
        </div>

        <?php if (empty($bookings)): ?>
            <div class="rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-violet-50 text-violet-600"><i class="bi bi-calendar2-check text-2xl"></i></div>
                <h2 class="mt-4 text-lg font-bold text-slate-800">No booking requests yet</h2>
                <p class="mt-2 text-sm text-slate-500">New requests for your profile will appear here.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($bookings as $booking): ?>
                    <article class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900"><?= esc($booking['name']) ?></h2>
                                <p class="mt-1 text-sm text-slate-500">Received <?= esc(date('M j, Y g:i A', strtotime($booking['created_at'] ?? 'now'))) ?></p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold <?= ($booking['status'] ?? '') === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>"><?= esc(ucfirst($booking['status'] ?? 'pending')) ?></span>
                        </div>
                        <div class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                            <p><span class="font-semibold text-slate-700">Phone:</span> <a class="text-violet-700 hover:underline" href="tel:<?= esc(preg_replace('/[^+0-9]/', '', $booking['phone'])) ?>"><?= esc($booking['phone']) ?></a></p>
                        </div>
                        <div class="mt-4 rounded-2xl bg-slate-50 p-4">
                            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">Message</h3>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700"><?= esc($booking['message']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?= $this->include('user-panel/includes/footer') ?>
