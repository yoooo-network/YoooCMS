<?php
$adminPath = trim((string) uri_string(), '/');
$isActive = static fn(string $path): bool => $adminPath === $path || str_starts_with($adminPath, $path . '/');
$linkClass = static fn(bool $active): string => 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition ' . ($active ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900');
$settingsOpen = str_starts_with($adminPath, 'ci-admin/settings') || $adminPath === 'ci-admin/seo';
$profilesOpen = str_starts_with($adminPath, 'ci-admin/profiles');
?>
<div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/40 lg:hidden" onclick="toggleSidebar()"></div>
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white shadow-xl transition-transform duration-200 ease-out lg:static lg:z-auto lg:translate-x-0 lg:shadow-none">
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-100 px-5">
        <a href="<?= site_url('ci-admin/dashboard') ?>" class="flex items-center gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-violet-600 text-white"><i class="bi bi-grid-1x2-fill"></i></span>
            <span class="font-bold text-slate-900">Admin Panel</span>
        </a>
        <div class="flex items-center gap-1">
            <form method="post" action="<?= site_url('ci-admin/cache/clear') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-violet-700" title="Clear cache" aria-label="Clear cache"><i class="bi bi-arrow-clockwise"></i></button>
            </form>
            <button type="button" onclick="toggleSidebar()" class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="Admin navigation">
        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Workspace</p>
        <ul class="space-y-1">
            <li><a href="<?= site_url('ci-admin/dashboard') ?>" class="<?= $linkClass($adminPath === 'ci-admin/dashboard') ?>"><i class="bi bi-speedometer2 text-lg"></i><span>Dashboard</span></a></li>
            <li><a href="<?= site_url('ci-admin/users') ?>" class="<?= $linkClass($isActive('ci-admin/users')) ?>"><i class="bi bi-people text-lg"></i><span>Manage Users</span></a></li>
            <li>
                <details class="group" <?= $profilesOpen ? 'open' : '' ?>>
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 [&::-webkit-details-marker]:hidden">
                        <i class="bi bi-person-vcard text-lg"></i><span class="flex-1">Manage Profiles</span><i class="bi bi-chevron-down text-xs transition group-open:rotate-180"></i>
                    </summary>
                    <ul class="ml-5 mt-1 space-y-1 border-l border-slate-200 pl-3">
                        <li><a href="<?= site_url('ci-admin/profiles') ?>" class="<?= $linkClass($adminPath === 'ci-admin/profiles') ?>"><i class="bi bi-card-list"></i><span>Manage Profiles</span></a></li>
                        <li><a href="<?= site_url('ci-admin/profiles/verifications') ?>" class="<?= $linkClass($adminPath === 'ci-admin/profiles/verifications' || $isActive('ci-admin/profiles/check-verification')) ?>"><i class="bi bi-patch-check"></i><span>Check Verifications</span></a></li>
                    </ul>
                </details>
            </li>
            <li><a href="<?= site_url('ci-admin/media') ?>" class="<?= $linkClass($isActive('ci-admin/media')) ?>"><i class="bi bi-images text-lg"></i><span>Media</span></a></li>
            <li><a href="<?= site_url('ci-admin/bookings') ?>" class="<?= $linkClass($isActive('ci-admin/bookings')) ?>"><i class="bi bi-calendar2-check text-lg"></i><span>Manage Bookings</span></a></li>
        </ul>

        <p class="mt-7 px-3 pb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Configuration</p>
        <details class="group" <?= $settingsOpen ? 'open' : '' ?>>
            <summary class="flex cursor-pointer list-none items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 [&::-webkit-details-marker]:hidden">
                <i class="bi bi-gear text-lg"></i><span class="flex-1">Settings</span><i class="bi bi-chevron-down text-xs transition group-open:rotate-180"></i>
            </summary>
            <ul class="ml-5 mt-1 space-y-1 border-l border-slate-200 pl-3">
                <li><a href="<?= site_url('ci-admin/settings/contact') ?>" class="<?= $linkClass($isActive('ci-admin/settings/contact')) ?>"><i class="bi bi-telephone"></i><span>Contacts</span></a></li>
                <li><a href="<?= site_url('ci-admin/settings/payment') ?>" class="<?= $linkClass($isActive('ci-admin/settings/payment')) ?>"><i class="bi bi-credit-card"></i><span>Payments</span></a></li>
                <li><a href="<?= site_url('ci-admin/seo') ?>" class="<?= $linkClass($isActive('ci-admin/seo')) ?>"><i class="bi bi-search"></i><span>SEO</span></a></li>
                <li><a href="<?= site_url('ci-admin/settings/site') ?>" class="<?= $linkClass($isActive('ci-admin/settings/site')) ?>"><i class="bi bi-globe2"></i><span>Site Settings</span></a></li>
                <li><a href="<?= site_url('ci-admin/settings/ui') ?>" class="<?= $linkClass($isActive('ci-admin/settings/ui')) ?>"><i class="bi bi-palette"></i><span>UI Settings</span></a></li>
                <li><a href="<?= site_url('ci-admin/settings/countries') ?>" class="<?= $linkClass($isActive('ci-admin/settings/countries') || $isActive('ci-admin/settings/locations')) ?>"><i class="bi bi-globe-americas"></i><span>Countries</span></a></li>
                <li><a href="<?= site_url('ci-admin/settings/cities') ?>" class="<?= $linkClass($isActive('ci-admin/settings/cities')) ?>"><i class="bi bi-geo-alt"></i><span>Cities</span></a></li>
            </ul>
        </details>

        <p class="mt-7 px-3 pb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Activity</p>
        <a href="<?= site_url('ci-admin/sent-emails') ?>" class="<?= $linkClass($isActive('ci-admin/sent-emails')) ?>"><i class="bi bi-envelope-paper text-lg"></i><span>Email Logs</span></a>
    </nav>

    <div class="border-t border-slate-100 p-4">
        <a href="<?= site_url('ci-admin/logout') ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-rose-50 hover:text-rose-700"><i class="bi bi-box-arrow-left text-lg"></i><span>Sign out</span></a>
    </div>
</aside>
