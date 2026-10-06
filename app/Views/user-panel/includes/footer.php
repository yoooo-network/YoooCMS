    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg">

        <div class="grid grid-cols-4 h-16">

            <a href="<?= localized_url('user-panel/dashboard') ?>"
               class="flex flex-col items-center justify-center transition <?= url_is('user-panel/dashboard*') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' ?>">

                <i class="bi bi-house-door-fill text-xl lg:text-2xl"></i>

                <span class="text-xs mt-1">
                    Home
                </span>
            </a>

            <a href="<?= localized_url('user-panel/edit-profile') ?>"
               class="flex flex-col items-center justify-center transition <?= url_is('user-panel/edit-profile*') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' ?>">

                <i class="bi bi-pencil-fill text-xl lg:text-2xl"></i>

                <span class="text-xs mt-1">Edit</span>
            </a>

            <a href="<?= localized_url('user-panel/bookings') ?>"
               class="flex flex-col items-center justify-center transition <?= url_is('user-panel/bookings*') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' ?>">

                <i class="bi bi-calendar-check-fill text-xl lg:text-2xl"></i>

                <span class="text-xs mt-1">
                    Bookings
                </span>
            </a>

            <a href="<?= localized_url('user-panel/settings') ?>"
               class="flex flex-col items-center justify-center transition <?= url_is('user-panel/settings*') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' ?>">

                <i class="bi bi-gear-fill text-xl lg:text-2xl"></i>

                <span class="text-xs mt-1">
                    Settings
                </span>

            </a>

        </div>
    </nav>

</div>
<?= view('components/sticky-buttons') ?>
</body>
</html>
