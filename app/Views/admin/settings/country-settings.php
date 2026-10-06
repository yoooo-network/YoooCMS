<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-7xl space-y-6">
        <header>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Configuration</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Countries</h1>
            <p class="mt-1 text-sm text-slate-500">Add, edit, and remove countries used by the site.</p>
        </header>

        <?php if ($message = session()->getFlashdata('success')) : ?><div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= esc($message) ?></div><?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')) : ?><div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= esc($message) ?></div><?php endif; ?>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="add-country-heading">
            <div class="mb-5"><h2 id="add-country-heading" class="text-lg font-semibold text-slate-900">Add country</h2><p class="mt-1 text-sm text-slate-500">The URL slug is generated from the country name.</p></div>
            <form action="<?= site_url('ci-admin/settings/locations/countries') ?>" method="post" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <?= csrf_field() ?>
                <label class="text-sm font-medium text-slate-700 lg:col-span-2">Country name<input name="name" type="text" maxlength="100" required class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                <label class="text-sm font-medium text-slate-700">Phone code<input name="phone_code" type="text" maxlength="10" placeholder="+1" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                <label class="text-sm font-medium text-slate-700">Currency<input name="currency_code" type="text" maxlength="3" pattern="[A-Za-z]{3}" placeholder="USD" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5 uppercase"></label>
                <label class="text-sm font-medium text-slate-700">Sort order<input name="sort_order" type="number" value="0" aria-label="Sort order" title="Sort order" class="mt-1.5 h-11 w-full rounded-lg border border-slate-300 px-2.5"></label>
                <div class="sm:col-span-2 lg:col-span-5"><button type="submit" class="rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-violet-700"><i class="bi bi-plus-lg mr-1"></i>Add country</button></div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="countries-heading">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div><h2 id="countries-heading" class="text-lg font-semibold text-slate-900">Countries</h2><p class="mt-1 text-sm text-slate-500"><?= count($countries) ?> total</p></div>
            </div>

            <?php if ($countries === []) : ?>
                <p class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">No countries have been added yet.</p>
            <?php else : ?>
                <div class="space-y-3">
                    <?php foreach ($countries as $country) : ?>
                        <article class="rounded-xl border border-slate-200 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2"><h3 class="font-semibold text-slate-900"><?= esc($country['name']) ?></h3><span class="rounded-full px-2 py-0.5 text-xs font-medium <?= ! empty($country['is_active']) ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>"><?= ! empty($country['is_active']) ? 'Active' : 'Inactive' ?></span></div>
                                    <p class="mt-1 text-xs text-slate-500">/<?= esc($country['slug']) ?> · <?= (int) ($country['city_count'] ?? 0) ?> cities<?= ! empty($country['phone_code']) ? ' · ' . esc($country['phone_code']) : '' ?><?= ! empty($country['currency_code']) ? ' · ' . esc($country['currency_code']) : '' ?></p>
                                </div>
                                <details class="group">
                                    <summary class="cursor-pointer list-none rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 [&::-webkit-details-marker]:hidden"><i class="bi bi-pencil mr-1"></i>Edit</summary>
                                    <form id="edit-country-<?= (int) $country['id'] ?>" action="<?= site_url('ci-admin/settings/locations/countries/' . (int) $country['id'] . '/update') ?>" method="post" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-2 lg:grid-cols-5">
                                        <?= csrf_field() ?>
                                        <label class="text-sm font-medium text-slate-700 lg:col-span-2">Country name<input name="name" type="text" maxlength="100" value="<?= esc($country['name']) ?>" required class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                                        <label class="text-sm font-medium text-slate-700">Phone code<input name="phone_code" type="text" maxlength="10" value="<?= esc($country['phone_code'] ?? '') ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                                        <label class="text-sm font-medium text-slate-700">Currency<input name="currency_code" type="text" maxlength="3" pattern="[A-Za-z]{3}" value="<?= esc($country['currency_code'] ?? '') ?>" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5 uppercase"></label>
                                        <label class="text-sm font-medium text-slate-700">Sort order<input name="sort_order" type="number" value="<?= (int) $country['sort_order'] ?>" aria-label="Sort order" class="mt-1.5 h-11 w-full rounded-lg border border-slate-300 px-2.5"></label>
                                    </form>
                                    <div class="mt-3 flex flex-wrap gap-2"><button type="submit" form="edit-country-<?= (int) $country['id'] ?>" class="rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white hover:bg-violet-700">Save country</button>
                                            <form action="<?= site_url('ci-admin/settings/locations/countries/' . (int) $country['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this country and all its cities? This cannot be undone.');">
                                                <?= csrf_field() ?><button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50"><i class="bi bi-trash mr-1"></i>Delete country</button>
                                            </form>
                                    </div>
                                </details>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>
<?= view('admin/includes/footer') ?>
