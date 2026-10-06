<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    private const LISTING_CACHE_TTL = 300;
    private const SITEMAP_CACHE_TTL = 900;

    protected $table = 'profiles';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'name',
		'gender',
		'sexuality',
        'dob',
        'location',
        'height',
        'weight',
        'description',
        'eye_color',
        'hair_type',
        'skin_color',
        'body_structure',
        'ethnicity',
        'images',
		'pricing',
		'services',
		'languages',
        'phone',
        'whatsapp',
        'telegram',
        'facebook',
        'instagram',
        'discord',
        'website',
        'other_pages',
        'created_at',
        'status',
		'membership',
        'is_verified',
        'updated_at'
    ];

    public function getListingProfiles(
        string $gender,
        ?string $countryName = null,
        ?string $cityName = null,
        ?string $membership = null,
        ?array $sexualities = null,
        ?bool $isVerified = null,
        int $limit = 24,
        ?array $services = null
    ): array {
        $cacheKey = $this->buildListingCacheKey(
            $gender,
            $countryName,
            $cityName,
            $membership,
            $sexualities,
            $isVerified,
            $limit,
            $services
        );
        $cache = cache();
        $cached = $cache->get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $builder = $this->builder();
        $builder->select('id, name, gender, sexuality, location, images, membership, status, is_verified, created_at');

        // Use simpler where clauses to avoid potential 500 errors with complex expressions in some SQL drivers
        $builder->groupStart()
                ->whereIn('status', ['approved', 'submitted', 'Approved', 'Submitted'])
                ->orWhere('status', null)
                ->groupEnd();

        $builder->where('gender', strtolower(trim($gender)));

        if ($countryName !== null && trim($countryName) !== '') {
            $builder->like('location', trim($countryName));
        }

        if ($cityName !== null && trim($cityName) !== '') {
            $builder->like('location', trim($cityName));
        }

        if ($membership !== null && trim($membership) !== '') {
            $builder->where('LOWER(TRIM(membership))', strtolower(trim($membership)));
        }

        if ($isVerified !== null) {
            $builder->where('is_verified', $isVerified ? 1 : 0);
        }

        if (is_array($services) && $services !== []) {
            $builder->groupStart();
            foreach ($services as $service) {
                // Using LIKE to search for the service name within the "selected" array of the JSON string.
                // This is compatible with all MySQL versions and avoids 500 errors on older DBs.
                $builder->orLike('services', '"' . $service . '"');
            }
            $builder->groupEnd();
        }

        if (is_array($sexualities) && $sexualities !== []) {
            $builder->groupStart();
            foreach (array_values($sexualities) as $index => $sexuality) {
                $value = trim((string) $sexuality);
                if ($value === '') {
                    continue;
                }

                if ($index === 0) {
                    $builder->like('sexuality', '"' . $value . '"');
                } else {
                    $builder->orLike('sexuality', '"' . $value . '"');
                }
            }
            $builder->groupEnd();
        }

        if ($membership !== null && trim($membership) !== '') {
            $builder->where('LOWER(TRIM(membership))', strtolower(trim($membership)));
            $builder->orderBy('id', 'DESC');
        } else {
            // Default: Premium/VIP first, then by ID (for Mobile App)
            // Use false as the third parameter to prevent CI from splitting and escaping the complex expression
            $builder->orderBy("CASE WHEN membership IN ('premium', 'vip', 'Premium', 'VIP') THEN 0 ELSE 1 END", 'ASC', false);
            $builder->orderBy('id', 'DESC');
        }

        $profiles = $builder
            ->limit(max(1, $limit))
            ->get()
            ->getResultArray();

        $cache->save($cacheKey, $profiles, self::LISTING_CACHE_TTL);

        return $profiles;
    }

    public function countApprovedProfiles(): int
    {
        $cache = cache();
        $cacheKey = 'profiles.count.approved';
        $cached = $cache->get($cacheKey);
        if (is_int($cached)) {
            return $cached;
        }

        $builder = $this->builder();
        $builder->where('LOWER(TRIM(status))', 'approved');

        $count = (int) $builder->countAllResults();
        $cache->save($cacheKey, $count, self::SITEMAP_CACHE_TTL);

        return $count;
    }

    public function getApprovedProfilesPage(int $limit = 1000, int $offset = 0): array
    {
        $cache = cache();
        $cacheKey = 'profiles.approved.page.' . max(1, $limit) . '.' . max(0, $offset);
        $cached = $cache->get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $builder = $this->builder();
        $builder->select('id, name');
        $builder->where('LOWER(TRIM(status))', 'approved');

        $profiles = $builder
            ->orderBy('id', 'DESC')
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();

        $cache->save($cacheKey, $profiles, self::SITEMAP_CACHE_TTL);

        return $profiles;
    }

    private function buildListingCacheKey(
        string $gender,
        ?string $countryName,
        ?string $cityName,
        ?string $membership,
        ?array $sexualities,
        ?bool $isVerified,
        int $limit,
        ?array $services = null
    ): string {
        $payload = [
            'gender' => strtolower(trim($gender)),
            'country' => strtolower(trim((string) $countryName)),
            'city' => strtolower(trim((string) $cityName)),
            'membership' => strtolower(trim((string) $membership)),
            'sexualities' => is_array($sexualities) ? array_values($sexualities) : [],
            'is_verified' => $isVerified,
            'limit' => max(1, $limit),
            'services' => is_array($services) ? array_values($services) : [],
        ];

        return 'profiles.listing.' . md5(json_encode($payload));
    }
}
