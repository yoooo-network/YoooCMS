<?= $this->include('includes/head') ?>
<body class="min-h-screen bg-cover bg-center bg-fixed"
      style="background-image: url('/images/background.webp');">

    <div class="min-h-screen bg-black/70 flex flex-col justify-between text-center px-4">

        <!-- Main Content -->
        <main class="my-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                <?= esc($siteName) ?>
            </h1>

            <p class="text-white/90 max-w-2xl mx-auto mb-8">
                Search Engine for Male, Female, Trans & Gay Escorts.
            </p>

            <nav class="flex flex-wrap justify-center gap-4" aria-label="Escort categories">
                <?php foreach ($categories as $categorySlug => $label): ?>
                    <?php $categoryPath = $language !== '' ? ($language . '/' . $categorySlug) : $categorySlug; ?>
                    <a href="<?= esc(site_url($categoryPath)) ?>"
                       class="min-w-[200px] px-6 py-3 border-2 border-rose-500 text-white rounded-lg
                              transition duration-200
                              hover:bg-rose-600 hover:border-rose-600">
                        <?= esc($label) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </main>

        <!-- Footer -->
        <footer class="pb-6 text-sm text-white/80">
            <a href="<?= esc($privacyUrl) ?>" class="hover:underline">Privacy Policy</a>
            <span class="mx-2">|</span>
            <a href="<?= esc($termsUrl) ?>" class="hover:underline">Terms and Conditions</a>
        </footer>

    </div>

</body>
</html>
