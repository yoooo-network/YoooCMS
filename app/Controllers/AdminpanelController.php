<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SeoModel;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\CountryModel;
use App\Models\CityModel;
use App\Services\EmailService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use DateTimeImmutable;
use DateTimeZone;

class AdminpanelController extends BaseController
{
    private const CONTACT_SETTINGS_PATH = WRITEPATH . 'settings/contact.json';
    private const PAYMENT_SETTINGS_PATH = WRITEPATH . 'settings/payment.json';
    private const SITE_CATEGORIES = ['male', 'female', 'gay', 'trans'];
    private const UI_THEMES = [
        'default' => [
            'name' => 'Default theme',
            'description' => 'The current Yooo.App design.',
        ],
    ];

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $tz = config('App')->appTimezone ?? 'UTC';
        $start = Time::today($tz);
        $end = $start->addDays(1);
        $usersToday = (new UserModel())->where('created_at >=', $start->toDateTimeString())->where('created_at <', $end->toDateTimeString())->countAllResults();
        $profileModel = new ProfileModel();
        $profilesToday = (new ProfileModel())->where('created_at >=', $start->toDateTimeString())->where('created_at <', $end->toDateTimeString())->countAllResults();
        $approvedProfilesToday = $profileModel->where('LOWER(TRIM(status))', 'approved')->where('updated_at >=', $start->toDateTimeString())->where('updated_at <', $end->toDateTimeString())->countAllResults();

        return view('admin/index', [
            'usersToday' => $usersToday,
            'profilesToday' => $profilesToday,
            'approvedProfilesToday' => $approvedProfilesToday,
            'emailsToday' => $this->countEmailsSentToday($start, $end, $tz),
        ]);
    }

    private function countEmailsSentToday(Time $start, Time $end, string $tz): int
    {
        $path = WRITEPATH . 'logs/sent-emails.log';
        $lines = is_file($path) ? @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : false;
        if (!is_array($lines)) return 0;
        $timezone = new DateTimeZone($tz);
        $from = new DateTimeImmutable($start->toDateTimeString(), $timezone);
        $until = new DateTimeImmutable($end->toDateTimeString(), $timezone);
        $count = 0;
        foreach ($lines as $line) {
            $entry = json_decode($line, true);
            if (!is_array($entry) || empty($entry['time']) || ($entry['status'] ?? '') !== 'success') continue;
            $sentAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $entry['time'], $timezone);
            if ($sentAt && $sentAt >= $from && $sentAt < $until) $count++;
        }
        return $count;
    }

    public function users()
    {
        $model = new UserModel();
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $id = $this->request->getGet('id');
        $name = $this->request->getGet('name');
        $email = $this->request->getGet('email');
        if ($id) $model->where('id', $id);
        if ($name) $model->like('name', $name);
        if ($email) $model->like('email', $email);
        $totalUsers = $model->countAllResults(false);
        $users = $model->orderBy('id', 'DESC')->findAll(100, ($page - 1) * 100);
        return view('admin/manage-users', compact('users', 'totalUsers', 'page', 'id', 'name', 'email'));
    }

    public function deleteUser($id)
    {
        $userModel = new UserModel();
        $profileModel = new ProfileModel();
        $user = $userModel->find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found.');
        $profile = $profileModel->where('user_id', $id)->first();
        if ($profile) {
            $this->deleteProfileImages($profile['images'] ?? '[]');
            $profileModel->delete($profile['id']);
        }
        $userModel->delete($id);
        return redirect()->to('/ci-admin/users')->with('success', 'User, profile, and images deleted.');
    }

    public function verifyEmail($id)
    {
        $model = new UserModel();
        $user = $model->find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found.');
        if (!empty($user['email_verified_at'])) return redirect()->back()->with('success', 'Email already verified.');
        $model->update((int) $id, ['email_verification_token' => null, 'email_verified_at' => date('Y-m-d H:i:s')]);
        return redirect()->back()->with('success', 'Email verified successfully.');
    }

    public function profiles()
    {
        $model = new ProfileModel();
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $id = $this->request->getGet('id'); $name = $this->request->getGet('name');
        $email = $this->request->getGet('email'); $city = $this->request->getGet('city');
        $gender = $this->request->getGet('gender');
        $builder = $model->builder()->select('profiles.*, users.email')->join('users', 'users.id = profiles.user_id', 'left');
        if ($id) $builder->where('profiles.id', $id);
        if ($name) $builder->like('profiles.name', $name);
        if ($email) $builder->like('users.email', $email);
        if ($city) $builder->like('profiles.location', $city);
        if ($gender) $builder->where('profiles.gender', $gender);
        $totalProfiles = $builder->countAllResults(false);
        $profiles = $builder->orderBy('profiles.id', 'DESC')->get(100, ($page - 1) * 100)->getResultArray();
        return view('admin/manage-profiles', compact('profiles', 'totalProfiles', 'page', 'id', 'name', 'email', 'city', 'gender'));
    }

    public function verificationQueue()
    {
        $profiles = (new ProfileModel())->orderBy('id', 'DESC')->findAll(500);
        $queue = [];
        foreach ($profiles as $profile) {
            $userId = (int) ($profile['user_id'] ?? 0);
            if ($userId <= 0) continue;
            $path = $this->verificationUploadPath($userId);
            $selfies = is_dir($path) ? (glob($path . 'live_selfie_*') ?: []) : [];
            $documents = is_dir($path) ? (glob($path . 'id_verification_*') ?: []) : [];
            if ($selfies === [] && $documents === []) continue;
            $profile['has_live_selfie'] = $selfies !== [];
            $profile['has_id_document'] = $documents !== [];
            $queue[] = $profile;
        }
        return view('admin/verification-queue', ['profiles' => $queue]);
    }

    public function deleteProfile($id)
    {
        $model = new ProfileModel();
        $profile = $model->find($id);
        if (!$profile) return redirect()->back()->with('error', 'Profile not found.');
        $this->deleteProfileImages($profile['images'] ?? '[]');
        $model->delete($id);
        if (!empty($profile['user_id'])) (new UserModel())->delete((int) $profile['user_id']);
        return redirect()->to('/ci-admin/profiles')->with('success', 'Profile, user, and images deleted.');
    }

    private function deleteProfileImages($json): void
    {
        foreach ((json_decode((string) $json, true) ?: []) as $image) {
            $base = pathinfo($image, PATHINFO_FILENAME);
            foreach ([$base . '.webp', $base . '_400.webp', $base . '_800.webp'] as $file) {
                $path = FCPATH . 'images/users/' . $file;
                if (is_file($path)) @unlink($path);
            }
        }
    }

    public function toggleStatus($id)
    {
        $model = new ProfileModel(); $profile = $model->find($id);
        if ($profile) $model->update($id, ['status' => $profile['status'] === 'approved' ? 'pending' : 'approved']);
        return redirect()->back();
    }

    public function toggleMembership($id)
    {
        $model = new ProfileModel(); $profile = $model->find($id);
        if ($profile) {
            $membership = $profile['membership'] === 'premium' ? 'free' : 'premium';
            $model->update($id, ['membership' => $membership]);
            if ($membership === 'premium' && !empty($profile['user_id'])) {
                $user = (new UserModel())->find((int) $profile['user_id']);
                if (is_array($user) && !empty($user['email'])) {
                    helper('text');
                    $name = trim((string) ($profile['name'] ?? $user['name'] ?? ''));
                    $slug = $name !== '' ? url_title($name, '-', true) : 'profile-' . (string) $profile['id'];
                    $profileUrl = site_url('en/profile/' . $profile['id'] . '/' . $slug);
                    $categorySlug = strtolower(trim((string) ($profile['gender'] ?? default_site_category())));
                    if (!in_array($categorySlug, site_categories(), true)) $categorySlug = default_site_category();
                    $parts = array_values(array_filter(array_map('trim', explode(',', (string) ($profile['location'] ?? '')))));
                    $cityUrl = site_url('en/user/dashboard');
                    if (count($parts) >= 2) $cityUrl = site_url('en/' . $categorySlug . '/' . url_title(end($parts), '-', true) . '/' . url_title($parts[0], '-', true));
                    if (!(new EmailService())->sendProfileUpgradedEmail(['name' => $user['name'] ?? '', 'email' => $user['email']], $profileUrl, $cityUrl)) log_message('error', 'Profile upgraded email failed for profile ID: ' . (string) $profile['id']);
                }
            }
        }
        return redirect()->back();
    }

    public function toggleVerification($id)
    {
        $model = new ProfileModel(); $profile = $model->find($id);
        if ($profile) {
            $verified = (int) ($profile['is_verified'] ?? 0) === 1 ? 0 : 1;
            $model->update($id, ['is_verified' => $verified]);
            if ($verified && !empty($profile['user_id'])) {
                $user = (new UserModel())->find((int) $profile['user_id']);
                if (is_array($user) && !empty($user['email'])) {
                    helper('text');
                    $name = trim((string) ($profile['name'] ?? $user['name'] ?? ''));
                    $slug = $name !== '' ? url_title($name, '-', true) : 'profile-' . (string) $profile['id'];
                    if (!(new EmailService())->sendProfileVerifiedEmail(['name' => $user['name'] ?? '', 'email' => $user['email']], site_url('en/profile/' . $profile['id'] . '/' . $slug))) log_message('error', 'Profile verified email failed for profile ID: ' . (string) $profile['id']);
                }
            }
        }
        return redirect()->back();
    }

    public function checkVerification($id)
    {
        $profile = (new ProfileModel())->find($id);
        if (!$profile || empty($profile['user_id'])) return redirect()->back()->with('error', 'Verification files not found for this profile.');
        $userId = (int) $profile['user_id'];
        $selfie = $this->findLatestVerificationFile($userId, 'live_selfie_');
        $document = $this->findLatestVerificationFile($userId, 'id_verification_');
        return view('admin/check-verification', [
            'profile' => $profile,
            'liveSelfieUrl' => $selfie ? site_url('ci-admin/profiles/verification-file/' . $id . '/live_selfie') : null,
            'idDocumentUrl' => $document ? site_url('ci-admin/profiles/verification-file/' . $id . '/id_verification') : null,
        ]);
    }

    public function verificationFile($id, $type)
    {
        $prefixes = ['live_selfie' => 'live_selfie_', 'id_verification' => 'id_verification_'];
        if (!isset($prefixes[(string) $type])) throw PageNotFoundException::forPageNotFound();
        $profile = (new ProfileModel())->find($id);
        if (!$profile || empty($profile['user_id'])) throw PageNotFoundException::forPageNotFound();
        $path = $this->findLatestVerificationFile((int) $profile['user_id'], $prefixes[(string) $type]);
        if (!$path || !is_file($path)) throw PageNotFoundException::forPageNotFound();
        return $this->response->setHeader('Content-Type', mime_content_type($path) ?: 'application/octet-stream')->setHeader('Content-Disposition', 'inline; filename="' . basename($path) . '"')->setBody((string) file_get_contents($path));
    }

    private function verificationUploadPath(int $userId): string
    {
        return WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'verifications' . DIRECTORY_SEPARATOR . 'user_' . $userId . DIRECTORY_SEPARATOR;
    }

    private function findLatestVerificationFile(int $userId, string $prefix): ?string
    {
        $files = glob($this->verificationUploadPath($userId) . $prefix . '*') ?: [];
        usort($files, static fn ($a, $b) => filemtime($b) <=> filemtime($a));
        return isset($files[0]) && is_file($files[0]) ? $files[0] : null;
    }

    public function media()
    {
        helper('filesystem');
        $path = FCPATH . 'images' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $images = [];
        if (is_dir($path)) {
            foreach (get_filenames($path, false) as $file) {
                if (!preg_match('/\.webp$/i', $file) || str_contains($file, '_400.webp') || str_contains($file, '_800.webp')) continue;
                $fullPath = $path . $file;
                $images[] = ['name' => $file, 'basename' => pathinfo($file, PATHINFO_FILENAME), 'size_kb' => round(filesize($fullPath) / 1024, 1), 'modified' => filemtime($fullPath), 'url' => base_url('images/users/' . $file)];
            }
        }
        usort($images, static fn ($a, $b) => $b['modified'] <=> $a['modified']);
        $totalImages = count($images);
        $images = array_slice($images, ($page - 1) * 100, 100);
        return view('admin/manage-media', compact('images', 'totalImages', 'page'));
    }

    public function deleteMedia($fileName)
    {
        $fileName = basename((string) $fileName);
        if ($fileName === '' || !preg_match('/\.webp$/i', $fileName)) return redirect()->back()->with('error', 'Invalid file name.');
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $path = FCPATH . 'images' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR;
        foreach ([$baseName . '.webp', $baseName . '_400.webp', $baseName . '_800.webp'] as $file) if (is_file($path . $file)) @unlink($path . $file);
        $model = new ProfileModel();
        foreach ($model->where("images LIKE '%" . $fileName . "%'")->findAll() as $profile) {
            $images = json_decode($profile['images'] ?? '[]', true) ?: [];
            $model->update($profile['id'], ['images' => json_encode(array_values(array_filter($images, static fn ($image) => $image !== $fileName)))]);
        }
        return redirect()->to('/ci-admin/media')->with('success', 'Image and related sizes deleted.');
    }

    public function emailLogs()
    {
        return view('admin/emails/sent-emails', ['logs' => $this->getRecentEmailLogs(100)]);
    }

    public function bookings()
    {
        $bookings = (new BookingModel())
            ->select('bookings.*, profiles.name AS profile_name, profiles.id AS profile_id')
            ->join('profiles', 'profiles.id = bookings.profile_id', 'left')
            ->orderBy('bookings.created_at', 'DESC')
            ->findAll(500);
        return view('admin/bookings', ['bookings' => $bookings]);
    }

    public function approveBooking(int $id)
    {
        $model = new BookingModel();
        if (!$model->find($id)) return redirect()->back()->with('error', 'Booking not found.');
        $model->update($id, ['status' => 'approved']);
        return redirect()->to('/ci-admin/bookings')->with('success', 'Booking approved.');
    }

    public function deleteBooking(int $id)
    {
        $model = new BookingModel();
        if (!$model->find($id)) return redirect()->back()->with('error', 'Booking not found.');
        $model->delete($id);
        return redirect()->to('/ci-admin/bookings')->with('success', 'Booking deleted.');
    }

    private function getRecentEmailLogs(int $limit): array
    {
        $path = WRITEPATH . 'logs/sent-emails.log';
        $lines = is_file($path) ? @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : false;
        if (!$lines) return [];
        $logs = [];
        foreach (array_reverse(array_slice($lines, -$limit)) as $line) {
            $entry = json_decode($line, true);
            if (is_array($entry)) $logs[] = ['time' => $entry['time'] ?? '', 'name' => $entry['name'] ?? '', 'email' => $entry['email'] ?? '', 'subject' => $entry['subject'] ?? '', 'status' => $entry['status'] ?? ''];
        }
        return $logs;
    }

    public function locations()
    {
        return redirect()->to('/ci-admin/settings/countries');
    }

    public function countries()
    {
        $countryModel = new CountryModel();
        $countries = $countryModel
            ->select('countries.*, COUNT(cities.id) AS city_count')
            ->join('cities', 'cities.country_id = countries.id', 'left')
            ->groupBy('countries.id')
            ->orderBy('countries.sort_order', 'ASC')
            ->orderBy('countries.name', 'ASC')
            ->findAll();

        return view('admin/settings/country-settings', ['countries' => $countries]);
    }

    public function cities()
    {
        $countryModel = new CountryModel();
        $cityModel = new CityModel();
        $countries = $countryModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll();
        $selectedCountryId = (int) ($this->request->getGet('country_id') ?? 0);
        if ($selectedCountryId && ! $countryModel->find($selectedCountryId)) $selectedCountryId = 0;
        if ($selectedCountryId === 0 && $countries !== []) $selectedCountryId = (int) $countries[0]['id'];
        $cities = $cityModel->where('country_id', $selectedCountryId)
            ->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll();

        return view('admin/settings/city-settings', [
            'countries' => $countries,
            'cities' => $cities,
            'selectedCountryId' => $selectedCountryId,
        ]);
    }

    public function createCountry()
    {
        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->locationSlug($name);
        $currencyCode = strtoupper(trim((string) $this->request->getPost('currency_code')));
        $phoneCode = trim((string) $this->request->getPost('phone_code'));
        if ($name === '' || mb_strlen($name) > 100 || $slug === null) return redirect()->back()->withInput()->with('error', 'Enter a valid country name.');
        if ($currencyCode !== '' && ! preg_match('/^[A-Z]{3}$/', $currencyCode)) return redirect()->back()->withInput()->with('error', 'Currency code must contain three letters.');

        try {
            $id = (new CountryModel())->insert([
                'name' => $name, 'slug' => $slug, 'phone_code' => $phoneCode !== '' ? $phoneCode : null,
                'currency_code' => $currencyCode !== '' ? $currencyCode : null,
                'is_active' => 1,
                'sort_order' => (int) $this->request->getPost('sort_order'),
            ]);
            if ($id === false) return redirect()->back()->withInput()->with('error', 'Could not add the country. Its name may already be in use.');
            $this->clearLocationCaches($slug, (int) $id);
        } catch (\Throwable $exception) {
            log_message('error', '[Admin locations] Could not create country: {error}', ['error' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Could not add the country. Its name may already be in use.');
        }
        return redirect()->to('/ci-admin/settings/countries')->with('success', 'Country added.');
    }

    public function updateCountry(int $id)
    {
        $model = new CountryModel();
        $country = $model->find($id);
        if (! $country) return redirect()->back()->with('error', 'Country not found.');
        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->locationSlug($name);
        $currencyCode = strtoupper(trim((string) $this->request->getPost('currency_code')));
        $phoneCode = trim((string) $this->request->getPost('phone_code'));
        if ($name === '' || mb_strlen($name) > 100 || $slug === null) return redirect()->back()->withInput()->with('error', 'Enter a valid country name.');
        if ($currencyCode !== '' && ! preg_match('/^[A-Z]{3}$/', $currencyCode)) return redirect()->back()->withInput()->with('error', 'Currency code must contain three letters.');

        try {
            if (! $model->update($id, [
                'name' => $name, 'slug' => $slug, 'phone_code' => $phoneCode !== '' ? $phoneCode : null,
                'currency_code' => $currencyCode !== '' ? $currencyCode : null,
                'sort_order' => (int) $this->request->getPost('sort_order'),
            ])) return redirect()->back()->withInput()->with('error', 'Could not update the country. Its name may already be in use.');
            $this->clearLocationCaches($country['slug'], $id);
            $this->clearLocationCaches($slug, $id);
        } catch (\Throwable $exception) {
            log_message('error', '[Admin locations] Could not update country {id}: {error}', ['id' => $id, 'error' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Could not update the country. Its name may already be in use.');
        }
        return redirect()->to('/ci-admin/settings/countries')->with('success', 'Country updated.');
    }

    public function deleteCountry(int $id)
    {
        $model = new CountryModel();
        $country = $model->find($id);
        if (! $country) return redirect()->back()->with('error', 'Country not found.');
        try {
            if (! $model->delete($id)) return redirect()->back()->with('error', 'Could not delete the country.');
            $this->clearLocationCaches($country['slug'], $id);
        } catch (\Throwable $exception) {
            log_message('error', '[Admin locations] Could not delete country {id}: {error}', ['id' => $id, 'error' => $exception->getMessage()]);
            return redirect()->back()->with('error', 'Could not delete the country.');
        }
        return redirect()->to('/ci-admin/settings/countries')->with('success', 'Country and its cities deleted.');
    }

    public function createCity()
    {
        $countryId = (int) $this->request->getPost('country_id');
        $country = (new CountryModel())->find($countryId);
        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->locationSlug($name);
        if (! $country) return redirect()->back()->withInput()->with('error', 'Choose a valid country.');
        if ($name === '' || mb_strlen($name) > 150 || $slug === null) return redirect()->back()->withInput()->with('error', 'Enter a valid city name.');

        try {
            $id = (new CityModel())->insert([
                'country_id' => $countryId, 'name' => $name, 'slug' => $slug,
                'is_active' => 1,
                'sort_order' => (int) $this->request->getPost('sort_order'),
            ]);
            if ($id === false) return redirect()->back()->withInput()->with('error', 'Could not add the city. That city name may already exist in this country.');
            $this->clearLocationCaches($slug, $countryId);
        } catch (\Throwable $exception) {
            log_message('error', '[Admin locations] Could not create city: {error}', ['error' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Could not add the city. That city name may already exist in this country.');
        }
        return redirect()->to('/ci-admin/settings/cities?country_id=' . $countryId)->with('success', 'City added.');
    }

    public function updateCity(int $id)
    {
        $model = new CityModel();
        $city = $model->find($id);
        if (! $city) return redirect()->back()->with('error', 'City not found.');
        $countryId = (int) $this->request->getPost('country_id');
        if (! (new CountryModel())->find($countryId)) return redirect()->back()->withInput()->with('error', 'Choose a valid country.');
        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->locationSlug($name);
        if ($name === '' || mb_strlen($name) > 150 || $slug === null) return redirect()->back()->withInput()->with('error', 'Enter a valid city name.');

        try {
            if (! $model->update($id, [
                'country_id' => $countryId, 'name' => $name, 'slug' => $slug,
                'sort_order' => (int) $this->request->getPost('sort_order'),
            ])) return redirect()->back()->withInput()->with('error', 'Could not update the city. That name may already exist in this country.');
            $this->clearLocationCaches($city['slug'], (int) $city['country_id']);
            $this->clearLocationCaches($slug, $countryId);
        } catch (\Throwable $exception) {
            log_message('error', '[Admin locations] Could not update city {id}: {error}', ['id' => $id, 'error' => $exception->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Could not update the city. That name may already exist in this country.');
        }
        return redirect()->to('/ci-admin/settings/cities?country_id=' . $countryId)->with('success', 'City updated.');
    }

    public function deleteCity(int $id)
    {
        $model = new CityModel();
        $city = $model->find($id);
        if (! $city) return redirect()->back()->with('error', 'City not found.');
        try {
            if (! $model->delete($id)) return redirect()->back()->with('error', 'Could not delete the city.');
            $this->clearLocationCaches($city['slug'], (int) $city['country_id']);
        } catch (\Throwable $exception) {
            log_message('error', '[Admin locations] Could not delete city {id}: {error}', ['id' => $id, 'error' => $exception->getMessage()]);
            return redirect()->back()->with('error', 'Could not delete the city.');
        }
        return redirect()->to('/ci-admin/settings/cities?country_id=' . (int) $city['country_id'])->with('success', 'City deleted.');
    }

    private function locationSlug(string $name): ?string
    {
        helper('text');
        $slug = url_title($name, '-', true);
        return $slug !== '' ? $slug : null;
    }

    private function clearLocationCaches(string $slug, int $countryId = 0): void
    {
        $cache = cache();
        $cache->delete('countries.active');
        $cache->delete('countries.slug.' . strtolower($slug));
        $cache->delete('cities.active');
        if ($countryId > 0) $cache->delete('cities.active.country.' . $countryId);
        $cache->delete('cities.slug.' . strtolower($slug));
        if ($countryId > 0) $cache->delete('cities.slug.' . strtolower($slug) . '.country.' . $countryId);
    }

    public function settingsSection(string $section)
    {
        $sections = [
            'site' => ['title' => 'Site Settings', 'description' => 'The site name and base URL are managed in the project .env file.', 'state' => 'Configured through .env'],
            'ui' => ['title' => 'UI Settings', 'description' => 'Select the active site theme.', 'state' => 'Theme selector available'],
            'locations' => ['title' => 'Locations', 'description' => 'Manage countries and cities used by the public site.', 'state' => 'Admin tools available'],
            'sitemap' => ['title' => 'Sitemap', 'description' => 'The public XML sitemaps are generated directly from the current profile and location data.', 'state' => 'Active'],
            'languages' => ['title' => 'Languages', 'description' => 'These languages are currently enabled for localized website routes.', 'state' => 'Active'],
            'permalink' => ['title' => 'Permalink', 'description' => 'Public URL patterns are currently defined in the CI4 route configuration and cannot be edited here.', 'state' => 'Fixed routes'],
        ];
        if (!isset($sections[$section])) throw PageNotFoundException::forPageNotFound();
        $data = $sections[$section];
        $data['section'] = $section;
        $data['siteName'] = env('SITE_NAME', 'Yooo.App');
        $data['siteUrl'] = rtrim((string) env('app.baseURL', base_url()), '/');
        $data['languages'] = $section === 'languages' ? supported_languages() : [];
        $data['sitemaps'] = $section === 'sitemap' ? ['Sitemap index' => site_url('sitemap.xml'), 'Profile sitemap index' => site_url('profile_sitemap.xml')] : [];
        return view('admin/settings/section', $data);
    }

    public function siteSettings()
    {
        return view('admin/settings/site-settings', ['settings' => $this->readSiteEnvironment()]);
    }

    public function uiSettings()
    {
        $selectedTheme = (string) env('SITE_THEME', 'default');
        if (! isset(self::UI_THEMES[$selectedTheme])) $selectedTheme = 'default';

        return view('admin/settings/ui-settings', [
            'themes' => self::UI_THEMES,
            'selectedTheme' => $selectedTheme,
        ]);
    }

    public function saveUiSettings()
    {
        $theme = (string) $this->request->getPost('theme');
        if (! isset(self::UI_THEMES[$theme])) {
            return redirect()->back()->withInput()->with('error', 'Select an available theme.');
        }
        if (! $this->writeEnvironmentValues(['SITE_THEME' => $theme])) {
            return redirect()->back()->withInput()->with('error', 'Could not save the theme selection to the project .env file.');
        }
        return redirect()->to('/ci-admin/settings/ui')->with('success', 'Theme selection saved.');
    }

    public function saveSiteSettings()
    {
        $languageOptions = ['en', 'de', 'es', 'fr', 'pt', 'ja', 'hi'];
        $languages = array_values(array_intersect($languageOptions, (array) $this->request->getPost('languages')));
        if (! in_array('en', $languages, true)) $languages[] = 'en';
        $categories = array_values(array_intersect(self::SITE_CATEGORIES, (array) $this->request->getPost('categories')));
        if ($categories === []) $categories = ['female'];

        $email = trim((string) $this->request->getPost('email_from'));
        $smtpPort = filter_var($this->request->getPost('email_port'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]);
        $cdnUrl = trim((string) $this->request->getPost('cdn_url'));
        if ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Enter a valid sender email address.');
        }
        if ($smtpPort === false) {
            return redirect()->back()->withInput()->with('error', 'Enter a valid email server port.');
        }
        if ($cdnUrl !== '' && ! filter_var($cdnUrl, FILTER_VALIDATE_URL)) {
            return redirect()->back()->withInput()->with('error', 'Enter a valid CDN URL.');
        }

        $values = [
            'SITE_CATEGORIES' => implode(',', $categories),
            'SITE_LANGUAGES' => implode(',', $languages),
            'email.protocol' => in_array($this->request->getPost('email_protocol'), ['smtp', 'mail', 'sendmail'], true) ? $this->request->getPost('email_protocol') : 'smtp',
            'email.fromEmail' => $email,
            'email.fromName' => trim((string) $this->request->getPost('email_from_name')),
            'email.SMTPHost' => trim((string) $this->request->getPost('email_host')),
            'email.SMTPUser' => trim((string) $this->request->getPost('email_user')),
            'email.SMTPPort' => (string) $smtpPort,
            'email.SMTPCrypto' => in_array($this->request->getPost('email_crypto'), ['', 'tls', 'ssl'], true) ? (string) $this->request->getPost('email_crypto') : 'tls',
            'app.cdnURL' => rtrim($cdnUrl, '/'),
            'SITE_AGE_NOTICE_ENABLED' => $this->request->getPost('age_notice') ? 'true' : 'false',
            'SITE_PRIVACY_COOKIES_NOTICE_ENABLED' => $this->request->getPost('privacy_cookies_notice') ? 'true' : 'false',
            'API_ENABLED' => $this->request->getPost('api_enabled') ? 'true' : 'false',
            'TURNSTILE_SITE_KEY' => trim((string) $this->request->getPost('turnstile_site_key')),
        ];
        $password = (string) $this->request->getPost('email_password');
        if ($password !== '') $values['email.SMTPPass'] = $password;
        $turnstileSecret = (string) $this->request->getPost('turnstile_secret_key');
        if ($turnstileSecret !== '') $values['TURNSTILE_SECRET_KEY'] = $turnstileSecret;

        if (! $this->writeEnvironmentValues($values)) {
            return redirect()->back()->withInput()->with('error', 'Could not save settings to the project .env file.');
        }
        return redirect()->to('/ci-admin/settings/site')->with('success', 'Site settings saved to .env.');
    }

    private function readSiteEnvironment(): array
    {
        $categories = site_categories();
        $privacyCookiesNotice = env('SITE_PRIVACY_COOKIES_NOTICE_ENABLED');
        $privacyCookiesEnabled = $privacyCookiesNotice === null
            ? (filter_var(env('SITE_PRIVACY_POLICY_ENABLED', true), FILTER_VALIDATE_BOOLEAN) || filter_var(env('SITE_COOKIES_POLICY_ENABLED', true), FILTER_VALIDATE_BOOLEAN))
            : filter_var($privacyCookiesNotice, FILTER_VALIDATE_BOOLEAN);
        return [
            'categories' => $categories,
            'languages' => supported_languages(),
            'email_protocol' => (string) env('email.protocol', 'smtp'),
            'email_from' => (string) env('email.fromEmail', ''),
            'email_from_name' => (string) env('email.fromName', ''),
            'email_host' => (string) env('email.SMTPHost', ''),
            'email_user' => (string) env('email.SMTPUser', ''),
            'email_port' => (string) env('email.SMTPPort', '465'),
            'email_crypto' => (string) env('email.SMTPCrypto', 'ssl'),
            'cdn_url' => (string) env('app.cdnURL', ''),
            'age_notice' => filter_var(env('SITE_AGE_NOTICE_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
            'privacy_cookies_notice' => $privacyCookiesEnabled,
            'api_enabled' => filter_var(env('API_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            'turnstile_site_key' => (string) env('TURNSTILE_SITE_KEY', ''),
        ];
    }

    private function writeEnvironmentValues(array $values): bool
    {
        $path = dirname(APPPATH) . DIRECTORY_SEPARATOR . '.env';
        if (! is_file($path) || ! is_readable($path) || ! is_writable($path)) return false;
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) return false;
        $found = [];
        foreach ($lines as $index => $line) {
            foreach ($values as $key => $value) {
                if (preg_match('/^\\s*' . preg_quote($key, '/') . '\\s*=/', $line)) {
                    $lines[$index] = $key . ' = ' . $this->formatEnvironmentValue((string) $value);
                    $found[$key] = true;
                    break;
                }
            }
        }
        foreach ($values as $key => $value) if (! isset($found[$key])) $lines[] = $key . ' = ' . $this->formatEnvironmentValue((string) $value);
        $temporary = $path . '.' . bin2hex(random_bytes(6)) . '.tmp';
        if (@file_put_contents($temporary, implode(PHP_EOL, $lines) . PHP_EOL, LOCK_EX) === false) return false;
        @chmod($temporary, 0600);
        if (! @rename($temporary, $path)) { @unlink($temporary); return false; }
        @chmod($path, 0600);
        return true;
    }

    private function formatEnvironmentValue(string $value): string
    {
        if (str_contains($value, "\n") || str_contains($value, "\r")) throw new \InvalidArgumentException('Environment values cannot contain line breaks.');
        return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
    }
    public function login()
    {
        helper('url');
        $session = session();

        if ($session->get('admin_logged_in')) {
            return redirect()->to('/ci-admin/dashboard');
        }

        if (strtoupper($this->request->getMethod()) === 'POST') {
            $username = trim((string) $this->request->getPost('username'));
            $password = (string) $this->request->getPost('password');

            $adminUsername = (string) env('ADMIN_USERNAME', '');
            $adminPassword = (string) env('ADMIN_PASSWORD', '');

            if ($adminUsername !== '' && $adminPassword !== ''
                && hash_equals($adminUsername, $username)
                && hash_equals($adminPassword, $password)) {
                $session->set([
                    'admin_logged_in' => true,
                    'admin_username'  => $adminUsername,
                ]);
                $session->regenerate();
                return redirect()->to('/ci-admin/dashboard');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid admin credentials.');
        }

        return view('admin/login');
    }

    public function logout()
    {
        helper('url');
        session()->destroy();
        return redirect()->to('/ci-admin')->with('message', 'Logged out.');
    }
    public function seo()
    {
        $seoModel = new SeoModel();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 100;

        $totalEntries = $seoModel->countAllResults(false);
        $entries  = $seoModel
            ->orderBy('id', 'DESC')
            ->findAll($perPage, ($page - 1) * $perPage);

        return view('admin/seo/seo', [
            'entries' => $entries,
            'seo'     => null,
            'totalEntries' => $totalEntries,
            'page'         => $page,
        ]);
    }

    public function edit($id)
    {
        $seoModel = new SeoModel();
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 100;
        $seo      = $seoModel->find($id);
        $totalEntries = $seoModel->countAllResults(false);
        $entries  = $seoModel
            ->orderBy('id', 'DESC')
            ->findAll($perPage, ($page - 1) * $perPage);

        if (!$seo) {
            return redirect()->to('/ci-admin/seo')->with('error', 'Record not found.');
        }

        return view('admin/seo/seo', [
            'entries' => $entries,
            'seo'     => $seo,
            'totalEntries' => $totalEntries,
            'page'         => $page,
        ]);
    }

    public function save()
    {
        $seoModel = new SeoModel();

        $id = (int) ($this->request->getPost('id') ?? 0);

        $data = [
            'url'           => trim((string) $this->request->getPost('url')),
            'title'         => $this->optionalField('title'),
            'description'   => $this->optionalField('description'),
            'meta_keywords' => $this->optionalField('meta_keywords'),
            'h1'            => $this->optionalField('h1'),
            'intro_content' => $this->optionalField('intro_content'),
            'seo_content'   => $this->optionalField('seo_content'),
        ];

        $rules = [
            'url'   => 'required|min_length[1]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill required fields.');
        }

        if ($id > 0) {
            $seoModel->update($id, $data);
            $msg = 'SEO entry updated.';
        } else {
            $seoModel->insert($data);
            $msg = 'SEO entry added.';
        }

        return redirect()->to('/ci-admin/seo')->with('success', $msg);
    }

    public function delete($id)
    {
        $seoModel = new SeoModel();
        $seoModel->delete($id);
        return redirect()->to('/ci-admin/seo')->with('success', 'SEO entry deleted.');
    }

    private function optionalField(string $field): string
    {
        $value = trim((string) $this->request->getPost($field));

        return $value === '' ? '' : $value;
    }

    public function contact()
    {
        return view('admin/settings/contact', [
            'settings' => $this->readContactSettings(),
        ]);
    }

    public function saveContact()
    {
        $settings = [
            'telegram' => [
                'enabled' => $this->request->getPost('telegram_enabled') ? 1 : 0,
                'value'   => trim((string) $this->request->getPost('telegram_value')),
            ],
            'whatsapp' => [
                'enabled' => $this->request->getPost('whatsapp_enabled') ? 1 : 0,
                'value'   => trim((string) $this->request->getPost('whatsapp_value')),
            ],
            'phone' => [
                'enabled' => $this->request->getPost('phone_enabled') ? 1 : 0,
                'value'   => trim((string) $this->request->getPost('phone_value')),
            ],
        ];

        if (!$this->writeContactSettings($settings)) {
            return redirect()->back()->withInput()->with('error', 'Could not save contact settings.');
        }

        return redirect()->to('/ci-admin/settings/contact')->with('success', 'Contact settings saved successfully.');
    }

    public function payment()
    {
        return view('admin/settings/payment', [
            'settings' => $this->readPaymentSettings(),
        ]);
    }

    public function savePayment()
    {
        $defaults = $this->paymentDefaults();
        $wallets = [];

        foreach ($defaults['wallets'] as $key => $wallet) {
            $wallets[$key] = [
                'name'    => trim((string) $this->request->getPost("wallet_{$key}_name")),
                'network' => trim((string) $this->request->getPost("wallet_{$key}_network")),
                'address' => trim((string) $this->request->getPost("wallet_{$key}_address")),
            ];
        }

        $settings = [
            'upi' => [
                'name' => trim((string) $this->request->getPost('upi_name')),
                'id'   => trim((string) $this->request->getPost('upi_id')),
            ],
            'wallets' => $wallets,
        ];

        if (!$this->writeSettings(self::PAYMENT_SETTINGS_PATH, $settings)) {
            return redirect()->back()->withInput()->with('error', 'Could not save payment settings.');
        }

        return redirect()->to('/ci-admin/settings/payment')->with('success', 'Payment settings saved successfully.');
    }

    private function readContactSettings(): array
    {
        $defaults = [
            'telegram' => ['enabled' => 0, 'value' => ''],
            'whatsapp' => ['enabled' => 0, 'value' => ''],
            'phone'    => ['enabled' => 0, 'value' => ''],
        ];

        if (!is_file(self::CONTACT_SETTINGS_PATH)) {
            return $defaults;
        }

        $raw = @file_get_contents(self::CONTACT_SETTINGS_PATH);
        if ($raw === false || $raw === '') {
            return $defaults;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }

        return [
            'telegram' => [
                'enabled' => !empty($decoded['telegram']['enabled']) ? 1 : 0,
                'value'   => trim((string) ($decoded['telegram']['value'] ?? '')),
            ],
            'whatsapp' => [
                'enabled' => !empty($decoded['whatsapp']['enabled']) ? 1 : 0,
                'value'   => trim((string) ($decoded['whatsapp']['value'] ?? '')),
            ],
            'phone' => [
                'enabled' => !empty($decoded['phone']['enabled']) ? 1 : 0,
                'value'   => trim((string) ($decoded['phone']['value'] ?? '')),
            ],
        ];
    }

    private function writeContactSettings(array $settings): bool
    {
        return $this->writeSettings(self::CONTACT_SETTINGS_PATH, $settings);
    }

    private function paymentDefaults(): array
    {
        return [
            'upi' => ['name' => 'Yoooo App', 'id' => '919692301234@boi'],
            'wallets' => [
                'btc' => ['name' => 'Bitcoin BTC', 'network' => 'BTC Network', 'address' => '1Ldtbf6ot4nbkRktkPX3tuyXjG8YMeDA9j'],
                'ltc' => ['name' => 'Litecoin LTC', 'network' => 'LTC Network', 'address' => 'Lfg2TMLVEWDaCPUm7nXyJhUsPg2nz6iS5N'],
                'eth' => ['name' => 'Ethereum ETH', 'network' => 'Ethereum Network', 'address' => '0x99EB504de2C602815fBe40c4963D0df23acD044e'],
                'trx' => ['name' => 'Tron TRX', 'network' => 'Tron Network', 'address' => 'TEoBqVa5pkqArcbsxoXvR6qjBevuXYrjmM'],
                'xrp' => ['name' => 'Ripple XRP', 'network' => 'XRP Network', 'address' => 'rKHyQX2ZYLPgPjhGnmM2R8wwSvCUTuYpCW'],
            ],
        ];
    }

    private function readPaymentSettings(): array
    {
        $defaults = $this->paymentDefaults();
        $raw = is_file(self::PAYMENT_SETTINGS_PATH) ? @file_get_contents(self::PAYMENT_SETTINGS_PATH) : false;
        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        if (!is_array($decoded)) {
            return $defaults;
        }

        foreach ($defaults['wallets'] as $key => $wallet) {
            foreach (array_keys($wallet) as $field) {
                $defaults['wallets'][$key][$field] = trim((string) ($decoded['wallets'][$key][$field] ?? $wallet[$field]));
            }
        }

        foreach (array_keys($defaults['upi']) as $field) {
            $defaults['upi'][$field] = trim((string) ($decoded['upi'][$field] ?? $defaults['upi'][$field]));
        }

        return $defaults;
    }

    private function writeSettings(string $path, array $settings): bool
    {
        $directory = dirname($path);
        if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
            return false;
        }

        $json = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        return @file_put_contents($path, $json, LOCK_EX) !== false;
    }
}
