<?php

namespace App\Controllers;

use App\Models\SeoModel;
use App\Models\ProfileModel;
use App\Models\BookingModel;
use App\Models\CountryModel;
use App\Models\CityModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProfileController extends BaseController
{
    public function profile(?int $id = null, ?string $slug = null)
    {
        if (empty($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $profile = (new ProfileModel())->find($id);
        if (!is_array($profile)) {
            throw PageNotFoundException::forPageNotFound();
        }

        // --- Data for footer ---
        $countries = $this->fetchCountries();
        $cities = $this->fetchCities();

        $citiesByCountry = [];
        foreach ($cities as $city) {
            $citiesByCountry[$city['country_id']][] = $city;
        }

        $profile = $this->normalizeProfile($profile);
        $profileName = trim((string) ($profile['name'] ?? 'Profile'));
        $canonicalSlug = url_title($profileName !== '' ? $profileName : 'profile-' . $id, '-', true);

        $gender = strtolower(trim((string) ($profile['gender'] ?? 'female')));

        if ($slug !== null && strtolower(trim($slug)) !== $canonicalSlug) {
            return redirect()->to(localized_url('profile/' . $id . '/' . $canonicalSlug), 301);
        }

        $data = [
            'countries' => $countries,
            'citiesByCountry' => $citiesByCountry,
            'language' => $this->currentLanguage(),
            'gender' => $gender,
        ];

        $seoEntry = $this->findSeoEntryForCurrentPath();

        $viewData = [
            'profile' => $profile,
            'seoEntry' => $seoEntry,
            'metaTitle' => $this->seoValue($seoEntry, 'title', $profileName . ' | ' . env('SITE_NAME', 'Yooo.App')),
            'metaDescription' => $this->seoValue($seoEntry, 'description', trim((string) ($profile['description'] ?? ''))),
            'metaKeywords' => $this->seoValue($seoEntry, 'meta_keywords'),
        ];

        return view('profile', array_merge($viewData, $data));
    }

    public function book(int $id)
    {
        $profile = (new ProfileModel())->find($id);
        if (!$profile) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'required|min_length[5]|max_length[40]',
            'message' => 'required|min_length[5]|max_length[2000]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('booking_errors', $this->validator->getErrors());
        }

        $saved = (new BookingModel())->insert([
            'profile_id' => $id,
            'name' => trim((string) $this->request->getPost('name')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'message' => trim((string) $this->request->getPost('message')),
            'status' => 'pending',
        ]);

        if (!$saved) {
            return redirect()->back()->withInput()->with('error', 'Your booking request could not be saved. Please try again.');
        }

        return redirect()->back()->with('success', 'Your booking request has been sent.');
    }

    /** Returns the SEO entry saved for the current profile URL, if any. */
    private function findSeoEntryForCurrentPath(): array
    {
        $currentPath = $this->normalizeSeoPath($this->request->getUri()->getPath());
        $seoEntries = (new SeoModel())->findAll();

        foreach ($seoEntries as $entry) {
            if ($this->normalizeSeoPath((string) ($entry['url'] ?? '')) === $currentPath) {
                return $entry;
            }
        }

        return [];
    }

    private function seoValue(array $seoEntry, string $field, ?string $fallback = null): ?string
    {
        $value = trim((string) ($seoEntry[$field] ?? ''));

        return $value !== '' ? $value : $fallback;
    }

    private function normalizeSeoPath(string $url): string
    {
        $url = trim($url);
        $path = parse_url($url, PHP_URL_PATH);

        if (!is_string($path)) {
            $path = preg_match('#^[a-z][a-z0-9+.-]*://#i', $url) === 1 ? '/' : $url;
        }

        $path = '/' . trim($path, '/');

        return $path === '/' ? $path : rtrim($path, '/');
    }

    private function currentLanguage(): string
    {
        $language = $this->request->getUri()->getSegment(1);

        return in_array($language, supported_languages(), true) ? $language : 'en';
    }

    private function normalizeProfile(array $profile): array
    {
        $profile['images'] = $this->normalizeImages($profile['images'] ?? []);
        $profile['pricing'] = $this->jsonToArray($profile['pricing'] ?? []);
        $profile['sexuality'] = $this->jsonToArray($profile['sexuality'] ?? []);
        $profile['other_pages'] = $this->jsonToArray($profile['other_pages'] ?? []);
        $profile['services'] = $this->normalizeStructuredList($profile['services'] ?? []);
        $profile['languages'] = $this->normalizeStructuredList($profile['languages'] ?? []);
        $profile['age'] = $this->ageFromDob($profile['dob'] ?? null);

        return $profile;
    }

    private function normalizeImages(mixed $value): array
    {
        return array_values(array_filter(array_map(function ($path): string {
            return $this->normalizeImagePath((string) $path);
        }, $this->normalizeStringList($this->jsonToArray($value))), static fn(string $path): bool => $path !== ''));
    }

    private function normalizeStructuredList(mixed $value): array
    {
        $items = $this->jsonToArray($value);

        if (array_key_exists('selected', $items)) {
            $selected = is_array($items['selected'] ?? null) ? $items['selected'] : [];
            $other = trim((string) ($items['other'] ?? ''));

            if ($other !== '') {
                $selected[] = $other;
            }

            return $this->normalizeStringList($selected);
        }

        return $this->normalizeStringList($items);
    }

    private function jsonToArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (!is_string($value)) {
            return [];
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return [];
        }

        $decoded = json_decode($trimmed, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return preg_split('/[\r\n,]+/', $trimmed) ?: [];
    }

    private function normalizeStringList(array $items): array
    {
        return array_values(array_filter(array_map(static function ($item): string {
            if (is_array($item)) {
                return trim((string) ($item['name'] ?? $item['title'] ?? $item['value'] ?? ''));
            }

            return trim((string) $item);
        }, $items), static fn(string $item): bool => $item !== ''));
    }

    private function normalizeImagePath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        $cdnUrl = rtrim((string) env('app.cdnURL', ''), '/');
        if ($cdnUrl !== '') {
            return $cdnUrl . '/images/users/' . ltrim($path, '/');
        }

        return site_url('images/users/' . ltrim($path, '/'));
    }

    private function ageFromDob(mixed $dob): ?int
    {
        $value = trim((string) $dob);
        if ($value === '') {
            return null;
        }

        try {
            return (new \DateTime())->diff(new \DateTime($value))->y;
        } catch (\Exception) {
            return null;
        }
    }

    private function fetchCountries(): array
    {
        return (new CountryModel())->getActiveCountries();
    }

    private function fetchCities(?int $countryId = null): array
    {
        return (new CityModel())->getActiveCities($countryId);
    }
}
