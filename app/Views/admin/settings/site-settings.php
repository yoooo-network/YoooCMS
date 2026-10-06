<?= view('admin/includes/header') ?>
<main class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-6xl space-y-6">
        <header>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Configuration</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Site Settings</h1>
            <p class="mt-1 text-sm text-slate-500">Manage site features and service credentials. Changes are saved to the project <code class="rounded bg-slate-100 px-1.5 py-0.5">.env</code> file.</p>
        </header>

        <?php if ($message = session()->getFlashdata('success')) : ?><div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"><?= esc($message) ?></div><?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')) : ?><div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"><?= esc($message) ?></div><?php endif; ?>

        <form action="<?= site_url('ci-admin/settings/site') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="categories-heading">
                <div class="mb-5"><h2 id="categories-heading" class="text-lg font-semibold text-slate-900">Profile categories</h2><p class="mt-1 text-sm text-slate-500">Choose the categories available on the site.</p></div>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <?php foreach (['male' => 'Male', 'female' => 'Female', 'gay' => 'Gay', 'trans' => 'Trans'] as $key => $label) : ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-3 text-sm font-medium text-slate-700 hover:border-violet-300">
                            <input type="checkbox" name="categories[]" value="<?= esc($key) ?>" class="size-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500" <?= in_array($key, $settings['categories'], true) ? 'checked' : '' ?>><?= esc($label) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="languages-heading">
                <div class="mb-5"><h2 id="languages-heading" class="text-lg font-semibold text-slate-900">Languages</h2><p class="mt-1 text-sm text-slate-500">English remains enabled as the default site language.</p></div>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <?php $languageNames = ['en' => 'English', 'de' => 'German', 'es' => 'Spanish', 'fr' => 'French', 'pt' => 'Portuguese', 'ja' => 'Japanese', 'hi' => 'Hindi']; ?>
                    <?php foreach ($languageNames as $code => $label) : ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-3 text-sm font-medium text-slate-700 hover:border-violet-300">
                            <input type="checkbox" name="languages[]" value="<?= esc($code) ?>" class="size-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500" <?= in_array($code, $settings['languages'], true) ? 'checked' : '' ?> <?= $code === 'en' ? 'checked disabled' : '' ?>><?= esc($label) ?> <span class="text-xs text-slate-400">(<?= esc(strtoupper($code)) ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="languages[]" value="en">
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="email-heading">
                <div class="mb-5"><h2 id="email-heading" class="text-lg font-semibold text-slate-900">Email configuration</h2><p class="mt-1 text-sm text-slate-500">Configure the sender and SMTP connection. Leave the password blank to keep the saved password.</p></div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <label class="text-sm font-medium text-slate-700">Delivery method<select name="email_protocol" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white p-2.5"><option value="smtp" <?= $settings['email_protocol'] === 'smtp' ? 'selected' : '' ?>>SMTP</option><option value="mail" <?= $settings['email_protocol'] === 'mail' ? 'selected' : '' ?>>PHP mail</option><option value="sendmail" <?= $settings['email_protocol'] === 'sendmail' ? 'selected' : '' ?>>Sendmail</option></select></label>
                    <label class="text-sm font-medium text-slate-700">Sender name<input name="email_from_name" value="<?= esc($settings['email_from_name']) ?>" type="text" autocomplete="organization" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">Sender email<input name="email_from" value="<?= esc($settings['email_from']) ?>" type="email" autocomplete="email" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">SMTP host<input name="email_host" value="<?= esc($settings['email_host']) ?>" type="text" autocomplete="url" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">SMTP username<input name="email_user" value="<?= esc($settings['email_user']) ?>" type="text" autocomplete="username" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">SMTP password<input name="email_password" value="" type="password" autocomplete="new-password" placeholder="Saved password is unchanged" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">SMTP port<input name="email_port" value="<?= esc($settings['email_port']) ?>" type="number" min="1" max="65535" required class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">Encryption<select name="email_crypto" class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white p-2.5"><option value="ssl" <?= $settings['email_crypto'] === 'ssl' ? 'selected' : '' ?>>SSL</option><option value="tls" <?= $settings['email_crypto'] === 'tls' ? 'selected' : '' ?>>TLS</option><option value="" <?= $settings['email_crypto'] === '' ? 'selected' : '' ?>>None</option></select></label>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="cdn-heading">
                <div class="mb-5"><h2 id="cdn-heading" class="text-lg font-semibold text-slate-900">CDN configuration</h2><p class="mt-1 text-sm text-slate-500">Set the base URL for your content delivery network.</p></div>
                <label class="block max-w-2xl text-sm font-medium text-slate-700">CDN base URL<input name="cdn_url" value="<?= esc($settings['cdn_url']) ?>" type="url" placeholder="https://cdn.example.com" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"><span class="mt-1 block text-xs font-normal text-slate-500">Leave blank to serve assets from the site host. This value is saved as <code>app.cdnURL</code>.</span></label>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="policy-heading">
                <div class="mb-5"><h2 id="policy-heading" class="text-lg font-semibold text-slate-900">Notices and policies</h2><p class="mt-1 text-sm text-slate-500">Control which notices and policy prompts are shown to visitors.</p></div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <?php foreach (['age_notice' => '18+ age notice', 'privacy_cookies_notice' => 'Privacy and cookies notice'] as $key => $label) : ?>
                        <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-4 text-sm font-medium text-slate-700"><span><?= esc($label) ?></span><span class="relative inline-flex"><input class="peer sr-only" type="checkbox" name="<?= esc($key) ?>" value="1" role="switch" aria-label="<?= esc($label) ?>" <?= $settings[$key] ? 'checked' : '' ?>><span class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-violet-600 peer-focus-visible:ring-2 peer-focus-visible:ring-violet-500 peer-focus-visible:ring-offset-2"></span><span class="pointer-events-none absolute left-0.5 top-0.5 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span></span></label>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="api-heading">
                <div class="flex items-center justify-between gap-4"><div><h2 id="api-heading" class="text-lg font-semibold text-slate-900">API</h2><p class="mt-1 text-sm text-slate-500">Enable or disable the public API.</p></div><label class="relative inline-flex shrink-0"><input class="peer sr-only" type="checkbox" name="api_enabled" value="1" role="switch" aria-label="Activate API" <?= $settings['api_enabled'] ? 'checked' : '' ?>><span class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-violet-600 peer-focus-visible:ring-2 peer-focus-visible:ring-violet-500 peer-focus-visible:ring-offset-2"></span><span class="pointer-events-none absolute left-0.5 top-0.5 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span></label></div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="turnstile-heading">
                <div class="mb-5"><h2 id="turnstile-heading" class="text-lg font-semibold text-slate-900">Cloudflare Turnstile</h2><p class="mt-1 text-sm text-slate-500">Set the site and secret keys used to verify CAPTCHA responses. Leave the secret blank to keep the saved key.</p></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="text-sm font-medium text-slate-700">Site key<input name="turnstile_site_key" value="<?= esc($settings['turnstile_site_key']) ?>" type="text" autocomplete="off" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                    <label class="text-sm font-medium text-slate-700">Secret key<input name="turnstile_secret_key" value="" type="password" autocomplete="new-password" placeholder="Saved secret is unchanged" class="mt-1.5 w-full rounded-lg border border-slate-300 p-2.5"></label>
                </div>
            </section>

            <div class="flex justify-end"><button type="submit" class="rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"><i class="bi bi-check2 mr-1"></i>Save site settings</button></div>
        </form>
    </div>
</main>
<?= view('admin/includes/footer') ?>
