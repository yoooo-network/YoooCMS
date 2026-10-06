<?php

namespace App\Controllers;

use App\Models\CountryModel;
use App\Models\CityModel;
use App\Models\ProfileModel;
use CodeIgniter\Controller;
use Config\App as AppConfig;

class Sitemap extends Controller
{
    public function index()
    {
        helper('url');

        $locales = $this->getSupportedLocales();

        $sitemaps = [];
        $totalProfiles = $this->countProfiles();
        $pages = (int) ceil($totalProfiles / 1000);

        foreach ($locales as $locale) {
            $sitemaps[] = site_url('sitemap_' . $locale . '_female.xml');
            $sitemaps[] = site_url('sitemap_' . $locale . '_male.xml');
            $sitemaps[] = site_url('sitemap_' . $locale . '_gay.xml');
            $sitemaps[] = site_url('sitemap_' . $locale . '_trans.xml');

            if ($pages > 0) {
                for ($page = 1; $page <= $pages; $page++) {
                    $sitemaps[] = site_url('profile_sitemap_' . $locale . '_' . $page . '.xml');
                }
            }
        }

        return $this->respondWithSitemapIndex($sitemaps);
    }

    public function category(string $locale, string $category)
    {
        $locale = strtolower(trim($locale));
        $category = strtolower(trim($category));

        return $this->respondWithCategorySitemap($locale, $category);
    }

    public function profileIndex()
    {
        helper('url');

        $locales = $this->getSupportedLocales();
        $totalProfiles = $this->countProfiles();
        $pages = (int) ceil($totalProfiles / 1000);

        $sitemaps = [];
        if ($pages > 0) {
            foreach ($locales as $locale) {
                for ($page = 1; $page <= $pages; $page++) {
                    $sitemaps[] = site_url('profile_sitemap_' . $locale . '_' . $page . '.xml');
                }
            }
        }

        return $this->respondWithSitemapIndex($sitemaps);
    }

    public function profilePage(string $locale, int $page)
    {
        helper('url');

        $locale = strtolower(trim($locale));
        if ($locale === '') {
            $locale = 'en';
        }

        $page = max(1, $page);
        $limit = 1000;
        $offset = ($page - 1) * $limit;

        $profiles = $this->fetchProfilesPage($limit, $offset);

        $urls = [];
        $addUrl = static function (string $path) use (&$urls): void {
            $path = trim($path);
            if ($path === '') {
                $path = '/';
            }

            $loc = site_url($path);
            if ($loc !== '') {
                $urls[$loc] = true;
            }
        };

        foreach ($profiles as $profile) {
            $id = (int) ($profile['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }

            $name = trim((string) ($profile['name'] ?? ''));
            $slug = $this->slugify($name !== '' ? $name : 'profile-' . $id);
            if ($slug === '') {
                $slug = 'profile-' . $id;
            }

            $addUrl($locale . '/profile/' . $id . '/' . $slug);
        }

        return $this->respondWithUrlset(array_keys($urls));
    }

    private function getSupportedLocales(): array
    {
        $appConfig = config(AppConfig::class);
        $locales = is_array($appConfig->supportedLocales ?? null) && $appConfig->supportedLocales
            ? $appConfig->supportedLocales
            : ['en'];

        $normalized = [];
        foreach ($locales as $locale) {
            $locale = strtolower(trim((string) $locale));
            if ($locale !== '') {
                $normalized[$locale] = true;
            }
        }

        if ($normalized === []) {
            return ['en'];
        }

        return array_keys($normalized);
    }

    private function respondWithCategorySitemap(string $locale, string $category)
    {
        helper('url');

        $locale = strtolower(trim($locale));
        $category = strtolower(trim($category));

        if ($locale === '') {
            $locale = 'en';
        }

        $urls = [];
        $addUrl = static function (string $path) use (&$urls): void {
            $path = trim($path);
            if ($path === '') {
                $path = '/';
            }

            $loc = site_url($path);
            if ($loc !== '') {
                $urls[$loc] = true;
            }
        };

        $addUrl('/');
        $addUrl($locale);
        $addUrl($locale . '/' . $category);

        $countrySlugById = $this->getCountrySlugById();
        foreach ($countrySlugById as $countrySlug) {
            $addUrl($locale . '/' . $category . '/' . $countrySlug);
        }

        $cityPairs = $this->getCityPairs($countrySlugById);
        foreach ($cityPairs as $pair) {
            $addUrl($locale . '/' . $category . '/' . $pair['country'] . '/' . $pair['city']);
        }

        return $this->respondWithUrlset(array_keys($urls));
    }

    private function slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

        return trim($value, '-');
    }

    private function getCountrySlugById(): array
    {
        $countries = $this->fetchCountries();

        $countrySlugById = [];
        foreach ($countries as $country) {
            $id = (int) ($country['id'] ?? 0);
            $slug = strtolower(trim((string) ($country['slug'] ?? '')));
            if ($id > 0 && $slug !== '') {
                $countrySlugById[$id] = $slug;
            }
        }

        return $countrySlugById;
    }

    private function getCityPairs(array $countrySlugById): array
    {
        $cities = $this->fetchCities();

        $pairs = [];
        foreach ($cities as $city) {
            $citySlug = strtolower(trim((string) ($city['slug'] ?? '')));
            $countryId = (int) ($city['country_id'] ?? 0);
            $countrySlug = $countrySlugById[$countryId] ?? '';

            if ($citySlug === '' || $countrySlug === '') {
                continue;
            }

            $pairs[] = [
                'city' => $citySlug,
                'country' => $countrySlug,
            ];
        }

        return $pairs;
    }

    private function fetchCountries(): array
    {
        return (new CountryModel())->getActiveCountries();
    }

    private function fetchCities(): array
    {
        return (new CityModel())->getActiveCities();
    }

    private function fetchProfilesPage(int $limit, int $offset): array
    {
        return (new ProfileModel())->getApprovedProfilesPage($limit, $offset);
    }

    private function countProfiles(): int
    {
        return (new ProfileModel())->countApprovedProfiles();
    }

    private function respondWithSitemapIndex(array $sitemaps)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $sitemapIndex = $dom->createElement('sitemapindex');
        $sitemapIndex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $lastmod = '2026-08-15';

        foreach ($sitemaps as $loc) {
            $loc = trim((string) $loc);
            if ($loc === '') {
                continue;
            }

            $sitemap = $dom->createElement('sitemap');
            $locNode = $dom->createElement('loc', $loc);
            $lastmodNode = $dom->createElement('lastmod', $lastmod);
            $sitemap->appendChild($locNode);
            $sitemap->appendChild($lastmodNode);
            $sitemapIndex->appendChild($sitemap);
        }

        $dom->appendChild($sitemapIndex);

        return $this->response
            ->setContentType('application/xml')
            ->setBody($dom->saveXML());
    }

    private function respondWithUrlset(array $urls)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $lastmod = '2026-08-15';

        foreach ($urls as $loc) {
            $loc = trim((string) $loc);
            if ($loc === '') {
                continue;
            }

            $url = $dom->createElement('url');
            $locNode = $dom->createElement('loc', $loc);
            $lastmodNode = $dom->createElement('lastmod', $lastmod);
            $changefreqNode = $dom->createElement('changefreq', 'weekly');
            $url->appendChild($locNode);
            $url->appendChild($lastmodNode);
            $url->appendChild($changefreqNode);
            $urlset->appendChild($url);
        }

        $dom->appendChild($urlset);

        return $this->response
            ->setContentType('application/xml')
            ->setBody($dom->saveXML());
    }
}
