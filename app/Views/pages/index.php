<?= view('includes/head') ?>
<?= view('includes/header') ?>

<main class="w-full max-w-5xl mx-auto px-4 pt-28 pb-28 sm:pt-32">
    <section class="text-center mb-10">
        <span class="inline-flex items-center gap-2 rounded-full bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700">
            <i class="bi bi-question-circle-fill"></i>
            Yooo.App Help Centre
        </span>
        <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">How can we help?</h1>
        <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
            Browse platform information, account and membership guidance, safety resources, and our policies.
        </p>
    </section>

    <?php
    $helpSections = [
        [
            'title' => 'Platform information',
            'icon' => 'bi-info-circle-fill',
            'pages' => [
                ['en/about', 'About Yooo.App', 'Learn about the platform and its story.', 'bi-building'],
                ['en/advertising', 'Advertising', 'Learn about advertising opportunities with Yooo.App.', 'bi-megaphone'],
                ['en/faq', 'Frequently Asked Questions', 'Answers about memberships, verification, payments, and more.', 'bi-patch-question'],
                ['en/packages', 'Membership Packages', 'Review membership plans and pricing information.', 'bi-stars'],
                ['en/contact', 'Contact Us', 'Get in touch with the Yooo.App team.', 'bi-envelope'],
            ],
        ],
        [
            'title' => 'Safety & reporting',
            'icon' => 'bi-shield-check',
            'pages' => [
                ['en/safety-guidelines', 'Safety Guidelines', 'Read our safety guidance and reporting information.', 'bi-shield-check'],
                ['en/report-misuse', 'Report Misuse', 'Report suspected abuse, fraud, or unsafe activity.', 'bi-flag'],
            ],
        ],
        [
            'title' => 'Legal',
            'icon' => 'bi-file-earmark-text-fill',
            'pages' => [
                ['en/privacy-policy', 'Privacy Policy', 'Understand how we handle personal information.', 'bi-lock'],
                ['en/terms-and-conditions', 'Terms and Conditions', 'Review the terms that apply to use of the platform.', 'bi-file-earmark-text'],
                ['en/refund-policy', 'Refund Policy', 'Read our policy regarding purchases and refunds.', 'bi-arrow-counterclockwise'],
            ],
        ],
    ];
    ?>

    <div class="space-y-10">
        <?php foreach ($helpSections as $section): ?>
            <section aria-labelledby="<?= esc(url_title($section['title'], '-', true)) ?>">
                <h2 id="<?= esc(url_title($section['title'], '-', true)) ?>" class="mb-4 flex items-center gap-2 text-xl font-bold text-slate-900">
                    <i class="bi <?= esc($section['icon']) ?> text-violet-600"></i>
                    <?= esc($section['title']) ?>
                </h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <?php foreach ($section['pages'] as [$slug, $title, $description, $icon]): ?>
                        <a href="<?= site_url($slug) ?>" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-xl text-violet-600 group-hover:bg-violet-600 group-hover:text-white">
                                    <i class="bi <?= esc($icon) ?>"></i>
                                </span>
                                <span>
                                    <span class="flex items-center gap-2 font-bold text-slate-900">
                                        <?= esc($title) ?> <i class="bi bi-arrow-right text-sm text-violet-600 transition group-hover:translate-x-1"></i>
                                    </span>
                                    <span class="mt-1 block text-sm leading-6 text-slate-600"><?= esc($description) ?></span>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</main>

<?= view('includes/footer') ?>
