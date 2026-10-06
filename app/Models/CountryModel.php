<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    private const CACHE_TTL = 21600;

    protected $table = 'countries';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'phone_code',
        'currency_code',
        'is_active',
        'sort_order',
    ];

    public function getActiveCountries(): array
    {
        $cache = cache();
        $cacheKey = 'countries.active';
        $cached = $cache->get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $countries = $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $cache->save($cacheKey, $countries, self::CACHE_TTL);

        return $countries;
    }

    public function findActiveBySlug(string $slug): ?array
    {
        $slug = strtolower(trim($slug));
        if ($slug === '') {
            return null;
        }

        $cache = cache();
        $cacheKey = 'countries.slug.' . $slug;
        $cached = $cache->get($cacheKey);
        if ($cached !== null) {
            return is_array($cached) ? $cached : null;
        }

        $country = $this->where('is_active', 1)
            ->where('slug', strtolower(trim($slug)))
            ->first();

        if (is_array($country)) {
            $cache->save($cacheKey, $country, self::CACHE_TTL);
            return $country;
        }

        $cache->save($cacheKey, false, 600);
        return null;
    }
}
