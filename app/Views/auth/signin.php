<?= $this->include('includes/head') ?>
<?= $this->include('includes/header') ?>
<main class="min-h-[calc(100vh-8rem)] lg:min-h-[calc(100vh-10rem)] pt-20 lg:pt-24 pb-20 lg:pb-24 px-4 flex items-center justify-center bg-gradient-to-br from-stone-100 via-white to-stone-100">
    <section class="w-full max-w-6xl bg-white rounded-3xl overflow-hidden shadow-2xl grid lg:grid-cols-2">
        <div class="hidden lg:block relative"> <img src="<?= site_url('images/background.webp') ?>" class="absolute inset-0 w-full h-full object-cover" alt="">
            <div class="absolute inset-0 bg-gradient-to-br from-black/90 via-black/70 to-black/40"></div>
            <div class="relative z-10 flex flex-col justify-between h-full p-12 text-white">
                <div> <a href="#" class="text-3xl font-bold tracking-wide"> Yooo.App </a> </div>
                <div> <span class="inline-flex items-center rounded-full bg-white/10 backdrop-blur px-4 py-2 text-sm mb-6"> <?= esc(lang('Site.membersOnly')) ?> </span>
                    <h1 class="text-5xl font-bold leading-tight mb-6"> <?= esc(lang('Site.welcomeBack')) ?> </h1>
                    <p class="text-lg text-white/80 leading-8"> <?= esc(lang('Site.signinIntro')) ?> </p>
                </div>
            </div>
        </div>
        <div class="flex items-start lg:items-center justify-center p-2">
            <div class="w-full max-w-md">
                <div class="text-center lg:text-left mb-2">
                    <h2 class="text-3xl font-bold text-gray-900"> <?= esc(lang('Site.signIn')) ?> </h2>
                    <p class="mt-2 text-gray-500"> <?= esc(lang('Site.signinDescription')) ?> </p>
                </div>
                <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-5 rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
                    <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-5 rounded-2xl bg-green-50 border border-green-100 px-4 py-3 text-sm text-green-700">
                    <?= session()->getFlashdata('success') ?>
                </div>
                <?php endif; ?>
                <form action="<?= localized_url('signin') ?>" method="post" class="space-y-5">
                    <?= csrf_field() ?>
                        <div> <label class="block text-sm font-medium mb-2"> <?= esc(lang('Site.emailAddress')) ?> </label>
                            <div class="relative"> <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i> <input type="email" name="email" value="<?= old('email') ?>" placeholder="<?= esc(lang('Site.enterEmail')) ?>" class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-4 focus:bg-white focus:border-black focus:ring-2 focus:ring-black/10 outline-none transition">                                </div>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <p class="mt-2 text-sm text-red-600">
                                <?= $validation->getError('email') ?>
                            </p>
                            <?php endif; ?> </div>
                        <div>
                            <div class="flex justify-between items-center mb-2"> <label class="text-sm font-medium"> <?= esc(lang('Site.password')) ?> </label> <a href="<?= localized_url('forgot-password') ?>" class="text-sm text-gray-500 hover:text-black"> <?= esc(lang('Site.forgotPassword')) ?> </a> </div>
<div class="relative">
    <!-- Lock icon -->
    <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

    <!-- Password input -->
    <input
        type="password"
        name="password"
        id="password"
        placeholder="<?= esc(lang('Site.enterPassword')) ?>"
        class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-14 focus:bg-white focus:border-black focus:ring-2 focus:ring-black/10 outline-none transition"
    >

    <!-- Eye button -->
    <button
        type="button"
        id="togglePassword"
        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl hover:bg-gray-200 transition"
    >
        <i class="bi bi-eye" id="eyeIcon"></i>
    </button>
</div>

<script>
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("bi-eye");
            eyeIcon.classList.add("bi-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("bi-eye-slash");
            eyeIcon.classList.add("bi-eye");
        }
    });
</script>

                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <p class="mt-2 text-sm text-red-600">
                                <?= $validation->getError('password') ?>
                            </p>
                            <?php endif; ?> </div>
                        <div class="flex items-center justify-between"> <label class="flex items-center gap-3 text-sm text-gray-600"> <input type="checkbox" name="remember" class="rounded border-gray-300"> <?= esc(lang('Site.rememberMe')) ?> </label> </div>
                        <?php if (!empty($turnstileSiteKey)): ?>
                        <div class="cf-turnstile" data-sitekey="<?= esc($turnstileSiteKey) ?>" data-theme="light"></div>
                        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                        <?php endif; ?>
                        <button type="submit" class="w-full h-14 rounded-2xl bg-violet-600 text-white font-semibold text-lg hover:bg-violet-700 active:scale-[0.99] transition"> <?= esc(lang('Site.signIn')) ?> </button>
                </form>
                <div class="relative my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center"> <span class="bg-white px-4 text-sm text-gray-400 uppercase tracking-wider"> <?= esc(lang('Site.or')) ?> </span> </div>
                </div><a href="<?= localized_url('signup') ?>" class="flex items-center justify-center w-full h-14 rounded-2xl border border-violet-600 text-violet-600 font-semibold text-lg hover:bg-violet-600 hover:text-white transition"> <?= esc(lang('Site.createNewAccount')) ?></a>                </div>
        </div>
    </section>
</main>
<?= $this->include('components/pop-up') ?>
<?= $this->include('includes/footer') ?>
