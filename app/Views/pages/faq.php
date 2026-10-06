<?= view('includes/head') ?>
<?= view('includes/header') ?>

<main class="static-page mx-auto w-full max-w-5xl mb-8 px-4 py-10">

    <!-- Hero -->
    <section class="text-center mb-12">

        <span class="inline-flex items-center px-4 py-1.5 rounded-full
                     bg-indigo-50 text-indigo-700 text-sm font-semibold mb-5">
            Transparency & Help Center
        </span>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-5">
            Frequently Asked Questions
        </h1>

        <p class="text-lg text-gray-600 leading-8 max-w-3xl mx-auto">
            Have questions about Yooo.App, memberships, verification,
            payments, safety, or our technology? We've put together
            straightforward answers to help you understand how the
            platform works before you use it.
        </p>

        <div class="flex flex-wrap justify-center gap-3 mt-7">

            <a href="#general"
               class="px-4 py-2 rounded-full bg-gray-100 text-gray-700
                      text-sm font-medium hover:bg-gray-200 transition">
                General
            </a>

            <a href="#membership"
               class="px-4 py-2 rounded-full bg-gray-100 text-gray-700
                      text-sm font-medium hover:bg-gray-200 transition">
                Membership
            </a>

            <a href="#safety"
               class="px-4 py-2 rounded-full bg-gray-100 text-gray-700
                      text-sm font-medium hover:bg-gray-200 transition">
                Safety
            </a>

            <a href="#payments"
               class="px-4 py-2 rounded-full bg-gray-100 text-gray-700
                      text-sm font-medium hover:bg-gray-200 transition">
                Payments
            </a>

        </div>

    </section>


    <!-- Trust Notice -->
    <section class="mb-12">

        <div class="relative overflow-hidden rounded-3xl
                    bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500
                    text-white p-7 md:p-9 shadow-lg">

            <div class="absolute -top-24 -right-24 w-64 h-64
                        rounded-full bg-white/10"></div>

            <div class="absolute -bottom-32 -left-20 w-72 h-72
                        rounded-full bg-white/10"></div>

            <div class="relative flex flex-col md:flex-row gap-6 items-start">

                <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-white/15
                            border border-white/20 flex items-center
                            justify-center text-2xl font-bold">
                    ?
                </div>

                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">
                        We believe you should know what you're signing up for.
                    </h2>

                    <p class="text-white/85 leading-7 max-w-3xl">
                        Yooo.App is designed to provide clear information about
                        our platform, memberships, verification processes,
                        safety practices, and limitations. If something is
                        unclear, we encourage you to ask before making a
                        payment or sharing personal information.
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3 text-sm">

                        <span class="px-3 py-1.5 rounded-lg bg-white/10
                                     border border-white/15">
                            Clear pricing
                        </span>

                        <span class="px-3 py-1.5 rounded-lg bg-white/10
                                     border border-white/15">
                            No guaranteed results
                        </span>

                        <span class="px-3 py-1.5 rounded-lg bg-white/10
                                     border border-white/15">
                            Safety & reporting
                        </span>

                        <span class="px-3 py-1.5 rounded-lg bg-white/10
                                     border border-white/15">
                            User choice
                        </span>

                    </div>
                </div>

            </div>
        </div>

    </section>


    <!-- General Questions -->
    <section id="general" class="mb-12">

        <div class="mb-6">
            <span class="text-sm font-bold uppercase tracking-wider
                         text-indigo-600">
                About the platform
            </span>

            <h2 class="text-3xl font-extrabold text-gray-900 mt-2">
                General Questions
            </h2>

            <p class="text-gray-500 mt-2">
                Start here if you're new to Yooo.App.
            </p>
        </div>


        <div class="space-y-4">

            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm overflow-hidden">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    What is Yooo.App?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180
                                 transition-transform">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        Yooo.App is an online platform that allows eligible
                        adult users to create profiles and connect with other
                        users. The platform is part of the wider Yoooo ecosystem,
                        which includes API infrastructure and other applications.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm overflow-hidden">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Who created Yoooo?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180
                                 transition-transform">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        Yoooo originally started as an engineering project
                        created by a small group of engineering students.
                        The initial idea was to reduce unnecessary intermediaries
                        and build technology that allowed people to create and
                        manage profiles more directly.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm overflow-hidden">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Is Yooo.App the same thing as Yoooo.in?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180
                                 transition-transform">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        Yooo.App and Yoooo.in are separate applications within
                        the wider Yoooo ecosystem. They are built around the
                        same underlying Yoooo technology and infrastructure.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm overflow-hidden">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Why does Yoooo have an API?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180
                                 transition-transform">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        The Yoooo API provides a common technical foundation
                        for profile-based applications. It allows developers
                        and website owners to build applications using
                        Yoooo infrastructure instead of creating every
                        component from scratch.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm overflow-hidden">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Is Yooo.App a legitimate website?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180
                                 transition-transform">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">

                    <p class="text-gray-600 leading-7 mb-4">
                        Yooo.App is a real platform operated as part of the
                        Yoooo ecosystem. We encourage users not to rely only
                        on our statements about the platform. Users can review
                        our public information, policies, technology resources,
                        safety guidelines, and contact information to understand
                        how the platform works.
                    </p>

                    <p class="text-gray-600 leading-7">
                        We also encourage users to independently verify any
                        information that is important to them before making
                        decisions or payments.
                    </p>

                </div>

            </details>

        </div>

    </section>


    <!-- Membership Questions -->
    <section id="membership" class="mb-12">

        <div class="mb-6">
            <span class="text-sm font-bold uppercase tracking-wider
                         text-purple-600">
                Pricing & Membership
            </span>

            <h2 class="text-3xl font-extrabold text-gray-900 mt-2">
                Membership Questions
            </h2>

            <p class="text-gray-500 mt-2">
                Understand exactly what you're paying for.
            </p>
        </div>


        <div class="space-y-4">

            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Is the 7-day free trial really free?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180 transition">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        Yes. The trial is offered at ₹0 for 7 days. Users can
                        explore the platform before deciding whether they want
                        to upgrade to a paid membership.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Will I automatically be charged after the free trial?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180 transition">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        No automatic charge is made when the free trial ends.
                        Upgrading to a paid membership is a separate decision
                        made by the user.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    What am I actually paying for?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180 transition">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">
                    <p class="text-gray-600 leading-7">
                        A paid membership provides access to the profile listing,
                        visibility, support, and other features included in the
                        selected membership plan. Membership is not a payment
                        for guaranteed clients, meetings, bookings, employment,
                        or income.
                    </p>
                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Does a membership guarantee clients?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180 transition">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">

                    <div class="bg-indigo-50 border border-indigo-100
                                rounded-xl p-5 mb-4">

                        <p class="font-semibold text-indigo-900">
                            No. A membership does not guarantee clients,
                            enquiries, bookings, meetings, employment,
                            or income.
                        </p>

                    </div>

                    <p class="text-gray-600 leading-7">
                        If another user is interested in your profile, they
                        may choose to contact you. Yooo.App cannot force
                        another person to communicate with, select, book,
                        or meet with you.
                    </p>

                </div>

            </details>


            <details class="group bg-white border border-gray-200
                            rounded-2xl shadow-sm">

                <summary class="flex items-center justify-between gap-4
                                cursor-pointer list-none p-5 md:p-6
                                font-semibold text-gray-900">

                    Are there hidden charges after I upgrade?

                    <span class="flex-shrink-0 w-8 h-8 rounded-full
                                 bg-gray-100 flex items-center justify-center
                                 text-gray-500 group-open:rotate-180 transition">
                        ↓
                    </span>

                </summary>

                <div class="px-5 md:px-6 pb-6">

                    <p class="text-gray-600 leading-7">
                        Membership pricing and applicable charges should be
                        reviewed before completing a purchase. Yooo.App does
                        not use unexpected booking, security-deposit, medical,
                        license, or similar “release” payments as a condition
                        for providing a client or meeting.
                    </p>

                    <p class="text-gray-500 text-sm leading-6 mt-3">
                        Always verify unexpected payment requests through
                        official Yooo.App channels before sending money.
                    </p>

                </div>

            </details>

        </div>

    </section>
<!-- Safety & Verification -->
<section id="safety" class="mb-12">

    <div class="mb-6">
        <span class="text-sm font-bold uppercase tracking-wider text-green-600">
            Safety & Trust
        </span>

        <h2 class="text-3xl font-extrabold text-gray-900 mt-2">
            Safety Questions
        </h2>

        <p class="text-gray-500 mt-2">
            Understand how Yooo.App approaches verification, scams, privacy,
            and user safety.
        </p>
    </div>

    <div class="space-y-4">

        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">
            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                How does Yooo.App help keep users safe?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    We use a combination of profile verification, account
                    security measures, reporting tools, moderation processes,
                    and safety guidance. These measures are designed to reduce
                    risks, but no online platform can eliminate every possible
                    risk or guarantee the behaviour of another person.
                </p>
            </div>
        </details>


        <!-- FAQ -->
        <details id="verification"
                 class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                How are profiles verified?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    Depending on the verification level and information required,
                    Yooo.App may use email verification, phone OTP verification,
                    selfie verification, and identity-document verification.
                </p>

                <p class="text-gray-600 leading-7 mt-3">
                    Verification helps us establish that an account is associated
                    with the information provided during verification. It does
                    not mean that Yooo.App guarantees a person's identity,
                    intentions, conduct, or future behaviour in every situation.
                </p>
            </div>
        </details>


        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                Does a verified badge mean the person is completely safe?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    No. A verification badge should not be interpreted as a
                    guarantee that someone is trustworthy, safe, or suitable
                    for you. Verification is one safety signal, not a substitute
                    for your own judgment.
                </p>
            </div>
        </details>


        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                Can someone impersonate another person?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    Attempts to impersonate another person are not permitted.
                    Verification and reporting mechanisms are intended to help
                    identify and address suspicious accounts.
                </p>

                <p class="text-gray-600 leading-7 mt-3">
                    If you believe an account is pretending to be someone else,
                    report the account to us with as much relevant information
                    as possible.
                </p>
            </div>
        </details>


        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                What should I do if someone asks me for a security deposit?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">

                <div class="bg-red-50 border border-red-100 rounded-xl p-5 mb-4">
                    <p class="font-semibold text-red-800">
                        Stop and verify the request before sending money.
                    </p>
                </div>

                <p class="text-gray-600 leading-7">
                    Be particularly cautious if someone asks you to pay a
                    “security deposit”, “release fee”, “verification fee”,
                    “medical fee”, “license fee”, “booking release fee”,
                    or similar unexpected payment in order to arrange a
                    meeting or receive money.
                </p>

                <p class="text-gray-600 leading-7 mt-3">
                    Do not assume that a person contacting you represents
                    Yooo.App. Contact us through an official channel if
                    you are unsure.
                </p>

            </div>
        </details>


        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                Does Yooo.App guarantee that users will not be scammed?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    No online platform can honestly guarantee that every
                    interaction will be risk-free. Our goal is to reduce
                    avoidable risks through verification, reporting,
                    moderation, security measures, and user education.
                </p>

                <p class="text-gray-600 leading-7 mt-3">
                    Users should remain cautious and should never send money
                    or sensitive information solely because another person
                    claims to be associated with Yooo.App.
                </p>
            </div>
        </details>


        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                What should I do if I receive a suspicious message?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    Do not send money, passwords, OTP codes, identity documents,
                    or other sensitive information. Save relevant evidence and
                    report the account through the appropriate Yooo.App
                    reporting channel.
                </p>
            </div>
        </details>


        <!-- FAQ -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                Can I report harassment or abusive behaviour?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    Yes. Users should report harassment, threats, impersonation,
                    fraud, abusive behaviour, or other serious violations through
                    our reporting channels. Reports can be reviewed and appropriate
                    action may be taken according to our policies.
                </p>
            </div>
        </details>

    </div>

</section>


<!-- Payments & Privacy -->
<section id="payments" class="mb-12">

    <div class="mb-6">
        <span class="text-sm font-bold uppercase tracking-wider text-orange-600">
            Payments & Privacy
        </span>

        <h2 class="text-3xl font-extrabold text-gray-900 mt-2">
            Payments, Privacy & Account Questions
        </h2>

        <p class="text-gray-500 mt-2">
            Clear answers about money, personal information, and account control.
        </p>
    </div>


    <div class="space-y-4">

        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                Does Yooo.App ask users to pay a booking fee?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    Never, Membership charges and any applicable platform charges are
                    shown through the relevant purchasing process. Yooo.App
                    should not be represented by an individual claiming that
                    an additional personal payment is required to “release”
                    or guarantee a booking.
                </p>
            </div>
        </details>


        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">
                Does Yooo.App ask for medical or licensing fees to arrange a meeting?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>
            </summary>

            <div class="px-5 md:px-6 pb-6">
                <p class="text-gray-600 leading-7">
                    Be extremely cautious about anyone claiming that you must
                    pay Yooo.App a medical, licensing, registration, security,
                    or release fee before a meeting can happen. These are all scam.
                </p>
            </div>
        </details>


        <!-- Delete Account -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">

                Can I delete my profile myself?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>

            </summary>

            <div class="px-5 md:px-6 pb-6">

                <p class="text-gray-600 leading-7">
                    Yes. You can delete your profile yourself from your
                    account settings without needing to contact an agent
                    or support representative.
                </p>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mt-4">

                    <p class="font-semibold text-gray-900 mb-2">
                        What happens after deletion?
                    </p>

                    <p class="text-sm text-gray-600 leading-6">
                        Your profile can be removed from the active platform,
                        while certain activity and security records may be
                        retained for up to <strong>30 days</strong> for
                        security, fraud-prevention, and investigation purposes.
                    </p>

                </div>

            </div>
        </details>


        <!-- Verification Badge -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">

                What does the verified badge actually mean?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>

            </summary>

            <div class="px-5 md:px-6 pb-6">

                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-4">

                    <p class="font-bold text-indigo-900">
                        A verified badge means the identity document
                        submitted during verification has been verified.
                    </p>

                </div>

                <p class="text-gray-600 leading-7">
                    It does <strong>not</strong> mean that Yooo.App guarantees
                    the person's intentions, personality, behaviour, honesty,
                    reliability, or future actions.
                </p>

                <p class="text-gray-600 leading-7 mt-3">
                    Verification is an identity-related safety measure,
                    not a guarantee that another user is trustworthy or
                    that an interaction will be safe.
                </p>

            </div>
        </details>
        <!-- Security Deposit -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">

                Does Yooo.App require a security deposit?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>

            </summary>

            <div class="px-5 md:px-6 pb-6">

                <div class="bg-red-50 border border-red-100 rounded-xl p-5 mb-4">

                    <p class="font-bold text-red-800 mb-2">
                        Never pay a "security deposit" because someone says
                        it is required by Yooo.App.
                    </p>

                    <p class="text-sm text-red-700 leading-6">
                        Security deposits, release fees, verification fees,
                        medical fees, and license fees are common scam
                        tactics used by people pretending to represent
                        platforms or other users.
                    </p>

                </div>

                <p class="text-gray-600 leading-7">
                    Yooo.App does not require users to send a security deposit
                    in order to receive or arrange a meeting.
                </p>

            </div>
        </details>


        <!-- Other Scam Fees -->
        <details class="group bg-white border border-gray-200 rounded-2xl shadow-sm">

            <summary class="flex items-center justify-between gap-4 cursor-pointer
                            list-none p-5 md:p-6 font-semibold text-gray-900">

                What other payments should I never make because someone claims
                Yooo.App requires them?

                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100
                             flex items-center justify-center text-gray-500
                             group-open:rotate-180 transition">
                    ↓
                </span>

            </summary>

            <div class="px-5 md:px-6 pb-6">

                <p class="text-gray-600 leading-7 mb-4">
                    Never send money because someone claims you need one of
                    the following payments to unlock, release, verify, or
                    arrange a meeting:
                </p>

                <div class="grid sm:grid-cols-2 gap-3">

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <strong class="text-red-800">Security Deposit</strong>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <strong class="text-red-800">Release Fee</strong>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <strong class="text-red-800">Verification Fee</strong>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <strong class="text-red-800">Medical Fee</strong>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <strong class="text-red-800">License Fee</strong>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <strong class="text-red-800">Booking Fee</strong>
                    </div>

                </div>

                <p class="text-gray-600 leading-7 mt-5">
                    If someone asks you for any unexpected payment and claims
                    it is required by Yooo.App, stop and verify the request
                    with us through an official channel.
                </p>

            </div>
        </details>
    </div>

</section>
</main>
<?= view('includes/footer') ?>
