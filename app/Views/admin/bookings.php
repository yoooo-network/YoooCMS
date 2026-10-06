<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-100 p-4 sm:p-6">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900">Manage bookings</h1>
            <p class="mt-1 text-sm text-slate-500">Review booking requests sent to profile owners.</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?><div class="mb-4 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-700"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?><div class="mb-4 rounded-xl bg-rose-50 p-3 text-sm text-rose-700"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

        <?php if (empty($bookings)): ?>
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-500">No booking requests yet.</div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($bookings as $booking): ?>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-lg font-bold text-slate-900"><?= esc($booking['name']) ?></h2>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold <?= ($booking['status'] ?? '') === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>"><?= esc(ucfirst($booking['status'] ?? 'pending')) ?></span>
                                </div>
                                <p class="mt-1 text-sm text-slate-500">For <span class="font-semibold text-slate-700"><?= esc($booking['profile_name'] ?? 'Deleted profile') ?></span> · <?= esc(date('M j, Y g:i A', strtotime($booking['created_at'] ?? 'now'))) ?></p>
                            </div>
                            <div class="flex gap-2">
                                <?php if (($booking['status'] ?? '') !== 'approved'): ?>
                                    <form method="post" action="<?= site_url('ci-admin/bookings/approve/' . $booking['id']) ?>" onsubmit="return confirm('Approve this booking request?')">
                                        <?= csrf_field() ?><button class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700" type="submit"><i class="bi bi-check2"></i> Approve</button>
                                    </form>
                                <?php endif; ?>
                                <form method="post" action="<?= site_url('ci-admin/bookings/delete/' . $booking['id']) ?>" onsubmit="return confirm('Delete this booking request?')">
                                    <?= csrf_field() ?><button class="rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700" type="submit"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                            <p><strong>Phone:</strong> <?= esc($booking['phone']) ?></p>
                        </div>
                        <div class="mt-3 rounded-xl bg-slate-50 p-4">
                            <p class="whitespace-pre-line text-sm leading-6 text-slate-700"><?= esc($booking['message']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?= view('admin/includes/footer') ?>
