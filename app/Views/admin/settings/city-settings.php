<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-7xl space-y-6">
        <header>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Configuration</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Cities</h1>
            <p class="mt-1 text-sm text-slate-500">Add, edit, and remove cities for each country.</p>
        </header>

        <?php if ($message = session()->getFlashdata('success')) : ?><div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= esc($message) ?></div><?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')) : ?><div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= esc($message) ?></div><?php endif; ?>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="cities-heading">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3"><div><h2 id="cities-heading" class="text-lg font-semibold text-slate-900">Cities</h2><p class="mt-1 text-sm text-slate-500"><?php $selectedCountry = null; foreach ($countries as $country) if ((int) $country['id'] === (int) $selectedCountryId) { $selectedCountry = $country; break; } ?><?= $selectedCountry ? 'Manage cities in ' . esc($selectedCountry['name']) : 'Select or add a country to manage its cities.' ?></p></div>
                <?php if ($countries !== []) : ?><form action="<?= site_url('ci-admin/settings/cities') ?>" method="get" class="flex items-center gap-2"><label for="city-country-filter" class="text-sm font-medium text-slate-600">Country</label><select id="city-country-filter" name="country_id" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" onchange="this.form.submit()"><?php foreach ($countries as $country) : ?><option value="<?= (int) $country['id'] ?>" <?= (int) $selectedCountryId === (int) $country['id'] ? 'selected' : '' ?>><?= esc($country['name']) ?></option><?php endforeach; ?></select></form><?php endif; ?>
            </div>

            <?php if ($countries !== []) : ?>
                <form action="<?= site_url('ci-admin/settings/locations/cities') ?>" method="post" class="mb-6 grid gap-4 rounded-xl bg-slate-50 p-4 sm:grid-cols-2 lg:grid-cols-5">
                    <?= csrf_field() ?>
                    <label class="text-sm font-medium text-slate-700 lg:col-span-2">City name<input name="name" type="text" maxlength="150" required class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700 lg:col-span-2">Country<select name="country_id" required class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white p-2.5"><?php foreach ($countries as $country) : ?><option value="<?= (int) $country['id'] ?>" <?= (int) $selectedCountryId === (int) $country['id'] ? 'selected' : '' ?>><?= esc($country['name']) ?></option><?php endforeach; ?></select></label>
                    <label class="text-sm font-medium text-slate-700">Sort order<input name="sort_order" type="number" value="0" aria-label="Sort order" class="mt-1.5 h-11 w-full rounded-lg border border-slate-300 px-2.5"></label>
                    <div class="sm:col-span-2 lg:col-span-5"><button type="submit" class="rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-violet-700"><i class="bi bi-plus-lg mr-1"></i>Add city</button></div>
                </form>

                <?php if ($cities === []) : ?>
                    <p class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500">No cities in this country yet.</p>
                <?php else : ?>
                    <div class="space-y-3">
                        <?php foreach ($cities as $city) : ?>
                            <article class="rounded-xl border border-slate-200 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div><div class="flex flex-wrap items-center gap-2"><h3 class="font-semibold text-slate-900"><?= esc($city['name']) ?></h3><span class="rounded-full px-2 py-0.5 text-xs font-medium <?= ! empty($city['is_active']) ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>"><?= ! empty($city['is_active']) ? 'Active' : 'Inactive' ?></span></div><p class="mt-1 text-xs text-slate-500">/<?= esc($city['slug']) ?></p></div>
                                    <details>
                                        <summary class="cursor-pointer list-none rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 [&::-webkit-details-marker]:hidden"><i class="bi bi-pencil mr-1"></i>Edit</summary>
                                        <form id="edit-city-<?= (int) $city['id'] ?>" action="<?= site_url('ci-admin/settings/locations/cities/' . (int) $city['id'] . '/update') ?>" method="post" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-2 lg:grid-cols-5">
                                            <?= csrf_field() ?>
                                            <label class="text-sm font-medium text-slate-700 lg:col-span-2">City name<input name="name" type="text" maxlength="150" value="<?= esc($city['name']) ?>" required class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                                            <label class="text-sm font-medium text-slate-700 lg:col-span-2">Country<select name="country_id" required class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white p-2.5"><?php foreach ($countries as $country) : ?><option value="<?= (int) $country['id'] ?>" <?= (int) $city['country_id'] === (int) $country['id'] ? 'selected' : '' ?>><?= esc($country['name']) ?></option><?php endforeach; ?></select></label>
                                            <label class="text-sm font-medium text-slate-700">Sort order<input name="sort_order" type="number" value="<?= (int) $city['sort_order'] ?>" aria-label="Sort order" class="mt-1.5 h-11 w-full rounded-lg border border-slate-300 px-2.5"></label>
                                        </form>
                                        <div class="mt-3 flex flex-wrap gap-2"><button type="submit" form="edit-city-<?= (int) $city['id'] ?>" class="rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white hover:bg-violet-700">Save city</button>
                                                <form action="<?= site_url('ci-admin/settings/locations/cities/' . (int) $city['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this city? This cannot be undone.');">
                                                    <?= csrf_field() ?><button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50"><i class="bi bi-trash mr-1"></i>Delete city</button>
                                                </form>
                                        </div>
                                    </details>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</main>
<?= view('admin/includes/footer') ?>
