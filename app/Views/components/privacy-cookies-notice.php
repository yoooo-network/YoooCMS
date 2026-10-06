<?php if (filter_var(env('SITE_PRIVACY_COOKIES_NOTICE_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) : ?>
<aside id="privacy-cookies-notice" class="fixed bottom-20 left-3 right-3 z-[90] mx-auto hidden max-w-3xl rounded-2xl border border-slate-200 bg-white p-4 shadow-xl sm:bottom-24 sm:p-5" role="dialog" aria-labelledby="privacy-cookies-title">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 id="privacy-cookies-title" class="font-semibold text-slate-900">Privacy and cookies</h2>
            <p class="mt-1 text-sm leading-5 text-slate-600">We use cookies to support site features and improve your experience. Read our <a class="font-medium text-violet-700 underline" href="<?= esc(site_url(($navigationLanguage ?? 'en') . '/privacy-policy')) ?>">privacy and cookies policy</a>.</p>
        </div>
        <button id="privacy-cookies-acknowledge" type="button" class="shrink-0 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-violet-700">Got it</button>
    </div>
</aside>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const notice = document.getElementById('privacy-cookies-notice');
    const storageKey = 'yooo_privacy_cookies_notice_seen';
    let dismissed = false;
    try { dismissed = localStorage.getItem(storageKey) === 'yes'; } catch (error) {}
    if (!dismissed && notice) notice.classList.remove('hidden');
    const button = document.getElementById('privacy-cookies-acknowledge');
    if (button) button.addEventListener('click', function () {
        try { localStorage.setItem(storageKey, 'yes'); } catch (error) {}
        notice.classList.add('hidden');
    });
});
</script>
<?php endif; ?>
