<?= view('includes/head') ?>
<?= view('includes/header') ?>

<main class="static-page container mx-auto px-4 py-10 mt-20 max-w-7xl">

    <!-- Section 1: Pricing Introduction -->
    <section class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 bg-gradient-to-r from-pink-500 to-indigo-600 bg-clip-text text-transparent">
            Membership Plans
        </h1>

        <p class="text-gray-600 max-w-2xl mx-auto leading-7">
            Start with a 7-day free trial and explore Yooo.App before deciding whether
            a paid membership is right for you. There is no automatic charge when
            your free trial ends.
        </p>
    </section>

    <!-- Section 2: Membership Plans -->
    <section class="mb-12">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

            <!-- Free Trial -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-7 md:p-8 flex flex-col">

                <div class="mb-6">
                    <span class="inline-block text-xs font-semibold uppercase tracking-wider
                                 bg-gray-100 text-gray-700 px-3 py-1 rounded-full mb-3">
                        Start here
                    </span>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Free Trial
                    </h2>

                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-extrabold text-gray-900">
                            0
                        </span>
                        <span class="text-gray-500 mb-2">
                            / 7 days
                        </span>
                    </div>
                </div>

                <ul class="space-y-3 text-gray-600 text-sm leading-6 mb-7">
                    <li>✓ Create your profile</li>
                    <li>✓ Profile visibility</li>
                    <li>✓ Receive eligible enquiries</li>
                    <li>✓ Communicate with interested users</li>
                    <li>✓ Access safety and reporting features</li>
                    <li>✓ No credit/debit card required to signup</li>
                </ul>

                <div class="mt-auto">
                    <a href="#"
                       class="block text-center bg-gray-900 text-white px-6 py-3 rounded-xl
                              font-semibold hover:bg-gray-800 transition">
                        Start 7-Day Free Trial
                    </a>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        No automatic charge when the trial ends.
                    </p>
                </div>
            </div>


            <!-- Basic -->
            <div class="bg-white border-2 border-indigo-200 shadow-lg rounded-2xl p-7 md:p-8
                        flex flex-col relative">

                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full">
                        Most Popular
                    </span>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Premium
                    </h2>

                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-extrabold text-gray-900">
                            $50
                        </span>
                        <span class="text-gray-500 mb-2">
                            / month
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        for India: ₹3,000/month
                    </p>
                </div>

                <ul class="space-y-3 text-gray-600 text-sm leading-6 mb-7">
                    <li>✓ Verified profile listing</li>
                    <li>✓ Premium profile placement</li>
                    <li>✓ Direct enquiries</li>
                    <li>✓ Profile privacy controls</li>
                    <li>✓ Priority support</li>
                    <li>✓ Dedicated account manager</li>
                    <li>✓ Enhanced privacy options</li>
                    <li>✓ Priority support</li>
                    <li>✓ Additional account assistance</li>
                </ul>

                <div class="mt-auto">
                    <a href="#"
                       class="block text-center bg-indigo-600 text-white px-6 py-3 rounded-xl
                              font-semibold hover:bg-indigo-700 transition">
                        Upgrade to Premium
                    </a>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        Cancel according to our membership terms.
                    </p>
                </div>
            </div>
        </div>
    </section>

<!-- Section 4: How Membership Works -->
<section class="max-w-6xl mx-auto mt-14 mb-8">
    <div class="text-center mb-8">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-sm font-semibold">
            Simple & Transparent
        </span>

        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-4">
            How Membership Works
        </h2>

        <p class="text-gray-500 max-w-2xl mx-auto mt-3 leading-7">
            No complicated commitments. Start for free, explore the platform,
            and upgrade only if you believe Yooo.App is useful for you.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-5">

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm
                    hover:shadow-lg transition">
            <div class="w-11 h-11 rounded-xl bg-gray-900 text-white flex items-center
                        justify-center font-bold mb-5">
                01
            </div>

            <h3 class="font-bold text-gray-900 text-lg mb-2">
                Start Free
            </h3>

            <p class="text-sm text-gray-500 leading-6">
                Create your profile and explore Yooo.App with your 7-day free trial.
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm
                    hover:shadow-lg transition">
            <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center
                        justify-center font-bold mb-5">
                02
            </div>

            <h3 class="font-bold text-gray-900 text-lg mb-2">
                Explore
            </h3>

            <p class="text-sm text-gray-500 leading-6">
                Use the available features and see whether the platform meets
                your expectations.
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm
                    hover:shadow-lg transition">
            <div class="w-11 h-11 rounded-xl bg-orange-500 text-white flex items-center
                        justify-center font-bold mb-5">
                03
            </div>

            <h3 class="font-bold text-gray-900 text-lg mb-2">
                Upgrade
            </h3>

            <p class="text-sm text-gray-500 leading-6">
                Upgrade when you're ready and manage your membership according
                to the applicable terms.
            </p>
        </div>

    </div>
</section>

<!-- Section 7: No Hidden Charges -->
<section class="max-w-6xl mx-auto mt-12 mb-10">
    <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">

        <div class="absolute -top-20 -right-20 w-56 h-56 rounded-full bg-pink-50"></div>
        <div class="absolute -bottom-24 -left-20 w-64 h-64 rounded-full bg-indigo-50"></div>

        <div class="relative p-7 md:p-10">
            <div class="mt-8 bg-gray-900 rounded-2xl p-6 md:p-7 text-white">
                <div class="flex flex-col md:flex-row md:items-center gap-5">

                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-white/10
                                border border-white/10 flex items-center justify-center">
                        <span class="text-xl font-bold">!</span>
                    </div>

                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-1">
                            Someone is asking you for an unexpected payment?
                        </h3>

                        <p class="text-gray-300 text-sm leading-6">
                            Stop before sending money. Do not share your OTP,
                            password, or financial credentials. Verify the request
                            through official Yooo.App channels and report suspicious
                            activity to us.
                        </p>
                    </div>

                    <a href="https://www.yooo.app/contact"
                       class="flex-shrink-0 inline-flex items-center justify-center
                              px-5 py-3 rounded-xl bg-white text-gray-900
                              font-semibold text-sm hover:bg-gray-100 transition">
                        Report a Concern
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

</main>
<?= view('includes/footer') ?>
