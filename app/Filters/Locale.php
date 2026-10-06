<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Sets the application's locale from the first URL segment. */
class Locale implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri();
        $path = trim((string) $uri->getPath(), '/');
        $segments = array_values(array_filter(explode('/', $path), static fn ($segment): bool => $segment !== ''));
        $firstSegment = $segments !== [] ? strtolower((string) $segments[0]) : '';

        if ($path !== '' && ! in_array($firstSegment, supported_languages(), true)) {
            $isAdminRoute = $firstSegment === 'ci-admin';
            $isStaticPublicRoute = in_array($firstSegment, ['help', 'about', 'advertising', 'contact', 'faq', 'packages', 'privacy-policy', 'refund-policy', 'report-misuse', 'safety-guidelines', 'terms-and-conditions'], true)
                || in_array($firstSegment, ['male', 'female', 'gay', 'trans'], true)
                || $firstSegment === 'profile';

            if (! $isAdminRoute && $isStaticPublicRoute) {
                $redirectPath = 'en';
                if ($path !== '') {
                    $redirectPath .= '/' . $path;
                }

                $query = $uri->getQuery();
                if ($query !== '') {
                    $redirectPath .= '?' . $query;
                }

                return redirect()->to(site_url($redirectPath), 301);
            }
        }

        $locale = in_array($firstSegment, supported_languages(), true)
            ? $firstSegment
            : config('App')->defaultLocale;

        $request->setLocale($locale);
        service('language')->setLocale($locale);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No response changes are required.
    }
}
