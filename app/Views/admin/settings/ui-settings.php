<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-6xl space-y-6">
        <header>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Configuration</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">UI Settings</h1>
            <p class="mt-1 text-sm text-slate-500">Choose the visual theme used by the site. More themes can be added here later.</p>
        </header>

        <?php if ($message = session()->getFlashdata('success')) : ?><div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= esc($message) ?></div><?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')) : ?><div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= esc($message) ?></div><?php endif; ?>

        <form action="<?= site_url('ci-admin/settings/ui') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            <section aria-labelledby="theme-heading">
                <div class="mb-4"><h2 id="theme-heading" class="text-lg font-semibold text-slate-900">Select theme</h2><p class="mt-1 text-sm text-slate-500">The selected theme is saved to the project <code class="rounded bg-slate-100 px-1.5 py-0.5">.env</code> file.</p></div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($themes as $themeKey => $theme) : $isSelected = $selectedTheme === $themeKey; ?>
                        <label class="group relative block cursor-pointer">
                            <input class="peer sr-only" type="radio" name="theme" value="<?= esc($themeKey) ?>" <?= $isSelected ? 'checked' : '' ?>>
                            <div class="overflow-hidden rounded-2xl border-2 border-slate-200 bg-white shadow-sm transition group-hover:border-violet-300 peer-checked:border-violet-600 peer-checked:ring-2 peer-checked:ring-violet-100 peer-focus-visible:ring-2 peer-focus-visible:ring-violet-500">
                                <div class="aspect-[16/9] bg-slate-100 p-4 sm:p-5">
                                    <div class="h-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                                        <div class="flex h-8 items-center gap-1.5 border-b border-slate-100 px-3"><span class="size-2 rounded-full bg-rose-300"></span><span class="size-2 rounded-full bg-amber-300"></span><span class="size-2 rounded-full bg-emerald-300"></span><span class="ml-2 h-2 w-1/3 rounded-full bg-slate-100"></span></div>
                                        <div class="flex h-[calc(100%-2rem)] flex-col p-3">
                                            <div class="flex items-center justify-between"><span class="h-3 w-20 rounded-full bg-violet-200"></span><span class="flex gap-1"><i class="size-2 rounded-full bg-slate-200"></i><i class="size-2 rounded-full bg-slate-200"></i><i class="size-2 rounded-full bg-slate-200"></i></span></div>
                                            <div class="mt-3 grid flex-1 grid-cols-3 gap-2"><div class="rounded-lg bg-violet-50"></div><div class="rounded-lg bg-slate-100"></div><div class="rounded-lg bg-slate-100"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start justify-between gap-3 p-4">
                                    <div><h3 class="font-semibold text-slate-900"><?= esc($theme['name']) ?></h3><p class="mt-1 text-sm text-slate-500"><?= esc($theme['description']) ?></p></div>
                                    <span class="grid size-5 shrink-0 place-items-center rounded-full border border-slate-300 text-transparent peer-checked:border-violet-600 peer-checked:bg-violet-600 peer-checked:text-white"><i class="bi bi-check text-sm"></i></span>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </section>

            <div class="flex justify-end"><button type="submit" class="rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"><i class="bi bi-check2 mr-1"></i>Save theme</button></div>
        </form>
    </div>
</main>
<?= view('admin/includes/footer') ?>
