<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('supported_languages')) {
    /** @return list<string> */
    function supported_languages(): array
    {
        $available = ['en', 'de', 'es', 'fr', 'pt', 'ja', 'hi'];
        $configured = env('SITE_LANGUAGES');
        if (! is_string($configured) || trim($configured) === '') {
            return $available;
        }

        $languages = array_values(array_unique(array_filter(array_map('trim', explode(',', $configured)), static fn(string $language): bool => in_array($language, $available, true))));
        return in_array('en', $languages, true) ? $languages : ['en', ...$languages];
    }
}

if (! function_exists('site_categories')) {
    /** @return list<string> */
    function site_categories(): array
    {
        $available = ['male', 'female', 'gay', 'trans'];
        $configured = env('SITE_CATEGORIES');
        if (! is_string($configured) || trim($configured) === '') {
            return $available;
        }

        $categories = array_values(array_unique(array_intersect($available, array_map('trim', explode(',', $configured)))));
        return $categories !== [] ? $categories : ['female'];
    }
}

if (! function_exists('default_site_category')) {
    function default_site_category(): string
    {
        $categories = site_categories();
        return in_array('female', $categories, true) ? 'female' : $categories[0];
    }
}

if (! function_exists('localized_url')) {
    /**
     * Builds a URL under the active supported language prefix.
     */
    function localized_url(string $path = '', ?string $language = null): string
    {
        $language ??= service('request')->getUri()->getSegment(1);
        $language = in_array($language, supported_languages(), true) ? $language : 'en';

        return site_url($language . '/' . ltrim($path, '/'));
    }
}
