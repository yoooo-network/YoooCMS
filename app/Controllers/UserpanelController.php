<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\BookingModel;
use App\Models\CountryModel;
use App\Models\CityModel;

class UserpanelController extends BaseController
{
    protected $dummyData = [
        'profile' => [],
        'countries' => [],
        'selectedCities' => []
    ];

    private function requireAuth()
    {
        if ((int) session()->get('user_id') <= 0) {
            return redirect()->to(localized_url('signin'));
        }
        return null;
    }

    private function userFromSession(): array
    {
        return [
            'id' => (int) session()->get('user_id'),
            'name' => (string) session()->get('user_name'),
            'email' => (string) session()->get('user_email'),
        ];
    }

    private function fetchProfile(): array
    {
        $profile = (new ProfileModel())->where('user_id', (int) session()->get('user_id'))->first();
        if (!is_array($profile)) return [];
        foreach (['images', 'pricing', 'services', 'languages', 'other_pages', 'sexuality'] as $field) {
            if (isset($profile[$field]) && is_string($profile[$field])) {
                $profile[$field] = json_decode($profile[$field], true) ?: [];
            }
        }
        return $profile;
    }

    private function fetchCountries(): array
    {
        return (new CountryModel())->getActiveCountries();
    }

    private function fetchCities(int $countryId): array
    {
        return (new CityModel())->getActiveCities($countryId);
    }

    private function mergeAndUpdateProfile(array $patch): array
    {
        $current = $this->fetchProfile();
        $payload = array_merge($current, $patch);

        // Normalize commonly required fields.
        $payload['name'] = trim((string) ($payload['name'] ?? $this->userFromSession()['name'] ?? ''));
        $payload['gender'] = strtolower(trim((string) ($payload['gender'] ?? '')));
        $payload['dob'] = trim((string) ($payload['dob'] ?? ''));
        $payload['location'] = trim((string) ($payload['location'] ?? ''));

        $model = new ProfileModel();
        $existing = $model->where('user_id', (int) session()->get('user_id'))->first();
        $jsonFields = ['images', 'pricing', 'services', 'languages', 'other_pages', 'sexuality'];
        foreach ($jsonFields as $field) {
            if (array_key_exists($field, $payload) && is_array($payload[$field])) $payload[$field] = json_encode($payload[$field]);
        }
        $allowed = ['name','gender','sexuality','dob','location','height','weight','description','eye_color','hair_type','skin_color','body_structure','ethnicity','images','pricing','services','languages','phone','whatsapp','telegram','facebook','instagram','discord','website','other_pages'];
        $payload = array_intersect_key($payload, array_flip($allowed));
        $merged = array_merge($existing ?? [], $payload);
        $isComplete = true;
        foreach (['name','gender','dob','location','height','weight','description','images','languages','pricing','services','phone'] as $required) {
            $value = $merged[$required] ?? null;
            if (is_string($value)) $value = trim($value);
            if ($value === null || $value === '') { $isComplete = false; break; }
            if (in_array($required, ['images','languages','pricing','services'], true)) {
                $decoded = is_string($value) ? json_decode($value, true) : $value;
                if (!is_array($decoded) || $decoded === []) { $isComplete = false; break; }
            }
        }
        if ($existing) {
            if ($isComplete) $payload['status'] = 'approved';
            $ok = $model->update((int) $existing['id'], $payload);
            return ['ok' => (bool) $ok, 'message' => $ok ? 'Profile updated successfully' : 'Unable to update profile.'];
        }
        $payload['user_id'] = (int) session()->get('user_id');
        $payload['status'] = $isComplete ? 'approved' : 'pending';
        $payload['membership'] = 'free';
        $payload['is_verified'] = 0;
        $id = $model->insert($payload, true);
        return ['ok' => (bool) $id, 'message' => $id ? 'Profile created successfully' : 'Unable to create profile.'];
    }

    public function dashboard()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        return view('user-panel/dashboard', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    public function bookings()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        $profile = $this->fetchProfile();
        $bookings = [];
        if (!empty($profile['id'])) {
            $bookings = (new BookingModel())->where('profile_id', (int) $profile['id'])->orderBy('created_at', 'DESC')->findAll();
        }

        return view('user-panel/bookings', [
            'user' => $this->userFromSession(),
            'profile' => $profile,
            'bookings' => $bookings,
        ]);
    }

    public function upgrade()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        return view('user-panel/upgrade', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    public function payment()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        return view('user-panel/payments/payment', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    public function cryptoPayment()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        return view('user-panel/payments/crypto', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
            'paymentSettings' => $this->paymentSettings(),
        ]);
    }

    public function upiPayment()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        return view('user-panel/payments/upi', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
            'paymentSettings' => $this->paymentSettings(),
        ]);
    }

    private function paymentSettings(): array
    {
        $defaults = ['upi' => ['name' => '', 'id' => ''], 'wallets' => []];
        $path = WRITEPATH . 'settings/payment.json';
        $raw = is_file($path) ? @file_get_contents($path) : false;
        $settings = is_string($raw) ? json_decode($raw, true) : null;

        if (!is_array($settings)) {
            return $defaults;
        }

        $wallets = [];
        foreach (($settings['wallets'] ?? []) as $key => $wallet) {
            if (!is_array($wallet) || empty($wallet['address'])) {
                continue;
            }
            $wallets[(string) $key] = [
                'name' => trim((string) ($wallet['name'] ?? 'Crypto')),
                'network' => trim((string) ($wallet['network'] ?? '')),
                'address' => trim((string) $wallet['address']),
            ];
        }

        return [
            'upi' => [
                'name' => trim((string) ($settings['upi']['name'] ?? '')),
                'id' => trim((string) ($settings['upi']['id'] ?? '')),
            ],
            'wallets' => $wallets,
        ];
    }

    public function editProfile()
    {
        if ($redirect = $this->requireAuth()) return $redirect;
        return view('user-panel/edit-profile', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    public function getCities(int $countryId)
    {
        return $this->response->setJSON($this->fetchCities($countryId));
    }

    public function editBasic()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->saveBasicInfo();
        }

        $profile = $this->fetchProfile();
        $countries = $this->fetchCountries();
        $selectedCities = [];

        if (!empty($profile['location'])) {
            $parts = explode(',', (string) $profile['location']);
            $countryName = trim((string) ($parts[1] ?? ''));
            foreach ($countries as $country) {
                if (trim((string) ($country['name'] ?? '')) === $countryName) {
                    $selectedCities = $this->fetchCities((int) ($country['id'] ?? 0));
                    break;
                }
            }
        }

        return view('user-panel/profile/basic', [
            'user' => $this->userFromSession(),
            'profile' => $profile,
            'countries' => $countries,
            'selectedCities' => $selectedCities,
        ]);
    }

    protected function saveBasicInfo()
    {
        $countryId = (int) ($this->request->getPost('country_id') ?? 0);
        $cityId = (int) ($this->request->getPost('city_id') ?? 0);

        $location = null;
        if ($countryId > 0 && $cityId > 0) {
            $countries = $this->fetchCountries();
            $cities = $this->fetchCities($countryId);
            $country = null;
            $city = null;
            foreach ($countries as $c) {
                if ((int) ($c['id'] ?? 0) === $countryId) {
                    $country = $c;
                    break;
                }
            }
            foreach ($cities as $c) {
                if ((int) ($c['id'] ?? 0) === $cityId) {
                    $city = $c;
                    break;
                }
            }
            if (is_array($country) && is_array($city)) {
                $location = trim((string) ($city['name'] ?? '')) . ', ' . trim((string) ($country['name'] ?? ''));
            }
        }

        $patch = [
            'name' => (string) $this->request->getPost('name'),
            'dob' => (string) $this->request->getPost('dob'),
            'description' => (string) $this->request->getPost('description'),
        ];
        if ($location !== null) {
            $patch['location'] = $location;
        }

        $res = $this->mergeAndUpdateProfile($patch);
        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save basic information.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Basic information saved successfully!');
    }

    public function editGender()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->saveGender();
        }

        return view('user-panel/profile/gender', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function saveGender()
    {
        $sexuality = [
            'types' => $this->request->getPost('sexuality') ?? [],
            'roles' => $this->request->getPost('roles') ?? [],
        ];

        $res = $this->mergeAndUpdateProfile([
            'gender' => (string) $this->request->getPost('gender'),
            'sexuality' => $sexuality,
        ]);

        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save gender and sexuality.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Gender and sexuality saved successfully!');
    }
    public function editPhysical()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->savePhysical();
        }

        return view('user-panel/profile/physical', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function savePhysical()
    {
        $res = $this->mergeAndUpdateProfile([
            'height' => (string) $this->request->getPost('height'),
            'weight' => (string) $this->request->getPost('weight'),
            'eye_color' => (string) $this->request->getPost('eye_color'),
            'hair_type' => (string) $this->request->getPost('hair_type'),
            'skin_color' => (string) $this->request->getPost('skin_color'),
            'body_structure' => (string) $this->request->getPost('body_structure'),
            'ethnicity' => (string) $this->request->getPost('ethnicity'),
        ]);

        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save physical details.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Physical details saved successfully!');
    }
    public function editContact()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->saveContact();
        }

        return view('user-panel/profile/contact', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function saveContact()
    {
        $res = $this->mergeAndUpdateProfile([
            'phone' => (string) $this->request->getPost('phone'),
            'whatsapp' => (string) $this->request->getPost('whatsapp'),
            'telegram' => (string) $this->request->getPost('telegram'),
            'facebook' => (string) $this->request->getPost('facebook'),
            'instagram' => (string) $this->request->getPost('instagram'),
            'discord' => (string) $this->request->getPost('discord'),
            'website' => (string) $this->request->getPost('website'),
        ]);

        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save contact details.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Contact details saved successfully!');
    }
    public function editLanguage()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->saveLanguage();
        }

        return view('user-panel/profile/language', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function saveLanguage()
    {
        $languages = [
            'selected' => $this->request->getPost('languages') ?? [],
            'other' => (string) $this->request->getPost('other_languages'),
        ];

        $res = $this->mergeAndUpdateProfile(['languages' => $languages]);

        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save languages.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Languages saved successfully!');
    }
    public function editPricing()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->savePricing();
        }

        $profile = $this->fetchProfile();
        
        // Parse the JSON pricing string and extract currency and rates
        if ($profile && !empty($profile['pricing'])) {
            $pricing = is_string($profile['pricing']) ? json_decode($profile['pricing'], true) : $profile['pricing'];
            if (is_array($pricing)) {
                foreach ($pricing as $key => $value) {
                    if (preg_match('/^(\d+(?:\.\d+)?)([A-Z]{3})$/', (string) $value, $matches)) {
                        $pricing[$key] = $matches[1];
                        $profile['currency'] = $matches[2];
                    }
                }
                $profile['pricing'] = $pricing;
            }
        }

        return view('user-panel/profile/pricing', [
            'user' => $this->userFromSession(),
            'profile' => $profile,
        ]);
    }

    protected function savePricing()
    {
        $cleanRate = static function ($value): string {
            $value = trim((string) $value);
            if ($value === '') {
                return '';
            }
            $value = preg_replace('/[^\d.]/', '', $value) ?? '';
            if ($value === '' || !is_numeric($value)) {
                return '';
            }
            return $value;
        };

        $currency = strtoupper(trim((string) ($this->request->getPost('currency') ?? 'INR')));
        if ($currency === '') {
            $currency = 'INR';
        }

        $pricing = [];
        $keys = ['1hr', '3hr', 'night', 'week', 'month'];
        foreach ($keys as $k) {
            $rate = $cleanRate($this->request->getPost('rate_' . $k));
            if ($rate !== '') {
                $pricing[$k] = $rate . $currency;
            }
        }

        $res = $this->mergeAndUpdateProfile([
            'pricing' => $pricing,
        ]);

        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save pricing.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Pricing details saved successfully!');
    }
    public function editServices()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->saveServices();
        }

        return view('user-panel/profile/services', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function saveServices()
    {
        $services = [
            'selected' => $this->request->getPost('services') ?? [],
            'other' => (string) $this->request->getPost('other_services'),
        ];

        $res = $this->mergeAndUpdateProfile(['services' => $services]);

        if (!$res['ok']) {
            return redirect()->back()->withInput()->with('error', $res['message'] ?: 'Unable to save services.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Services saved successfully!');
    }
    public function editPhotos()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->savePhotos();
        }

        return view('user-panel/profile/photos', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function savePhotos()
    {
        $files = $this->request->getFiles();
        $photos = $files['photos'] ?? [];
        
        if (!is_array($photos) || $photos === []) {
            return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Profile photos viewed (No new uploads).');
        }

        $apiBase = rtrim((string) env('API_BASE_URL', ''), '/');
        $token = $this->token();
        if ($apiBase === '' || $token === '') {
            return redirect()->back()->with('error', 'Upload API is not configured.');
        }

        $uploaded = 0;
        $hasRealUploadAttempt = false;
        $errors = [];
        $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024;

        foreach ($photos as $img) {
            if (!$img) continue;
            
            if ($img->getError() === UPLOAD_ERR_NO_FILE) continue;
            if (!$img->isValid() || $img->hasMoved()) continue;
            
            $hasRealUploadAttempt = true;

            if ($img->getSize() > $maxFileSize) {
                $errors[] = 'Each photo must be 5MB or smaller.';
                continue;
            }

            $mime = strtolower((string) $img->getMimeType());
            if (!in_array($mime, $allowedMime, true)) {
                $errors[] = 'Only JPG, PNG, and WEBP images are allowed.';
                continue;
            }

            $ch = curl_init($apiBase . '/user/profile/upload-photo');
            $postFields = [
                'photo' => curl_file_create(
                    $img->getTempName(),
                    $mime,
                    (string) $img->getClientName()
                ),
            ];

            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json',
                ],
            ]);

            $body = curl_exec($ch);
            $curlError = curl_error($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($curlError !== '') {
                $errors[] = $curlError;
                continue;
            }

            $raw = is_string($body) ? json_decode($body, true) : null;
            $ok = $status >= 200 && $status < 300 && is_array($raw) && (($raw['status'] ?? '') === 'success');
            if ($ok) {
                $uploaded++;
                continue;
            }

            $errors[] = is_array($raw) ? (string) ($raw['message'] ?? 'Upload failed.') : 'Upload failed.';
        }

        if (!$hasRealUploadAttempt && $uploaded === 0 && $errors === []) {
            return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Profile photos viewed (No new uploads).');
        }

        if ($uploaded === 0) {
            return redirect()->back()->with('error', implode(' ', array_unique($errors)) ?: 'Failed to upload photos.');
        }

        return redirect()->to(localized_url('user-panel/edit-profile'))->with('success', 'Photos uploaded successfully!');
    }

    public function deletePhoto()
    {
        $token = $this->token();
        if ($token === '') {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized.', 'csrfHash' => csrf_hash()]);
        }

        $filename = trim((string) $this->request->getPost('filename'));
        if ($filename === '') {
            return $this->response->setJSON(['success' => false, 'error' => 'Filename required.', 'csrfHash' => csrf_hash()]);
        }

        $profile = $this->fetchProfile();
        $images = $profile['images'] ?? [];
        
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        if (!is_array($images)) {
            $images = [];
        }
        
        $images = array_values(array_filter($images, static fn($img) => trim((string) $img) !== $filename));

        $res = $this->mergeAndUpdateProfile(['images' => $images]);

        if (!$res['ok']) {
            return $this->response->setJSON(['success' => false, 'error' => $res['message'] ?: 'Failed to delete photo.', 'csrfHash' => csrf_hash()]);
        }

        return $this->response->setJSON(['success' => true, 'csrfHash' => csrf_hash()]);
    }

    private function isProfileSubmissionSuccessful(array $profile): bool
    {
        $submitted = session()->get('profile_submitted_successfully');
        if ($submitted === true) {
            return true;
        }

        if (($profile['is_submitted'] ?? false) === true) {
            return true;
        }

        $status = strtolower(trim((string) ($profile['status'] ?? $profile['profile_status'] ?? '')));
        if (in_array($status, ['submitted', 'under_review', 'pending_approval', 'published', 'approved'], true)) {
            return true;
        }

        return trim((string) ($profile['submitted_at'] ?? '')) !== '';
    }

    public function verifyProfile()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        $profile = $this->fetchProfile();

        return view('user-panel/verify-profile', [
            'profile' => $profile,
            'user' => $this->userFromSession(),
            'verificationFiles' => session()->get('verification_files') ?? [],
            'canVerify' => $this->isProfileSubmissionSuccessful($profile),
        ]);
    }

    public function applyVerification()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'get') {
            return $this->verifyProfile();
        }

        $apiBase = rtrim((string) env('API_BASE_URL', ''), '/');
        $token = $this->token();
        if ($apiBase === '' || $token === '') {
            return redirect()->back()->with('error', 'Verification upload is not configured.');
        }

        $live = $this->request->getFile('live_selfie_file');
        $id = $this->request->getFile('id_document_file');

        $postFields = [];
        if ($live && $live->isValid() && !$live->hasMoved()) {
            $postFields['live_selfie_file'] = curl_file_create(
                $live->getTempName(),
                (string) $live->getMimeType(),
                (string) $live->getClientName()
            );
        }
        if ($id && $id->isValid() && !$id->hasMoved()) {
            $postFields['id_document_file'] = curl_file_create(
                $id->getTempName(),
                (string) $id->getMimeType(),
                (string) $id->getClientName()
            );
        }

        if ($postFields === []) {
            return redirect()->back()->with('error', 'Please choose at least one verification image.');
        }

        $ch = curl_init($apiBase . '/user/profile/upload-verification-files');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ],
        ]);

        $body = curl_exec($ch);
        $curlError = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError !== '') {
            return redirect()->back()->with('error', 'Verification upload failed: ' . $curlError);
        }

        $raw = is_string($body) ? json_decode($body, true) : null;
        $ok = $status >= 200 && $status < 300 && is_array($raw) && (($raw['status'] ?? '') === 'success');
        if (!$ok) {
            $message = is_array($raw) ? (string) ($raw['message'] ?? 'Verification upload failed.') : 'Verification upload failed.';
            return redirect()->back()->with('error', $message);
        }

        $filesState = session()->get('verification_files') ?? [];
        $uploadedFiles = $raw['data']['files'] ?? [];
        if (is_array($uploadedFiles)) {
            if (!empty($uploadedFiles['live_selfie'])) {
                $filesState['live_selfie'] = (string) $uploadedFiles['live_selfie'];
            }
            if (!empty($uploadedFiles['id_verification'])) {
                $filesState['id_verification'] = (string) $uploadedFiles['id_verification'];
            }
        }
        session()->set('verification_files', $filesState);

        return redirect()->to(localized_url('user-panel/dashboard'))->with('success', 'Verification files uploaded successfully. Your profile is now pending approval.');
    }

    public function settings()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        return view('user-panel/settings', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    public function privacySettings()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            // TBD: Save logic for privacy settings
            return redirect()->to(localized_url('user-panel/settings'))->with('success', 'Privacy settings saved successfully!');
        }

        return view('user-panel/settings/privacy', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    public function changePassword()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->updatePassword();
        }

        return view('user-panel/settings/change-password', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function updatePassword()
    {
        $userModel = new UserModel();
        $user = $userModel->find((int) session()->get('user_id'));
        $current = (string) $this->request->getPost('current_password');
        $new = (string) $this->request->getPost('new_password');
        $confirm = (string) $this->request->getPost('confirm_password');
        if (!$user || !password_verify($current, (string) ($user['password'] ?? ''))) {
            return redirect()->back()->withInput()->with('error', 'Current password is incorrect.');
        }
        if (strlen($new) < 8 || $new !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'New passwords must match and contain at least 8 characters.');
        }
        $userModel->update((int) $user['id'], ['password' => password_hash($new, PASSWORD_DEFAULT)]);
        return redirect()->to(localized_url('user-panel/settings/password'))->with('success', 'Password updated successfully.');
    }

    public function deleteAccount()
    {
        if ($redirect = $this->requireAuth()) return $redirect;

        if (strtolower($this->request->getMethod()) === 'post') {
            return $this->processDeleteAccount();
        }

        return view('user-panel/settings/delete-account', [
            'user' => $this->userFromSession(),
            'profile' => $this->fetchProfile(),
        ]);
    }

    protected function processDeleteAccount()
    {
        $sessionUserId = (int) session()->get('user_id');
        $postedUserId = (int) ($this->request->getPost('user_id') ?? 0);

        if ($sessionUserId === 0 || $postedUserId === 0 || $sessionUserId !== $postedUserId) {
            return redirect()->back()->with('error', 'Invalid account deletion request.');
        }

        // This endpoint must remove the current user's profile record only. The
        // authenticated user record is retained so the user can create a new
        // profile later.
        $profile = (new ProfileModel())->where('user_id', $sessionUserId)->first();
        if ($profile) (new ProfileModel())->delete((int) $profile['id']);

        return redirect()->to(localized_url('user-panel/settings'))
            ->with('success', $result['message'] ?: 'Your profile has been deleted successfully.');
    }
}
