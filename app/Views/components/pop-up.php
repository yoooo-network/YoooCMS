<div id="app-install-popup" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[1px]">
    <div class="relative w-full max-w-md rounded-3xl border border-indigo-100 bg-white p-6 shadow-2xl">
        <button type="button" id="app-install-close" class="absolute right-4 top-4 inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 hover:text-slate-700" aria-label="<?= esc(lang('Site.closeAppInstallPopup')) ?>">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
        <div class="text-center">
            <div class="mx-auto mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                <i class="bi bi-phone text-xl"></i>
            </div>
            <p class="text-lg font-semibold text-slate-900"><?= esc(lang('Site.installMobileApp')) ?></p>
            <p class="mt-2 text-sm text-slate-600"><?= esc(lang('Site.installMobileAppDescription')) ?></p>
            <a
                href="https://www.yooo.app/yooo-app.apk"
                class="mt-5 inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-500 to-sky-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:shadow-md"
            >
                <?= esc(lang('Site.downloadApp')) ?>
            </a>
        </div>
    </div>
</div>
<script>
    (function () {
        var popup = document.getElementById('app-install-popup');
        var closeBtn = document.getElementById('app-install-close');
        var storageKey = 'yooo_app_install_popup_closed';

        if (!popup || !closeBtn) {
            return;
        }

        if (window.localStorage.getItem(storageKey) === '1') {
            return;
        }

        popup.classList.remove('hidden');
        popup.classList.add('flex');

        closeBtn.addEventListener('click', function () {
            popup.classList.remove('flex');
            popup.classList.add('hidden');
            window.localStorage.setItem(storageKey, '1');
        });
    })();
</script>
