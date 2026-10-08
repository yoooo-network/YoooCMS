<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$languagePattern = implode('|', array_map(static fn(string $language): string => preg_quote($language, '/'), supported_languages()));
$configuredCategories = site_categories();
$categoryPattern = implode('|', array_map(static fn(string $category): string => preg_quote($category, '/'), $configuredCategories));
$pageCache1h = ['filter' => 'cachettl:3600'];
$pageCache1d = ['filter' => 'cachettl:86400'];
$pageCache1w = ['filter' => 'cachettl:604800'];

$routes->get('/', 'Home::landing');
$routes->get('install', 'InstallController::index');
$routes->post('install', 'InstallController::index');
$routes->post('install/test', 'InstallController::testConnection');

$routes->get('help', static fn() => redirect()->to(site_url('en/help'), 301));
$routes->get('about', static fn() => redirect()->to(site_url('en/about'), 301));
$routes->get('advertising', static fn() => redirect()->to(site_url('en/advertising'), 301));
$routes->get('contact', static fn() => redirect()->to(site_url('en/contact'), 301));
$routes->get('faq', static fn() => redirect()->to(site_url('en/faq'), 301));
$routes->get('packages', static fn() => redirect()->to(site_url('en/packages'), 301));
$routes->get('privacy-policy', static fn() => redirect()->to(site_url('en/privacy-policy'), 301));
$routes->get('refund-policy', static fn() => redirect()->to(site_url('en/refund-policy'), 301));
$routes->get('report-misuse', static fn() => redirect()->to(site_url('en/report-misuse'), 301));
$routes->get('safety-guidelines', static fn() => redirect()->to(site_url('en/safety-guidelines'), 301));
$routes->get('terms-and-conditions', static fn() => redirect()->to(site_url('en/terms-and-conditions'), 301));

if ($categoryPattern !== '') {
    $routes->get("({$categoryPattern})", static fn($category) => redirect()->to(site_url("en/{$category}"), 301));
    $routes->get("({$categoryPattern})/(:segment)", static fn($category, $segment) => redirect()->to(site_url("en/{$category}/{$segment}"), 301));
    $routes->get("({$categoryPattern})/(:segment)/(:segment)", static fn($category, $a, $b) => redirect()->to(site_url("en/{$category}/{$a}/{$b}"), 301));
}

$routes->get('profile', static fn() => redirect()->to(site_url('en/profile'), 301));
$routes->get('profile/(:num)', static fn($id) => redirect()->to(site_url("en/profile/{$id}"), 301));
$routes->get('profile/(:num)/(:segment)', static fn($id, $segment) => redirect()->to(site_url("en/profile/{$id}/{$segment}"), 301));

$routes->match(['get', 'post'], 'signin', static fn() => redirect()->to(site_url('en/signin'), 301));
$routes->match(['get', 'post'], 'signup', static fn() => redirect()->to(site_url('en/signup'), 301));
$routes->match(['get', 'post'], 'forgot-password', static fn() => redirect()->to(site_url('en/forgot-password'), 301));
$routes->match(['get', 'post'], 'reset-password', static fn() => redirect()->to(site_url('en/reset-password'), 301));
$routes->get('logout', static fn() => redirect()->to(site_url('en/logout'), 301));

$routes->get("({$languagePattern})", 'Home::landing/$1');

$routes->get("({$languagePattern})/help", 'PageController::index', $pageCache1w);
$routes->get("({$languagePattern})/about", 'PageController::view/about', $pageCache1w);
$routes->get("({$languagePattern})/advertising", 'PageController::view/advertising', $pageCache1w);
$routes->get("({$languagePattern})/contact", 'PageController::view/contact', $pageCache1w);
$routes->get("({$languagePattern})/faq", 'PageController::view/faq', $pageCache1w);
$routes->get("({$languagePattern})/packages", 'PageController::view/packages', $pageCache1w);
$routes->get("({$languagePattern})/privacy-policy", 'PageController::view/privacy-policy', $pageCache1w);
$routes->get("({$languagePattern})/refund-policy", 'PageController::view/refund-policy', $pageCache1w);
$routes->get("({$languagePattern})/report-misuse", 'PageController::view/report-misuse', $pageCache1w);
$routes->get("({$languagePattern})/safety-guidelines", 'PageController::view/safety-guidelines', $pageCache1w);
$routes->get("({$languagePattern})/terms-and-conditions", 'PageController::view/terms-and-conditions', $pageCache1w);

if ($categoryPattern !== '') {
    $routes->get("({$languagePattern})/({$categoryPattern})", 'Home::index/$1/$2', $pageCache1h);
    $routes->get("({$languagePattern})/({$categoryPattern})/(:segment)", 'Home::index/$1/$2/$3', $pageCache1h);
    $routes->get("({$languagePattern})/({$categoryPattern})/(:segment)/(:segment)", 'Home::index/$1/$2/$3/$4', $pageCache1h);
}

$routes->get("({$languagePattern})/profile", 'ProfileController::profile', $pageCache1d);
$routes->get("({$languagePattern})/profile/(:num)", 'ProfileController::profile/$2');
$routes->get("({$languagePattern})/profile/(:num)/(:segment)", 'ProfileController::profile/$2/$3');
$routes->post("({$languagePattern})/profile/(:num)/book", 'ProfileController::book/$2', ['filter' => 'csrf']);

$routes->match(['get', 'post'], "({$languagePattern})/signin", 'AuthController::signin');
$routes->match(['get', 'post'], "({$languagePattern})/signup", 'AuthController::signup');
$routes->match(['get', 'post'], "({$languagePattern})/forgot-password", 'AuthController::forgotPassword');
$routes->match(['get', 'post'], "({$languagePattern})/reset-password", 'AuthController::resetPassword');
$routes->get("({$languagePattern})/logout", 'AuthController::logout');

$routes->group('(?:' . $languagePattern . ')/user-panel', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'UserpanelController::dashboard');
    $routes->get('dashboard', 'UserpanelController::dashboard');
    $routes->get('bookings', 'UserpanelController::bookings');
    $routes->get('upgrade', 'UserpanelController::upgrade');
    $routes->get('payments/payment', 'UserpanelController::payment');
    $routes->get('payments/crypto', 'UserpanelController::cryptoPayment');
    $routes->get('payments/upi', 'UserpanelController::upiPayment');

    $routes->get('edit-profile', 'UserpanelController::editProfile');
    $routes->match(['get', 'post'], 'profile/edit-basic', 'UserpanelController::editBasic');
    $routes->match(['get', 'post'], 'profile/edit-gender', 'UserpanelController::editGender');
    $routes->match(['get', 'post'], 'profile/edit-physical', 'UserpanelController::editPhysical');
    $routes->match(['get', 'post'], 'profile/edit-contact', 'UserpanelController::editContact');
    $routes->match(['get', 'post'], 'profile/edit-language', 'UserpanelController::editLanguage');
    $routes->match(['get', 'post'], 'profile/edit-pricing', 'UserpanelController::editPricing');
    $routes->match(['get', 'post'], 'profile/edit-services', 'UserpanelController::editServices');
    $routes->match(['get', 'post'], 'profile/edit-photos', 'UserpanelController::editPhotos');
    $routes->match(['get', 'post'], 'apply-verification', 'UserpanelController::applyVerification');

    $routes->get('profile/cities/(:num)', 'UserpanelController::getCities/$1');
    $routes->post('profile/delete-photo', 'UserpanelController::deletePhoto');

    $routes->get('settings', 'UserpanelController::settings');
    $routes->match(['get', 'post'], 'settings/privacy', 'UserpanelController::privacySettings');
    $routes->match(['get', 'post'], 'settings/password', 'UserpanelController::changePassword');
    $routes->match(['get', 'post'], 'settings/delete-account', 'UserpanelController::deleteAccount');
});

$routes->group('ci-admin', function ($routes) {
    $routes->get('/', 'AdminpanelController::login');
    $routes->match(['GET', 'POST'], 'login', 'AdminpanelController::login');
    $routes->get('logout', 'AdminpanelController::logout');
});

$routes->group('', ['namespace' => 'App\\Controllers'], function ($routes) {
    $routes->get('sitemap.xml', 'Sitemap::index');
    $routes->get('profile_sitemap.xml', 'Sitemap::profileIndex');
    $routes->get('profile_sitemap_([a-z]{2})_(:num).xml', 'Sitemap::profilePage/$1/$2');
    $routes->get('sitemap_([a-z]{2})_(male|female|gay|trans).xml', 'Sitemap::category/$1/$2');
});


// --------------------------------------------------------------------
// API V1 Routes
// --------------------------------------------------------------------
$apiEnabled = filter_var(env('API_ENABLED', true), FILTER_VALIDATE_BOOLEAN);
if ($apiEnabled) $routes->group('api/v1', ['namespace' => 'App\Controllers\Api\V1'], function($routes) {
    
    // Public Endpoints
    $routes->get('home', 'Home::index');
    $routes->get('countries', 'Country::index');
    $routes->get('cities', 'City::index');
    $routes->get('profiles', 'Profile::index', ['filter' => 'cors']);
    $routes->get('profile/(:num)', 'Profile::show/$1');
    $routes->post('profile/(:num)/book', 'Profile::book/$1');
    
    // Auth Endpoints
    $routes->post('login', 'Auth::login');
    $routes->post('signup', 'Auth::signup');
    $routes->get('auth/verify-email', 'Auth::verifyEmail');
    $routes->post('auth/resend-verification', 'Auth::resendVerification');
    $routes->post('auth/forgot-password', 'Auth::forgotPassword');
    $routes->post('user/forgot-password', 'User::forgotPassword');
    $routes->match(['GET', 'POST'], 'auth/reset-password', 'Auth::resetPassword');
    $routes->post('system/cache-clear/(:segment)', 'CacheProxy::clear/$1');

    // Protected Endpoints (Requires JWT Token)
    $routes->group('', ['filter' => 'api_auth'], function($routes) {
        $routes->get('dashboard', 'User::dashboard');
        $routes->get('user/profile', 'User::profile');
        $routes->get('user/bookings', 'User::bookings');
        $routes->post('user/profile/update', 'User::updateProfile');
        $routes->post('user/profile/upload-photo', 'User::uploadPhoto');
        $routes->post('user/profile/upload-verification-files', 'User::uploadVerificationFiles');
        $routes->post('upload-contacts', 'User::uploadContacts');
        $routes->post('user/change-password', 'User::changePassword');
        $routes->post('user/delete-account', 'User::deleteAccount');
    });
});


//Admin Panel Routes
$routes->group('ci-admin', ['filter' => 'adminauth'], function ($routes) {
    $routes->get('dashboard', 'AdminpanelController::dashboard');
    $routes->get('seo', 'AdminpanelController::seo');
    $routes->get('seo/edit/(:num)', 'AdminpanelController::edit/$1');
    $routes->post('seo/save', 'AdminpanelController::save');
    $routes->post('seo/delete/(:num)', 'AdminpanelController::delete/$1');

    $routes->get('settings/contact', 'AdminpanelController::contact');
    $routes->post('settings/contact', 'AdminpanelController::saveContact');
    $routes->get('settings/payment', 'AdminpanelController::payment');
    $routes->post('settings/payment', 'AdminpanelController::savePayment');
    $routes->get('settings/site', 'AdminpanelController::siteSettings');
    $routes->post('settings/site', 'AdminpanelController::saveSiteSettings');
    $routes->get('settings/ui', 'AdminpanelController::uiSettings');
    $routes->post('settings/ui', 'AdminpanelController::saveUiSettings');
    $routes->get('settings/countries', 'AdminpanelController::countries');
    $routes->get('settings/cities', 'AdminpanelController::cities');
    $routes->get('settings/locations', 'AdminpanelController::locations');
    $routes->post('settings/locations/countries', 'AdminpanelController::createCountry');
    $routes->post('settings/locations/countries/(:num)/update', 'AdminpanelController::updateCountry/$1');
    $routes->post('settings/locations/countries/(:num)/delete', 'AdminpanelController::deleteCountry/$1');
    $routes->post('settings/locations/cities', 'AdminpanelController::createCity');
    $routes->post('settings/locations/cities/(:num)/update', 'AdminpanelController::updateCity/$1');
    $routes->post('settings/locations/cities/(:num)/delete', 'AdminpanelController::deleteCity/$1');
    $routes->get('settings/(:segment)', 'AdminpanelController::settingsSection/$1');

    $routes->get('users', 'AdminpanelController::users');
    $routes->post('users/delete/(:num)', 'AdminpanelController::deleteUser/$1');
    $routes->post('users/verify-email/(:num)', 'AdminpanelController::verifyEmail/$1');

    $routes->get('profiles', 'AdminpanelController::profiles');
    $routes->get('profiles/verifications', 'AdminpanelController::verificationQueue');
    $routes->get('profiles/check-verification/(:num)', 'AdminpanelController::checkVerification/$1');
    $routes->get('profiles/verification-file/(:num)/(:segment)', 'AdminpanelController::verificationFile/$1/$2');
    $routes->post('profiles/toggle-status/(:num)', 'AdminpanelController::toggleStatus/$1');
    $routes->post('profiles/toggle-membership/(:num)', 'AdminpanelController::toggleMembership/$1');
    $routes->post('profiles/toggle-verification/(:num)', 'AdminpanelController::toggleVerification/$1');
    $routes->post('profiles/delete/(:num)', 'AdminpanelController::deleteProfile/$1');

    $routes->get('media', 'AdminpanelController::media');
    $routes->post('media/delete/(:any)', 'AdminpanelController::deleteMedia/$1');

    $routes->get('sent-emails', 'AdminpanelController::emailLogs');
    $routes->get('bookings', 'AdminpanelController::bookings');
    $routes->post('bookings/approve/(:num)', 'AdminpanelController::approveBooking/$1');
    $routes->post('bookings/delete/(:num)', 'AdminpanelController::deleteBooking/$1');
    $routes->post('cache/clear', 'CacheController::clear');
    $routes->post('refresh', 'CacheController::clear');

});
