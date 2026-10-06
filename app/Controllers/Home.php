<?php

namespace App\Controllers;

use App\Models\SeoModel;
use App\Models\ProfileModel;
use App\Models\CountryModel;
use App\Models\CityModel;

class Home extends BaseController
{
    /** Render the category chooser without selecting a category or redirecting. */
    public function landing(string $language = 'en'): string
    {
        $language = in_array($language, supported_languages(), true) ? $language : 'en';
        service('language')->setLocale($language);
        $categories = [
            'male' => lang('Site.maleEscorts'),
            'female' => lang('Site.femaleEscorts'),
            'gay' => lang('Site.gayEscorts'),
            'trans' => lang('Site.transEscorts'),
        ];
        $categories = array_intersect_key($categories, array_flip(site_categories()));

        return view('home', [
            'language' => $language,
            'categories' => $categories,
            'siteName' => env('SITE_NAME', 'Yooo.App'),
            'privacyUrl' => site_url($language . '/privacy-policy'),
            'termsUrl' => site_url($language . '/terms-and-conditions'),
            'metaTitle' => env('SITE_NAME', 'Yooo.App'),
        ]);
    }

    public function index(string $language = 'en', ?string $category = null, ?string $countrySlug = null, ?string $citySlug = null): string
    {
        $language = in_array($language, supported_languages(), true) ? $language : 'en';
        $category = in_array($category, site_categories(), true)
            ? $category
            : (in_array('female', site_categories(), true) ? 'female' : default_site_category());

        // --- Data for footer ---
        $countries = $this->fetchCountries();
        $cities = $this->fetchCities();

        $citiesByCountry = [];
        foreach ($cities as $city) {
            $citiesByCountry[$city['country_id']][] = $city;
        }
        
        // --- Data for profile listing ---
        // "gay" is a URL category, not a stored gender. It lists male
        // profiles whose sexuality includes homo or bisexual.
        $profileGender = $category === 'gay' ? 'male' : $category;
        $filters = ['gender' => $profileGender];
        $isGayListing = $category === 'gay';
        if ($isGayListing) {
            $filters['sexuality'] = 'Homo,Bisexual';
        }
        $countryName = null;
        $cityName = null;
        $selectedCountry = null;
        $selectedCity = null;

        if ($countrySlug) {
            $selectedCountry = $this->findBySlug($countries, $countrySlug);
            if ($selectedCountry) {
                $countryName = $selectedCountry['name'];
                $filters['country'] = $countryName;
            }
        }
        
        if ($citySlug && $selectedCountry) {
             $countryCities = $citiesByCountry[$selectedCountry['id']] ?? [];
             $selectedCity = $this->findBySlug($countryCities, $citySlug);
             if($selectedCity){
                $cityName = $selectedCity['name'];
                $filters['city'] = $cityName;
             }
        }
        
        $profiles = $this->fetchProfiles($filters);

        // SEO entries are managed by URL in the admin panel. Load the entry for
        // the current public path once, then make it available to the head and
        // content partials rendered by index.php.
        $seoEntry = $this->findSeoEntryForCurrentPath();
        $categoryLabels = [
            'male' => 'Male Escorts',
            'female' => 'Female Escorts',
            'gay' => 'Gay Escorts',
            'trans' => 'Trans Escorts',
        ];
        $seoMeta = $this->buildListingSeoMeta(
            $category,
            $categoryLabels[$category],
            $selectedCountry,
            $selectedCity
        );

        // Admin-managed SEO values override the route's default metadata only
        // when a non-empty value has been supplied.
        foreach (['title' => 'title', 'description' => 'description', 'keywords' => 'meta_keywords'] as $metaKey => $entryKey) {
            $value = trim((string) ($seoEntry[$entryKey] ?? ''));
            if ($value !== '') {
                $seoMeta[$metaKey] = $value;
            }
        }

        $data = [
            'countries' => $countries,
            'citiesByCountry' => $citiesByCountry,
            'profiles' => $profiles,
            'language' => $language,
            'category' => $category,
            'categorySlug' => $category,
            'countrySlug' => $countrySlug,
            'citySlug' => $citySlug,
            'countryLabel' => $countryName,
            'cityLabel' => $cityName,
            'countryName' => $countryName,
            'cityName' => $cityName,
            'seoEntry' => $seoEntry,
            'metaTitle' => $seoMeta['title'],
            'metaDescription' => $seoMeta['description'],
            'metaKeywords' => $seoMeta['keywords'],
            // Directory URLs are collection pages. Keep this explicit instead
            // of inferring it from the request path in the shared head partial,
            // which is also used by static and account pages.
            'collectionPageEnabled' => true,
            'breadcrumbEnabled' => true,
        ];

        return view('index', $data);
    }

    /**
     * Finds the admin SEO row for the current route. Stored URLs may be saved
     * as either a path (/en/female) or a complete URL, so compare normalized
     * paths rather than requiring one particular format in the admin form.
     */
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

    private function normalizeSeoPath(string $url): string
    {
        $url = trim($url);
        $path = parse_url($url, PHP_URL_PATH);

        if (!is_string($path)) {
            // A full domain without a path (for example https://example.com)
            // represents the homepage.
            $path = preg_match('#^[a-z][a-z0-9+.-]*://#i', $url) === 1 ? '/' : $url;
        }

        $path = '/' . trim($path, '/');

        return $path === '/' ? $path : rtrim($path, '/');
    }

    private function buildListingSeoMeta(
        string $category,
        string $categoryLabel,
        ?array $selectedCountry,
        ?array $selectedCity
    ): array {
        $siteName = env('SITE_NAME', 'Yooo.App');
        $countryName = is_array($selectedCountry) ? trim((string) ($selectedCountry['name'] ?? '')) : '';
        $cityName = is_array($selectedCity) ? trim((string) ($selectedCity['name'] ?? '')) : '';

        $metaTemplates = [
            'male' => [
                'city' => ['Gigolos in {place} - Book Gigolos Payboys Male escorts Callboys - Yooo.App', 'Discover the best gigolo service in {place} with top-rated callboy agencies and male escort providers. Easily hire or book a playboy through our reliable service platforms and websites. Explore online gigolo portals and callboy services to find the perfect match. Experience exceptional male escort services and the leading playboy agencies in {place} with our trusted provider platforms and booking websites.', '{place} gigolo, male escort {place}, gigolo service {place}, callboy {place}, escorts {place}'],
                'country' => ['Gigolos in {place} - Callboy Payboy Male escorts Jobs - Yooo.App', 'Discover the best gigolo service in {place} with top-rated callboy agencies and male escort providers. Easily hire or book a playboy through our reliable service platforms and websites. Explore online gigolo portals and callboy services to find the perfect match. Experience exceptional male escort services and the leading playboy agencies in {place} with our trusted provider platforms and booking websites.', '{place} gigolo, male escort {place}, gigolo service {place}, callboy {place}'],
            ],
            'female' => [
                'city' => ['Escorts in {place} - Book Callgirls, Escorts and Companions - Yooo.App', 'Discover the best Callgirl service in {place} with top-rated female escort agencies. Explore online platforms to hire or book a hooker or prostitute through reliable service providers and websites. Whether you\'re seeking a female escort service or a dedicated hooker portal, find the top {place} escort agencies and providers to meet your needs.', '{place} escorts, female escort {place}, escort service {place}, callgirl {place}'],
                'country' => ['Escort service in {place} - Callgirls, Hookers, Female escorts - Yooo.App', 'Discover the best Callgirl service in {place} with top-rated female escort agencies. Explore online platforms to hire or book a hooker or prostitute through reliable service providers and websites. Whether you\'re seeking a female escort service or a dedicated hooker portal, find the top {place} escort agencies and providers to meet your needs.', '{place} escorts, female escort {place}, escort service {place}, callgirl {place}'],
            ],
            'gay' => [
                'city' => ['Gay escorts {place} - Book Top Bottom Versatile Gay escorts - Yooo.App', 'Discover the best top gay escorts in {place} with our online platform. Easily hire or book bottom and versatile gay escorts through our reliable service providers and websites. Explore top-rated gay escort agencies and escort platforms to find the perfect match. Experience high-quality gay escort services and connect with leading escort providers in {place} through our trusted escort portals and agencies.', '{place} gay escort, top escort {place}, bottom gay {place}, vers gay {place}, gay escorts {place}'],
                'country' => ['Gay escorts in {place} - Top Bottom Versatile Gay escorts - Yooo.App', 'Discover the best top gay escorts in {place} with our online platform. Easily hire or book bottom and versatile gay escorts through our reliable service providers and websites. Explore top-rated gay escort agencies and escort platforms to find the perfect match. Experience high-quality gay escort services and connect with leading escort providers in {place} through our trusted escort portals and agencies.', '{place} gay escorts, top gay escort {place}, bottom gay {place}, vers gay {place}'],
            ],
            'trans' => [
                'city' => ['Trans escorts {place} - Book Ladyboys, Shemales, TS escorts - Yooo.App', 'Discover the best ladyboys service in {place} with top-rated shemale escort agencies and trans escort providers. Easily hire or book ladyboys through our reliable service platforms and websites. Explore online shemale services and trans escort portals to find the perfect match. Experience exceptional trans escort services and leading ladyboys agencies in {place} with our trusted provider platforms and booking websites.', '{place} ts escort, trans escort {place}, ladyboys in {place}, shemale escorts {place}, TS escorts {place}'],
                'country' => ['Trans escorts in {place} - Ladyboys, Shemales, TS escorts - Yooo.App', 'Discover the best ladyboys service in {place} with top-rated shemale escort agencies and trans escort providers. Easily hire or book ladyboys through our reliable service platforms and websites. Explore online shemale services and trans escort portals to find the perfect match. Experience exceptional trans escort services and leading ladyboys agencies in {place} with our trusted provider platforms and booking websites.', '{place} trans women, ladyboys {place}, TS escort {place}, shemale escorts {place}'],
            ],
        ];

        $scope = $cityName !== '' ? 'city' : ($countryName !== '' ? 'country' : null);
        if ($scope !== null && isset($metaTemplates[$category][$scope])) {
            [$title, $description, $keywords] = $metaTemplates[$category][$scope];
            $place = $scope === 'city' ? $cityName : $countryName;
            $title = str_replace('Yooo.App', $siteName, $title);

            return [
                'title' => str_replace('{place}', $place, $title),
                'description' => str_replace('{place}', $place, $description),
                'keywords' => str_replace('{place}', $place, $keywords),
            ];
        }

        return [
            'title' => $categoryLabel . ' | ' . $siteName,
            'description' => 'Browse independent ' . strtolower($categoryLabel) . ' profiles by country, city, and locality.',
            'keywords' => strtolower($categoryLabel) . ', independent escorts, escort directory',
        ];
    }
    
    private function findBySlug(array $rows, string $slug): ?array
    {
        $slug = strtolower(trim($slug));
        foreach ($rows as $row) {
            if (isset($row['slug']) && strtolower(trim((string) $row['slug'])) === $slug) {
                return $row;
            }
        }
        return null;
    }

    private function fetchProfiles(array $filters): array
    {
        $sexualities = isset($filters['sexuality']) ? explode(',', (string) $filters['sexuality']) : null;
        $profiles = (new ProfileModel())->getListingProfiles(
            (string) ($filters['gender'] ?? 'female'),
            $filters['country'] ?? null,
            $filters['city'] ?? null,
            null,
            $sexualities,
            null,
            100
        );
        return $this->formatProfilesForCards($profiles);
    }

    private function formatProfilesForCards(array $profiles): array
    {
        return array_map(function (array $profile) {
            $images = $this->normalizeImageList($profile['images'] ?? []);
            $mainImage = $images[0] ?? null;
            $id = (int) ($profile['id'] ?? 0);
            $name = trim((string) ($profile['name'] ?? 'Profile'));
            $slug = url_title($name !== '' ? $name : 'profile-' . $id, '-', true);

            return [
                'id' => $id,
                'name' => $name !== '' ? $name : 'Profile',
                'location' => $profile['location'] ?? '',
                'gender' => $profile['gender'] ?? '',
                'sexuality' => $profile['sexuality'] ?? [],
                'image' => $mainImage ? $this->normalizeImagePath((string) $mainImage) : 'https://www.yooo.app/images/yoooo-female.webp',
                'is_verified' => $profile['is_verified'] ?? 0,
                'membership' => $profile['membership'] ?? 'free',
                'url' => $id > 0 ? localized_url('profile/' . $id . '/' . $slug) : '#',
            ];
        }, $profiles);
    }

    private function normalizeImageList(mixed $value): array
    {
        if (is_array($value)) {
            return array_values($value);
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
            return array_values($decoded);
        }

        return [$trimmed];
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


    private function fetchCountries(): array
    {
        return (new CountryModel())->getActiveCountries();
    }

    private function fetchCities(?int $countryId = null): array
    {
        return (new CityModel())->getActiveCities($countryId);
    }
}
