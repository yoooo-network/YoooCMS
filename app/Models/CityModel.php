<?php

namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    private const CACHE_TTL = 21600;

    protected $table = 'cities';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'country_id',
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    public function getActiveCities(?int $countryId = null): array
    {
        $cache = cache();
        $cacheKey = $countryId !== null
            ? 'cities.active.country.' . (int) $countryId
            : 'cities.active';
        $cached = $cache->get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $builder = $this->where('is_active', 1);

        if ($countryId !== null) {
            $builder->where('country_id', $countryId);
        }

        $cities = $builder
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $cache->save($cacheKey, $cities, self::CACHE_TTL);

        return $cities;
    }

    public function findActiveBySlug(string $slug, ?int $countryId = null): ?array
    {
        $slug = strtolower(trim($slug));
        if ($slug === '') {
            return null;
        }

        $cache = cache();
        $cacheKey = $countryId !== null
            ? 'cities.slug.' . $slug . '.country.' . (int) $countryId
            : 'cities.slug.' . $slug;
        $cached = $cache->get($cacheKey);
        if ($cached !== null) {
            return is_array($cached) ? $cached : null;
        }

        $builder = $this->where('is_active', 1)
            ->where('slug', $slug);

        if ($countryId !== null) {
            $builder->where('country_id', $countryId);
        }

        $city = $builder->first();

        if (is_array($city)) {
            $cache->save($cacheKey, $city, self::CACHE_TTL);
            return $city;
        }

        $cache->save($cacheKey, false, 600);
        return null;
    }
}
