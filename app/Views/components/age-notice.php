<?php if (filter_var(env('SITE_AGE_NOTICE_ENABLED', false), FILTER_VALIDATE_BOOLEAN)) : ?>
<div id="age-notice" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/80 p-4" role="dialog" aria-modal="true" aria-labelledby="age-notice-title">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
        <div class="mb-4 grid size-12 place-items-center rounded-full bg-violet-100 text-xl font-bold text-violet-700">18+</div>
        <h2 id="age-notice-title" class="text-xl font-bold text-slate-900">Adults only</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">This website is intended for adults aged 18 and over. Please confirm your age to continue.</p>
        <button id="age-notice-confirm" type="button" class="mt-6 w-full rounded-xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-700">I am 18 or older</button>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const notice = document.getElementById('age-notice');
    const storageKey = 'yooo_age_confirmed';
    let confirmed = false;
    try { confirmed = localStorage.getItem(storageKey) === 'yes'; } catch (error) {}
    if (!confirmed && notice) {
        notice.classList.remove('hidden');
        notice.classList.add('flex');
    }
    const confirmButton = document.getElementById('age-notice-confirm');
    if (confirmButton) confirmButton.addEventListener('click', function () {
        try { localStorage.setItem(storageKey, 'yes'); } catch (error) {}
        notice.classList.add('hidden');
        notice.classList.remove('flex');
    });
});
</script>
<?php endif; ?>
