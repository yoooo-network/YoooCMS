<!DOCTYPE html>
<?php
helper('url');

$requestedLanguage = strtolower((string) service('request')->getUri()->getSegment(1));
$panelLanguage = in_array($requestedLanguage, supported_languages(), true) ? $requestedLanguage : 'en';
$panelProfile = is_array($profile ?? null) ? $profile : [];
$profileId = filter_var($panelProfile['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$profileName = trim((string) ($panelProfile['name'] ?? ''));
$profileSlug = $profileId
    ? url_title($profileName !== '' ? $profileName : 'profile-' . $profileId, '-', true)
    : '';
?>
<html lang="<?= esc($panelLanguage) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($metaTitle ?? $title ?? 'Yooo.App Dashboard') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">
</head>

<body class="bg-slate-50 font-[Inter]">

<div class="min-h-screen flex flex-col">

    <!-- Header -->

    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">

        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">

            <a href="<?= localized_url('user-panel/dashboard', $panelLanguage) ?>" class="font-extrabold text-lg lg:text-2xl text-violet-600" aria-label="Yooo.App dashboard">
                Yooo.App
            </a>

            <div class="flex items-center gap-2">
                <?php if ($profileId): ?>
                    <a href="<?= localized_url('profile/' . $profileId . '/' . $profileSlug, $panelLanguage) ?>"
                       class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600" target="_blank" title="View Public Profile">

                        <i class="bi bi-person-fill"></i>

                    </a>
                <?php else: ?>
                    <span class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 cursor-not-allowed" title="Create a profile to preview it" aria-label="Profile not created">

                        <i class="bi bi-person-fill"></i>

                    </span>
                <?php endif; ?>
                <a href="<?= localized_url('logout', $panelLanguage) ?>"
                   class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500" title="Log out" aria-label="Log out">

                    <i class="bi bi-box-arrow-right"></i>

                </a>

            </div>

        </div>

    </header>
