<?= $this->include('includes/head') ?>
<?= $this->include('includes/header') ?>
<main class="min-h-[calc(100vh-8rem)] lg:min-h-[calc(100vh-10rem)] pt-20 lg:pt-24 pb-20 lg:pb-24 px-4 flex items-center justify-center bg-gradient-to-br from-stone-100 via-white to-stone-100">
    <section class="w-full max-w-6xl bg-white rounded-3xl overflow-hidden shadow-2xl grid lg:grid-cols-2">

        <!-- Left -->
        <div class="hidden lg:block relative">
            <img src="<?= site_url('images/background.webp') ?>"
                class="absolute inset-0 w-full h-full object-cover"
                alt="">

            <div class="absolute inset-0 bg-gradient-to-br from-black/90 via-black/70 to-black/40"></div>

            <div class="relative z-10 flex flex-col justify-between h-full p-12 text-white">

                <div>
                    <a href="<?= localized_url('female') ?>" class="text-3xl font-bold tracking-wide">
                        Yooo.App
                    </a>
                </div>

                <div>

                    <span class="inline-flex items-center rounded-full bg-white/10 backdrop-blur px-4 py-2 text-sm mb-6">
                        <?= esc(lang('Site.passwordReset')) ?>
                    </span>

                    <h1 class="text-5xl font-bold leading-tight mb-6">
                        <?= esc(lang('Site.newPasswordHeading')) ?>
                    </h1>

                    <p class="text-lg text-white/80 leading-8">
                        <?= esc(lang('Site.newPasswordIntro')) ?>
                    </p>

                </div>

            </div>
        </div>

        <!-- Right -->
        <div class="flex items-start lg:items-center justify-center p-5 sm:p-8 lg:p-10">

            <div class="w-full max-w-md">

                <div class="text-center lg:text-left mb-4">

                    <h2 class="text-3xl font-bold text-gray-900">
                        <?= esc(lang('Site.resetPassword')) ?>
                    </h2>

                    <p class="mt-2 text-gray-500">
                        <?= esc(lang('Site.resetPasswordDescription')) ?>
                    </p>

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

                <form action="<?= current_url() ?>" method="post" class="space-y-5">

                    <?= csrf_field() ?>

                    <!-- New Password -->
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            <?= esc(lang('Site.newPassword')) ?>
                        </label>

                        <div class="relative">

                            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                            <input
                                type="password"
                                name="password"
                                placeholder="<?= esc(lang('Site.enterNewPassword')) ?>"
                                class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-14 focus:bg-white focus:border-black focus:ring-2 focus:ring-black/10 outline-none transition">

                            <button
                                type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl hover:bg-gray-200 transition">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    <!-- Confirm Password -->
                    <div>

                        <label class="block text-sm font-medium mb-2">
                            <?= esc(lang('Site.confirmPassword')) ?>
                        </label>

                        <div class="relative">

                            <i class="bi bi-shield-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                            <input
                                type="password"
                                name="confirm_password"
                                placeholder="<?= esc(lang('Site.confirmNewPassword')) ?>"
                                class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-14 focus:bg-white focus:border-black focus:ring-2 focus:ring-black/10 outline-none transition">

                            <button
                                type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl hover:bg-gray-200 transition">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="w-full h-14 rounded-2xl bg-violet-600 text-white font-semibold text-lg hover:bg-violet-700 active:scale-[0.99] transition">
                        <?= esc(lang('Site.updatePassword')) ?>
                    </button>

                </form>

                <!-- Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>

                    <div class="relative flex justify-center">
                        <span class="bg-white px-4 text-sm text-gray-400 uppercase tracking-wider">
                            <?= esc(lang('Site.or')) ?>
                        </span>
                    </div>
                </div>

                <a href="<?= localized_url('signin') ?>"
                    class="flex items-center justify-center w-full h-14 rounded-2xl border border-violet-600 text-violet-600 font-semibold text-lg hover:bg-violet-600 hover:text-white transition">
                    <?= esc(lang('Site.backToSignIn')) ?>
                </a>

            </div>

        </div>

    </section>
</main>
<?= $this->include('includes/footer') ?>
